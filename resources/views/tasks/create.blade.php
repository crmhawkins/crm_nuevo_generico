@extends('layouts.app')

@section('titulo', 'Crear Tarea')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
@endsection

@section('content')
<div class="page-heading card" style="box-shadow: none !important">
    <div class="page-title card-body">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Crear Tarea</h3>
                <p class="text-subtitle text-muted">Formulario para crear una tarea</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('tareas.index')}}">Tareas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Creat tarea</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section mt-4">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('tarea.store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="title">Título:</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" >
                                    @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="description">Descripción:</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" >{{ old('description') }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="extra_employee">Asignar Empleado</label>
                                    <button type="button" id="addExtraEmployee" class="btn btn-info btn-sm ml-2"><i class="fas fa-plus"></i></button>
                                    <div id="dynamic_field_employee" class="mt-3">
                                        <table class="table-employees table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>H.Estimadas</th>
                                                    <th>H.Reales</th>
                                                    <th>Estado</th>
                                                    <th>Borrar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($data)
                                                @foreach ($data as $item)
                                                <tr id="rowEmployee{{$item['num']}}" class="dynamic-added">
                                                    <td style="width: 250px !important">
                                                        <select class="choices form-select" name="employeeId{{$item['num']}}" class="form-control">
                                                            <option value="">Empleado</option>
                                                            @foreach($employees as $empleado)
                                                            <option value="{{$empleado->id}}" @if( $item['id'] == $empleado->id ) selected @endif>{{$empleado->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td ><input type="text" class="form-control" name="estimatedTime{{$item['num']}}" value="{{$item['horas_estimadas']}}"></td>
                                                    <td ><input type="text" class="form-control" name="realTime{{$item['num']}}" value="{{$item['horas_reales']}}"></td>
                                                    <td  style="width: 200px !important">
                                                        <select class="choices form-select" name="status{{$item['num']}}" class="form-control">
                                                            <option  value="">-- Seleccione --</option>
                                                            @foreach($status as $s)
                                                            <option value="{{$s->id}}" @if($s->id == $item['status']) selected @endif>{{$s->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td >
                                                        <button type="button" name="remove" id="{{$item['num']}}" class="btn btn-danger btn_remove_mail">X</button>
                                                        <input type="hidden" name="taskId{{$item['num']}}" value="{{$item['task_id']}}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="priority">Prioridad:</label>
                                    <select name="priority" class="form-control">
                                        @foreach($prioritys as $p)
                                        <option value="{{$p->id}}" >{{$p->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="estimatedTime">Tiempo Estimado:</label>
                                    <input type="text" name="estimatedTime" class="form-control" placeholder="00:00:00">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="status">Estado:</label>
                                    <select name="status" class="form-control">
                                        @foreach($status as $s)
                                        <option value="{{$s->id}}">{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="numEmployee" id="numEmployee" value="{{count($data)}}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 mt-lg-0 mt-4">
                <div class="card-body p-3">
                    <div class="card-title">
                        Acciones
                        <hr>
                    </div>
                    <div class="card-body">
                        <button id="guardarTarea" class="btn btn-success btn-block mb-2">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
@include('partials.toast')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
<script>
    $(document).ready(function() {
        var i = {{ count($data) }};


        $('#guardarTarea').click(function(e) {
            e.preventDefault();
            $('form').submit();
        });

        $('#addExtraEmployee').click(function() {

            if (i == 0 || $("#estimatedTime" + i).val() != '') {
                i++;
                $('.table-employees tbody').append(`
                    <tr id="rowEmployee${i}" class="dynamic-added">
                        <td style="width: 250px !important">
                            <select class="choices form-select" name="employeeId${i}" class="form-control">
                                <option value="">Empleado</option>
                                @foreach($employees as $empleado)
                                    <option value="{{$empleado->id}}">{{$empleado->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td ><input type="text" class="form-control" name="estimatedTime${i}" value="00:00:00" placeholder="Horas estimadas"></td>
                        <td ><input type="text" class="form-control" name="realTime${i}" value="00:00:00" placeholder="Horas reales"></td>
                        <td style="width: 200px !important">
                            <select class="choices form-select" name="status${i}" class="form-control">
                                <option value="">-- Seleccione --</option>
                                @foreach($status as $s)
                                <option {{2 == $s->id ? 'selected' : '' }} value="{{$s->id}}">{{$s->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td >
                            <button type="button" name="remove" id="${i}" class="btn btn-danger btn_remove_mail">X</button>
                            <input type="hidden" name="taskId${i}" value="temp">
                        </td>
                    </tr>
                `);
                $('#numEmployee').val(i);
            }
        });

        $(document).on('click', '.btn_remove_mail', function() {
            var button_id = $(this).attr("id");
            $('#rowEmployee' + button_id).remove();
            i--;
            $('#numEmployee').val(i);
        });
    });
</script>
@endsection
