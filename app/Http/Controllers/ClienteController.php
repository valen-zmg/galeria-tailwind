<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function getClientes()
    {
        $clientes = Cliente::all();
        return view('clientes', compact('clientes'));
    }

    public function createCliente(Request $request)
    {
        $request->validate([
            "inputNombre" => 'required|string|max:255',
            "inputEmail" => 'required|email|unique:clientes,email',
            "inputDireccion" => 'required|string|max:255',
            "inputTelefono" => 'required|digits:10'
        ]);

        Cliente::create([
            "nombre" => $request->inputNombre,
            "email" => $request->inputEmail,
            "direccion" => $request->inputDireccion,
            "telefono" => $request->inputTelefono
        ]);

        return redirect("/clientes")->with(["mensaje" => "cliente registrado"]);
    }

    public function updateCliente(int $id, Request $request)
    {
        $request->validate([
            "inputNombre" => 'required|string|max:255',
            "inputEmail" => 'required|email|unique:clientes,email,' . $id,
            "inputDireccion" => 'required|string|max:255',
            "inputTelefono" => 'required|digits:10'
        ]);

        Cliente::findOrFail($id)->update([
            "nombre" => $request->inputNombre,
            "email" => $request->inputEmail,
            "direccion" => $request->inputDireccion,
            "telefono" => $request->inputTelefono
        ]);

        return redirect("/clientes")->with(["mensaje" => "cliente actualizado"]);
    }

    public function deleteCliente(int $id)
    {
        Cliente::findOrFail($id)->delete();

        return redirect("/clientes")->with(["mensaje" => "cliente eliminado"]);
    }
}
