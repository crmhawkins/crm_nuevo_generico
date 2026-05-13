@extends('layouts.app')

@section('titulo', 'Estados de Email')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-envelope-check"></i> Estados de Email</h3>
            <p class="text-subtitle text-muted">Listado de estados de correo</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Estados de Email</li>
                </ol>
            </nav>
            <a href="{{ route('admin.statusMail.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-plus-lg"></i> Crear Estado
            </a>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Color</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statuses as $status)
                            <tr>
                                <td>{{ $status->name }}</td>
                                <td><span class="badge bg-{{ $status->color ?? 'secondary' }}">Color</span></td>
                                <td>
                                    <a href="{{ route('admin.statusMail.edit', $status->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('admin.statusMail.destroy', $status->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
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
    @include('partials.toast')
@endsection
