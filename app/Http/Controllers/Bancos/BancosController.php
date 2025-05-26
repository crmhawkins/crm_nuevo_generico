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

        return redirect()->route('bancos.index')->with('status', 'Banco creado con éxito!');

    }

    public function update(Request $request, $banco){
        $rules = [
            'name' => 'required|string|max:255',
            'cuenta' => 'required|string|max:255',
        ];

        // Validar los datos del formulario
        $validatedData = $request->validate($rules);
        $banco = BankAccounts::find($banco);

        $updated = $banco->update([
            'name' => $validatedData['name'],
            'cuenta' => $validatedData['cuenta']
        ]);

        return redirect()->back()->with('status', 'Banco actualizado con éxito!');

    }

    public function destroy(Request $request){
        $banco = BankAccounts::find($request->id);
        if($banco){
            $banco->delete();
            return response()->json([
                'status' => true,
                'mensaje' => 'Banco eliminado con éxito!'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'mensaje' => 'Banco no encontrado'
            ]);
        }
    }
}
