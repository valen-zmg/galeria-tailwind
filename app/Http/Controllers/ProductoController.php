<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function getProductos()
    {
        $productos = Producto::select(
            'productos.id',
            'productos.producto',
            'productos.precio',
            'productos.stock',
            'productos.categoria_id',
            'categoria.categoria'
        )->join('categoria', 'productos.categoria_id', '=', 'categoria.id')
         ->get();

        $categorias = Categoria::all();

        return view('productos', compact('productos', 'categorias'));
    }

    public function createProducto(Request $request)
    {
        $request->validate([
            "inputProducto" => 'required',
            "inputPrecio" => 'required',
            "inputStock" => 'required',
            "inputCategoria_id" => 'required'
        ]);

        Producto::create([
            "producto" => $request->inputProducto,
            "precio" => $request->inputPrecio,
            "stock" => $request->inputStock,
            "categoria_id" => $request->inputCategoria_id
        ]);

        return redirect("/productos")->with(["mensaje" => "producto registrado"]);
    }

    public function updateProducto(int $id, Request $request)
    {
        $request->validate([
            "inputProducto" => 'required',
            "inputPrecio" => 'required',
            "inputStock" => 'required',
            "inputCategoria_id" => 'required'
        ]);

        Producto::findOrFail($id)->update([
            "producto" => $request->inputProducto,
            "precio" => $request->inputPrecio,
            "stock" => $request->inputStock,
            "categoria_id" => $request->inputCategoria_id
        ]);

        return redirect("/productos")->with(["mensaje" => "producto actualizado"]);
    }

    public function deleteProducto(int $id)
    {
        Producto::findOrFail($id)->delete();

        return redirect("/productos")->with(["mensaje" => "producto eliminado"]);
    }
}

