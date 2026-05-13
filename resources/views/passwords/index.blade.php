@extends('layouts.app')

@section('titulo', 'Contraseñas')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-key"></i> Contraseñas</h3>
            <p class="text-subtitle text-muted">Gestión de contraseñas</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Contraseñas</li>
                </ol>
            </nav>
            <a href="{{ route('passwords.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-plus-lg"></i> Crear
            </a>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @livewire('passwords-table')
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')
@endsection
