<div>
    {{-- Filtros modernos --}}
    <div class="row mb-4">
        <div class="col-md-3">
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
                    <input wire:model.debounce.300ms="buscar" type="text" class="form-control" placeholder="Buscar usuarios...">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1">
                <i class="fas fa-building me-1"></i>Departamento
            </label>
            <select wire:model="selectedDepartamento" class="form-select">
                <option value="">-- Todos los departamentos --</option>
                @foreach ($departamentos as $departamento)
                    <option value="{{$departamento->id}}">{{$departamento->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1">
                <i class="fas fa-shield-alt me-1"></i>Nivel de Acceso
            </label>
            <select wire:model="selectedNivel" class="form-select">
                <option value="">-- Todos los niveles --</option>
                @foreach ($niveles as $nivele)
                    <option value="{{$nivele->id}}">{{$nivele->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary w-100">
                <i class="fas fa-plus me-2"></i>Nuevo Usuario
            </a>
        </div>
    </div>

    @if ( $users && $users->count() > 0 )
        {{-- Tabla moderna --}}
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th class="text-center">
                            <i class="fas fa-user-circle me-2"></i>Avatar
                        </th>
                        @foreach ([
                            'name' => 'Nombre',
                            'acceso' => 'Nivel de Acceso',
                            'departamento' => 'Departamento',
                            'cargo' => 'Cargo',
                        ] as $field => $label)
                            <th>
                                <a href="#" wire:click.prevent="sortBy('{{ $field }}')" class="text-white text-decoration-none">
                                    <i class="fas fa-{{ $field === 'name' ? 'user' : ($field === 'acceso' ? 'shield-alt' : ($field === 'departamento' ? 'building' : 'briefcase')) }} me-2"></i>{{ $label }}
                                    @if ($sortColumn == $field)
                                        <i class="fas fa-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="text-center">
                            <i class="fas fa-cogs me-2"></i>Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $users as $user )
                    <tr>
                        <td class="text-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->surname)
                                        <br><small class="text-muted">{{ $user->surname }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->acceso)
                                <span class="badge-modern badge-{{ strtolower(str_replace(' ', '', $user->acceso)) }}">
                                    {{ $user->acceso }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($user->departamento)
                                <span class="badge-modern badge-personal">
                                    <i class="fas fa-building me-1"></i>{{ $user->departamento }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($user->cargo)
                                <span class="badge-modern badge-gestor">
                                    <i class="fas fa-briefcase me-1"></i>{{ $user->cargo }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{route('users.jornadas', $user->id)}}" class="action-btn info" title="Ver jornadas">
                                    <i class="fas fa-clock"></i>
                                </a>
                                <a href="{{route('users.edit', $user->id)}}" class="action-btn edit" title="Editar usuario">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="action-btn delete" data-id="{{$user->id}}" title="Eliminar usuario">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{-- Paginación --}}
            @if($perPage !== 'all' && $users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="no-data">
            <i class="fas fa-users"></i>
            <h4>No se encontraron usuarios</h4>
            <p>No hay registros de usuarios en el sistema.</p>
            <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i>Crear Primer Usuario
            </a>
        </div>
    @endif
</div>
@section('scripts')


    @include('partials.toast')

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
                title: "¿Estas seguro que quieres eliminar este usuario?",
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
                        if (data.error) {
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
            const url = '{{route("users.delete")}}'
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
