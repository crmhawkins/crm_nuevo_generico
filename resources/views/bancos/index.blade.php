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
                    @if ($bancos->count() >= 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="header-table">
                                    <tr>
                                            <th class="px-3" style="font-size:0.75rem">NOMBRE </th>
                                            <th class="px-3" style="font-size:0.75rem">CUENTA</th>
                                </thead>
                                <tbody>
                                    @foreach ($bancos as $banco)
                                        <tr class="clickable-row" data-href="{{ route('bancos.edit', $banco->id) }}">
                                            <td>{{$banco->name}}</td>
                                            <td>{{$banco->cuenta}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        {{ $bancos->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <h3 class="text-center fs-3">No se encontraron registros de <strong>Bancos</strong></h3>
                        </div>
                    @endif
                </div>
            </div>

        </section>

    </div>
@endsection

@section('scripts')


    @include('partials.toast')

@endsection

