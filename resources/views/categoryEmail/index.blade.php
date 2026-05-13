@extends('layouts.app')

@section('titulo', 'Categorías de Email')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-tags"></i> Categorías de Email</h3>
            <p class="text-subtitle text-muted">Gestión de categorías</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categorías de Email</li>
                </ol>
            </nav>
            <a href="{{ route('admin.categoriaEmail.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-plus-lg"></i> Crear Categoría
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
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <a href="{{ route('admin.categoriaEmail.edit', $category->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('admin.categoriaEmail.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
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
