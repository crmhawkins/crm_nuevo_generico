@extends('layouts.app')

@section('titulo', 'Detalles del Recuento de Cabina')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}">
@endsection

@section('content')
<div class="page-heading card" style="box-shadow: none !important">
    <div class="page-title card-body">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detalles del Recaudacion</h3>
                <p class="text-subtitle text-muted">Vista detallada del recuento de maquina</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('cabinas.index') }}">Cabinas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalles del Recuento</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section mt-4">
        <div class="card">
            <div class="card-body">
                <div class="bloque-formulario">
                    <div class="row">
                        @foreach ([
                            'billetes_500' => '500€',
                            'billetes_200' => '200€',
                            'billetes_100' => '100€',
                            'billetes_50'  => '50€',
                            'billetes_20'  => '20€',
                            'billetes_10'  => '10€',
                            'billetes_5'   => '5€',
                            'monedas_200' => '2€',
                            'monedas_100' => '1€',
                            'monedas_50'  => '0,50€',
                            'monedas_20'  => '0,20€',
                            'monedas_10'  => '0,10€',
                            'monedas_5'   => '0,05€',
                            'monedas_2'   => '0,02€',
                            'monedas_1'   => '0,01€'
                        ] as $key => $label)
                        <div class="col-md-6 col-lg-4">
                            <div class="mb-3">
                                <label>{{ $label }}</label>
                                <input type="text" class="form-control" value="{{ $cabina->{$key} ?? '0' }}" disabled>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label>Cantidad Total:</label>
                            <input type="text" class="form-control" value="{{ $cabina->monto ?? '0' }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
