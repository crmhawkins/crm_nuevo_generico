<?php

namespace App\Http\Controllers\Horas;

use App\Exports\JornadasExport;
use App\Exports\JornadasFichajeExport;
use App\Http\Controllers\Controller;
use App\Models\Alerts\Alert;
use App\Models\Bajas\Baja;
use App\Models\Holidays\HolidaysPetitions;
use App\Models\Jornada\Jornada;
use App\Models\Tasks\LogTasks;
use App\Models\Users\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HorasController extends Controller
{


    protected function indexHoras(Request $request){
        // Usar el nuevo sistema de fichaje
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->endOfMonth()->format('Y-m-d'));
        $usuarioFiltro = $request->input('usuario_id');
        
        // Obtener todos los usuarios activos
        $usuarios = User::where('inactive', 0)->get();
        
        // Construir query base para fichajes
        $query = \App\Models\Fichaje::with('user')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        // Aplicar filtro de usuario si está seleccionado
        if ($usuarioFiltro) {
            $query->where('user_id', $usuarioFiltro);
        }
        
        // Obtener fichajes con paginación
        $fichajes = $query->orderBy('fecha', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);
        
        // Calcular estadísticas
        $estadisticas = $this->calcularEstadisticasFichaje($fechaInicio, $fechaFin, $usuarioFiltro);
        
        return view('horas.index', compact(
            'fichajes', 
            'usuarios', 
            'fechaInicio', 
            'fechaFin', 
            'usuarioFiltro',
            'estadisticas'
        ));
    }
    
    private function calcularEstadisticasFichaje($fechaInicio, $fechaFin, $usuarioFiltro = null)
    {
        $query = \App\Models\Fichaje::whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        if ($usuarioFiltro) {
            $query->where('user_id', $usuarioFiltro);
        }
        
        $fichajes = $query->get();
        
        // Calcular tiempo trabajado dinámicamente
        $totalTiempoTrabajado = 0;
        $totalTiempoPausa = 0;
        
        foreach ($fichajes as $fichaje) {
            $tiempoTrabajado = $this->calcularTiempoTrabajadoFichaje($fichaje);
            $tiempoPausa = $this->calcularTiempoPausaFichaje($fichaje);
            
            $totalTiempoTrabajado += $tiempoTrabajado;
            $totalTiempoPausa += $tiempoPausa;
        }
        
        return [
            'total_jornadas' => $fichajes->count(),
            'total_tiempo_trabajado' => $totalTiempoTrabajado,
            'total_tiempo_pausa' => $totalTiempoPausa,
            'jornadas_completas' => $fichajes->whereNotNull('hora_salida')->count(),
            'jornadas_activas' => $fichajes->whereNull('hora_salida')->count(),
        ];
    }
    
    private function calcularTiempoTrabajadoFichaje($fichaje)
    {
        if (!$fichaje->hora_entrada) {
            return 0;
        }
        
        $horaSalida = $fichaje->hora_salida ?? now();
        $tiempoTotal = $fichaje->hora_entrada->diffInMinutes($horaSalida);
        
        // Restar tiempo de pausa si existe
        $tiempoPausa = $this->calcularTiempoPausaFichaje($fichaje);
        
        return max(0, $tiempoTotal - $tiempoPausa);
    }
    
    private function calcularTiempoPausaFichaje($fichaje)
    {
        if (!$fichaje->hora_pausa_inicio) {
            return 0;
        }
        
        $horaPausaFin = $fichaje->hora_pausa_fin ?? now();
        return $fichaje->hora_pausa_inicio->diffInMinutes($horaPausaFin);
    }
    public function jornadas(Request $request)
    {
        // Usar el mismo método que indexHoras para obtener datos de fichaje
        return $this->indexHoras($request);
    }

    public function create()
    {
        $usuarios = User::where('inactive',0)->get();
        return view('horas.create', ['usuarios' => $usuarios]);
    }

    public function edit($id)
    {
        $jornada = Jornada::find($id);
        return view('horas.edit', ['jornada' => $jornada]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'admin_user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
            'hora_pausa_inicio' => 'nullable|date_format:H:i',
            'hora_pausa_fin' => 'nullable|date_format:H:i',
            'estado' => 'required|in:trabajando,pausa,salida',
        ], [
            'admin_user_id.required' => 'El campo "Usuario" es requerido',
            'admin_user_id.exists' => 'El usuario seleccionado no existe',
            'fecha.required' => 'El campo "Fecha" es requerido',
            'fecha.date' => 'El campo "Fecha" debe ser una fecha válida',
            'hora_entrada.date_format' => 'El campo "Hora de entrada" debe tener formato HH:MM',
            'hora_salida.date_format' => 'El campo "Hora de salida" debe tener formato HH:MM',
            'hora_pausa_inicio.date_format' => 'El campo "Inicio de pausa" debe tener formato HH:MM',
            'hora_pausa_fin.date_format' => 'El campo "Fin de pausa" debe tener formato HH:MM',
            'estado.required' => 'El campo "Estado" es requerido',
            'estado.in' => 'El estado debe ser: trabajando, pausa o salida',
        ]);

        try {
            // Crear el fichaje usando el nuevo sistema
            $fichajeData = [
                'user_id' => $validatedData['admin_user_id'],
                'fecha' => $validatedData['fecha'],
                'estado' => $validatedData['estado'],
            ];

            // Agregar horas si están presentes
            if ($validatedData['hora_entrada']) {
                $fichajeData['hora_entrada'] = $validatedData['fecha'] . ' ' . $validatedData['hora_entrada'];
            }

            if ($validatedData['hora_salida']) {
                $fichajeData['hora_salida'] = $validatedData['fecha'] . ' ' . $validatedData['hora_salida'];
            }

            if ($validatedData['hora_pausa_inicio']) {
                $fichajeData['hora_pausa_inicio'] = $validatedData['fecha'] . ' ' . $validatedData['hora_pausa_inicio'];
            }

            if ($validatedData['hora_pausa_fin']) {
                $fichajeData['hora_pausa_fin'] = $validatedData['fecha'] . ' ' . $validatedData['hora_pausa_fin'];
            }

            // Calcular tiempo trabajado si hay entrada y salida
            if ($validatedData['hora_entrada'] && $validatedData['hora_salida']) {
                $entrada = Carbon::parse($fichajeData['hora_entrada']);
                $salida = Carbon::parse($fichajeData['hora_salida']);
                $fichajeData['tiempo_trabajado'] = $entrada->diffInMinutes($salida);
            } else {
                $fichajeData['tiempo_trabajado'] = 0;
            }

            // Calcular tiempo de pausa si hay inicio y fin de pausa
            if ($validatedData['hora_pausa_inicio'] && $validatedData['hora_pausa_fin']) {
                $pausaInicio = Carbon::parse($fichajeData['hora_pausa_inicio']);
                $pausaFin = Carbon::parse($fichajeData['hora_pausa_fin']);
                $fichajeData['tiempo_pausa'] = $pausaInicio->diffInMinutes($pausaFin);
            } else {
                $fichajeData['tiempo_pausa'] = 0;
            }

            // Crear el fichaje
            $fichaje = \App\Models\Fichaje::create($fichajeData);

            if (!$fichaje) {
                return redirect()->back()->with('toast', [
                    'icon' => 'error',
                    'mensaje' => 'Error al crear la jornada'
                ]);
            }

            return redirect()->route('horas.listado')->with('toast', [
                'icon' => 'success',
                'mensaje' => 'La jornada se creó correctamente'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('toast', [
                'icon' => 'error',
                'mensaje' => 'Error al crear la jornada: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ], [
            'start_time.required' => 'El campo "Hora de inicio" es requerido',
            'end_time.required' => 'El campo "Hora de fin" es requerido',
            'start_time.date' => 'El campo "Hora de inicio" debe ser una fecha valida',
            'end_time.date' => 'El campo "Hora de fin" debe ser una fecha valida',
        ]);

        $validatedData['start_time'] = Carbon::parse($validatedData['start_time']);
        $validatedData['end_time'] = Carbon::parse($validatedData['end_time']);

        $jornada = Jornada::find($id);
        $jornada->start_time = $validatedData['start_time'];
        $jornada->end_time = $validatedData['end_time'];
        if($jornada->end_time != null){
            $jornada->is_active = false;
        }
        $jornada->save();

        return redirect()->route('horas.listado')->with('toast', [
            'icon' => 'success',
            'mensaje' => 'La Jornada se actualizo correctamente'
        ]);

    }

    public function exportHoras(Request $request)
    {
        $fechaInicio = Carbon::parse($request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d')));
        $fechaFin = Carbon::parse($request->input('fecha_fin', now()->endOfMonth()->format('Y-m-d')));
        $usuarioId = $request->input('usuario_id');
        $departamentoId = $request->input('departamento_id');
        $año = $request->input('año');
        $mes = $request->input('mes');
        $buscar = $request->input('buscar');
        
        return Excel::download(new JornadasFichajeExport($fechaInicio, $fechaFin, $usuarioId, $departamentoId, $año, $mes, $buscar), 'jornadas_fichaje.xlsx');
    }

    public function vacaciones($ini, $fin, $id){
        $vacaciones = HolidaysPetitions::where('admin_user_id', $id)
        ->whereDate('from','>=', $ini)
        ->whereDate('to','<=', $fin)
        ->where('holidays_status_id', 1)
        ->get();

        $dias = $vacaciones->sum('total_days');

        return $dias;
    }

    public function puntualidad($ini, $fin, $id){
        $puntualidad = Alert::where('admin_user_id', $id)
        ->whereDate('created_at','>=', $ini)
        ->whereDate('created_at','<=', $fin)
        ->where('stage_id', 23)
        ->whereRaw('admin_user_id = reference_id')
        ->get();

        $dias = $puntualidad->count();

        return $dias;
    }

    public function horasTrabajadasDia($dia, $id){

        $totalWorkedSeconds = 0;
        $jornadas = Jornada::where('admin_user_id', $id)
        ->whereDate('start_time', $dia)
        ->get();

        // Se recorren los almuerzos de hoy
        foreach($jornadas as $jornada){
            $workedSeconds = Carbon::parse($jornada->start_time)->diffInSeconds($jornada->end_time ?? Carbon::now());
            $totalPauseSeconds = $jornada->pauses->sum(function ($pause) {
                return Carbon::parse($pause->start_time)->diffInSeconds($pause->end_time ?? Carbon::now());
            });
            $totalWorkedSeconds += $workedSeconds - $totalPauseSeconds;
        }
        $horasTrabajadasFinal = $totalWorkedSeconds / 60;

        return $horasTrabajadasFinal;
    }
    public function horaInicioJornada($dia, $id){

        $jornada = Jornada::where('admin_user_id', $id)
        ->whereDate('start_time', $dia)
        ->get()->first();
        if(!isset($jornada)){
            return 'N/A';
        }
        $inicio = Carbon::createFromFormat('Y-m-d H:i:s', $jornada->start_time, 'UTC');
        $inicioEspaña = $inicio->setTimezone('Europe/Madrid');

        return $inicioEspaña->format('H:i:s');
    }

    public function tiempoProducidoDia($dia, $id) {
        $tiempoTarea = 0;
        $tareasHoy = LogTasks::where('admin_user_id', $id)->whereDate('date_start','=', $dia)->get();
        foreach($tareasHoy as $tarea) {
            if ($tarea->status == 'Pausada') {
                $tiempoini = Carbon::parse($tarea->date_start);
                $tiempoFinal = Carbon::parse($tarea->date_end);
                $tiempoTarea +=  $tiempoFinal->diffInMinutes($tiempoini);
            }
        }
        return $tiempoTarea;
    }

    public function bajas($id, $ini, $fin,) {

        $diasTotales = 0;
        $bajas = Baja::where('admin_user_id', $id)
            ->where(function ($query) use ($ini, $fin) {
            $query->whereBetween('inicio', [$ini, $fin])
                  ->orWhereBetween('fin', [$ini, $fin])
                  ->orWhere(function ($query) use ($ini, $fin) {
                      $query->where('inicio', '<=', $ini)
                            ->where('fin', '>=', $fin);
                  });
        })->get();

        foreach ($bajas as $baja) {
            $inicioBaja = Carbon::parse($baja->inicio);
            $finBaja = Carbon::parse($baja->fin) ?? Carbon::now();

            // Ajustar fechas al intervalo especificado
            $fechaInicio = $inicioBaja->greaterThan($ini) ? $inicioBaja : $ini;
            $fechaFin = $finBaja->lessThan($fin) ? $finBaja : $fin;

            // Calcular los días entre las fechas ajustadas y sumarlos
            $dias = $fechaInicio->diffInDays($fechaFin) + 1;
            $diasTotales += $dias;
        }

        return $diasTotales;

    }

    public function calendar($id)
    {
        $user = User::where('id', $id)->first();

        // Obtener los eventos de tareas para el usuario
        $events = $this->getjornadas($id);
        // Convertir los eventos en formato adecuado para FullCalendar (si no están ya en ese formato)
        $eventData = [];
        foreach ($events as $event) {

            $inicio = Carbon::createFromFormat('Y-m-d H:i:s', $event[1], 'UTC');
            $inicioEspaña = $inicio->setTimezone('Europe/Madrid');
            if(isset($event[2])){
                $fin = Carbon::createFromFormat('Y-m-d H:i:s', $event[2], 'UTC');
                $finEspaña = $fin->setTimezone('Europe/Madrid');
            }

            $eventData[] = [
                'title' => $event[0],
                'start' => $inicioEspaña->toIso8601String(), // Aquí debería estar la fecha y hora de inicio
                'end' => $event[2] ? $finEspaña->toIso8601String() : null , // Aquí debería estar la fecha y hora de fin
                'allDay' => false, // Indica si el evento es de todos los días
                'color' =>$event[3]
            ];
        }
        // Datos adicionales de horas trabajadas y producidas
        $horas_hoy = $this->getHorasTrabajadasHoy($user);
        $horas_hoy2 = $this->getHorasTrabajadasHoy2($user);

        // Pasar los datos de eventos a la vista como JSON
        return view('horas.timeLine', [
            'user' => $user,
            'horas_hoy' => $horas_hoy,
            'horas_hoy2' => $horas_hoy2,
            'events' => $eventData // Enviar los eventos como JSON
        ]);
    }


    // Horas producidas hoy
    public function getHorasTrabajadasHoy($user)
    {
        // Se obtiene los datos
        $id = $user->id;
        $fecha = Carbon::now()->toDateString();;
        $resultado = 0;
        $totalMinutos2 = 0;

        $logsTasks = LogTasks::where('admin_user_id', $id)
        ->whereDate('date_start', '=', $fecha)
        ->get();

        foreach($logsTasks as $item){
            if($item->date_end == null){
                $item->date_end = Carbon::now();
            }
            $to_time2 = strtotime($item->date_start);
            $from_time2 = strtotime($item->date_end);
            $minutes2 = ($from_time2 - $to_time2) / 60;
            $totalMinutos2 += $minutes2;
        }

        $hora2 = floor($totalMinutos2 / 60);
        $minuto2 = ($totalMinutos2 % 60);
        $horas_dia2 = $hora2 . ' Horas y ' . $minuto2 . ' minutos';

        $resultado = $horas_dia2;

        return $resultado;
    }

    // Horas trabajadas hoy
    public function getHorasTrabajadasHoy2($user)
    {
         // Se obtiene los datos
         $id = $user->id;
         $fecha = Carbon::now()->toDateString();
         $hoy = Carbon::now();
         $resultado = 0;
         $totalMinutos2 = 0;


        $almuerzoHoras = 0;

        $jornadas = Jornada::where('admin_user_id', $id)
        ->whereDate('start_time', $hoy)
        ->get();

        $totalWorkedSeconds = 0;
        foreach($jornadas as $jornada){
            $workedSeconds = Carbon::parse($jornada->start_time)->diffInSeconds($jornada->end_time ?? Carbon::now());
            $totalPauseSeconds = $jornada->pauses->sum(function ($pause) {
                return Carbon::parse($pause->start_time)->diffInSeconds($pause->end_time ?? Carbon::now());
            });
            $totalWorkedSeconds += $workedSeconds - $totalPauseSeconds;
        }
        $horasTrabajadasFinal = $totalWorkedSeconds / 60;

        $hora = floor($horasTrabajadasFinal / 60);
        $minuto = ($horasTrabajadasFinal % 60);

        $horas_dia = $hora . ' Horas y ' . $minuto . ' minutos';

        return $horas_dia;
    }

    public function getjornadas($idUsuario)
    {
        $events = [];
        $jornadas = Jornada::where('admin_user_id', $idUsuario)->get();
        $now = Carbon::now()->format('Y-m-d H:i:s');


        foreach ($jornadas as $index => $log) {

           $fin = $now;

           if ($log->end_time == null) {
                $events[] =[
                    'Jornada sin finalizar',
                    $log->start_time,
                    $fin,
                    '#FD994E'

                ];
            } else {
                $events[] = [
                    'Jornada finalizada',
                    $log->start_time,
                    $log->end_time,
                    '#FD994E'

                ];
            }

            $pausas = $log->pauses;
            foreach ($pausas as $pausa) {
                if ($pausa->end_time == null) {
                    $events[] = [
                        'Pausa sin finalizar',
                        $pausa->start_time,
                        $fin,
                        '#FF0000'
                    ];
                } else {
                    $events[] = [
                        'Pausa finalizada',
                        $pausa->start_time,
                        $pausa->end_time,
                        '#FF0000'
                    ];
                }
            }
        }
        return $events;
    }

    public function destroy(Request $request)
    {
        $jornada = Jornada::find($request->id);

        if (!$jornada) {
            return response()->json([
                'status' => false,
                'mensaje' => "Error en el servidor, intentelo mas tarde."
            ]);
        }
        $pausas = $jornada->pauses;
        foreach ($pausas as $pausa) {
            $pausa->delete();
        }

        $jornada->delete();
        return response()->json([
            'status' => true,
            'mensaje' => 'La Jornada fue borrada correctamente'
        ]);
    }
}
