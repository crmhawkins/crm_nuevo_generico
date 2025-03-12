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

    @if ($maquinas)
        <div class="table-responsive">
             <table class="table table-hover">
                <thead class="header-table">
                    <tr>
                        @foreach ([
                            'nombre' => 'NOMBRE',
                            'n_serie' => 'Nº SERIE',
                            'categoria_id' => 'CATEGORIA',
                            'almacen_id' => 'ALMACEN',
                            'salon_id' => 'SALON',
                            'local_id' => 'LOCAL',
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
                    @foreach ($maquinas as $maquina)
                        <tr class="clickable-row" data-href="{{route('maquinas.edit', $maquina->id)}}">
                            <td class="px-3">{{ $maquina->nombre }}</td>
                            <td>{{ $maquina->n_serie }}</td>
                            <td>{{ optional($maquina->categoria)->nombre ?? ($maquina->categoria_id ? 'Categoria borrada' : 'Sin categoria asignada')}}</td>
                            <td>{{ optional($maquina->almacen)->nombre ?? ''}}</td>
                            <td>{{ optional($maquina->salon)->nombre ?? ''}}</td>
                            <td>{{ optional($maquina->local)->local ?? ''}}</td>
                            <td class="flex flex-row justify-evenly align-middle" style="min-width: 120px">
                                <a class="" href="{{ route('maquinas.edit', $maquina->id) }}"><img src="{{ asset('assets/icons/edit.svg') }}" alt="Editar maquina"></a>
                                <a class="delete" data-id="{{ $maquina->id }}" href=""><img src="{{ asset('assets/icons/trash.svg') }}" alt="Eliminar maquina"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (count($maquinas) == 0)
                <div class="text-center py-4">
                    <h3 class="text-center fs-3">No se encontraron registros de <strong>MAQUINAS.</strong></h3>
                    <p class="mt-2">Pulse el botón superior para crear alguna maquina.</p>
                </div>
            @endif

            @if ($perPage !== 'all')
                {{ $maquinas->links('vendor.pagination.bootstrap-5') }}
            @endif
        </div>
    @else
        <div class="text-center py-4">
            <h3 class="text-center fs-3">No se encontraron registros de <strong>MAQUINAS</strong></h3>
            <p class="mt-2">Pulse el botón superior para crear alguna maquina.</p>
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
                title: "¿Estás seguro que quieres eliminar este maquina?",
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
            const url = '{{ route("maquinas.delete") }}';
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
