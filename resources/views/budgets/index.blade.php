@extends('layouts.app')

@section('titulo', 'Presupuestos')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-file-earmark-text"></i> Presupuestos</h3>
            <p class="text-subtitle text-muted">Gestión de presupuestos</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Presupuestos</li>
                </ol>
            </nav>
            <a href="{{ route('presupuesto.create') }}" class="btn btn-primary ms-2">
                <i class="bi bi-plus-lg"></i> Nuevo presupuesto
            </a>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @livewire('budgets-table')
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')
@endsection
