@extends('layouts.app')

@section('titulo', 'Configuración')

@section('css')
@endsection

@section('content')
    <div class="page-heading card" style="box-shadow: none !important">
        <div class="page-title card-body">
            <div class="row justify-content-between">
                <div class="col-md-4 order-md-1 order-last">
                    <h3>Configuración</h3>
                    <p class="text-subtitle text-muted">Configuración de la empresa</p>
                </div>
                <div class="col-md-4 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Configuración</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section pt-4">
            <div class="card">

                <div class="card-body">
                    @livewire('settings-table')
                </div>
            </div>

        </section>

    </div>
@endsection

@section('scripts')
@include('partials.toast')
@endsection
