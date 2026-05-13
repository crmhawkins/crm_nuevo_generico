@extends('layouts.app')

@section('titulo', 'Cuentas Contables')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-calculator"></i> Cuentas Contables</h3>
            <p class="text-subtitle text-muted">Plan de cuentas</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Contabilidad</li>
                    <li class="breadcrumb-item active">Cuentas</li>
                </ol>
            </nav>
            <a href="{{ route('cuentasContables.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-plus-lg"></i> Añadir cuenta contable
            </a>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif
                <div class="mb-3">
                    <form action="{{ route('cuentasContables.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
                            <select name="subGrupo" class="form-control">
                                <option value="">Selecciona Sub Grupo</option>
                                @foreach ($subgrupos as $subgrupo)
                                    <option value="{{ $subgrupo->id }}" {{ request('subGrupo') == $subgrupo->id ? 'selected' : '' }}>{{ $subgrupo->numero }} - {{ $subgrupo->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th><a href="{{ route('cuentasContables.index', array_merge(request()->query(), ['sort' => 'sub_grupo_id', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}">Grupo @if(request('sort') == 'sub_grupo_id')<i class="fa {{ request('order', 'asc') == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>@endif</a></th>
                            <th><a href="{{ route('cuentasContables.index', array_merge(request()->query(), ['sort' => 'numero', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}">Número @if(request('sort') == 'numero')<i class="fa {{ request('order', 'asc') == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>@endif</a></th>
                            <th><a href="{{ route('cuentasContables.index', array_merge(request()->query(), ['sort' => 'nombre', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}">Nombre @if(request('sort') == 'nombre')<i class="fa {{ request('order', 'asc') == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>@endif</a></th>
                            <th><a href="{{ route('cuentasContables.index', array_merge(request()->query(), ['sort' => 'descripcion', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}">Descripción @if(request('sort') == 'descripcion')<i class="fa {{ request('order', 'asc') == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>@endif</a></th>
                            <th>Acciones/Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($response as $item)
                            <tr>
                                <td>{{ $item->grupo->numero }} - {{ $item->grupo->nombre }}</td>
                                <td>{{ $item->numero }}</td>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->descripcion }}</td>
                                <td><a href="{{ route('cuentasContables.edit', $item->id) }}" class="btn btn-secundario">Editar</a></td>
                                <td>
                                    <form action="{{ route('cuentasContables.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-danger delete-btn">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $response->appends(request()->query())->links() }}
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
@include('partials.toast')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      const deleteButtons = document.querySelectorAll('.delete-btn');
      deleteButtons.forEach(button => {
          button.addEventListener('click', function (event) {
              event.preventDefault();
              const form = this.closest('form');
              Swal.fire({
                  title: '¿Estás seguro?',
                  text: "¡No podrás revertir esto!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Sí, eliminar!',
                  cancelButtonText: 'Cancelar'
              }).then((result) => {
                  if (result.isConfirmed) { form.submit(); }
              });
          });
      });
  });
</script>
@endsection
