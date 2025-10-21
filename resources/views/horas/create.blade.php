@extends('layouts.app')

@section('titulo', 'Crear Jornada')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
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
    
    /* Formulario moderno */
    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        display: block;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
        transform: translateY(-2px);
    }
    
    .form-control:hover, .form-select:hover {
        border-color: #667eea;
        transform: translateY(-1px);
    }
    
    .btn-modern {
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-success-modern {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        width: 100%;
        justify-content: center;
    }
    
    .btn-success-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
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
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
        display: block;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .header-title {
            font-size: 2rem;
        }
        
        .form-container {
            padding: 25px;
        }
        
        .form-control, .form-select {
            padding: 12px 15px;
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
                        <i class="fas fa-plus-circle me-3"></i>Crear Jornada
                    </h1>
                    <p class="header-subtitle">Formulario para crear una nueva jornada de trabajo</p>
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
                                <a href="{{route('horas.listado')}}" class="text-decoration-none">
                                    <i class="fa-solid fa-clock me-1"></i>Jornadas
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fa-solid fa-plus me-1"></i>Crear Jornada
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="form-container">
            <form action="{{route('horas.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="admin_user_id">
                                <i class="fas fa-user me-2"></i>Usuario
                            </label>
                            <select class="form-select @error('admin_user_id') is-invalid @enderror" name="admin_user_id" id="admin_user_id">
                                @if ($usuarios->count() > 0)
                                    <option value="">--- Seleccione un usuario ---</option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ (old('admin_user_id') == $usuario->id) ? 'selected' : '' }}>
                                            {{ $usuario->name.' '.$usuario->surname }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="">No existen usuarios disponibles</option>
                                @endif
                            </select>
                            @error('admin_user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="fecha">
                                <i class="fas fa-calendar me-2"></i>Fecha de la Jornada
                            </label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}">
                            @error('fecha')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="hora_entrada">
                                <i class="fas fa-sign-in-alt me-2"></i>Hora de Entrada
                            </label>
                            <input type="time" class="form-control @error('hora_entrada') is-invalid @enderror" id="hora_entrada" name="hora_entrada" value="{{ old('hora_entrada') }}">
                            @error('hora_entrada')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="hora_salida">
                                <i class="fas fa-sign-out-alt me-2"></i>Hora de Salida
                            </label>
                            <input type="time" class="form-control @error('hora_salida') is-invalid @enderror" id="hora_salida" name="hora_salida" value="{{ old('hora_salida') }}">
                            @error('hora_salida')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="hora_pausa_inicio">
                                <i class="fas fa-pause me-2"></i>Inicio de Pausa
                            </label>
                            <input type="time" class="form-control @error('hora_pausa_inicio') is-invalid @enderror" id="hora_pausa_inicio" name="hora_pausa_inicio" value="{{ old('hora_pausa_inicio') }}">
                            @error('hora_pausa_inicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="hora_pausa_fin">
                                <i class="fas fa-play me-2"></i>Fin de Pausa
                            </label>
                            <input type="time" class="form-control @error('hora_pausa_fin') is-invalid @enderror" id="hora_pausa_fin" name="hora_pausa_fin" value="{{ old('hora_pausa_fin') }}">
                            @error('hora_pausa_fin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="estado">
                        <i class="fas fa-info-circle me-2"></i>Estado de la Jornada
                    </label>
                    <select class="form-select @error('estado') is-invalid @enderror" name="estado" id="estado">
                        <option value="trabajando" {{ old('estado') == 'trabajando' ? 'selected' : '' }}>Trabajando</option>
                        <option value="pausa" {{ old('estado') == 'pausa' ? 'selected' : '' }}>En Pausa</option>
                        <option value="salida" {{ old('estado') == 'salida' ? 'selected' : '' }}>Salida</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('horas.listado') }}" class="btn-modern btn-secondary-modern w-100">
                            <i class="fas fa-arrow-left"></i>Cancelar
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn-modern btn-success-modern">
                            <i class="fas fa-save"></i>Guardar Jornada
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Choices.js para el select de usuarios
    const userSelect = new Choices('#admin_user_id', {
        searchEnabled: true,
        searchChoices: true,
        searchResultLimit: 10,
        placeholder: true,
        placeholderValue: '--- Seleccione un usuario ---',
        noResultsText: 'No se encontraron usuarios',
        noChoicesText: 'No hay usuarios disponibles'
    });

    // Validación en tiempo real
    $('#hora_entrada, #hora_salida').on('change', function() {
        const entrada = $('#hora_entrada').val();
        const salida = $('#hora_salida').val();
        
        if (entrada && salida) {
            const entradaTime = new Date('2000-01-01 ' + entrada);
            const salidaTime = new Date('2000-01-01 ' + salida);
            
            if (entradaTime >= salidaTime) {
                alert('La hora de salida debe ser posterior a la hora de entrada');
                $('#hora_salida').val('');
            }
        }
    });

    // Validación de pausas
    $('#hora_pausa_inicio, #hora_pausa_fin').on('change', function() {
        const pausaInicio = $('#hora_pausa_inicio').val();
        const pausaFin = $('#hora_pausa_fin').val();
        
        if (pausaInicio && pausaFin) {
            const inicioTime = new Date('2000-01-01 ' + pausaInicio);
            const finTime = new Date('2000-01-01 ' + pausaFin);
            
            if (inicioTime >= finTime) {
                alert('La hora de fin de pausa debe ser posterior a la hora de inicio de pausa');
                $('#hora_pausa_fin').val('');
            }
        }
    });

    // Auto-calcular estado basado en las horas
    function actualizarEstado() {
        const entrada = $('#hora_entrada').val();
        const salida = $('#hora_salida').val();
        const pausaInicio = $('#hora_pausa_inicio').val();
        const pausaFin = $('#hora_pausa_fin').val();
        
        if (!entrada) {
            $('#estado').val('trabajando');
        } else if (entrada && !salida) {
            if (pausaInicio && !pausaFin) {
                $('#estado').val('pausa');
            } else {
                $('#estado').val('trabajando');
            }
        } else if (entrada && salida) {
            $('#estado').val('salida');
        }
    }

    // Escuchar cambios en los campos de hora
    $('#hora_entrada, #hora_salida, #hora_pausa_inicio, #hora_pausa_fin').on('change', actualizarEstado);

    // Efectos de animación en el formulario
    $('.form-control, .form-select').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });

    // Validación del formulario antes de enviar
    $('form').on('submit', function(e) {
        const usuario = $('#admin_user_id').val();
        const fecha = $('#fecha').val();
        
        if (!usuario) {
            e.preventDefault();
            alert('Por favor seleccione un usuario');
            $('#admin_user_id').focus();
            return false;
        }
        
        if (!fecha) {
            e.preventDefault();
            alert('Por favor seleccione una fecha');
            $('#fecha').focus();
            return false;
        }
        
        // Mostrar loading en el botón
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');
        submitBtn.prop('disabled', true);
    });
});
</script>
@endsection
