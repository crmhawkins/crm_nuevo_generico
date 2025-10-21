<div>
    {{-- Filtros modernos --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                    <label class="form-label mb-1">
                        <i class="fas fa-list me-1"></i>Mostrar
                    </label>
                    <select wire:model="perPage" class="form-select" style="width: 80px;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Todo</option>
                    </select>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label mb-1">
                        <i class="fas fa-search me-1"></i>Buscar
                    </label>
                    <input wire:model.debounce.300ms="buscar" type="text" class="form-control" placeholder="Escriba el nombre del cargo...">
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <a href="{{ route('cargo.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Cargo
            </a>
        </div>
    </div>

    @if ( $cargos && $cargos->count() > 0 )
        {{-- Tabla moderna --}}
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>
                            <a href="#" wire:click.prevent="sortBy('name')" class="text-white text-decoration-none">
                                <i class="fas fa-briefcase me-2"></i>Nombre del Cargo
                                @if ($sortColumn == 'name')
                                    <i class="fas fa-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <i class="fas fa-cogs me-2"></i>Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $cargos as $cargo )
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                    {{ substr($cargo->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $cargo->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{route('cargo.edit', $cargo->id)}}" class="action-btn edit" title="Editar cargo">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="action-btn delete" data-id="{{$cargo->id}}" title="Eliminar cargo">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{-- Paginación --}}
            @if($perPage !== 'all' && $cargos->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $cargos->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="no-data">
            <i class="fas fa-briefcase"></i>
            <h4>No se encontraron cargos</h4>
            <p>No hay registros de cargos en el sistema.</p>
            <a href="{{ route('cargo.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i>Crear Primer Cargo
            </a>
        </div>
    @endif
</div>
@section('scripts')


    @include('partials.toast')
    <script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>

    <script>
        $(document).ready(() => {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id'); // Usa $(this) para obtener el atributo data-id
                botonAceptar(id);

            });
        });

        function botonAceptar(id){
            // Salta la alerta para confirmar la eliminacion
            Swal.fire({
                title: "¿Estas seguro que quieres eliminar este cargo?",
                html: "<p>Esta acción es irreversible.</p>", // Corrige aquí
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Borrar",
                cancelButtonText: "Cancelar",
                // denyButtonText: `No Borrar`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // Llamamos a la funcion para borrar el usuario
                    $.when( getDelete(id) ).then(function( data, textStatus, jqXHR ) {
                        console.log(data)
                        if (!data.status) {
                            // Si recibimos algun error
                            Toast.fire({
                                icon: "error",
                                title: data.mensaje
                            })
                        } else {
                            // Todo a ido bien
                            Toast.fire({
                                icon: "success",
                                title: data.mensaje
                            })
                            .then(() => {
                                location.reload()
                            })
                        }
                    });
                }
            });
        }
        function getDelete(id) {
            // Ruta de la peticion
            const url = '{{route("cargo.delete")}}'
            // Peticion
            return $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    'id': id,
                },
                dataType: "json"
            });
        }
    </script>
@endsection
