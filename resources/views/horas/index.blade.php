@extends('layouts.app')

@section('titulo', 'Horas')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-clock"></i> Horas</h3>
            <p class="text-subtitle text-muted">Control horario</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Horas</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('horas.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="week" class="form-label">Seleccione la Semana</label>
                            <input type="week" id="week" name="week" class="form-control" value="{{ request('week', now()->format('Y-\WW')) }}">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-outline-primary">Ver Jornada</button>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <a href="{{ route('horas.export', ['week' => request('week', now()->format('Y-\WW'))]) }}" class="btn btn-outline-success">Exportar a Excel</a>
                        </div>
                    </div>
                </form>

                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Departamento</th>
                            <th>Vacaciones</th>
                            <th>Puntualidad</th>
                            <th>Baja</th>
                            <th>Horas Trabajadas / Horas Producidas</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            <tr class="usuario-row">
                                <td>{{ $usuario['usuario'] }}</td>
                                <td>{{ $usuario['departamento'] }}</td>
                                <td>{{ $usuario['vacaciones'] }} días</td>
                                <td>{{ $usuario['puntualidad'] }} días</td>
                                <td>{{ $usuario['baja'] }} días</td>
                                <td>{{ $usuario['horas_trabajadas'] }} / {{ $usuario['horas_producidas'] }}</td>
                                <td>
                                    <button class="btn btn-outline-secondary toggle-details" type="button" data-toggle="collapse" data-target="#detalles-{{ $loop->index }}" aria-expanded="false">
                                        Ver Detalles
                                    </button>
                                </td>
                            </tr>
                            <tr id="detalles-{{ $loop->index }}" class="collapse">
                                <td colspan="6">
                                    <table class="table table-sm border-0">
                                        <tbody>
                                            <tr>
                                                <td><strong>Horas Trabajadas:</strong></td>
                                                <td><strong>Lunes:</strong> {{ $usuario['horasTrabajadasLunes'] }}</td>
                                                <td><strong>Martes:</strong> {{ $usuario['horasTrabajadasMartes'] }}</td>
                                                <td><strong>Miércoles:</strong> {{ $usuario['horasTrabajadasMiercoles'] }}</td>
                                                <td><strong>Jueves:</strong> {{ $usuario['horasTrabajadasJueves'] }}</td>
                                                <td><strong>Viernes:</strong> {{ $usuario['horasTrabajadasViernes'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Horas Producidas:</strong></td>
                                                <td><strong>Lunes:</strong> {{ $usuario['horasProducidasLunes'] }}</td>
                                                <td><strong>Martes:</strong> {{ $usuario['horasProducidasMartes'] }}</td>
                                                <td><strong>Miércoles:</strong> {{ $usuario['horasProducidasMiercoles'] }}</td>
                                                <td><strong>Jueves:</strong> {{ $usuario['horasProducidasJueves'] }}</td>
                                                <td><strong>Viernes:</strong> {{ $usuario['horasProducidasViernes'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table1 tbody').on('click', '.toggle-details', function () {
            let target = $(this).data('target');
            $(target).collapse('toggle');
            $(target).on('shown.bs.collapse', () => {
                $(this).text('Ocultar');
            });
            $(target).on('hidden.bs.collapse', () => {
                $(this).text('Ver Detalles');
            });
        });
    });
</script>
@include('partials.toast')
@endsection
