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

        <section class="section mt-4">
            @if ($configuracion)
                <form action="{{ route('configuracion.update', $configuracion->id) }}" method="POST" enctype="multipart/form-data">
            @else
                <form action="{{ route('configuracion.store') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                                    @error('logo')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @if(isset($configuracion->logo))
                                        <div class="mt-3">
                                            <label>Logo Actual:</label>
                                            <div>
                                                <img src="{{ asset($configuracion->logo) }}" alt="Logo de la empresa" class="img-fluid" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Nombre de la Empresa</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $configuracion->company_name ?? '') }}" >
                                    @error('company_name')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nif" class="form-label">NIF</label>
                                    <input type="text" class="form-control @error('nif') is-invalid @enderror" id="nif" name="nif" value="{{ old('nif', $configuracion->nif ?? '') }}" >
                                    @error('nif')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Dirección</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $configuracion->address ?? '') }}" >
                                    @error('address')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Provincia</label>
                                    <input type="text" class="form-control" id="province" name="province" value="{{ $configuracion->province ?? '' }}" >
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="town" name="town" value="{{ $configuracion->town ?? '' }}" >
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Codigo postal</label>
                                    <input type="text" class="form-control" id="postCode" name="postCode" value="{{ $configuracion->postCode ?? '' }}" >
                                </div>

                                <div class="mb-3">
                                    <label for="price_hour" class="form-label">Precio por Hora</label>
                                    <input type="number" step="0.01" class="form-control @error('price_hour') is-invalid @enderror" id="price_hour" name="price_hour" value="{{ old('price_hour', $configuracion->price_hour ?? '') }}" >
                                    @error('price_hour')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bank_account_data" class="form-label">Datos de la Cuenta Bancaria</label>
                                    <input type="text" class="form-control" id="bank_account_data" name="bank_account_data" value="{{ $configuracion->bank_account_data ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $configuracion->telephone ?? '') }}">
                                    @error('telephone')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $configuracion->email ?? '') }}" >
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="certificado" class="form-label">Certificado</label>
                                    <input type="file" class="form-control @error('certificado') is-invalid @enderror" id="certificado" name="certificado">
                                    @error('certificado')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @if(isset($configuracion->certificado))
                                        <p class="mt-2">Descargar Certificado Actual: <a href="{{ asset('storage/' . $configuracion->certificado) }}" class="btn btn-success" target="_blank">Descargar</a></p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="contrasena" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control @error('contrasena') is-invalid @enderror" id="contrasena" name="contrasena" value="{{ old('contrasena', $configuracion->contrasena ?? '') }}">
                                    @error('contrasena')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header">
                                    <h5>Acciones</h5>
                                </div>
                                <div class="card-body d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Guardar Configuración</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@section('scripts')
@include('partials.toast')
@endsection
