@extends('layouts.app')

@section('titulo', 'Diario de Caja')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-cash-coin"></i> Diario de Caja</h3>
            <p class="text-subtitle text-muted">Registro diario</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Contabilidad</li>
                    <li class="breadcrumb-item active">Diario de caja</li>
                </ol>
            </nav>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDiarioCaja">
                <i class="bi bi-plus-lg"></i> Añadir al diario de caja
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDiarioCaja" tabindex="-1" aria-labelledby="modalDiarioCajaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDiarioCajaLabel">Añadir al Diario de Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <a href="{{ route('diarioCaja.ingreso') }}" class="btn btn-primary">Añadir Ingreso</a>
                    <a href="{{ route('diarioCaja.gasto') }}" class="btn btn-secondary">Añadir Gasto</a>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Asiento</th>
                                <th>Estado</th>
                                <th>Cuenta</th>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Forma de Pago</th>
                                <th>Debe</th>
                                <th>Haber</th>
                                <th>Saldo</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($response) > 0)
                                @foreach ($response as $linea)
                                <tr>
                                    <td>{{ $linea->asiento_contable }}</td>
                                    <td>{{ $linea->estado->nombre }}</td>
                                    <td
                                        style="cursor: pointer"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        data-bs-title="{{ $linea->determineCuenta()->nombre }}">
                                        {{ $linea->determineCuenta()->numero }}
                                    </td>
                                    <td>{{ $linea->date }}</td>
                                    <td>{{ $linea->concepto }}</td>
                                    <td>{{ $linea->forma_pago }}</td>
                                    <td>{{ $linea->debe }} €</td>
                                    <td>{{ $linea->haber }} €</td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-warning">Editar</button>
                                        <button class="btn btn-danger">Eliminar</button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
@include('partials.toast')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

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
