@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/css/dashboard.css')}}" />

@endsection

@section('content')
<div class="page-heading card" style="box-shadow: none !important" >
    <div class="page-title card-body">
        <div class="row">
            <div class="col-12 col-md-4 order-md-1 order-last">
                <h3>Dashboard</h3>
            </div>

            <div class="col-12 col-md-8 order-md-2 order-s">
                {{-- <div class="row justify-end">
                    <button id="endllamadaBtn" class="btn jornada btn-danger mx-2 col-2" onclick="endLlamada()" style="display:none;">Finalizar llamada</button>
                     <h2 id="timer" class="display-6 font-weight-bold col-3">00:00:00</h2>
                    <button id="startJornadaBtn" class="btn jornada btn-primary mx-2 col-2" onclick="startJornada()">Inicio Jornada</button>
                    <button id="startPauseBtn" class="btn jornada btn-secondary mx-2 col-2" onclick="startPause()" style="display:none;">Iniciar Pausa</button>
                    <button id="endPauseBtn" class="btn jornada btn-dark mx-2 col-2" onclick="endPause()" style="display:none;">Finalizar Pausa</button>
                    <button id="endJornadaBtn" class="btn jornada btn-danger mx-2 col-2" onclick="endJornada()" style="display:none;">Fin de Jornada</button>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="card2 mt-4">
        <div class="card-body2">
            <div class="row justify-between">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap">
                        <div class="card mb-3 mr-3 col-12 row-cols-xl">
                            <div class="card-body">
                                <div class="d-flex flex-wrap">
                                    <div class="col-12 d-flex justify-content-center mb-4 align-items-center">
                                         <div class="mx-6 text-center">
                                            <h5 class="my-3">{{$user->name}}&nbsp;{{$user->surname}}</h5>
                                            <p class="text-muted mb-1">{{$user->departamento->name}}</p>
                                            <p class="text-muted mb-4">{{$user->acceso->name}}</p>
                                        </div>
                                        <div class="mx-6">
                                            @if ($user->image == null)
                                                <img alt="avatar" class="rounded-circle img-fluid  m-auto" style="width: 150px;" src="{{asset('assets/images/guest.webp')}}" />
                                            @else
                                                <img alt="avatar" class="rounded-circle img-fluid  m-auto" style="width: 150px;" src="{{ asset('/storage/avatars/'.$user->image) }}" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mb-3 card-body">
                            <h5 class="card-title fw-bold">Presupuestos</h5>
                            <div class="row row-cols-1 row-cols-xl-3 g-xl-4 g-3 mb-3">
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">Pendientes de confirmar</h5>
                                            <span class="display-6 m-0"><b>{{count($user->presupuestosPorEstado(1))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">Pendientes de aceptar</h5>
                                            <span class="display-6 m-0"><b>{{count($user->presupuestosPorEstado(2))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">Aceptados</h5>
                                            <span class="display-6 m-0"><b>{{count($user->presupuestosPorEstado(3))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="mb-3 card-body col-12">
                            <h5 class="card-title fw-bold">Facturas</h5>
                            <div class="row row-cols-1 row-cols-xl-5 g-xl-4 g-3 mb-3">
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">Pendientes</h5>
                                            <span class="display-6 m-0"><b>{{count($user->facturasPorEstado(1))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">No Cobradas</h5>
                                            <span class="display-6 m-0"><b>{{count($user->facturasPorEstado(2))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">Cobradas</h5>
                                            <span class="display-6 m-0"><b>{{count($user->facturasPorEstado(3))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">Cobradas Parcialmente</h5>
                                            <span class="display-6 m-0"><b>{{count($user->facturasPorEstado(4))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <h5 class="card-title m-0 text-color-4 fw-bold">Canceladas</h5>
                                            <span class="display-6 m-0"><b>{{count($user->facturasPorEstado(5))}}</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
@include('partials.toast')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>

@endsection

