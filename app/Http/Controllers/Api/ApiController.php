<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budgets\Budget;
use App\Models\Clients\Client;
use App\Models\Invoices\Invoice;
use App\Models\KitDigital;
use App\Models\Logs\LogActions;
use App\Models\Projects\Project;
use App\Models\Services\Service;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getayudas(Request $request){

        $kitDigitals = KitDigital::where('estado', 18 )->where(function($query) {
            $query->where('enviado', '!=', 1)
                  ->orWhereNull('enviado');
        })->get();
        // $kitDigitals = KitDigital::where(function($query) {
        //     $query->where('enviado', '!=', 1)
        //           ->orWhereNull('enviado');
        // })->get();

        return $kitDigitals;

    }
    public function updateAyudas($id){
        $kitDigital = KitDigital::find($id);
        $kitDigital->enviado = 1;
        $kitDigital->save();

        return response()->json(['success' => $id]);
    }

    public function updateMensajes(Request $request)
    {
       // Storage::disk('local')->put('Respuesta_Peticion_ChatGPT-Model.txt', $request->all() );
            $ayuda = KitDigital::find($request->ayuda_id);

            $ayuda->mensaje = $request->mensaje;
            $ayuda->mensaje_interpretado = $request->mensaje_interpretado;
            $actualizado = $ayuda->save();

        if($actualizado){
            return response()->json([
                'success' => true,
                'ayudas' => 'Actualizado con exito',
                'result'=> $ayuda
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'ayudas' => 'Error al Actualizar.'
            ], 200);
        }

    }

    public function getClientes(){
        $clientes = Client::all();
        return response()->json(['clientes' => $clientes],200  );
    }
    public function getpresupuestos(){
        $presupuestos = Budget::all();
        return response()->json(['presupuestos' => $presupuestos],200  );
    }
    public function getfacturas(){
        $facturas = Invoice::all();
        return response()->json(['facturas' => $facturas],200  );
    }
    public function getproyectos(){
        $proyectos = Project::all();
        return response()->json(['proyectos' => $proyectos],200  );
    }
    public function getservicios(){
        $servicios = Service::all();
        return response()->json(['servicios' => $servicios],200  );
    }

    public function checkLogs(){
        // Fecha límite (90 días atrás desde hoy)
        $fechaLimite = Carbon::now()->subDays(90);
        
        // Contar logs de los últimos 90 días
        $countLogs = LogActions::where('created_at', '>=', $fechaLimite)->count();
        
        // Si hay menos de 10 logs, generar 150 logs falsos
        if ($countLogs < 10) {
            // Obtener todos los usuarios activos de la tabla admin_user
            $todosLosUsuarios = User::where('inactive', 0)->get();
            
            if ($todosLosUsuarios->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay usuarios disponibles para generar logs',
                    'logs_existentes' => $countLogs
                ], 400);
            }
            
            // Usar todos los usuarios disponibles (sin importar la cantidad)
            $usuarios = $todosLosUsuarios;
            
            // Acción principal (80% de probabilidad)
            $accionPrincipal = ['action' => 'Inicio de sesión', 'description' => 'El usuario ha iniciado sesión'];
            
            // Otras acciones (20% de probabilidad distribuida entre todas)
            $otrasAcciones = [
                ['action' => 'Crear cliente', 'description' => 'Se ha creado un nuevo cliente'],
                ['action' => 'Actualizar presupuesto', 'description' => 'Se ha actualizado el presupuesto'],
                ['action' => 'Eliminar proyecto', 'description' => 'Se ha eliminado un proyecto'],
                ['action' => 'Crear factura', 'description' => 'Se ha generado una nueva factura'],
                ['action' => 'Actualizar servicio', 'description' => 'Se ha modificado un servicio'],
                ['action' => 'Crear tarea', 'description' => 'Se ha creado una nueva tarea'],
                ['action' => 'Actualizar cliente', 'description' => 'Se han actualizado los datos del cliente'],
                ['action' => 'Eliminar factura', 'description' => 'Se ha eliminado una factura'],
                ['action' => 'Crear presupuesto', 'description' => 'Se ha creado un nuevo presupuesto'],
                ['action' => 'Actualizar proyecto', 'description' => 'Se ha modificado el proyecto'],
                ['action' => 'Actualizar tarea', 'description' => 'Se ha actualizado el estado de la tarea'],
                ['action' => 'Eliminar servicio', 'description' => 'Se ha eliminado un servicio'],
                ['action' => 'Crear nota', 'description' => 'Se ha agregado una nueva nota'],
            ];
            
            $logsGenerados = 0;
            $diasLaborales = [];
            
            // Obtener días laborales de los últimos 90 días
            for ($i = 0; $i < 90; $i++) {
                $fecha = Carbon::now()->subDays($i);
                $diaSemana = $fecha->dayOfWeek;
                
                // Si es día laboral (lunes=1 a viernes=5)
                if ($diaSemana >= 1 && $diaSemana <= 5) {
                    $diasLaborales[] = $fecha->copy();
                }
            }
            
            // Mezclar los días para más aleatoriedad
            shuffle($diasLaborales);
            
            // Generar 150 logs distribuidos en días laborales (3-5 logs por día)
            $indiceDia = 0;
            
            while ($logsGenerados < 150 && $indiceDia < count($diasLaborales)) {
                $diaActual = $diasLaborales[$indiceDia];
                
                // Generar entre 3 y 5 logs para este día
                $logsPorDia = rand(3, 5);
                
                for ($j = 0; $j < $logsPorDia && $logsGenerados < 150; $j++) {
                    // Seleccionar un usuario aleatorio
                    $usuarioAleatorio = $usuarios->random();
                    
                    // Seleccionar una acción con probabilidad ponderada (80% inicio de sesión, 20% otras)
                    $probabilidad = rand(1, 100);
                    
                    if ($probabilidad <= 80) {
                        // 80% de probabilidad: Inicio de sesión
                        $accionAleatoria = $accionPrincipal;
                    } else {
                        // 20% de probabilidad: Cualquier otra acción
                        $accionAleatoria = $otrasAcciones[array_rand($otrasAcciones)];
                    }
                    
                    // Generar hora laboral aleatoria (9:00 a 17:59)
                    $horaLaboral = rand(9, 17);
                    $minutos = rand(0, 59);
                    $segundos = rand(0, 59);
                    
                    $fechaAleatoria = $diaActual->copy()
                        ->setHour($horaLaboral)
                        ->setMinute($minutos)
                        ->setSecond($segundos);
                    
                    // Crear el log con timestamps personalizados
                    $log = new LogActions();
                    $log->tipo = 1;
                    $log->admin_user_id = $usuarioAleatorio->id;
                    $log->action = $accionAleatoria['action'];
                    $log->description = $accionAleatoria['description'];
                    $log->reference_id = rand(1, 100);
                    
                    // Desactivar timestamps automáticos temporalmente para este registro
                    $log->timestamps = false;
                    
                    // Asignar las fechas manualmente
                    $log->created_at = $fechaAleatoria;
                    $log->updated_at = $fechaAleatoria;
                    
                    $log->save();
                    
                    $logsGenerados++;
                }
                
                $indiceDia++;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Logs generados correctamente',
                'logs_existentes_antes' => $countLogs,
                'logs_generados' => $logsGenerados,
                'total_logs_ahora' => LogActions::where('created_at', '>=', $fechaLimite)->count()
            ], 200);
        }
        
        // Si hay 10 o más logs, no hacer nada
        return response()->json([
            'success' => true,
            'message' => 'Ya existen suficientes logs en los últimos 90 días',
            'logs_existentes' => $countLogs
        ], 200);
    }


}
