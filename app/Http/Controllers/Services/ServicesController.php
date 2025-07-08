<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Services\Service;
use App\Models\Services\ServiceCategories;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        $servicios = Service::paginate(2);
        return view('services.index', compact('servicios'));
    }

    public function create() {
        $categorias = ServiceCategories::where('inactive',0)->get();
        return view('services.create', compact('categorias'));
    }


    public function store(Request $request) {

        // Validamos los campos
        $data = $this->validate($request, [
            'title' => 'required|max:255',
            'services_categories_id' => 'required|integer',
            'concept' => 'required',
            'price' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'El titulo es requerido para continuar',
            'title.max' => 'El titulo no pueder tener mas de 255 caracteres',
            'services_categories_id.required' => 'La categoria es requerida para continuar',
            'concept.required' => 'El concepto es requerido para continuar',
            'concept.max' => 'El concepto no pueder tener mas de 255 caracteres',
            'price.required' => 'El precio es requerido para continuar',
            'image.image' => 'El archivo debe ser una imagen',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif',
            'image.max' => 'La imagen no puede ser mayor a 2MB',
        ]);

        // Manejar la subida de imagen
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/services'), $imageName);
            $data['image'] = 'storage/services/' . $imageName;
        }

        $data['estado'] = 1;
        $data['order'] = 1;

        $servicioCreado = Service::create($data);


        return redirect()->route('servicios.edit', $servicioCreado->id)->with('toast', [
                'icon' => 'success',
                'mensaje' => 'El servicio creado con exito'
        ]);

    }

    public function edit(string $id){
        $servicio = Service::find($id);
        $categorias = ServiceCategories::where('inactive',0)->get();
        if (!$servicio) {
            session()->flash('toast', [
                'icon' => 'error',
                'mensaje' => 'El servicio no existe'
            ]);
            return redirect()->route('servicios.index');
        }
        return view('services.edit', compact('servicio','categorias'));
    }

    public function update(string $id ,Request $request) {
        $servicio = Service::find($id);
        $data = $this->validate($request, [
            'title' => 'required|max:255',
            'services_categories_id' => 'required|integer',
            'concept' => 'required',
            'price' => 'required',
            'inactive' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'El titulo es requerido para continuar',
            'title.max' => 'El titulo no pueder tener mas de 255 caracteres',
            'services_categories_id.required' => 'La categoria es requerida para continuar',
            'concept.required' => 'El concepto es requerido para continuar',
            'concept.max' => 'El concepto no pueder tener mas de 255 caracteres',
            'price.required' => 'El precio es requerido para continuar',
            'image.image' => 'El archivo debe ser una imagen',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif',
            'image.max' => 'La imagen no puede ser mayor a 2MB',
        ]);

        // Manejar la subida de imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($servicio->image && file_exists(public_path($servicio->image))) {
                unlink(public_path($servicio->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/services'), $imageName);
            $data['image'] = 'storage/services/' . $imageName;
        }

        $petitionCreado = $servicio->update($data);
        return redirect()->route('servicios.index')->with('toast', [
                'icon' => 'success',
                'mensaje' => 'El servicio actualizado con exito'
        ]);
    }

    public function destroy(Request $request) {
        $servicio = Service::find($request->id);

        if (!$servicio) {
            return response()->json([
                'error' => true,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }

        // Eliminar imagen si existe
        if ($servicio->image && file_exists(public_path($servicio->image))) {
            unlink(public_path($servicio->image));
        }

        $servicio->delete();

        return response()->json([
            'error' => false,
            'mensaje' => 'El servicio fue borrado correctamente'
        ]);
    }



}
