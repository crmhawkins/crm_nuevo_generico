<?php

namespace App\Http\Controllers\Neveras;

use App\Http\Controllers\Controller;
use App\Models\Neveras\Neveras;
use App\Models\Salones\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NeverasController extends Controller
{
    public function index()
    {
        return view('neveras.index');
    }

    public function edit($id)
    {
        $nevera = Neveras::find($id);
        $salones = Salon::all();
        return view('neveras.edit', compact('nevera','salones'));
    }
    public function create()
    {
        $salones = Salon::all();
        return view('neveras.create', compact('salones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'temperatura_actual' => 'required',
            'temperatura_maxima' => 'nullable',
            'temperatura_minima' => 'nullable',
            'fecha' => 'required',
            'hora' => 'required',
        ]);

        // Crear la control temperatura
        $nevera = new Neveras;
        $nevera->nombre = $request->nombre;
        $nevera->temperatura_actual = $request->temperatura_actual;
        $nevera->temperatura_maxima = $request->temperatura_maxima;
        $nevera->temperatura_minima = $request->temperatura_minima;
        $nevera->fecha = $request->fecha;
        $nevera->hora = $request->hora;
        $nevera->observaciones = $request->observaciones;
        $nevera->admin_user_id = Auth::user()->id;
        $nevera->salon_id = $request->salon_id;


        $nevera->save();

        return redirect()->route('neveras.edit',$nevera->id)->with('toast', [
                'icon' => 'success',
                'mensaje' => 'El nevera se creó correctamente'
            ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'temperatura_actual' => 'required',
            'temperatura_maxima' => 'nullable',
            'temperatura_minima' => 'nullable',
            'fecha' => 'required',
            'hora' => 'required',
        ]);

        $nevera = Neveras::findOrFail($id);

        $nevera->nombre = $request->nombre;
        $nevera->temperatura_actual = $request->temperatura_actual;
        $nevera->temperatura_maxima = $request->temperatura_maxima;
        $nevera->temperatura_minima = $request->temperatura_minima;
        $nevera->fecha = $request->fecha;
        $nevera->hora = $request->hora;
        $nevera->observaciones = $request->observaciones;
        $nevera->salon_id = $request->salon_id;

        $nevera->save();

        return redirect()->route('neveras.index')->with('toast', [
            'icon' => 'success',
            'mensaje' => 'El nevera se actualizó correctamente'
        ]);
    }

    public function destroy(Request $request)
    {
        $nevera = Neveras::find($request->id);

        if (!$nevera) {
            return response()->json([
                'status' => false,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }

        $nevera->delete();
        return response()->json([
            'status' => true,
            'mensaje' => 'El nevera fue borrado correctamente'
        ]);
    }
}
