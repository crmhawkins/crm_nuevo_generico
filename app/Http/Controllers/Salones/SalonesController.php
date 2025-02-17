<?php

namespace App\Http\Controllers\Salones;

use App\Http\Controllers\Controller;
use App\Models\Clients\Client;
use App\Models\Dominios\estadosDominios;
use App\Models\Salones\Salon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalonesController extends Controller
{
    public function index()
    {
        return view('salones.index');
    }

    public function edit($id)
    {
        $salon = Salon::find($id);
        return view('salones.edit', compact('salon'));
    }
    public function create()
    {
        return view('salones.create');
    }

    public function store(Request $request)
    {
        // Validamos los campos
        $data =  $this->validate($request, [
            'nombre' => 'required|max:200',
            'direccion' => 'required',
            'Apertura' => 'required',
            'Cierre' => 'required',

        ], [
            'nombre.required' => 'El nombre es requerido para continuar',
            'direccion.required' => 'La dirrección es requerida para continuar',
            'Apertura.required' => 'La hora de apertura es requerida para continuar',
            'Cierre.required' => 'La hora de cierre es requerida para continuar',

        ]);

        // $data = $request->all();

        $crearDominio = Salon::create($data);

        if (!$crearDominio) {

            return redirect()->back()->with('toast',[
                'icon' => 'error',
                'mensaje' => 'Error al crear el salon'
            ]);
        }else{

            return redirect()->route('salones.index')->with('toast',[
                'icon' => 'success',
                'mensaje' => 'El salon se creo correctamente'
            ]);
        }

    }

    public function update(Request $request, $id)
    {

        $salon = Salon::find($id);
        // Validamos los campos
        $data =  $this->validate($request, [
            'nombre' => 'required|max:200',
            'direccion' => 'required',
            'Apertura' => 'required',
            'Cierre' => 'required',

        ], [
            'nombre.required' => 'El nombre es requerido para continuar',
            'direccion.required' => 'La dirrección es requerida para continuar',
            'Apertura.required' => 'La hora de apertura es requerida para continuar',
            'Cierre.required' => 'La hora de cierre es requerida para continuar',

        ]);

        $salonSaved=$salon->update(attributes: $data);

        if (!$salonSaved) {

            return redirect()->back()->with('toast',[
                'icon' => 'error',
                'mensaje' => 'Error al actualizar el salon'
            ]);
        }else{

            return redirect()->route('salones.index')->with('toast',[
                'icon' => 'success',
                'mensaje' => 'El salon se actualizo correctamente'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $salon = Salon::find($request->id);

        if (!$salon) {
            return response()->json([
                'error' => true,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }

        $salon->delete();
        return response()->json([
            'error' => false,
            'mensaje' => 'El salon fue borrado correctamente'
        ]);
    }

    public function show($id)
    {
        $salon = Salon::find($id);
        return view('salones.show', compact('salon'));
    }
}
