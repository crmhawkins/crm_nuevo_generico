<div>
    @php
        // Verificar valores de sesión para debug
        $sessionNombre = session('beneficiario_nombre_completo', '');
        $sessionIniciales = '';
        if (!empty($sessionNombre)) {
            $palabras = array_filter(explode(' ', trim($sessionNombre)));
            $palabras = array_values($palabras);
            if (count($palabras) >= 2) {
                $sessionIniciales = strtoupper(mb_substr($palabras[0], 0, 1, 'UTF-8') . ' ' . mb_substr($palabras[count($palabras) - 1], 0, 1, 'UTF-8'));
            } elseif (count($palabras) == 1) {
                $sessionIniciales = strtoupper(mb_substr($palabras[0], 0, 1, 'UTF-8'));
            }
        }
    @endphp

    @if(!empty($beneficiarioNombreCompleto) && !empty($iniciales))
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto text-center" style="min-width: 120px;">
                        <div class="beneficiario-logo" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 auto 10px auto;">
                            <span style="font-size: 32px; line-height: 1; letter-spacing: 3px; font-family: Arial, sans-serif;">{{ $iniciales }}</span>
                        </div>
                        <div style="font-size: 11px; color: #495057; font-weight: 500; word-wrap: break-word; line-height: 1.3;">{{ $beneficiarioNombreCompleto }}</div>
                    </div>
                    <div class="col">
                        <h5 class="mb-2" style="font-weight: 600; color: #212529;">Nombre del beneficiario:</h5>
                        <p class="mb-0" style="font-size: 16px; color: #495057; font-weight: 500;">{{ $beneficiarioNombreCompleto }}</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif(!empty($sessionNombre) && !empty($sessionIniciales))
        {{-- Fallback: usar datos directos de sesión si no vienen del componente --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto text-center" style="min-width: 120px;">
                        <div class="beneficiario-logo" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 auto 10px auto;">
                            <span style="font-size: 32px; line-height: 1; letter-spacing: 3px; font-family: Arial, sans-serif;">{{ $sessionIniciales }}</span>
                        </div>
                        <div style="font-size: 11px; color: #495057; font-weight: 500; word-wrap: break-word; line-height: 1.3;">{{ $sessionNombre }}</div>
                    </div>
                    <div class="col">
                        <h5 class="mb-2" style="font-weight: 600; color: #212529;">Nombre del beneficiario:</h5>
                        <p class="mb-0" style="font-size: 16px; color: #495057; font-weight: 500;">{{ $sessionNombre }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="filtros row mb-4">
        <div class="col-md-3 col-sm-12">
            <div class="flex flex-row justify-start">
                <div class="mr-3">
                    <label for="">Nº</label>
                    <select wire:model="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">Todo</option>
                    </select>
                </div>
                <div class="w-50">
                    <label for="">Buscar</label>
                    <input wire:model.debounce.300ms="buscar" type="text" class="form-control w-100" placeholder="Escriba la palabra a buscar...">
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-12">
            <div class="flex flex-row justify-end">
                <div class="mr-3 w-50">
                    <label for="">Año</label>
                    <select wire:model="selectedYear" class="form-select">
                        <option value=""> Año </option>
                        @for ($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mr-3">
                    <label for="">Usuario</label>
                    <select wire:model="usuario" name="" id="" class="form-select ">
                        <option value="">-- Seleccione un Tipo --</option>
                         @foreach ($usuarios as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @if ( $logs )
        <div class="table-responsive">
             <table class="table table-hover">
                <thead class="header-table">
                    <tr>
                        @foreach ([
                            'usuario' => 'USUARIO',
                            'action' => 'ACCION',
                            'description' => 'DESCRIPCION',
                            'reference_id' => 'REFERENCIA',
                            'created_at' => 'FECHA CREACION',

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
                </thead>
                <tbody>
                    @foreach ( $logs as $log )
                        <tr>
                            <td>{{$log->usuario}}</td>
                            <td>{{$log->action}}</td>
                            <td>{{$log->description}}</td>
                            <td>{{$log->reference_id}}</td>
                            <td>{{$log->created_at}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($perPage !== 'all')
                {{ $logs->links() }}
            @endif
        </div>
    @else
        <div class="text-center py-4">
            <h3 class="text-center fs-3">No se encontraron registros de <strong>LOGS</strong></h3>
        </div>
    @endif
    {{-- {{$users}} --}}
</div>
