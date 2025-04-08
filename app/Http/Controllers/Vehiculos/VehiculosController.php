<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Vehiculos;
use Illuminate\Http\Request;


class VehiculosController extends Controller
{
    public function index()
    {
        return view('vehiculos.index');
    }

    public function edit($id)
    {
        $vehiculo = Vehiculos::find($id);
        return view('vehiculos.edit', compact('vehiculo'));
    }
    public function create()
    {
        return view('vehiculos.create' );
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required',
            'modelo' => 'nullable',
            'fecha_compra' => 'nullable',
            'fecha_itv' => 'nullable',
        ]);

        // Crear la nómina
        $vehiculo = new Vehiculos;
        $vehiculo->matricula = $request->matricula;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->fecha_compra = $request->fecha_compra;
        $vehiculo->fecha_itv = $request->fecha_itv;

        $vehiculo->save();

        return redirect()->route('vehiculos.edit',$vehiculo->id)->with('toast', [
                'icon' => 'success',
                'mensaje' => 'El vehiculo se creó correctamente'
            ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'matricula' => 'required',
            'modelo' => 'nullable',
            'fecha_compra' => 'nullable',
            'fecha_itv' => 'nullable',
        ]);

        $vehiculo = Vehiculos::findOrFail($id);

        $vehiculo->matricula = $request->matricula;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->fecha_compra = $request->fecha_compra;
        $vehiculo->fecha_itv = $request->fecha_itv;

        $vehiculo->save();

        return redirect()->route('vehiculos.index')->with('toast', [
            'icon' => 'success',
            'mensaje' => 'El vehiculo se actualizó correctamente'
        ]);
    }

    public function destroy(Request $request)
    {
        $vehiculo = Vehiculos::find($request->id);

        if (!$vehiculo) {
            return response()->json([
                'status' => false,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }

        $vehiculo->delete();
        return response()->json([
            'status' => true,
            'mensaje' => 'El vehiculo fue borrado correctamente'
        ]);
    }
}
