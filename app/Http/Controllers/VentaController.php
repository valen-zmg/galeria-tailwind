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
    {   //ventas y sus relaciones con cliente y detalles.producto
        $ventas = Venta::with(['cliente', 'detalles.producto'])->latest()->get();
        $clientes = Cliente::all();
        $productos = Producto::all();
        //retorna a la vista de ventas
        return view('ventas', compact('ventas', 'clientes', 'productos'));
    }

    public function createVenta(Request $request)
    {
        $request->validate([
            'inputCliente_id' => 'required|exists:clientes,id',
            'inputFecha' => 'required|date',
            'inputEstado' => 'required|in:pendiente,confirmada,cancelada',
            'inputProducto_id' => 'required|exists:productos,id',
            'inputCantidad' => 'required|integer|min:1',
        ], [
            'inputCliente_id.required' => 'Selecciona un cliente.',
            'inputFecha.required' => 'La fecha es obligatoria.',
            'inputProducto_id.required' => 'Selecciona un producto.',
            'inputCantidad.required' => 'La cantidad es obligatoria.',
            'inputCantidad.min' => 'La cantidad debe ser al menos 1.',
        ]);

        $producto = Producto::findOrFail($request->inputProducto_id);
        $cantidad = (int) $request->inputCantidad;

        //validaciones de stock
        if ($producto->stock <= 0) {
            return back()->withInput()->withErrors([
                'inputProducto_id' => "El producto {$producto->producto} no tiene stock."
            ]);
        }
        if ($producto->stock < $cantidad) {
            return back()->withInput()->withErrors([
                'inputCantidad' => "Stock insuficiente. Disponible: {$producto->stock}."
            ]);
        }
        //transacción para guardar
        DB::transaction(function () use ($request, $producto, $cantidad) {
            $total = $producto->precio * $cantidad;
            //crear venta y detalle de venta
            $venta = Venta::create([
                'cliente_id' => $request->inputCliente_id,
                'fecha_venta' => $request->inputFecha,
                'estado' => $request->inputEstado,
                'total' => $total,
            ]);

            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
                'total' => $total,
            ]);

            //si una venta cancelada no descuenta stock
            if ($request->inputEstado !== 'cancelada') {
                $producto->decrement('stock', $cantidad);
            }
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

        // Si se reactiva una venta cancelada, debe haber stock
        if ($anterior === 'cancelada' && $nuevo !== 'cancelada') {
            foreach ($venta->detalles as $d) {
                if ($d->producto->stock < $d->cantidad) {
                    return redirect('/ventas')->with([
                        'error' => "No hay stock suficiente de {$d->producto->producto} para reactivar la venta."
                    ]);
                }
            }
        }
        //manipula inventario
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

        //si solo se eliminan ventas pendientes o canceladas
        if (!in_array($venta->estado, ['pendiente', 'cancelada'])) {
            return redirect('/ventas')->with([
                'error' => 'Solo se pueden eliminar ventas pendientes o canceladas.'
            ]);
        }

        DB::transaction(function () use ($venta) {
            //si venta pendiente tenía stock descontado se regresa
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
