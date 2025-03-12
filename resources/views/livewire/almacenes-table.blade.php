<div>
    <div class="filtros row mb-4">
        <div class="col-md-7">
            <div class="d-flex flex-row justify-start">
                <div class="w-25">
                    <label for="">Nº</label>
                    <select wire:model="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Todo</option>
                    </select>
                </div>
                <div class="ms-3 w-75">
                    <label for="">Buscar</label>
                    <input wire:model.debounce.300ms="buscar" type="text" class="form-control w-100" placeholder="Escriba la palabra a buscar...">
                </div>
            </div>
        </div>
    </div>

    @if ($almacenes)
        <div class="table-responsive">
             <table class="table table-hover">
                <thead class="header-table">
                    <tr>
                        @foreach ([
                            'nombre' => 'NOMBRE',
                            'direccion' => 'DIRECCIÓN',
                        ] as $field => $label)
                            <th class="px-3" style="font-size:0.75rem">
                                <a href="#" wire:click.prevent="sortBy('{{ $field }}')">
                                    {{ $label }}
                                    @if ($sortColumn == $field)
                                        <span>{!! $sortDirection == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($almacenes as $almacen)
                        <tr class="clickable-row" data-href="{{route('almacenes.edit', $almacen->id)}}">
                            <td class="px-3">{{ $almacen->nombre }}</td>
                            <td>{{ $almacen->direccion }}</td>
                            <td class="flex flex-row justify-evenly align-middle" style="min-width: 120px">
                                <a class="" href="{{ route('almacenes.show', $almacen->id) }}"><img src="{{ asset('assets/icons/eye.svg') }}" alt="Mostrar almacen"></a>
                                <a class="" href="{{ route('almacenes.edit', $almacen->id) }}"><img src="{{ asset('assets/icons/edit.svg') }}" alt="Editar almacen"></a>
                                <a class="delete" data-id="{{ $almacen->id }}" href=""><img src="{{ asset('assets/icons/trash.svg') }}" alt="Eliminar almacen"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (count($almacenes) == 0)
                <div class="text-center py-4">
                    <h3 class="text-center fs-3">No se encontraron registros de <strong>ALMACENES.</strong></h3>
                    <p class="mt-2">Pulse el botón superior para crear algún almacen.</p>
                </div>
            @endif

            @if ($perPage !== 'all')
                {{ $almacenes->links('vendor.pagination.bootstrap-5') }}
            @endif
        </div>
    @else
        <div class="text-center py-4">
            <h3 class="text-center fs-3">No se encontraron registros de <strong>ALMACENES</strong></h3>
            <p class="mt-2">Pulse el botón superior para crear algún almacen.</p>
        </div>
    @endif
</div>

@section('scripts')
    @include('partials.toast')

    <script>
        $(document).ready(() => {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                botonAceptar(id);
            });
        });

        function botonAceptar(id) {
            Swal.fire({
                title: "¿Estás seguro que quieres eliminar este almacen?",
                html: "<p>Esta acción es irreversible.</p>",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Borrar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.when(getDelete(id)).then(function(data) {
                        if (data.error) {
                            Toast.fire({
                                icon: "error",
                                title: data.mensaje
                            })
                        } else {
                            Toast.fire({
                                icon: "success",
                                title: data.mensaje
                            }).then(() => {
                                location.reload()
                            })
                        }
                    });
                }
            });
        }

        function getDelete(id) {
            const url = '{{ route("almacenes.delete") }}';
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
