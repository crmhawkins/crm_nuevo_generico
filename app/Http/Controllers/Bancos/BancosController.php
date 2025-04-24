<?php

namespace App\Http\Controllers\Bancos;

use App\Http\Controllers\Controller;
use App\Models\Other\BankAccounts;
use Illuminate\Http\Request;

class BancosController extends Controller
{

    public function index(){
        $bancos = BankAccounts::paginate(10); // O el número que quieras por página
        return view('bancos.index',compact('bancos'));
    }

    public function create(){
        return view('bancos.create');
    }

    public function edit(BankAccounts $banco){
        return view('bancos.edit',compact('banco'));
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required|string|max:255',
            'cuenta' => 'required|string|max:255',

        ];

        // Validar los datos del formulario
        $validatedData = $request->validate($rules);
        $banco = BankAccounts::create($validatedData);

        return redirect()->back()->with('status', 'Banco creado con éxito!');

    }

    public function update(Request $request, BankAccounts $banco){
        $rules = [
            'name' => 'required|string|max:255',
            'cuenta' => 'required|string|max:255',
        ];

        // Validar los datos del formulario
        $validatedData = $request->validate($rules);
        $banco->update([
            'name' => $validatedData['name']
        ]);

        return redirect()->back()->with('status', 'Banco actualizado con éxito!');

    }

    public function destroy(BankAccounts $banco){
        $banco->delete();

        return redirect()->back()->with('status', 'Banco eliminado con éxito!');
    }
}
