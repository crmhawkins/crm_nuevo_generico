@extends('layouts.app')

@section('titulo', 'Configuración')

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
    
    /* Estilo especial para file input */
    .file-input-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    .file-input-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px 20px;
        border: 2px dashed #667eea;
        border-radius: 12px;
        background: rgba(102, 126, 234, 0.05);
        color: #667eea;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        min-height: 60px;
    }
    
    .file-input-label:hover {
        background: rgba(102, 126, 234, 0.1);
        border-color: #4f46e5;
        transform: translateY(-2px);
    }
    
    .file-input-label i {
        font-size: 1.5rem;
        margin-right: 10px;
    }
    
    /* Preview del logo */
    .logo-preview {
        margin-top: 20px;
        text-align: center;
        padding: 20px;
        background: rgba(102, 126, 234, 0.05);
        border-radius: 12px;
        border: 2px dashed rgba(102, 126, 234, 0.2);
    }
    
    .logo-preview img {
        max-height: 150px;
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .logo-preview-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        display: block;
    }
    
    /* Botones modernos */
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
    
    /* Panel de acciones */
    .actions-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        height: fit-content;
    }
    
    .actions-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
    }
    
    /* Secciones del formulario */
    .form-section {
        margin-bottom: 40px;
        padding: 25px;
        background: rgba(248, 250, 252, 0.8);
        border-radius: 15px;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        color: #667eea;
    }
    
    /* Password input container */
    .password-input-container {
        position: relative;
        width: 100%;
    }
    
    .password-toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 8px 12px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        border-radius: 8px;
        z-index: 10;
    }
    
    .password-toggle-btn:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }
    
    .password-toggle-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }
    
    .password-input-container input {
        padding-right: 50px !important;
    }
    
    .password-toggle-btn i {
        transition: all 0.3s ease;
    }
    
    .password-toggle-btn:hover i {
        transform: scale(1.1);
    }
    
    /* Certificado actions */
    .certificado-actions {
        padding: 20px;
        background: rgba(102, 126, 234, 0.05);
        border-radius: 12px;
        border: 2px dashed rgba(102, 126, 234, 0.2);
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
        
        .password-input-container input {
            padding-right: 45px !important;
        }
        
        .actions-panel {
            margin-top: 30px;
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
                        <i class="fas fa-cog me-3"></i>Configuración
                    </h1>
                    <p class="header-subtitle">Configuración de la empresa y datos corporativos</p>
                </div>
                <div class="col-md-4 text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}" class="text-decoration-none">
                                    <i class="fa-solid fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fa-solid fa-cog me-1"></i>Configuración
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Formulario principal --}}
            <div class="col-12 col-lg-9">
                <div class="form-container">
                    <form id="config-form" action="{{ $configuracion ? route('configuracion.update', $configuracion->id) : route('configuracion.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @endif

                        {{-- Sección de Logo --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-image"></i>Logo de la Empresa
                            </h3>
                            
                            <div class="form-group">
                                <label class="form-label" for="logo">
                                    <i class="fas fa-upload me-2"></i>Subir Logo
                                </label>
                                <div class="file-input-container">
                                    <input type="file" 
                                           class="file-input" 
                                           id="logo" 
                                           name="logo" 
                                           accept="image/*"
                                           onchange="previewLogo(this)">
                                    <label for="logo" class="file-input-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span id="file-text">Seleccionar archivo de logo</span>
                                    </label>
                                </div>
                                <small class="text-muted">Formatos soportados: JPG, PNG, GIF. Tamaño máximo: 5MB</small>
                            </div>

                            @if(isset($configuracion->logo) && $configuracion->logo)
                                <div class="logo-preview">
                                    <label class="logo-preview-label">
                                        <i class="fas fa-eye me-2"></i>Logo Actual:
                                    </label>
                                    <img src="{{ asset($configuracion->logo) }}" 
                                         alt="Logo de la empresa" 
                                         id="current-logo"
                                         class="img-fluid">
                                </div>
                            @endif
                            
                            <div id="new-logo-preview" class="logo-preview" style="display: none;">
                                <label class="logo-preview-label">
                                    <i class="fas fa-eye me-2"></i>Nuevo Logo:
                                </label>
                                <img id="preview-image" src="" alt="Vista previa del logo" class="img-fluid">
                            </div>
                        </div>

                        {{-- Sección de Información Básica --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-building"></i>Información Básica
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="company_name">
                                            <i class="fas fa-building me-2"></i>Nombre de la Empresa
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('company_name') is-invalid @enderror" 
                                               id="company_name" 
                                               name="company_name" 
                                               value="{{ $configuracion->company_name ?? '' }}"
                                               placeholder="Ingrese el nombre de la empresa"
                                               required>
                                        @error('company_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="nif">
                                            <i class="fas fa-id-card me-2"></i>NIF/CIF
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('nif') is-invalid @enderror" 
                                               id="nif" 
                                               name="nif" 
                                               value="{{ $configuracion->nif ?? '' }}"
                                               placeholder="Ingrese el NIF/CIF"
                                               required>
                                        @error('nif')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección de Dirección --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-map-marker-alt"></i>Dirección
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="address">
                                            <i class="fas fa-road me-2"></i>Dirección
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('address') is-invalid @enderror" 
                                               id="address" 
                                               name="address" 
                                               value="{{ $configuracion->address ?? '' }}"
                                               placeholder="Ingrese la dirección completa"
                                               required>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="province">
                                            <i class="fas fa-map me-2"></i>Provincia
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('province') is-invalid @enderror" 
                                               id="province" 
                                               name="province" 
                                               value="{{ $configuracion->province ?? '' }}"
                                               placeholder="Provincia">
                                        @error('province')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="town">
                                            <i class="fas fa-city me-2"></i>Ciudad
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('town') is-invalid @enderror" 
                                               id="town" 
                                               name="town" 
                                               value="{{ $configuracion->town ?? '' }}"
                                               placeholder="Ciudad">
                                        @error('town')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="postCode">
                                            <i class="fas fa-mail-bulk me-2"></i>Código Postal
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('postCode') is-invalid @enderror" 
                                               id="postCode" 
                                               name="postCode" 
                                               value="{{ $configuracion->postCode ?? '' }}"
                                               placeholder="Código postal">
                                        @error('postCode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección de Contacto --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-phone"></i>Información de Contacto
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="telephone">
                                            <i class="fas fa-phone me-2"></i>Teléfono
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('telephone') is-invalid @enderror" 
                                               id="telephone" 
                                               name="telephone" 
                                               value="{{ $configuracion->telephone ?? '' }}"
                                               placeholder="Número de teléfono">
                                        @error('telephone')
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
                                               value="{{ $configuracion->email ?? '' }}"
                                               placeholder="Correo electrónico">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección de Datos Financieros --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-euro-sign"></i>Datos Financieros
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="price_hour">
                                            <i class="fas fa-clock me-2"></i>Precio por Hora (€)
                                        </label>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control @error('price_hour') is-invalid @enderror" 
                                               id="price_hour" 
                                               name="price_hour" 
                                               value="{{ $configuracion->price_hour ?? '' }}"
                                               placeholder="0.00"
                                               required>
                                        @error('price_hour')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="bank_account_data">
                                            <i class="fas fa-university me-2"></i>Datos de la Cuenta Bancaria
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('bank_account_data') is-invalid @enderror" 
                                               id="bank_account_data" 
                                               name="bank_account_data" 
                                               value="{{ $configuracion->bank_account_data ?? '' }}"
                                               placeholder="IBAN o datos bancarios">
                                        @error('bank_account_data')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección de Certificado --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-certificate"></i>Certificado
                            </h3>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="certificado">
                                            <i class="fas fa-file-upload me-2"></i>Subir Certificado
                                        </label>
                                        <div class="file-input-container">
                                            <input type="file" 
                                                   class="file-input" 
                                                   id="certificado" 
                                                   name="certificado" 
                                                   accept=".pfx,.p12"
                                                   onchange="previewCertificado(this)">
                                            <label for="certificado" class="file-input-label">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <span id="certificado-text">Seleccionar archivo</span>
                                            </label>
                                        </div>
                                        <small class="text-muted">Formatos soportados: .pfx, .p12</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="contrasena">
                                            <i class="fas fa-lock me-2"></i>Contraseña del Certificado
                                        </label>
                                        <div class="password-input-container">
                                            <input type="password" 
                                                   class="form-control @error('contrasena') is-invalid @enderror" 
                                                   id="contrasena" 
                                                   name="contrasena" 
                                                   value="{{ $configuracion->contrasena ?? '' }}"
                                                   placeholder="Ingrese la contraseña del certificado">
                                            <button type="button" 
                                                    class="password-toggle-btn" 
                                                    onclick="togglePassword()"
                                                    aria-label="Mostrar/ocultar contraseña">
                                                <i id="password-toggle-icon" class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('contrasena')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if(isset($configuracion->certificado) && $configuracion->certificado)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="certificado-actions" style="margin-top: 20px; padding: 20px; background: rgba(102, 126, 234, 0.05); border-radius: 12px; border: 2px dashed rgba(102, 126, 234, 0.2);">
                                            <label class="form-label">
                                                <i class="fas fa-download me-2"></i>Descargar Certificado Actual:
                                            </label>
                                            <a href="{{ route('configuracion.download-certificado') }}" 
                                               class="btn-modern btn-success-modern" 
                                               style="width: auto; margin-top: 10px;">
                                                <i class="fas fa-download"></i>Descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Panel de acciones --}}
            <div class="col-12 col-lg-3">
                <div class="actions-panel">
                    <h3 class="actions-title">
                        <i class="fas fa-cogs me-2"></i>Acciones
                    </h3>
                    
                    <div class="d-grid gap-3">
                        <button type="submit" form="config-form" class="btn-modern btn-success-modern">
                            <i class="fas fa-save"></i>Guardar Configuración
                        </button>
                        
                        <a href="{{ route('dashboard') }}" class="btn-modern btn-secondary-modern text-center">
                            <i class="fas fa-arrow-left"></i>Volver al Dashboard
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>Información
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li><i class="fas fa-check me-2"></i>Los campos marcados con * son obligatorios</li>
                            <li><i class="fas fa-image me-2"></i>El logo se mostrará en el sistema</li>
                            <li><i class="fas fa-euro-sign me-2"></i>El precio por hora se usa para cálculos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@include('partials.toast')
<script>
    // Función para mostrar/ocultar contraseña
    function togglePassword() {
        const passwordInput = document.getElementById('contrasena');
        const toggleIcon = document.getElementById('password-toggle-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    // Función para mostrar vista previa del logo
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Mostrar la vista previa
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('new-logo-preview').style.display = 'block';
                
                // Ocultar el logo actual si existe
                const currentLogo = document.getElementById('current-logo');
                if (currentLogo) {
                    currentLogo.style.display = 'none';
                }
            };
            
            reader.readAsDataURL(input.files[0]);
            
            // Actualizar el texto del label
            document.getElementById('file-text').textContent = input.files[0].name;
        }
    }
    
    // Función para actualizar el texto del certificado seleccionado
    function previewCertificado(input) {
        if (input.files && input.files[0]) {
            document.getElementById('certificado-text').textContent = input.files[0].name;
        } else {
            document.getElementById('certificado-text').textContent = 'Seleccionar archivo';
        }
    }

    // Validación del formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                // Mostrar loading en el botón
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
                submitBtn.disabled = true;
            });
        }
        
        // Efectos de animación en los campos
        const inputs = document.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    });
</script>
@endsection