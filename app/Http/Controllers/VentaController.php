<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function getVentas()
    {
        $ventas = Venta::with(['cliente', 'detalles.producto'])->latest()->get();
        $clientes = Cliente::all();
        $productos = Producto::all();

        return view('ventas', compact('ventas', 'clientes', 'productos'));
    }

    public function createVenta(Request $request)
    {
        $request->validate([
            'inputCliente_id' => 'required|exists:clientes,id',
            'inputFecha' => 'required|date',
            'inputEstado' => 'required|in:pendiente,confirmada,cancelada',
        ], [
            'inputCliente_id.required' => 'Selecciona un cliente.',
            'inputFecha.required' => 'La fecha es obligatoria.',
        ]);

        //solo las filas donde se eligió un producto (sin límite de filas)
        $items = [];
        foreach ($request->productos ?? [] as $fila) {
            if (!empty($fila['id'])) {
                if (empty($fila['cantidad']) || $fila['cantidad'] < 1) {
                    return back()->withInput()->withErrors([
                        'productos' => 'Escribe la cantidad en cada producto elegido.'
                    ]);
                }
                //repiten un producto, se suman las cantidades
                $items[$fila['id']] = ($items[$fila['id']] ?? 0) + (int) $fila['cantidad'];
            }
        }

        if (count($items) == 0) {
            return back()->withInput()->withErrors([
                'productos' => 'Agrega al menos un producto.'
            ]);
        }

        //stock de cada producto
        $errores = [];
        foreach ($items as $productoId => $cantidad) {
            $prod = Producto::findOrFail($productoId);
            if ($prod->stock <= 0) {
                $errores[] = "El producto {$prod->producto} no tiene stock.";
            } elseif ($prod->stock < $cantidad) {
                $errores[] = "Stock insuficiente de {$prod->producto}. Disponible: {$prod->stock}.";
            }
        }
        if (count($errores) > 0) {
            return back()->withInput()->withErrors($errores);
        }

        DB::transaction(function () use ($request, $items) {
            $venta = Venta::create([
                'cliente_id' => $request->inputCliente_id,
                'fecha_venta' => $request->inputFecha,
                'estado' => $request->inputEstado,
                'total' => 0,
            ]);

            $totalVenta = 0;
            foreach ($items as $productoId => $cantidad) {
                $prod = Producto::findOrFail($productoId);
                $subtotal = $prod->precio * $cantidad;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $prod->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $prod->precio,
                    'total' => $subtotal,
                ]);

                $totalVenta += $subtotal;

                //venta cancelada no descuenta stock
                if ($request->inputEstado !== 'cancelada') {
                    $prod->decrement('stock', $cantidad);
                }
            }

            $venta->update(['total' => $totalVenta]);
        });

        return redirect('/ventas')->with(['mensaje' => 'venta registrada']);
    }

    public function updateVenta(int $id, Request $request)
    {
        $request->validate([
            'inputCliente_id' => 'required|exists:clientes,id',
            'inputFecha' => 'required|date',
            'inputEstado' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        $venta = Venta::with('detalles.producto')->findOrFail($id);
        $anterior = $venta->estado;
        $nuevo = $request->inputEstado;

        //reactiva una venta cancelada, debe haber stock
        if ($anterior === 'cancelada' && $nuevo !== 'cancelada') {
            foreach ($venta->detalles as $d) {
                if ($d->producto->stock < $d->cantidad) {
                    return redirect('/ventas')->with([
                        'error' => "No hay stock suficiente de {$d->producto->producto} para reactivar la venta."
                    ]);
                }
            }
        }

        DB::transaction(function () use ($venta, $request, $anterior, $nuevo) {
            if ($anterior !== 'cancelada' && $nuevo === 'cancelada') {
                foreach ($venta->detalles as $d) {
                    $d->producto->increment('stock', $d->cantidad);
                }
            } elseif ($anterior === 'cancelada' && $nuevo !== 'cancelada') {
                foreach ($venta->detalles as $d) {
                    $d->producto->decrement('stock', $d->cantidad);
                }
            }

            $venta->update([
                'cliente_id' => $request->inputCliente_id,
                'fecha_venta' => $request->inputFecha,
                'estado' => $nuevo,
            ]);
        });

        return redirect('/ventas')->with(['mensaje' => 'venta actualizada']);
    }

    public function deleteVenta(int $id)
    {
        $venta = Venta::with('detalles.producto')->findOrFail($id);

        //eliminan ventas pendientes o canceladas
        if (!in_array($venta->estado, ['pendiente', 'cancelada'])) {
            return redirect('/ventas')->with([
                'error' => 'Solo se pueden eliminar ventas pendientes o canceladas.'
            ]);
        }

        DB::transaction(function () use ($venta) {
            //venta pendiente tenía stock descontado se regresa
            if ($venta->estado === 'pendiente') {
                foreach ($venta->detalles as $d) {
                    $d->producto->increment('stock', $d->cantidad);
                }
            }

            $venta->detalles()->delete();
            $venta->delete();
        });

        return redirect('/ventas')->with(['mensaje' => 'venta eliminada']);
    }
}
