<div>
    {{-- Filtros --}}
    <div class="filtros row mb-4">
        <div class="col-md-3">
            <div class="flex flex-row justify-start">
                <div class="mr-3">
                    <label for="">Nª</label>
                    <select wire:model="perPage" class="form-select">
                        <option value="10">10 </option>
                        <option value="25">25 </option>
                        <option value="15">50 </option>
                        <option value="all">Todo</option>
                    </select>
                </div>
                <div class="w-75">
                    <label for="">Buscar</label>
                    <input wire:model.debounce.300ms="buscar" type="text" class="form-control w-100" placeholder="Escriba la palabra a buscar...">
                </div>
            </div>
        </div>
    </div>

    @if ( $companies )

        {{-- Tabla --}}
        <div class="table-responsive">
             <table class="table table-hover">
                <thead class="header-table">
                    <tr>
                        @foreach ([
                            'company_name' => 'NOMBRE',
                            'nif' => 'NIF',
                            'address' => 'DIRECCION',
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
                        <th class="text-center" style="font-size:0.75rem">ACCIONES</th>
                </thead>
                <tbody>
                    {{-- Recorremos los servicios --}}
                    @foreach ( $companies as $company )
                        <tr class="clickable-row" data-href="{{route('configuracion.edit', $company->id)}}" >
                            <td class="px-3">{{$company->company_name}}</td>
                            <td class="">{{$company->nif}}</td>
                            <td class="">{{$company->address}}</td>
                            <td class="flex flex-row justify-evenly align-middle" style="min-width: 120px">
                                <a class="" href="{{route('configuracion.edit', $company->id)}}"><img src="{{asset('assets/icons/edit.svg')}}" alt="Editar servicio"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if( count($companies) == 0 )
                <div class="text-center py-4">
                    <h3 class="text-center fs-3">No se encontraron registros de <strong>Empresas</strong></h3>
                </div>
            @endif
            @if($perPage !== 'all')
                {{ $companies->links() }}
            @endif
        </div>
    @else
        <div class="text-center py-4">
            <h3 class="text-center fs-3">No se encontraron registros de <strong>Empresas</strong></h3>
        </div>
    @endif
    {{-- {{$users}} --}}
</div>
@section('scripts')
<!-- Choices.js CSS -->
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>



    @include('partials.toast')




@endsection
