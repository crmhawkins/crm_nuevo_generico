@extends('layouts.app')

@section('titulo', 'Logs')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-journal-text"></i> Logs</h3>
            <p class="text-subtitle text-muted">Registro de actividad</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Logs</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @livewire('logs-table')
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')
@endsection
