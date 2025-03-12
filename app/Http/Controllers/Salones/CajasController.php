<?php

namespace App\Http\Controllers\Salones;

use App\Http\Controllers\Controller;
use App\Models\Clients\Client;
use App\Models\Dominios\estadosDominios;
use App\Models\Salones\Caja as Cabina;
use App\Models\Salones\Salon;
use App\Models\Users\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajasController extends Controller
{
    public function index()
    {
        return view('cajas.index');
    }

    // public function create()
    // {
    //     $salones = Salon::all();
    //     return view('cabinas.create',compact('salones'));
    // }

    // public function store(Request $request)
    // {
    //     // Validamos los campos
    //     if(!$request->has('pin')){
    //         return redirect()->back()->with('toast',[
    //             'icon' => 'error',
    //             'mensaje' => 'El pin es requerido para continuar'
    //         ]);
    //     }
    //     $user = User::where('pin',$request->pin)->first();


    //     $data =  $this->validate($request, [
    //         'monto' => 'required',
    //         'billetes_500' => 'nullable',
    //         'billetes_200' => 'nullable',
    //         'billetes_100' => 'nullable',
    //         'billetes_50' => 'nullable',
    //         'billetes_20' => 'nullable',
    //         'billetes_10' => 'nullable',
    //         'billetes_5' => 'nullable',
    //         'monedas_200' => 'nullable',
    //         'monedas_100' => 'nullable',
    //         'monedas_50' => 'nullable',
    //         'monedas_20' => 'nullable',
    //         'monedas_10' => 'nullable',
    //         'monedas_5' => 'nullable',
    //         'monedas_2' => 'nullable',
    //         'monedas_1' => 'nullable',
    //     ], [
    //         'monto.required' => 'La Cantidad es requerida para continuar',
    //     ]);

    //     $data['admin_user_id'] = $user->id;
    //     $data['salon_id'] = Auth::user()->salon_id;
    //     $data['fecha'] = Carbon::now(new DateTimeZone('Europe/Madrid'));

    //     $crearcabina = Cabina::create($data);

    //     if (!$crearcabina) {

    //         return redirect()->back()->with('toast',[
    //             'icon' => 'error',
    //             'mensaje' => 'Error al crear el salon'
    //         ]);
    //     }else{

    //         return redirect()->route('cabinas.index')->with('toast',[
    //             'icon' => 'success',
    //             'mensaje' => 'El salon se creo correctamente'
    //         ]);
    //     }

    // }

    // public function update(Request $request, $id)
    // {

    //     $salon = Salon::find($id);
    //     // Validamos los campos
    //     $data =  $this->validate($request, [
    //         'nombre' => 'required|max:200',
    //         'direccion' => 'required',
    //         'Apertura' => 'required',
    //         'Cierre' => 'required',

    //     ], [
    //         'nombre.required' => 'El nombre es requerido para continuar',
    //         'direccion.required' => 'La dirrección es requerida para continuar',
    //         'Apertura.required' => 'La hora de apertura es requerida para continuar',
    //         'Cierre.required' => 'La hora de cierre es requerida para continuar',

    //     ]);

    //     $salonSaved=$salon->update(attributes: $data);

    //     if (!$salonSaved) {

    //         return redirect()->back()->with('toast',[
    //             'icon' => 'error',
    //             'mensaje' => 'Error al actualizar el salon'
    //         ]);
    //     }else{

    //         return redirect()->route('cabinas.index')->with('toast',[
    //             'icon' => 'success',
    //             'mensaje' => 'El salon se actualizo correctamente'
    //         ]);
    //     }
    // }

    // public function destroy(Request $request)
    // {
    //     $salon = Salon::find($request->id);

    //     if (!$salon) {
    //         return response()->json([
    //             'error' => true,
    //             'mensaje' => "Error en el servidor, intentelo mas tarde."
    //         ]);
    //     }

    //     $salon->delete();
    //     return response()->json([
    //         'error' => false,
    //         'mensaje' => 'El salon fue borrado correctamente'
    //     ]);
    // }

    // public function show($id)
    // {
    //     $cabina = Cabina::find($id);
    //     $salon = Salon::find($cabina->salon_id);
    //     return view('cabinas.show', compact('salon','cabina'));
    // }
}
