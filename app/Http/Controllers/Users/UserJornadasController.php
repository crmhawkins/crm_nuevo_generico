<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Fichaje;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserJornadasController extends Controller
{
    public function index(Request $request, $userId)
    {
        // Obtener el usuario
        $usuario = User::findOrFail($userId);
        
        // Filtros por defecto
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Construir query para las jornadas del usuario específico
        $query = Fichaje::where('user_id', $userId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        // Obtener jornadas con paginación
        $jornadas = $query->orderBy('fecha', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);
        
        // Calcular estadísticas del usuario
        $estadisticas = $this->calcularEstadisticasUsuario($userId, $fechaInicio, $fechaFin);
        
        return view('users.jornadas', compact(
            'usuario',
            'jornadas', 
            'fechaInicio', 
            'fechaFin',
            'estadisticas'
        ));
    }
    
    public function store(Request $request, $userId)
    {
        $usuario = User::findOrFail($userId);

        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora_entrada' => 'nullable|string',
            'hora_salida' => 'nullable|string',
        ], [
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha no es válida',
        ]);

        $fecha = Carbon::parse($validated['fecha'])->format('Y-m-d');
        $entrada = !empty($validated['hora_entrada']) ? $this->parseHoraFlexible($fecha, $validated['hora_entrada']) : null;
        $salida = !empty($validated['hora_salida']) ? $this->parseHoraFlexible($fecha, $validated['hora_salida']) : null;

        if ($entrada && $salida && $salida->lt($entrada)) {
            return back()->with('toast', [
                'icon' => 'error',
                'mensaje' => 'La hora de salida no puede ser menor que la de entrada'
            ]);
        }

        $estado = 'entrada';
        if ($salida) {
            $estado = 'salida';
        } elseif ($entrada) {
            $estado = 'trabajando';
        }

        Fichaje::create([
            'user_id' => $usuario->id,
            'fecha' => $fecha,
            'hora_entrada' => $entrada ? $entrada->format('H:i:s') : null,
            'hora_salida' => $salida ? $salida->format('H:i:s') : null,
            'estado' => $estado,
        ]);

        return redirect()->route('users.jornadas', $usuario->id)->with('toast', [
            'icon' => 'success',
            'mensaje' => 'La jornada se creó correctamente'
        ]);
    }

    public function update(Request $request, $userId, $fichajeId)
    {
        $usuario = User::findOrFail($userId);
        $fichaje = Fichaje::where('id', $fichajeId)->where('user_id', $usuario->id)->firstOrFail();

        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora_entrada' => 'nullable|string',
            'hora_salida' => 'nullable|string',
        ], [
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha no es válida',
            'hora_entrada.string' => 'La hora de entrada no es válida',
            'hora_salida.string' => 'La hora de salida no es válida',
        ]);

        // Construir datetimes en base a la fecha seleccionada
        $fecha = Carbon::parse($validated['fecha'])->format('Y-m-d');
        $entrada = null;
        $salida = null;
        if (!empty($validated['hora_entrada'])) {
            $entrada = $this->parseHoraFlexible($fecha, $validated['hora_entrada']);
        }
        if (!empty($validated['hora_salida'])) {
            $salida = $this->parseHoraFlexible($fecha, $validated['hora_salida']);
        }

        if ($entrada && $salida && $salida->lt($entrada)) {
            return back()->with('toast', [
                'icon' => 'error',
                'mensaje' => 'La hora de salida no puede ser menor que la de entrada'
            ]);
        }

        $fichaje->fecha = $fecha;
        $fichaje->hora_entrada = $entrada;
        $fichaje->hora_salida = $salida;
        // Estado derivado simple
        if ($salida) {
            $fichaje->estado = 'salida';
        } elseif ($entrada) {
            // Mantener estado actual si ya estaba en pausa, sino trabajando
            $fichaje->estado = $fichaje->estado === 'pausa' ? 'pausa' : 'trabajando';
        }
        $fichaje->save();

        return redirect()->route('users.jornadas', $usuario->id)->with('toast', [
            'icon' => 'success',
            'mensaje' => 'La jornada se actualizó correctamente'
        ]);
    }

    private function parseHoraFlexible(string $fecha, string $hora)
    {
        $hora = trim($hora);
        $formatos = ['H:i:s', 'H:i', 'h:i:s A', 'h:i A'];
        foreach ($formatos as $formato) {
            try {
                return Carbon::createFromFormat('Y-m-d ' . $formato, $fecha . ' ' . $hora);
            } catch (\Exception $e) {
                // probar siguiente formato
            }
        }
        // Fallback al parser de Carbon
        return Carbon::parse($fecha . ' ' . $hora);
    }

    public function destroy(Request $request, $userId, $fichajeId)
    {
        $usuario = User::findOrFail($userId);
        $fichaje = Fichaje::where('id', $fichajeId)->where('user_id', $usuario->id)->firstOrFail();

        $fichaje->delete(); // Pausas se eliminan por ON DELETE CASCADE

        return redirect()->route('users.jornadas', $usuario->id)->with('toast', [
            'icon' => 'success',
            'mensaje' => 'La jornada fue eliminada correctamente'
        ]);
    }
    private function calcularEstadisticasUsuario($userId, $fechaInicio, $fechaFin)
    {
        $jornadas = Fichaje::where('user_id', $userId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();
        
        // Calcular tiempo trabajado dinámicamente para cada jornada
        $totalTiempoTrabajado = 0;
        $totalTiempoPausa = 0;
        $jornadasCompletas = 0;
        $jornadasActivas = 0;
        $diasTrabajados = 0;
        $tiemposTrabajados = [];
        
        foreach ($jornadas as $jornada) {
            $tiempoTrabajado = $this->calcularTiempoTrabajado($jornada);
            $tiempoPausa = $this->calcularTiempoPausa($jornada);
            
            $totalTiempoTrabajado += $tiempoTrabajado;
            $totalTiempoPausa += $tiempoPausa;
            $tiemposTrabajados[] = $tiempoTrabajado;
            
            if ($jornada->hora_entrada) {
                $diasTrabajados++;
            }
            
            if ($jornada->hora_salida) {
                $jornadasCompletas++;
            } else {
                $jornadasActivas++;
            }
        }
        
        // Calcular promedio de horas por día
        $promedioHoras = $diasTrabajados > 0 ? $totalTiempoTrabajado / $diasTrabajados : 0;
        
        // Calcular jornada más larga
        $jornadaMasLarga = count($tiemposTrabajados) > 0 ? max($tiemposTrabajados) : 0;
        
        // Calcular jornada más corta (excluyendo 0)
        $tiemposPositivos = array_filter($tiemposTrabajados, function($t) { return $t > 0; });
        $jornadaMasCorta = count($tiemposPositivos) > 0 ? min($tiemposPositivos) : 0;
        
        return [
            'total_jornadas' => $jornadas->count(),
            'total_tiempo_trabajado' => $totalTiempoTrabajado,
            'total_tiempo_pausa' => $totalTiempoPausa,
            'jornadas_completas' => $jornadasCompletas,
            'jornadas_activas' => $jornadasActivas,
            'dias_trabajados' => $diasTrabajados,
            'promedio_horas_dia' => $promedioHoras,
            'jornada_mas_larga' => $jornadaMasLarga,
            'jornada_mas_corta' => $jornadaMasCorta,
        ];
    }
    
    private function calcularTiempoTrabajado($jornada)
    {
        if (!$jornada->hora_entrada) {
            return 0;
        }
        
        $horaSalida = $jornada->hora_salida ?? now();
        $tiempoTotal = $jornada->hora_entrada->diffInMinutes($horaSalida);
        
        // Restar tiempo de pausa si existe
        $tiempoPausa = $this->calcularTiempoPausa($jornada);
        
        return max(0, $tiempoTotal - $tiempoPausa);
    }
    
    private function calcularTiempoPausa($jornada)
    {
        if (!$jornada->hora_pausa_inicio) {
            return 0;
        }
        
        $horaPausaFin = $jornada->hora_pausa_fin ?? now();
        return $jornada->hora_pausa_inicio->diffInMinutes($horaPausaFin);
    }
}
