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
        <div class="col-md-5 col-sm-12 mt-sm-2 mt-md-0">
            <div class="flex flex-row justify-end">
                <div class="mr-3">
                    <label for="">Salones</label>
                    <select wire:model="selectedSalon" name="" id="" class="form-select ">
                        <option value="">Seleccione un Salon</option>
                        @foreach ($salones as $salon)
                            <option value="{{$salon->id}}">{{$salon->nombre}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if ($cajas)
        <div class="table-responsive">
             <table class="table table-hover">
                <thead class="header-table">
                    <tr>
                        @foreach ([
                            'salon_id' => 'SALON',
                            'apertura' => 'APERTURA',
                            'cierre' => 'CIERRE',
                            'previsto' => 'PREVISTO',
                            'diferencia' => 'DIFERENCIA',
                            'cambio' => 'CAMBIO',
                            'created_at' => 'H.APERTURA',
                            'updated_at' => 'H.CIERRE',
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

                    </tr>
                </thead>
                <tbody>
                    @foreach ($cajas as $caja)
                        <tr>
                            <td class="px-3">{{ optional($caja->salon)->nombre }}</td>
                            <td>{{ $caja->apertura }}</td>
                            <td>{{ $caja->cierre }}</td>
                            <td>{{ $caja->previsto }}</td>
                            <td>{{ $caja->diferencia }}</td>
                            <td>{{ $caja->cambio }}</td>
                            <td>{{ $caja->created_at ? \Carbon\Carbon::parse($caja->created_at)->format('Y-m-d H:i') : '' }}</td>
                            <td>{{ $caja->updated_at != $caja->created_at ? \Carbon\Carbon::parse($caja->updated_at)->format('Y-m-d H:i') : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (count($cajas) == 0)
                <div class="text-center py-4">
                    <h3 class="text-center fs-3">No se encontraron registros de <strong>CABINAS.</strong></h3>
                    <p class="mt-2">Pulse el botón superior para crear algún caja.</p>
                </div>
            @endif

            @if ($perPage !== 'all')
                {{ $cajas->links('vendor.pagination.bootstrap-5') }}
            @endif
        </div>
    @else
        <div class="text-center py-4">
            <h3 class="text-center fs-3">No se encontraron registros de <strong>CABINAS</strong></h3>
            <p class="mt-2">Pulse el botón superior para crear algún caja.</p>
        </div>
    @endif
</div>

@section('scripts')
    @include('partials.toast')

    {{-- <script>
        $(document).ready(() => {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                botonAceptar(id);
            });
        });

        function botonAceptar(id) {
            Swal.fire({
                title: "¿Estás seguro que quieres eliminar esta caja?",
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
            const url = '{{ route("cajas.delete") }}';
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
    </script> --}}
@endsection
