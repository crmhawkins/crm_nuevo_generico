@extends('layouts.app')

@section('titulo', 'Editar Usuario')

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
                        <i class="fas fa-user-edit me-3"></i>Editar Usuario
                    </h1>
                    <p class="header-subtitle">Formulario para editar el usuario: <strong>{{ $usuario->username }}</strong></p>
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
                                <i class="fa-solid fa-user-edit me-1"></i>Editar Usuario
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="form-container">
            <form action="{{route('user.update',$usuario->id)}}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="name">
                                <i class="fas fa-user me-2"></i>Nombre
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ $usuario->name }}"
                                   placeholder="Ingrese el nombre"
                                   required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="surname">
                                <i class="fas fa-user me-2"></i>Apellidos
                            </label>
                            <input type="text" 
                                   class="form-control @error('surname') is-invalid @enderror" 
                                   id="surname" 
                                   name="surname" 
                                   value="{{ $usuario->surname }}"
                                   placeholder="Ingrese los apellidos">
                            @error('surname')
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
                            <label class="form-label" for="username">
                                <i class="fas fa-at me-2"></i>Nombre de Usuario
                            </label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ $usuario->username }}"
                                   placeholder="Ingrese el nombre de usuario"
                                   required>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="email">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ $usuario->email }}"
                                   placeholder="Ingrese el email"
                                   required>
                            @error('email')
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
                            <label class="form-label" for="password">
                                <i class="fas fa-lock me-2"></i>Nueva Contraseña
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   autocomplete="new-password"
                                   placeholder="Deje en blanco para mantener la actual">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">
                                <i class="fas fa-lock me-2"></i>Confirmar Nueva Contraseña
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   autocomplete="new-password"
                                   placeholder="Confirme la nueva contraseña">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="pin">
                                <i class="fas fa-key me-2"></i>PIN (4 dígitos)
                            </label>
                            <input type="text" 
                                   class="form-control @error('pin') is-invalid @enderror" 
                                   id="pin" 
                                   name="pin" 
                                   value="{{ old('pin', $usuario->pin) }}"
                                   placeholder="Ingrese PIN de 4 dígitos"
                                   maxlength="4"
                                   pattern="[0-9]{4}"
                                   required>
                            @error('pin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">El PIN debe tener exactamente 4 dígitos numéricos</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="access_level_id">
                                <i class="fas fa-shield-alt me-2"></i>Rol de la App
                            </label>
                            <select class="form-select @error('access_level_id') is-invalid @enderror" id="access_level_id" name="access_level_id" required>
                                <option value="">Seleccione el rol del usuario</option>
                                @foreach ( $role as $rol )
                                    <option @if($rol->id == $usuario->access_level_id) selected @endif value="{{$rol->id}}">{{$rol->name}}</option>
                                @endforeach
                            </select>
                            @error('access_level_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="admin_user_department_id">
                                <i class="fas fa-building me-2"></i>Departamento
                            </label>
                            <select class="form-select @error('admin_user_department_id') is-invalid @enderror" id="admin_user_department_id" name="admin_user_department_id" required>
                                <option value="">Seleccione el departamento</option>
                                @foreach ( $departamentos as $departamento )
                                    <option @if($departamento->id == $usuario->admin_user_department_id) selected @endif value="{{$departamento->id}}">{{$departamento->name}}</option>
                                @endforeach
                            </select>
                            @error('admin_user_department_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="admin_user_position_id">
                                <i class="fas fa-briefcase me-2"></i>Posición
                            </label>
                            <select class="form-select @error('admin_user_position_id') is-invalid @enderror" id="admin_user_position_id" name="admin_user_position_id" required>
                                <option value="">Seleccione la posición</option>
                                @foreach ( $posiciones as $posicion )
                                    <option @if($posicion->id == $usuario->admin_user_position_id) selected @endif value="{{$posicion->id}}">{{$posicion->name}}</option>
                                @endforeach
                            </select>
                            @error('admin_user_position_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('users.index') }}" class="btn-modern btn-secondary-modern w-100">
                            <i class="fas fa-arrow-left"></i>Cancelar
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn-modern btn-success-modern">
                            <i class="fas fa-save"></i>Actualizar Usuario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validación del PIN en tiempo real
    $('#pin').on('input', function() {
        const pin = $(this).val();
        const pinField = $(this);
        const feedback = pinField.siblings('.invalid-feedback');
        
        // Limpiar solo números
        const cleanPin = pin.replace(/[^0-9]/g, '');
        if (pin !== cleanPin) {
            pinField.val(cleanPin);
        }
        
        // Validar longitud
        if (cleanPin.length > 4) {
            pinField.val(cleanPin.substring(0, 4));
        }
        
        // Validar formato
        if (cleanPin.length === 4) {
            pinField.removeClass('is-invalid').addClass('is-valid');
            feedback.text('');
        } else if (cleanPin.length > 0) {
            pinField.removeClass('is-valid').addClass('is-invalid');
            feedback.text('El PIN debe tener exactamente 4 dígitos');
        } else {
            pinField.removeClass('is-valid is-invalid');
            feedback.text('');
        }
    });

    // Validación al enviar el formulario
    $('form').on('submit', function(e) {
        const pin = $('#pin').val();
        if (pin.length !== 4 || !/^[0-9]{4}$/.test(pin)) {
            e.preventDefault();
            $('#pin').addClass('is-invalid');
            $('#pin').siblings('.invalid-feedback').text('El PIN debe tener exactamente 4 dígitos numéricos');
            $('#pin').focus();
        }
    });

    // Efectos de focus y blur
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
});
</script>
@endsection