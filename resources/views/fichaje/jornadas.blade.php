@extends('layouts.app')

@section('titulo', 'Jornadas con Fichaje')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
<style>
    .jornadas-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .jornadas-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .filtros-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .filtro-group {
        margin-bottom: 20px;
    }
    
    .filtro-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }
    
    .filtro-group input, .filtro-group select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .filtro-group input:focus, .filtro-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn-filtrar, .btn-exportar {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-filtrar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-filtrar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .btn-exportar {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .btn-exportar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
    }
    
    .estadisticas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .estadistica-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .estadistica-valor {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 5px;
    }
    
    .estadistica-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .jornadas-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .jornadas-table th {
        background: #495057;
        color: white;
        padding: 15px 12px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .jornadas-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
        font-size: 0.85rem;
    }
    
    .jornadas-table tr:hover {
        background: rgba(102, 126, 234, 0.05);
    }
    
    .estado-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .estado-trabajando { background: #d1ecf1; color: #0c5460; }
    .estado-pausa { background: #fff3cd; color: #856404; }
    .estado-salida { background: #f8d7da; color: #721c24; }
    .estado-entrada { background: #d4edda; color: #155724; }
    
    .tiempo-trabajado {
        color: #28a745;
        font-weight: 600;
    }
    
    .tiempo-pausa {
        color: #ffc107;
        font-weight: 600;
    }
    
    .sin-datos {
        text-align: center;
        padding: 40px;
        color: #6c757d;
        font-style: italic;
    }
    
    .pagination {
        justify-content: center;
        margin-top: 30px;
    }
    
    .pagination .page-link {
        color: #667eea;
        border-color: #dee2e6;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
    }
</style>
@endsection

@section('content')
<div class="jornadas-container">
    <div class="container">
        <div class="jornadas-card">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2><i class="fas fa-clock me-2"></i>Jornadas con Fichaje</h2>
                    <p class="text-muted">Historial completo de jornadas con información detallada de pausas</p>
                </div>
                <div class="col-md-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Jornadas Fichaje</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="filtros-section">
                <form method="GET" action="{{ route('fichaje.jornadas') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="filtro-group">
                                <label for="fecha_inicio">Fecha Inicio:</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" 
                                       value="{{ $fechaInicio }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="filtro-group">
                                <label for="fecha_fin">Fecha Fin:</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" 
                                       value="{{ $fechaFin }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filtro-group">
                                <label for="usuario_id">Empleado:</label>
                                <select id="usuario_id" name="usuario_id" class="form-control">
                                    <option value="">-- Todos los empleados --</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" 
                                                {{ $usuarioFiltro == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->name }} {{ $usuario->surname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="filtro-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn-filtrar w-100">
                                        <i class="fas fa-search me-2"></i>Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Estadísticas -->
            <div class="estadisticas-grid">
                <div class="estadistica-card">
                    <div class="estadistica-valor">{{ $estadisticas['total_jornadas'] }}</div>
                    <div class="estadistica-label">Total Jornadas</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor">{{ sprintf('%02d:%02d', floor($estadisticas['total_tiempo_trabajado'] / 60), $estadisticas['total_tiempo_trabajado'] % 60) }}</div>
                    <div class="estadistica-label">Tiempo Trabajado</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor">{{ sprintf('%02d:%02d', floor($estadisticas['total_tiempo_pausa'] / 60), $estadisticas['total_tiempo_pausa'] % 60) }}</div>
                    <div class="estadistica-label">Tiempo en Pausa</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor">{{ $estadisticas['jornadas_completas'] }}</div>
                    <div class="estadistica-label">Jornadas Completas</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor">{{ $estadisticas['jornadas_activas'] }}</div>
                    <div class="estadistica-label">Jornadas Activas</div>
                </div>
            </div>
            
            <!-- Botón Exportar -->
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <form method="POST" action="{{ route('fichaje.jornadas.export') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio }}">
                        <input type="hidden" name="fecha_fin" value="{{ $fechaFin }}">
                        <input type="hidden" name="usuario_id" value="{{ $usuarioFiltro }}">
                        <button type="submit" class="btn-exportar">
                            <i class="fas fa-file-excel me-2"></i>Exportar a Excel
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Tabla de Jornadas -->
            <div class="table-responsive">
                <table class="jornadas-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Empleado</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Tiempo Trabajado</th>
                            <th>Tiempo Pausa</th>
                            <th>Pausas</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jornadas as $jornada)
                        <tr>
                            <td>{{ $jornada->fecha->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $jornada->user ? ($jornada->user->name . ' ' . $jornada->user->surname) : 'Usuario no encontrado' }}</strong>
                                @if($jornada->user && $jornada->user->department)
                                    <br><small class="text-muted">{{ $jornada->user->department->name }}</small>
                                @endif
                            </td>
                            <td>{{ $jornada->hora_entrada ? $jornada->hora_entrada->format('H:i:s') : '-' }}</td>
                            <td>{{ $jornada->hora_salida ? $jornada->hora_salida->format('H:i:s') : '-' }}</td>
                            <td class="tiempo-trabajado">
                                {{ sprintf('%02d:%02d', floor($jornada->tiempo_trabajado / 60), $jornada->tiempo_trabajado % 60) }}
                            </td>
                            <td class="tiempo-pausa">
                                {{ sprintf('%02d:%02d', floor($jornada->tiempo_pausa / 60), $jornada->tiempo_pausa % 60) }}
                            </td>
                            <td>
                                @if($jornada->hora_pausa_inicio)
                                    @if($jornada->hora_pausa_fin)
                                        <span class="badge bg-success">{{ $jornada->hora_pausa_inicio->format('H:i') }} - {{ $jornada->hora_pausa_fin->format('H:i') }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $jornada->hora_pausa_inicio->format('H:i') }} - En curso</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Sin pausas</span>
                                @endif
                            </td>
                            <td>
                                <span class="estado-badge estado-{{ $jornada->estado }}">
                                    {{ ucfirst($jornada->estado) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="sin-datos">
                                <i class="fas fa-info-circle me-2"></i>No se encontraron jornadas con los filtros aplicados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            @if($jornadas->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $jornadas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
<script>
    $(document).ready(function() {
        // Inicializar Choices.js para el select de usuarios
        const usuarioSelect = new Choices('#usuario_id', {
            searchEnabled: true,
            searchChoices: true,
            searchPlaceholderValue: 'Buscar empleado...',
            noResultsText: 'No se encontraron empleados',
            noChoicesText: 'No hay empleados disponibles'
        });
    });
</script>
@endsection
