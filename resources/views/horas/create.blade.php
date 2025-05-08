@extends('layouts.app')

@section('titulo', 'Editar Jornada')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
@endsection

@section('content')
    <div class="page-heading card" style="box-shadow: none !important" >
        <div class="page-title card-body">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Crear Jornada</h3>
                    <p class="text-subtitle text-muted">Formulario para crear una jornada</p>
                </div>

                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('horas.listado')}}">Jornadas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar jornada</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section mt-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('horas.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col mb-4">
                            <label class="mb-2 text-left uppercase" style="font-weight: bold" for="titulo">Usuario</label>
                            <div class="form-group flex flex-row align-items-center mb-0">
                                <select class="choices w-100 form-select @error('admin_user_id') is-invalid @enderror" name="admin_user_id">
                                    @if ($usuarios->count() > 0)
                                        <option value="{{null}}">--- Seleccione un usuario ---</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" {{ (old('admin_user_id') == $usuario->id) ? 'selected' : '' }}>
                                                {{ $usuario->name.' '.$usuario->surname }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">No existen usuarios todavía</option>
                                    @endif
                                </select>
                            </div>
                            @error('gestor_id')
                                <p class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2 text-left uppercase" style="font-weight: bold" for="start_time">Fecha Inicio</label>
                            <input placeholder="yyyy/mm/dd hh:mm" type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time"  name="start_time">
                            @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2 text-left uppercase" style="font-weight: bold" for="end_time">Fecha Fin</label>
                            <input placeholder="yyyy/mm/dd hh:mm" type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time"  name="end_time">
                            @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        {{-- Boton --}}
                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-success w-100 text-uppercase">
                                Guardar
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
<script>

</script>
@endsection
