@extends('layouts.app')

@section('titulo', 'Jornadas de ' . $usuario->name)

@section('css')
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
    
    .user-info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .user-avatar-large {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 2rem;
        margin-right: 20px;
    }
    
    .user-details h3 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .user-details p {
        margin: 5px 0 0 0;
        color: #6c757d;
        font-size: 1rem;
    }
    
    .user-badges {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .badge-modern {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-department {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .badge-position {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }
    
    .badge-access {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    
    /* Estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }
    
    .stat-icon.info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    /* Filtros */
    .filters-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 25px;
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
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }
    
    .btn-modern {
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
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
    
    .btn-secondary-modern {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }
    
    .btn-secondary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
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
        text-align: left;
        border: none;
    }
    
    .modern-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 0.9rem;
        text-align: left;
        transition: all 0.3s ease;
    }
    
    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: scale(1.01);
    }
    
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .estado-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .estado-trabajando {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .estado-pausa {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }
    
    .estado-salida {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    .tiempo-trabajado {
        color: #28a745;
        font-weight: 600;
    }
    
    .tiempo-pausa {
        color: #ffc107;
        font-weight: 600;
    }
    
    .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-item a {
        color: #6b7280;
        transition: color 0.2s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #6366f1;
    }
    
    .breadcrumb-item.active {
        color: #374151;
        font-weight: 500;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .header-title {
            font-size: 2rem;
        }
        
        .user-info-card {
            padding: 20px;
        }
        
        .user-avatar-large {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .table-container {
            padding: 20px;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 12px 8px;
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
                        <i class="fas fa-clock me-3"></i>Jornadas de {{ $usuario->name }}
                    </h1>
                    <p class="header-subtitle">Historial detallado de jornadas laborales con información de pausas</p>
                </div>
                <div class="col-md-4 text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}" class="text-decoration-none">
                                    <i class="fa-solid fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('users.index')}}" class="text-decoration-none">
                                    <i class="fa-solid fa-users me-1"></i>Usuarios
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fa-solid fa-clock me-1"></i>Jornadas
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Información del usuario --}}
        <div class="user-info-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar-large">
                            {{ substr($usuario->name, 0, 1) }}{{ substr($usuario->surname, 0, 1) }}
                        </div>
                        <div class="user-details">
                            <h3>{{ $usuario->name }} {{ $usuario->surname }}</h3>
                            <p><i class="fas fa-envelope me-2"></i>{{ $usuario->email }}</p>
                            <div class="user-badges">
                                @if($usuario->departamento)
                                    <span class="badge-modern badge-department">
                                        <i class="fas fa-building me-1"></i>{{ $usuario->departamento->name }}
                                    </span>
                                @endif
                                @if($usuario->posicion)
                                    <span class="badge-modern badge-position">
                                        <i class="fas fa-briefcase me-1"></i>{{ $usuario->posicion->name }}
                                    </span>
                                @endif
                                @if($usuario->acceso)
                                    <span class="badge-modern badge-access">
                                        <i class="fas fa-shield-alt me-1"></i>{{ $usuario->acceso->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('users.index') }}" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-arrow-left"></i>Volver a Usuarios
                    </a>
                </div>
            </div>
        </div>

        {{-- Estadísticas --}}
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
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">{{ sprintf('%02d:%02d', floor($estadisticas['promedio_horas_dia'] / 60), $estadisticas['promedio_horas_dia'] % 60) }}</div>
                <div class="stat-label">Promedio/Día</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-value">{{ $estadisticas['dias_trabajados'] }}</div>
                <div class="stat-label">Días Trabajados</div>
            </div>
        </div>

        {{-- Panel de filtros --}}
        <div class="filters-panel">
            <form action="{{ route('users.jornadas', $usuario->id) }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha Inicio
                            </label>
                            <input type="date" name="fecha_inicio" class="filter-input" value="{{ $fechaInicio }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha Fin
                            </label>
                            <input type="date" name="fecha_fin" class="filter-input" value="{{ $fechaFin }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="filter-group">
                            <label class="filter-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-modern btn-primary-modern">
                                    <i class="fas fa-search"></i>Filtrar
                                </button>
                                <a href="{{ route('users.jornadas', $usuario->id) }}" class="btn-modern btn-secondary-modern">
                                    <i class="fas fa-refresh"></i>Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabla de jornadas --}}
        <div class="table-container">
            @if($jornadas->count() > 0)
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                <th><i class="fas fa-sign-in-alt me-2"></i>Entrada</th>
                                <th><i class="fas fa-sign-out-alt me-2"></i>Salida</th>
                                <th><i class="fas fa-clock me-2"></i>Tiempo Trabajado</th>
                                <th><i class="fas fa-pause me-2"></i>Tiempo Pausa</th>
                                <th><i class="fas fa-stopwatch me-2"></i>Pausas</th>
                                <th><i class="fas fa-info-circle me-2"></i>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jornadas as $jornada)
                            <tr>
                                <td><strong>{{ $jornada->fecha->format('d/m/Y') }}</strong></td>
                                <td>
                                    @if($jornada->hora_entrada)
                                        <span class="badge-modern badge-department">
                                            <i class="fas fa-sign-in-alt"></i>
                                            {{ $jornada->hora_entrada->format('H:i:s') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($jornada->hora_salida)
                                        <span class="badge-modern badge-position">
                                            <i class="fas fa-sign-out-alt"></i>
                                            {{ $jornada->hora_salida->format('H:i:s') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="tiempo-trabajado">
                                    <i class="fas fa-clock me-1"></i>
                                    @php
                                        $tiempoTrabajadoSegundos = 0;
                                        $tiempoPausaSegundos = 0;
                                        
                                        if ($jornada->hora_entrada) {
                                            $horaSalida = $jornada->hora_salida ?? now();
                                            $tiempoTotalSegundos = $jornada->hora_entrada->diffInSeconds($horaSalida);
                                            
                                            // Calcular tiempo de pausa total en segundos
                                            if (method_exists($jornada, 'pausas')) {
                                                $pausas = $jornada->pausas()->orderBy('created_at')->get();
                                                foreach ($pausas as $p) {
                                                    if ($p->inicio) {
                                                        $inicio = \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($p->inicio)->format('H:i:s'));
                                                        $fin = $p->fin ? \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($p->fin)->format('H:i:s')) : now();
                                                        $tiempoPausaSegundos += $inicio->diffInSeconds($fin);
                                                    }
                                                }
                                            } else {
                                                // Fallback para fichajes antiguos
                                                if ($jornada->hora_pausa_inicio) {
                                                    $inicio = \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . $jornada->hora_pausa_inicio->format('H:i:s'));
                                                    $fin = $jornada->hora_pausa_fin ? \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . $jornada->hora_pausa_fin->format('H:i:s')) : now();
                                                    $tiempoPausaSegundos = $inicio->diffInSeconds($fin);
                                                }
                                            }
                                            
                                            $tiempoTrabajadoSegundos = max(0, $tiempoTotalSegundos - $tiempoPausaSegundos);
                                        }
                                    @endphp
                                    {{ sprintf('%02d:%02d:%02d', floor($tiempoTrabajadoSegundos / 3600), floor(($tiempoTrabajadoSegundos % 3600) / 60), $tiempoTrabajadoSegundos % 60) }}
                                </td>
                                <td class="tiempo-pausa">
                                    <i class="fas fa-pause me-1"></i>
                                    @php
                                        $tiempoPausaSegundos = 0;
                                        
                                        if (method_exists($jornada, 'pausas')) {
                                            $pausas = $jornada->pausas()->orderBy('created_at')->get();
                                            foreach ($pausas as $p) {
                                                if ($p->inicio) {
                                                    $inicio = \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($p->inicio)->format('H:i:s'));
                                                    $fin = $p->fin ? \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($p->fin)->format('H:i:s')) : now();
                                                    $tiempoPausaSegundos += $inicio->diffInSeconds($fin);
                                                }
                                            }
                                        } else {
                                            // Fallback para fichajes antiguos
                                            if ($jornada->hora_pausa_inicio) {
                                                $inicio = \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . $jornada->hora_pausa_inicio->format('H:i:s'));
                                                $fin = $jornada->hora_pausa_fin ? \Carbon\Carbon::parse($jornada->fecha->format('Y-m-d') . ' ' . $jornada->hora_pausa_fin->format('H:i:s')) : now();
                                                $tiempoPausaSegundos = $inicio->diffInSeconds($fin);
                                            }
                                        }
                                    @endphp
                                    {{ sprintf('%02d:%02d:%02d', floor($tiempoPausaSegundos / 3600), floor(($tiempoPausaSegundos % 3600) / 60), $tiempoPausaSegundos % 60) }}
                                </td>
                                <td style="max-width: 300px; word-wrap: break-word;">
                                    @php
                                        // Obtener todas las pausas del día
                                        $pausasTexto = 'Sin pausas';
                                        if (method_exists($jornada, 'pausas')) {
                                            $pausas = $jornada->pausas()->orderBy('created_at')->get();
                                            if ($pausas->count() > 0) {
                                                $pausasArray = [];
                                                foreach ($pausas as $p) {
                                                    $inicio = $p->inicio ? \Carbon\Carbon::parse($p->inicio)->format('H:i:s') : '-';
                                                    $fin = $p->fin ? \Carbon\Carbon::parse($p->fin)->format('H:i:s') : 'En curso';
                                                    $pausasArray[] = $inicio . ' - ' . $fin;
                                                }
                                                $pausasTexto = implode(', ', $pausasArray);
                                            }
                                        } elseif ($jornada->hora_pausa_inicio) {
                                            $inicio = $jornada->hora_pausa_inicio->format('H:i:s');
                                            $fin = $jornada->hora_pausa_fin ? $jornada->hora_pausa_fin->format('H:i:s') : 'En curso';
                                            $pausasTexto = $inicio . ' - ' . $fin;
                                        }
                                    @endphp
                                    @if($pausasTexto !== 'Sin pausas')
                                        @php
                                            $pausasArray = explode(', ', $pausasTexto);
                                        @endphp
                                        @foreach($pausasArray as $pausa)
                                            @php
                                                $isEnCurso = str_contains($pausa, 'En curso');
                                                $badgeClass = $isEnCurso ? 'badge-position' : 'badge-department';
                                                $iconClass = $isEnCurso ? 'fas fa-play' : 'fas fa-stopwatch';
                                            @endphp
                                            <span class="badge-modern {{ $badgeClass }}" style="margin-right: 4px; margin-bottom: 2px; display: inline-block;">
                                                <i class="{{ $iconClass }}"></i>
                                                {{ $pausa }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="badge-modern badge-access">
                                            <i class="fas fa-minus"></i>
                                            Sin pausas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="estado-badge estado-{{ $jornada->estado }}">
                                        <i class="fas fa-{{ $jornada->estado === 'trabajando' ? 'play' : ($jornada->estado === 'pausa' ? 'pause' : ($jornada->estado === 'salida' ? 'stop' : 'play-circle')) }}"></i>
                                        {{ ucfirst($jornada->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $jornadas->links() }}
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-clock"></i>
                    <h4>No se encontraron jornadas</h4>
                    <p>No hay registros de jornadas para el período seleccionado.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
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
@endsection
