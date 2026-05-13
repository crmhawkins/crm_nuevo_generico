@extends('layouts.app')

@section('titulo', 'Correos')

@section('css')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
    }
</style>
@endsection

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-envelope"></i> Correos</h3>
            <p class="text-subtitle text-muted">Bandeja de entrada</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Correos</li>
                </ol>
            </nav>
            <a class="btn btn-primary" href="{{ route('admin.emails.create') }}">
                <i class="bi bi-plus-lg"></i> Nuevo Correo
            </a>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body row">
                <div class="col-md-3">
                    <ul class="nav flex-column nav-tabs list-unstyled" id="emailTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button" role="tab" aria-controls="inbox" aria-selected="true">
                                <i class="bi bi-inbox me-2"></i> Bandeja de Entrada
                            </button>
                        </li>
                        @foreach ($categorias as $categoria)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="category-{{ $categoria->id }}-tab" data-bs-toggle="tab" data-bs-target="#category-{{ $categoria->id }}" type="button" role="tab" aria-controls="category-{{ $categoria->id }}" aria-selected="false">
                                <i class="bi bi-tag me-2"></i> {{ $categoria->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="emailTabContent">
                        <div class="tab-pane fade show active" id="inbox" role="tabpanel" aria-labelledby="inbox-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover align-middle table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Remitente</th>
                                            <th>Asunto</th>
                                            <th>Categoría</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($emails->where('category_id', '!=', 6) as $email)
                                        <tr class="clickable-row" data-href="{{ route('admin.emails.show', $email->id) }}">
                                            <td class="text-truncate" style="max-width: 150px;">{{ $email->sender }}</td>
                                            <td>{{ Str::limit($email->subject, 50) }}</td>
                                            <td>{{ $email->category->name ?? 'Sin categoría' }}</td>
                                            <td><span class="badge bg-{{ $email->status->color ?? 'secondary' }}">{{ $email->status->name ?? 'Desconocido' }}</span></td>
                                            <td>{{ $email->created_at->format('d M Y, g:i A') }}</td>
                                            <td class="text-end">
                                                <button data-id="{{ $email->id }}" class="btn btn-sm btn-outline-danger delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No hay correos disponibles.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $emails->links() }}
                            </div>
                        </div>
                        @foreach ($categorias as $categoria)
                        <div class="tab-pane fade" id="category-{{ $categoria->id }}" role="tabpanel" aria-labelledby="category-{{ $categoria->id }}-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover align-middle table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Remitente</th>
                                            <th>Asunto</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($emails->where('category_id', $categoria->id) as $email)
                                        <tr class="clickable-row" data-href="{{ route('admin.emails.show', $email->id) }}">
                                            <td class="text-truncate" style="max-width: 150px;">{{ $email->sender }}</td>
                                            <td>{{ Str::limit($email->subject, 50) }}</td>
                                            <td><span class="badge bg-{{ $email->status->color ?? 'secondary' }}">{{ $email->status->name ?? 'Desconocido' }}</span></td>
                                            <td>{{ $email->created_at->format('d M Y, g:i A') }}</td>
                                            <td class="text-end">
                                                <button data-id="{{ $email->id }}" class="btn btn-sm btn-outline-danger delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No hay correos disponibles en esta categoría.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
@include('partials.toast')
<script>
$(document).ready(() => {
    $('.clickable-row').on('click', function() {
        window.location = $(this).data('href');
    });

    $('.delete').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        botonAceptar(id);
    });
});

function botonAceptar(id){
    Swal.fire({
        title: "¿Estás seguro de que quieres eliminar este correo?",
        html: "<p>Esta acción es irreversible.</p>",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Borrar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.when(getDelete(id)).then(function(data, textStatus, jqXHR) {
                if (!data.status) {
                    Toast.fire({ icon: "error", title: data.mensaje });
                } else {
                    Toast.fire({ icon: "success", title: data.mensaje }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}

function getDelete(id) {
    const url = '{{ route("admin.emails.destroy") }}';
    return $.ajax({
        type: "POST",
        url: url,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { 'id': id },
        dataType: "json"
    });
}
</script>
@endsection
