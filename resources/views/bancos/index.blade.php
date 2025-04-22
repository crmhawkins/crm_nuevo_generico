@extends('layouts.app')

@section('titulo', 'Bancos')

@section('css')
<link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">

@endsection

@section('content')

    <div class="page-heading card" style="box-shadow: none !important" >

        {{-- Titulos --}}
        <div class="page-title card-body">
            <div class="row justify-content-between">
                <div class="col-12 col-md-4 order-md-1 order-last">
                    <h3><i class="bi bi-bookmark-check"></i> Bancos</h3>
                    <p class="text-subtitle text-muted">Listado de bancos</p>
                    {{-- {{$bancos->count()}} --}}
                </div>
                <div class="col-12 col-md-4 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bancos</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section pt-4">
            <div class="card">

                <div class="card-body">
                        {{-- Contenido para dispositivos móviles --}}
                        <div>
                            @if ($bancos->count() >= 0)
                                @foreach ($bancos as $banco)
                                    <div class="card border-bottom">
                                        <div class="card-body" href="{{route('bancos.edit',$banco->id)}}">
                                            <h5 class="card-title">{{ $banco->name }}</h5>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-center">
                                    {{ $bancos->links() }}
                                </div>
                            @endif
                        </div>

                </div>
            </div>

        </section>

    </div>
@endsection

@section('scripts')


    @include('partials.toast')

@endsection

