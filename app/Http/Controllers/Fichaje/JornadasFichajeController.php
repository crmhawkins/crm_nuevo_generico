<?php

namespace App\Http\Controllers\Fichaje;

use App\Http\Controllers\Controller;
use App\Models\Fichaje;
use App\Models\Users\User;
use App\Exports\JornadasFichajeExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JornadasFichajeController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todos los usuarios activos
        $usuarios = User::where('inactive', 0)->get();
        
        // Filtros por defecto
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $usuarioFiltro = $request->input('usuario_id');
        
        // Construir query base
        $query = Fichaje::with('user')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        // Aplicar filtro de usuario si está seleccionado
        if ($usuarioFiltro) {
            $query->where('user_id', $usuarioFiltro);
        }
        
        // Obtener jornadas con paginación
        $jornadas = $query->orderBy('fecha', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);
        
        // Calcular estadísticas
        $estadisticas = $this->calcularEstadisticas($fechaInicio, $fechaFin, $usuarioFiltro);
        
        return view('fichaje.jornadas', compact(
            'jornadas', 
            'usuarios', 
            'fechaInicio', 
            'fechaFin', 
            'usuarioFiltro',
            'estadisticas'
        ));
    }
    
    public function exportar(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $usuarioFiltro = $request->input('usuario_id');
        
        $nombreArchivo = 'jornadas_fichaje_' . $fechaInicio . '_' . $fechaFin;
        if ($usuarioFiltro) {
            $usuario = User::find($usuarioFiltro);
            $nombreArchivo .= '_' . str_replace(' ', '_', $usuario->name . '_' . $usuario->surname);
        }
        $nombreArchivo .= '.xlsx';
        
        return Excel::download(
            new JornadasFichajeExport($fechaInicio, $fechaFin, $usuarioFiltro), 
            $nombreArchivo
        );
    }
    
    private function calcularEstadisticas($fechaInicio, $fechaFin, $usuarioFiltro = null)
    {
        $query = Fichaje::whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        if ($usuarioFiltro) {
            $query->where('user_id', $usuarioFiltro);
        }
        
        $jornadas = $query->get();
        
        return [
            'total_jornadas' => $jornadas->count(),
            'total_tiempo_trabajado' => $jornadas->sum('tiempo_trabajado'),
            'total_tiempo_pausa' => $jornadas->sum('tiempo_pausa'),
            'jornadas_completas' => $jornadas->whereNotNull('hora_salida')->count(),
            'jornadas_activas' => $jornadas->whereNull('hora_salida')->count(),
        ];
    }
}
