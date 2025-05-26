<?php

namespace App\Http\Controllers\Avisos;

use App\Http\Controllers\Controller;
use App\Models\Alerts\Alert;
use App\Models\Avisos\Extintores;
use App\Models\Avisos\Oca;
use App\Models\Avisos\Plaga;
use App\Models\Avisos\Seguro;
use App\Models\Bajas\Baja;
use App\Models\Salones\Salon;
use App\Models\Suppliers\Supplier;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisosController extends Controller
{

    //Index

    public function indexSeguro()
    {
        return view('seguro.index');
    }
    public function indexExtintores()
    {
        return view('extintores.index');
    }
    public function indexOca()
    {
        return view('oca.index');
    }
    public function indexPlagas()
    {
        return view('plagas.index');
    }


    //Create

    public function createSeguro()
    {
        $salones = Salon::all();
        return view('seguro.create', compact('salones'));
    }
    public function createExtintores()
    {
        $salones = Salon::all();
        return view('extintores.create', compact('salones'));
    }
    public function createOca()
    {
        $salones = Salon::all();
        return view('oca.create', compact('salones'));
    }
    public function createPlagas()
    {
        $salones = Salon::all();
        return view('plagas.create', compact('salones'));
    }


    //Edit

    public function editSeguros($id)
    {
        $documento = Seguro::find($id);
        $salones = Salon::all();
        $proveedores = Supplier::all();
        return view('seguro.edit', compact('salones', 'documento', 'proveedores'));
    }
    public function editExtintores($id)
    {
        $documento = Extintores::find($id);
        $salones = Salon::all();
        return view('seguro.edit', compact('salones', 'documento'));
    }
    public function editOca($id)
    {
        $documento = Oca::find($id);
        $salones = Salon::all();
        return view('seguro.edit', compact('salones', 'documento'));
    }
    public function editPlagas($id)
    {
        $documento = Plaga::find($id);
        $salones = Salon::all();
        return view('seguro.edit', compact('salones', 'documento'));
    }



    //Store

    public function store(Request $request)
    {
        $this->validate($request, [
            'alta' => 'nullable',
            'caducidad' => 'nullable',
            'descripcion' => 'nullable',
            'salon_id' => 'nullable', // Asegura que cada archivo sea válido
            'proveedor_id' => 'nullable', // Asegura que cada archivo sea válido
        ]);


        $data = new Baja();
        $data->admin_user_id = Auth::user()->id;
        $data->alta = $request->inicio;
        $data->caducidad = $request->caducidad;
        $data->descripcion = $request->descripcion;
        $data->salon_id = $request->salon_id;
        $data->proveedor_id = $request->proveedor_id;
        $data->save();

        return redirect()->route('seguros.index')->with('toast',[
            'icon' => 'success',
            'mensaje' => 'Aviso de seguro creado correctamente'
        ]);
    }


    public function update(Request $request , Baja $baja)
    {
        $this->validate($request, [
            'admin_user_id' => 'required',
            'inicio' => 'required',
            'fin' => 'nullable',
            'observacion' => 'nullable',
            'archivos.*' => 'file|nullable', // Asegura que cada archivo sea válido y que no se supere el tamaño máximo permitido
        ]);
        $baja->admin_user_id = $request->admin_user_id;
        $baja->inicio = $request->inicio;
        $baja->fin = $request->fin;
        $baja->observacion = $request->observacion;
        // Almacenar múltiples archivos en un array y codificar a JSON
        if ($request->hasFile('archivos')) {
            $paths = $baja->archivos ? json_decode($baja->archivos, true) : [];

            foreach ($request->file('archivos') as $archivo) {
                $filename = 'Baja_' . $request->admin_user_id . '_' . today()->format('Y_m_d') . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $path = $archivo->storeAs('Bajas', $filename, 'public');
                $paths[] = $path;
            }
            $baja->archivos = json_encode($paths);
        }

        $baja->save();

        return redirect()->route('bajas.index')->with('success', 'Baja actualizada exitosamente');
    }

    public function destroy(Request $request,)
    {
        $baja = Baja::find($request->id);
        if($baja){
            $baja->delete();
            return response()->json([
                'status' => true,
                'mensaje' => 'Baja eliminada con éxito!'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'mensaje' => 'Baja no encontrada'
            ]);
        }

    }

}
