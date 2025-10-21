@extends('layouts.app')

@section('titulo', 'Cargos')

@section('css')
<link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">
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
    
    /* Botones de acción */
    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }
    
    .action-btn.edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
        color: white;
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }
    
    .action-btn.delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
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
                        <i class="fas fa-briefcase me-3"></i>Cargos
                    </h1>
                    <p class="header-subtitle">Listado de cargos y posiciones de la empresa</p>
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
                                <i class="fa-solid fa-briefcase me-1"></i>Cargos
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Tabla de cargos --}}
        <div class="table-container">
            @php
                use Jenssegers\Agent\Agent;
                $agent = new Agent();
            @endphp
            
            @if ($agent->isMobile())
                {{-- Contenido para dispositivos móviles --}}
                @livewire('cargos-table')
            @else
                {{-- Contenido para dispositivos de escritorio --}}
                @livewire('cargos-table')
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    @include('partials.toast')
@endsection