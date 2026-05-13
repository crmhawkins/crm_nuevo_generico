@extends('layouts.app')

@section('titulo', 'Productividad')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-graph-up"></i> Productividad</h3>
            <p class="text-subtitle text-muted">Métricas de productividad</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Productividad</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('productividad.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="mes" class="form-label">Seleccione el Mes</label>
                            <input type="month" id="mes" name="mes" class="form-control" value="{{ request('mes', now()->format('Y-m')) }}">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-outline-primary">Ver Productividad</button>
                        </div>
                    </div>
                </form>

                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Productividad (%)</th>
                            <th>Tareas finalizadas</th>
                            <th>Horas Estimadas</th>
                            <th>Horas Reales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productividadUsuarios as $usuario)
                            <tr>
                                <td>{{ $usuario['id'] }}</td>
                                <td>{{ $usuario['nombre'] }}</td>
                                <td>{{ $usuario['productividad'] }}%</td>
                                <td>{{ $usuario['tareasfinalizadas'] }}</td>
                                <td>{{ $usuario['horasEstimadas'] }}</td>
                                <td>{{ $usuario['horasReales'] }}</td>
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
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table1').DataTable({
            paging: false,
            info: false,
            searching: false,
            ordering: true,
            responsive: true
        });
    });
</script>
@include('partials.toast')
@endsection
