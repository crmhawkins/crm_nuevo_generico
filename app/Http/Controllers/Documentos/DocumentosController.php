<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use App\Models\Documentos\Documento;
use App\Models\Salones\Salon;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentosController extends Controller
{
    public function index()
    {
        return view('documentos.index');
    }

    public function edit($id)
    {
        $documento = Documento::find($id);
        $salones = Salon::all();
        return view('documentos.edit', compact('documento','salones'));
    }
    public function create()
    {
        $salones = Salon::all();
        return view('documentos.create', compact('salones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'salon_id' => 'required|exists:salones,id',
            'nombre' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'archivo' => 'nullable|file|mimes:pdf|max:2048', // Asegura que sea un PDF y no supere los 2MB
        ]);

        $path = null;

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $filename = 'Documento_'.$request->nombre ?? 'N/A'.'_'.$request->fecha.'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('documentos', $filename, 'public');
        }

        // Crear la nómina
        $documento = new Documento;
        $documento->admin_user_id = Auth::user()->id;
        $documento->salon_id = $request->salon_id;
        $documento->nombre = $request->nombre;
        $documento->fecha = $request->fecha;
        $documento->archivo = $path;
        $documento->save();

        return redirect()->route('documentos.edit',$documento->id)->with('toast', [
                'icon' => 'success',
                'mensaje' => 'La documento se creo correctamente'
            ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'salon_id' => 'required|exists:salones,id',
            'nombre' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'archivo' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $documento = Documento::findOrFail($id);

        if ($request->hasFile('archivo')) {
            // Eliminar el archivo anterior si existe
            Storage::delete('public/' . $documento->archivo);

            // Subir el nuevo archivo
            $file = $request->file('archivo');
            $filename =  'Documento_'.$request->nombre ?? 'N/A'.'_'.$request->fecha.'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('documentos', $filename, 'public');
            $documento->archivo = $path;
        }

        // Actualizar otros campos
        $documento->nombre = $request->nombre;
        $documento->salon_id = $request->salon_id;
        $documento->fecha = $request->fecha;
        $documento->save();

        return redirect()->route('documentos.index')->with('toast', [
            'icon' => 'success',
            'mensaje' => 'La documento se actualizo correctamente'
        ]);
    }

    public function destroy(Request $request)
    {
        $documento = Documento::find($request->id);

        if (!$documento) {
            return response()->json([
                'status' => false,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }

        $documento->delete();
        return response()->json([
            'status' => true,
            'mensaje' => 'La Documento fue borrada correctamente'
        ]);
    }
}
