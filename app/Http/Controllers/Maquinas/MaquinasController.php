<?php

namespace App\Http\Controllers\Maquinas;

use App\Http\Controllers\Controller;
use App\Models\Almacenes\Almacen;
use App\Models\Clients\ClientLocal;
use App\Models\Maquinas\Maquina;
use App\Models\Maquinas\MaquinaCategoria;
use App\Models\Salones\Salon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaquinasController extends Controller
{
    public function index()
    {
        return view('maquinas.index');
    }

    public function edit($id)
    {
        $maquina = Maquina::find($id);
        $categorias = MaquinaCategoria::all();
        $almacenes = Almacen::all();
        $salones = Salon::all();
        $locales = ClientLocal::all();
        return view('maquinas.edit', compact('maquina','categorias','almacenes','salones','locales'));
    }
    public function create()
    {
        $categorias = MaquinaCategoria::all();
        $almacenes = Almacen::all();
        $salones = Salon::all();
        $locales = ClientLocal::all();
        return view('maquinas.create', compact('categorias','almacenes','salones','locales'));
    }

    public function store(Request $request)
    {
        // Validamos los campos
        $data =  $this->validate($request, [
            'nombre' => 'required|max:200',
            'n_serie' => 'nullable',
            'categoria_id' => 'nullable',
            'otros' => 'nullable',
            'almacen_id' => 'nullable',
            'salon_id' => 'nullable',
            'local_id' => 'nullable',
        ], [
            'nombre.required' => 'El nombre es requerido para continuar',
        ]);

        // $data = $request->all();

        $crearDominio = Maquina::create($data);

        if (!$crearDominio) {

            return redirect()->back()->with('toast',[
                'icon' => 'error',
                'mensaje' => 'Error al crear la maquina'
            ]);
        }else{

            return redirect()->route('maquinas.index')->with('toast',[
                'icon' => 'success',
                'mensaje' => 'La maquina se creo correctamente'
            ]);
        }

    }

    public function update(Request $request, $id)
    {

        $almacen = Maquina::find($id);
        // Validamos los campos
        $data =  $this->validate($request, [
            'nombre' => 'required|max:200',
            'direccion' => 'nullable',
            'n_serie' => 'nullable',
            'categoria_id' => 'nullable',
            'otros' => 'nullable',
            'almacen_id' => 'nullable',
            'salon_id' => 'nullable',
            'local_id' => 'nullable',
        ], [
            'nombre.required' => 'El nombre es requerido para continuar',
        ]);

        $salonSaved=$almacen->update(attributes: $data);

        if (!$salonSaved) {

            return redirect()->back()->with('toast',[
                'icon' => 'error',
                'mensaje' => 'Error al actualizar la maquina'
            ]);
        }else{

            return redirect()->route('maquinas.index')->with('toast',[
                'icon' => 'success',
                'mensaje' => 'La maquina se actualizo correctamente'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $almacen = Maquina::find($request->id);

        if (!$almacen) {
            return response()->json([
                'error' => true,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }

        $almacen->delete();
        return response()->json([
            'error' => false,
            'mensaje' => 'La maquina fue borrada correctamente'
        ]);
    }

}
