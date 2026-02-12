@extends('layouts.app')

@section('titulo', 'Registros')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />

@endsection

@section('content')

    <div class="page-heading card" style="box-shadow: none !important" >
        <div class="page-title card-body">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Registros</h3>
                    <p class="text-subtitle text-muted">Listado de registros</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Registros</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section pt-4">
            @if(isset($beneficiarioNombreCompleto) && $beneficiarioNombreCompleto)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="beneficiario-logo" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    <div style="font-size: 24px; line-height: 1.2;">{{ $iniciales }}</div>
                                    <div style="font-size: 10px; margin-top: 4px; text-align: center; max-width: 70px; overflow: hidden; text-overflow: ellipsis;">{{ $beneficiarioNombreCompleto }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1">Nombre del beneficiario:</h5>
                                <p class="mb-0 text-muted">{{ $beneficiarioNombreCompleto }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    {{-- <livewire:users-table-view> --}}
                    @php
                        use Jenssegers\Agent\Agent;

                        $agent = new Agent();
                    @endphp
                    @if ($agent->isMobile())
                        {{-- Contenido para dispositivos móviles --}}

                        @livewire('logs-table')

                    @else
                        {{-- Contenido para dispositivos de escritorio --}}
                        {{-- <livewire:users-table-view> --}}
                        @livewire('logs-table')
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')


    @include('partials.toast')

@endsection
