<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function getCategorias()
    {
        // select * from categoria;
        $categorias = Categoria::all();
        return view('categoria', compact('categorias'));
    }

    public function createCategoria(Request $request)
    {
        $request->validate([
            "categoria" => "required"
        ]);

        Categoria::create([
            "categoria" => $request->categoria
        ]);

        return redirect("/categorias")->with([
            "mensaje" => "categoria registrada"
        ]);
    }

    public function updateCategoria($id, Request $request)
    {
        $request->validate([
            "categoria" => "required"
        ]);

        // select * from categoria where id = $id
        $categoria = Categoria::findOrFail($id);
        $categoria->update([
            "categoria" => $request->categoria
        ]);

        return redirect("/categorias")->with([
            "mensaje" => "categoria actualizada"
        ]);
    }

    public function deleteCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect("/categorias")->with([
            "mensaje" => "categoria eliminada"
        ]);
    }
}
