@extends('layouts.app')

@section('titulo', 'Vacaciones')

@section('content')

<div class="page-heading card" style="box-shadow:none!important">

    <div class="page-title card-body">
        <div>
            <h3><i class="bi bi-umbrella"></i> Vacaciones</h3>
            <p class="text-subtitle text-muted">Gestión de vacaciones</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vacaciones</li>
                </ol>
            </nav>
            <a class="btn btn-outline-secondary" href="{{ route('holiday.create') }}">
                <i class="bi bi-plus-lg"></i> Petición de vacaciones
            </a>
        </div>
    </div>

    <div class="row px-3 pb-3">
        <div class="col-lg col-md-6 mt-3">
            <div class="card2">
                <div class="card-body">
                    <div class="col-12" style="text-align: center">
                        @if($userHolidaysQuantity)
                            @if($userHolidaysQuantity->quantity == 1)
                                <p>Tienes <span style="color:green"><strong>{{ $userHolidaysQuantity->quantity }}</strong></span> día de vacaciones</p>
                            @endif
                            @if($userHolidaysQuantity->quantity > 1)
                                <p>Tienes <span style="color:green"><strong>{{ $userHolidaysQuantity->quantity }}</strong></span> días de vacaciones</p>
                            @endif
                        @else
                            <p>No tienes días de vacaciones</p>
                        @endif
                        @if($numberOfHolidayPetitions)
                            @if($numberOfHolidayPetitions == 1)
                                <p>Tienes <span style="color:orange"><strong>{{ $numberOfHolidayPetitions }}</strong></span> petición pendiente</p>
                            @endif
                            @if($numberOfHolidayPetitions > 1)
                                <p>Tienes <span style="color:orange"><strong>{{ $numberOfHolidayPetitions }}</strong></span> peticiones pendientes</p>
                            @endif
                        @else
                            <p>No tienes peticiones pendientes</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-6 mt-3">
            <div class="card2">
                <div class="card-body">
                    <div class="col-12" style="text-align: center">
                        <p><strong>ESTADOS</strong></p>
                        <p>
                            <i class="fa fa-square" style="color:#FFDD9E"></i>&nbsp;&nbsp;PENDIENTE
                            <i class="fa fa-square" style="margin-left:5%;color:#C3EBC4"></i>&nbsp;&nbsp;ACEPTADA
                            <i class="fa fa-square" style="margin-left:5%;color:#FBC4C4"></i>&nbsp;&nbsp;DENEGADA
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @livewire('myholidays-table')
            </div>
        </div>
    </section>

</div>

@endsection

@section('scripts')
    @include('partials.toast')
@endsection
