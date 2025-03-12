<?php

namespace App\Http\Controllers\Maquinas;

use App\Http\Controllers\Controller;
use App\Models\Maquinas\MaquinaCategoria;
use Illuminate\Http\Request;

class MaquinasCategoriesController extends Controller
{
    public function index()
    {
        $servicios = MaquinaCategoria::paginate(2);
        return view('maquinas-categories.index', compact('servicios'));
    }

    public function create() {
        return view('maquinas-categories.create');
    }

    public function store(Request $request) {
        // Validamos los campos
        $data = $this->validate($request, [
            'nombre' => 'required|max:255',

        ], [
            'nombre.required' => 'El titulo es requerido para continuar',
            'nombre.max' => 'El titulo no pueder tener mas de 255 caracteres',
        ]);

        $categoriaCreada = MaquinaCategoria::create($data);

        if($categoriaCreada){
            return redirect()->route('maquinasCategoria.edit', $categoriaCreada->id)->with('toast', [
                    'icon' => 'success',
                    'mensaje' => 'El categoria creada con exito'
            ]);
        }else{
            return redirect()->back()->with('toast', [
                'icon' => 'error',
                'mensaje' => 'Error en la creacion de la categoria'
            ]);
        }

    }

    public function edit(string $id){
        $categoria = MaquinaCategoria::find($id);
        if (!$categoria) {
            session()->flash('toast', [
                'icon' => 'error',
                'mensaje' => 'La categoria no existe'
            ]);
            return redirect()->route('maquinasCategoria.index');
        }
        return view('maquinas-categories.edit', compact('categoria'));
    }

    public function update(string $id ,Request $request) {
        $categoria = MaquinaCategoria::find($id);
        $data = $this->validate($request, [
            'nombre' => 'required|max:255',

        ], [
            'nombre.required' => 'El titulo es requerido para continuar',
            'nombre.max' => 'El titulo no pueder tener mas de 255 caracteres',
        ]);

        $categoriaCreada = $categoria->update($data);

        return redirect()->route('maquinasCategoria.index')->with('toast', [
                'icon' => 'success',
                'mensaje' => 'El servicio actualizado con exito'
        ]);
    }

    public function destroy(Request $request) {
        $servicio = MaquinaCategoria::find($request->id);

        if (!$servicio) {
            return response()->json([
                'error' => true,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }

        $servicio->delete();

        return response()->json([
            'error' => false,
            'mensaje' => 'La categoria de servicio fue borrada correctamente'
        ]);
    }


}
