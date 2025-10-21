@extends('layouts.app')

@section('titulo', 'Jornadas Por Fecha')

@section('css')
<link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">
<link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    /* Reset y base */
    * {
        box-sizing: border-box;
    }
    
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .main-container {
        background: transparent;
        padding: 0;
    }
    
    /* Header moderno */
    .modern-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }
    
    .header-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        font-weight: 400;
    }
    
    /* Cards de estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    
    .stat-icon.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .stat-icon.success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    .stat-icon.warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; }
    .stat-icon.info { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); color: white; }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Panel de filtros */
    .filters-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .filter-group {
        margin-bottom: 20px;
    }
    
    .filter-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: block;
        font-size: 0.9rem;
    }
    
    .filter-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }
    
    .btn-modern {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-success-modern {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    
    .btn-success-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        color: white;
    }
    
    /* Tabla moderna */
    .table-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
    }
    
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .modern-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .modern-table th {
        padding: 20px 15px;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        border: none;
    }
    
    .modern-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 0.9rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: scale(1.01);
    }
    
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Badges modernos */
    .badge-modern {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .badge-success-modern {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }
    
    .badge-warning-modern {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }
    
    .badge-secondary-modern {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
    }
    
    .badge-info-modern {
        background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
    }
    
    /* Estados */
    .estado-trabajando {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    
    .estado-pausa {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .estado-salida {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .estado-entrada {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    /* Tiempo trabajado */
    .tiempo-trabajado {
        color: #28a745;
        font-weight: 700;
        font-size: 1rem;
    }
    
    .tiempo-pausa {
        color: #ffc107;
        font-weight: 700;
        font-size: 1rem;
    }
    
    /* Sin datos */
    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .no-data i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .no-data h4 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    
    .no-data p {
        font-size: 1rem;
        opacity: 0.8;
    }
    
    /* Paginación moderna */
    .pagination-modern {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }
    
    .pagination-modern .page-link {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(102, 126, 234, 0.2);
        color: #667eea;
        padding: 10px 15px;
        margin: 0 2px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .pagination-modern .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }
    
    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .modern-table {
            font-size: 0.8rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 12px 8px;
        }
        
        .header-title {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="main-container">
        {{-- Header moderno --}}
        <div class="modern-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="header-title">
                        <i class="fas fa-clock me-3"></i>Jornadas
                    </h1>
                    <p class="header-subtitle">Listado de jornadas por fechas</p>
                </div>
                <div class="col-md-4 text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Jornadas</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Estadísticas --}}
        @if(isset($estadisticas))
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $estadisticas['total_jornadas'] }}</div>
                <div class="stat-label">Total Jornadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $estadisticas['jornadas_completas'] }}</div>
                <div class="stat-label">Jornadas Completas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stat-value">{{ $estadisticas['jornadas_activas'] }}</div>
                <div class="stat-label">Jornadas Activas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ sprintf('%02d:%02d', floor($estadisticas['total_tiempo_trabajado'] / 60), $estadisticas['total_tiempo_trabajado'] % 60) }}</div>
                <div class="stat-label">Tiempo Total</div>
            </div>
        </div>
        @endif

        {{-- Panel de filtros --}}
        <div class="filters-panel">
            <form action="{{ route('horas.listado') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha Inicio
                            </label>
                            <input type="date" name="fecha_inicio" class="filter-input" value="{{ $fechaInicio ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha Fin
                            </label>
                            <input type="date" name="fecha_fin" class="filter-input" value="{{ $fechaFin ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-user me-2"></i>Empleado
                            </label>
                            <select name="usuario_id" class="filter-input">
                                <option value="">-- Todos los empleados --</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ ($usuarioFiltro ?? '') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} {{ $usuario->surname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-modern btn-primary-modern">
                                    <i class="fas fa-search"></i>Filtrar
                                </button>
                                <a href="{{ route('horas.export', ['fecha_inicio' => $fechaInicio ?? '', 'fecha_fin' => $fechaFin ?? '', 'usuario_id' => $usuarioFiltro ?? '']) }}" class="btn-modern btn-success-modern">
                                    <i class="fas fa-file-excel"></i>Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabla de fichajes --}}
        <div class="table-container">
            @if($fichajes->count() > 0)
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                <th><i class="fas fa-user me-2"></i>Empleado</th>
                                <th><i class="fas fa-building me-2"></i>Departamento</th>
                                <th><i class="fas fa-sign-in-alt me-2"></i>Entrada</th>
                                <th><i class="fas fa-sign-out-alt me-2"></i>Salida</th>
                                <th><i class="fas fa-clock me-2"></i>Tiempo Trabajado</th>
                                <th><i class="fas fa-pause me-2"></i>Tiempo Pausa</th>
                                <th><i class="fas fa-stopwatch me-2"></i>Pausas</th>
                                <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fichajes as $fichaje)
                            <tr>
                                <td><strong>{{ $fichaje->fecha->format('d/m/Y') }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                            {{ substr($fichaje->user->name ?? 'U', 0, 1) }}{{ substr($fichaje->user->surname ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $fichaje->user ? ($fichaje->user->name . ' ' . $fichaje->user->surname) : 'Usuario no encontrado' }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-modern badge-info-modern">
                                        <i class="fas fa-building"></i>
                                        {{ $fichaje->user && $fichaje->user->departamento ? $fichaje->user->departamento->name : 'Sin departamento' }}
                                    </span>
                                </td>
                                <td>
                                    @if($fichaje->hora_entrada)
                                        <span class="badge-modern badge-success-modern">
                                            <i class="fas fa-sign-in-alt"></i>
                                            {{ $fichaje->hora_entrada->format('H:i:s') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($fichaje->hora_salida)
                                        <span class="badge-modern badge-warning-modern">
                                            <i class="fas fa-sign-out-alt"></i>
                                            {{ $fichaje->hora_salida->format('H:i:s') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="tiempo-trabajado">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ sprintf('%02d:%02d', floor($fichaje->tiempo_trabajado / 60), $fichaje->tiempo_trabajado % 60) }}
                                </td>
                                <td class="tiempo-pausa">
                                    <i class="fas fa-pause me-1"></i>
                                    {{ sprintf('%02d:%02d', floor($fichaje->tiempo_pausa / 60), $fichaje->tiempo_pausa % 60) }}
                                </td>
                                <td>
                                    @if($fichaje->hora_pausa_inicio)
                                        @if($fichaje->hora_pausa_fin)
                                            <span class="badge-modern badge-success-modern">
                                                <i class="fas fa-stopwatch"></i>
                                                {{ $fichaje->hora_pausa_inicio->format('H:i') }} - {{ $fichaje->hora_pausa_fin->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="badge-modern badge-warning-modern">
                                                <i class="fas fa-play"></i>
                                                {{ $fichaje->hora_pausa_inicio->format('H:i') }} - En curso
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge-modern badge-secondary-modern">
                                            <i class="fas fa-minus"></i>
                                            Sin pausas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-modern estado-{{ $fichaje->estado }}">
                                        <i class="fas fa-{{ $fichaje->estado === 'trabajando' ? 'play' : ($fichaje->estado === 'pausa' ? 'pause' : ($fichaje->estado === 'salida' ? 'stop' : 'play-circle')) }}"></i>
                                        {{ ucfirst($fichaje->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Paginación --}}
                <div class="pagination-modern">
                    {{ $fichajes->links() }}
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-clock"></i>
                    <h4>No se encontraron jornadas</h4>
                    <p>No hay registros de fichaje para el período seleccionado.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Animaciones suaves
    $(document).ready(function() {
        // Animación de entrada para las cards
        $('.stat-card').each(function(index) {
            $(this).css('opacity', '0').css('transform', 'translateY(20px)');
            setTimeout(() => {
                $(this).animate({
                    opacity: 1
                }, 300).css('transform', 'translateY(0)');
            }, index * 100);
        });
        
        // Efecto hover en las filas de la tabla
        $('.modern-table tbody tr').hover(
            function() {
                $(this).addClass('shadow-lg');
            },
            function() {
                $(this).removeClass('shadow-lg');
            }
        );
    });
</script>
@include('partials.toast')
@endsection
