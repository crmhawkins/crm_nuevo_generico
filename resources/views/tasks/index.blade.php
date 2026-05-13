@extends('layouts.app')

@section('titulo', 'Tareas')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-check2-square"></i> Tareas</h3>
            <p class="text-subtitle text-muted">Listado de tareas</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tareas</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @livewire('tasks-table')
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')
@endsection
