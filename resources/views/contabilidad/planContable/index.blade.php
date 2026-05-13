@extends('layouts.app')

@section('titulo', 'Plan Contable')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-list-columns"></i> Plan Contable</h3>
            <p class="text-subtitle text-muted">Plan contable</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Contabilidad</li>
                    <li class="breadcrumb-item active">Plan</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Nombre</th>
                            <th>Nivel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grupos as $grupo)
                            <tr>
                                <td><strong>{{ $grupo->numero }}</strong></td>
                                <td><strong>{{ $grupo->nombre }}</strong></td>
                                <td>Grupo</td>
                            </tr>
                            @foreach ($grupo->subGrupos as $subGrupo)
                                <tr>
                                    <td>{{ $subGrupo->numero }}</td>
                                    <td>{{ $subGrupo->nombre }}</td>
                                    <td>SubGrupo</td>
                                </tr>
                                @foreach ($subGrupo->cuentas as $cuenta)
                                    <tr>
                                        <td>{{ $cuenta->numero }}</td>
                                        <td>{{ $cuenta->nombre }}</td>
                                        <td>Cuenta</td>
                                    </tr>
                                    @foreach ($cuenta->subCuentas as $subCuenta)
                                        <tr>
                                            <td>{{ $subCuenta->numero }}</td>
                                            <td>{{ $subCuenta->nombre }}</td>
                                            <td>SubCuenta</td>
                                        </tr>
                                        @foreach ($subCuenta->cuentasHijas as $cuentaHija)
                                            <tr>
                                                <td>{{ $cuentaHija->numero }}</td>
                                                <td>{{ $cuentaHija->nombre }}</td>
                                                <td>SubCuenta Hija</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')
@endsection
