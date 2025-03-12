@extends('layouts.app')

@section('titulo', 'Crear Maquina')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
@endsection

@section('content')
 <div class="page-heading card" style="box-shadow: none !important" >
    <div class="page-title card-body">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Crear Maquina</h3>
                <p class="text-subtitle text-muted">Formulario para registrar un maquina</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('maquinas.index')}}">Maquinas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear maquina</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section mt-4">
        <div class="card">
            <div class="card-body">
                <form action="{{route('maquinas.store')}}" method="POST">
                    @csrf
                    <div class="bloque-formulario">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nombre">Nombre:</label>
                                    <input placeholder="Nombre del maquina" type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" value="{{ old('nombre') }}" name="nombre">
                                    @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="direccion">Nº Serie:</label>
                                    <input placeholder="Nº de serie del maquina" type="text" class="form-control @error('n_serie') is-invalid @enderror" id="n_serie" value="{{ old('n_serie') }}" name="n_serie">
                                    @error('n_serie')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="direccion">Categoria:</label>
                                    <select class="choices w-100 form-control @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id">
                                        <option value="">Seleccione una categoria</option>
                                        @foreach ( $categorias as $categoria )
                                            <option {{ old('categoria_id') == $categoria->id ? 'selected' : '' }} value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="direccion">Almacen:</label>
                                    <select class="choices w-100 form-control @error('almacen_id') is-invalid @enderror" id="almacen_id" name="almacen_id">
                                        <option value="">Seleccione un almacen</option>
                                        @foreach ( $almacenes as $almacen )
                                            <option {{ old('almacen_id') == $almacen->id ? 'selected' : '' }} value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('almacen_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="direccion">Salon:</label>
                                    <select class="choices w-100 form-control @error('salon_id') is-invalid @enderror" id="salon_id" name="salon_id">
                                        <option value="">Seleccione un salon</option>
                                        @foreach ( $salones as $salon )
                                            <option {{ old('salon_id') == $salon->id ? 'selected' : '' }} value="{{ $salon->id }}">{{ $salon->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('salon_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="direccion">Local:</label>
                                    <select class="choices w-100 form-control @error('local_id') is-invalid @enderror" id="local_id" name="local_id">
                                        <option value="">Seleccione un local</option>
                                        @foreach ( $locales as $local )
                                            <option {{ old('local_id') == $local->id ? 'selected' : '' }} value="{{ $local->id }}">{{ $local->local }}</option>
                                        @endforeach
                                    </select>
                                    @error('local_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <button type="submit" class="btn btn-success w-100 text-uppercase">
                            {{ __('Registrar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection

@section('scripts')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>

@endsection
