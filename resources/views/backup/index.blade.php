@extends('layouts.app')

@section('titulo', 'Backup')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-database"></i> Backup</h3>
            <p class="text-subtitle text-muted">Gestión de copias de seguridad</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Backup</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="" method="">
                    @csrf
                    <div class="mb-3">
                        <label for="backupFrequency" class="form-label" style="width: 200px">Frecuencia del Backup</label>
                        <select class="form-select" id="backupFrequency" name="frequency">
                            <option value="daily">Diario</option>
                            <option value="weekly">Semanal</option>
                            <option value="monthly">Mensual</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Programar Backup</button>
                </form>

                <div class="mt-4">
                    <h4>Descargar Backup</h4>
                    <a href="#" class="btn btn-secondary">Descargar Backup</a>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')
@endsection
