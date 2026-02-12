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
            @if(isset($beneficiarioNombreCompleto) && !empty($beneficiarioNombreCompleto) && isset($iniciales) && !empty($iniciales))
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
