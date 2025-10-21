<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Fichaje;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
