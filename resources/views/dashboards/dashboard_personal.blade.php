@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />

<style>
    /* Estilos básicos */

    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(
            var(--progress-color) calc(var(--percentage, 0) * 1%),
            #e0e0e0 calc(var(--percentage, 0) * 1%)
        );
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .progress-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--progress-color);
        position: absolute;
    }

    .progress-circle::before {
        content: '';
        width: 100px;
        height: 100px;
        background-color: #fff;
        border-radius: 50%;
        position: absolute;
        z-index: 1;
    }

    .progress-circle::after {
        content: attr(data-percentage) '%';
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--progress-color);
        position: absolute;
        z-index: 2;
    }
    span.tarea-gestor {
        display: block;
        font-size: 0.9rem;
        color: gray;
    }

    .input-group-text {
        position: relative;
        background-color: white;
        cursor: pointer;
        width: 40px;
        border: 1px solid #afb0b0;
        box-shadow: 0 0 3px #afb0b0;
    }

    .input-group-text i {
        color: #6c757d;
    }

    .file-icon {
        text-align: center;
        margin-bottom: 5px;
    }

    .file-icon i {
        font-size: 50px;
    }

    #file-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .chat-container {
        max-height: 400px;
        min-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        padding: 8px;
        border-radius: 5px;
    }

    .message {
        margin-bottom: 5px;
        border-radius: 5px;
        display: inline-block;
        max-width: 80%;
    }

    .mine {
        background-color: #dcf8c6;
        text-align: left;
        float: right;
        clear: both;
    }

    .theirs {
        background-color: #f1f0f0;
        text-align: left;
        float: left;
        clear: both;
    }

    @keyframes pulse-animation {
        0% {
            text-shadow: 0 0 0px #ffffff;
            box-shadow: 0 0 0 0px #ff000077;
        }
        100% {
            text-shadow: 0 0 20px #ffffff;
            box-shadow: 0 0 0 20px #ff000000;
        }
    }

    .pulse {
        color: #fff;
        font-size: 12px;
        animation: pulse-animation 2s infinite;
        text-shadow: 0 0 10px #ffffff;
        background-color: #ff0000c9;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        box-shadow: 0 0 1px 1px #ff0000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #to-do {
        max-height: 600px;
        overflow-y: auto;
        border-radius: 10px;
        padding: 15px;
    }

    #to-do-container {
        max-height: 600px;
        min-height: 600px;
        margin-top: 0.75rem;
        margin-bottom: 0.75rem;
        overflow: hidden;
        border: 1px solid black;
        border-radius: 20px;
    }

    .info {
        display: none;
        padding: 15px;
        background-color: #fcfcfc;
        margin-top: 5px;
    }

    .card-header {
        background-color: #f8f9fa;
        color: #333;
        font-size: 1.2rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .side-column {
        margin-bottom: 20px;
    }

    .jornada {
        padding: 10px 0;
        font-size: 1.2rem;
        text-align: center;
        color: white;
        cursor: pointer;
    }

    .view-selector {
        margin-top: 10px;
        text-align: center;
    }

    .todo-item {
        background-color: #f0f0f0;
        border-radius: 5px;
        margin: 5px;
        padding: 10px;
    }

    /* Estilo personalizado para scrollbars */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.7);
    }

    /* Estilos para la responsividad */
    .calendar-view {
        display: grid;
        gap: 10px;
        padding: 10px;
    }

    .calendar-week-view {
        grid-template-rows: repeat(7, 1fr);
    }

    .calendar-month-view {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }

    .calendar-day {
        background-color: #f8f9fa;
        border: 1px solid #e3e6f0;
        padding: 10px;
        min-height: 100px;
        display: flex;
        flex-direction: column;
    }

    .calendar-item {
        background-color: #f0f0f0;
        border-radius: 5px;
        margin: 5px 0;
        padding: 10px;
    }

    .clickable {
        cursor: pointer;
    }

    .tareas_revision, .tareas {
        height: 50% !important;
        overflow-x: hidden;
        overflow-y: auto;
    }

     .tarea{
        border: 1px solid black;
        border-radius: 20px;
    }
    .info {
        display: none;
        padding: 15px;
        background-color: white;
        margin-top: 5px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .scroll{
        max-height: 770px;
        overflow-y: auto;
    }
    .side-column{
        height: 100% !important;
    }
    @media (max-width: 768px) {
        .calendar-month-view {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
        .side-column {
            display: flex;
            flex-direction: column;
        }

        .view-selector button,
        .jornada {
            font-size: 0.9rem;
        }

        .side-column,
        .card-body {
            padding: 5px;
        }
    }
</style>
@endsection

@section('content')
    <div class="page-heading card" style="box-shadow: none !important" >
        <div class="page-title card-body">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 text-center text-md-left">
                    <h2 class="display-6 font-weight-bold">Jornada</h2>
                </div>
                <div class="col-12 col-md-6 order-md-2 text-center text-md-right">
                    <h2 id="timer" class="display-6 font-weight-bold">00:00:00</h2>
                </div>
            </div>

        </div>
        <div class="card mt-4">
            <div class="card-body">
                <div class="text-center " >
                    <h2 class="display-6 font-weight-bold">Control de Jornada</h2>
                </div>
                <div class="row my-3 ">
                    <button id="startJornadaBtn" class="btn jornada btn-primary" onclick="startJornada()">Inicio Jornada</button>
                    <button id="endJornadaBtn" class="btn jornada btn-danger" onclick="endJornada()" style="display:none;">Fin de Jornada</button>
                </div>
                <div class="row my-3">
                    <button id="startPauseBtn" class="btn jornada btn-secondary" onclick="startPause()" style="display:none;">Iniciar Pausa</button>
                    <button id="endPauseBtn" class="btn jornada btn-dark" onclick="endPause()" style="display:none;">Finalizar Pausa</button>
                </div>
            </div>
        </div>
        {{-- <div class="card mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7 mb-3">
                        <div class="side-column d-flex flex-column h-100">
                            <div class="card mb-3 flex-grow-1">
                                <div class="card-body">
                                    <h3 class="card-title h4">Tareas</h3>
                                    <ul class="nav nav-tabs" id="taskTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $tasks['taskPlay'] ? 'active' : '' }}" id="active-task-tab" data-bs-toggle="tab" data-bs-target="#active-task" type="button" role="tab" aria-controls="active-task" aria-selected="{{ $tasks['taskPlay'] ? 'true' : 'false' }}">
                                                Tarea Activa
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ !$tasks['taskPlay'] ? 'active' : '' }}" id="pending-tasks-tab" data-bs-toggle="tab" data-bs-target="#pending-tasks" type="button" role="tab" aria-controls="pending-tasks" aria-selected="{{ !$tasks['taskPlay'] ? 'true' : 'false' }}">
                                                Tareas Pendientes
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="revision-tasks-tab" data-bs-toggle="tab" data-bs-target="#revision-tasks" type="button" role="tab" aria-controls="revision-tasks" aria-selected="false">
                                                Tareas en Revisión
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-3">
                                        <div class="scroll tab-pane p-3 fade {{ $tasks['taskPlay'] ? 'show active' : '' }}" id="active-task" role="tabpanel" aria-labelledby="active-task-tab">
                                            @if ($tasks['taskPlay'])
                                                <div class="card tarea tarea-activa mb-3 p-2">
                                                    <div id="{{ $tasks['taskPlay']->id }}" class="tarea-sing card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="col-2 text-center">
                                                                <span class="tarea-numero">
                                                                    <i class="fas fa-caret-square-right" style="color: black; font-size: 3rem;"></i>
                                                                </span>
                                                            </div>
                                                            <div class="col-10">
                                                                <span class="d-block tarea-cliente status_{{ $tasks['taskPlay']->estado->name }}">
                                                                    Cliente: @if ($tasks['taskPlay']->presupuesto && $tasks['taskPlay']->presupuesto->cliente)
                                                                        {{ $tasks['taskPlay']->presupuesto->cliente->name }}
                                                                    @endif
                                                                </span>
                                                                <span class="d-block tarea-nombre fw-bolder fs-4">{{ $tasks['taskPlay']->title }}</span>
                                                                <span class="d-block tarea-gestor">
                                                                    Gestor: @if ($tasks['taskPlay']->gestor)
                                                                        {{ $tasks['taskPlay']->gestor->name }}
                                                                    @endif | {{ $tasks['taskPlay']->id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="infotask"></div>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <h3>No hay tareas activas</h3>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Tareas Pendientes -->
                                        <div class="scroll tab-pane p-4 fade {{ !$tasks['taskPlay'] ? 'show active' : '' }}" id="pending-tasks" role="tabpanel" aria-labelledby="pending-tasks-tab">
                                            <div class="mb-3">
                                                <select class="js-select2 form-control select-task" style="width: 100%;" data-placeholder="Buscar..." name="cliente" id="selectTask">
                                                    <option value="0">Seleccion o busque tarea...</option>
                                                    @if ($tasks['tasksPause'])
                                                        @foreach ($tasks['tasksPause'] as $taskSingle)
                                                            <option value="{{ $taskSingle->id }}">
                                                                @if ($taskSingle->presupuesto)
                                                                    @if ($taskSingle->presupuesto->cliente)
                                                                        {{ $taskSingle->presupuesto->cliente->name }}
                                                                    @endif
                                                                @endif
                                                                | {{ $taskSingle->title }} |
                                                                @if ($taskSingle->gestor)
                                                                        {{ $taskSingle->gestor->name }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if ($tasks['tasksPause']->isNotEmpty())
                                                @php($numero = 1)
                                                @foreach ($tasks['tasksPause'] as $tarea)
                                                    <div class="card tarea task-item mb-3 p-2" id="task-{{ $tarea->id }}">
                                                        <div id="{{ $tarea->id }}" class="tarea-sing card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="col-2 text-center">
                                                                    <span class="tarea-numero">{{ $numero }}ª</span>
                                                                </div>
                                                                <div class="col-10">
                                                                    <span class="d-block tarea-cliente status_{{ $tarea->priority_id }}">
                                                                        Cliente: @if ($tarea->presupuesto && $tarea->presupuesto->cliente)
                                                                            {{ $tarea->presupuesto->cliente->name }}
                                                                        @endif
                                                                    </span>
                                                                    <span class="d-block tarea-nombre fw-bolder fs-4">{{ $tarea->title }}</span>
                                                                    <span class="d-block tarea-gestor">
                                                                        Gestor: @if ($tarea->gestor)
                                                                            {{ $tarea->gestor->name }}
                                                                        @endif | {{ $tarea->id }}
                                                                    </span>
                                                                    <span class="tarea-gestor text-success fw-bolder" style="color:green; font-weight:bold;">
                                                                        Fecha estimada entrega: {{ $actualFechaFinal }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="infotask"></div>
                                                    </div>
                                                    @php($numero += 1)
                                                @endforeach
                                            @else
                                                <div class="text-center">
                                                    <h3>No hay tareas en pendientes</h3>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Tareas en Revisión -->
                                        <div class="scroll tab-pane fade" id="revision-tasks" role="tabpanel" aria-labelledby="revision-tasks-tab">
                                            <div class="accordion" id="revisionTasksAccordion">
                                                @if ($tasks['tasksRevision']->isNotEmpty())
                                                    @php($nombre = 1)
                                                    @foreach ($tasks['tasksRevision'] as $tarea)
                                                        <div class="card tarea mb-3 p-2">
                                                            <div id="{{ $tarea->id }}" class="tarea-sing card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="col-2 text-center">
                                                                        <span class="tarea-numero">{{ $nombre }}ª</span>
                                                                    </div>
                                                                    <div class="col-10">
                                                                        <span class="d-block tarea-cliente status_{{ $tarea->estado->name }}">
                                                                            Cliente: @if ($tarea->presupuesto && $tarea->presupuesto->cliente)
                                                                                {{ $tarea->presupuesto->cliente->name }}
                                                                            @endif
                                                                        </span>
                                                                        <span class="d-block tarea-nombre fw-bolder fs-4">{{ $tarea->title }}</span>
                                                                        <span class="d-block tarea-gestor">
                                                                            Gestor: @if ($tarea->gestor)
                                                                                {{ $tarea->gestor->name }}
                                                                            @endif | {{ $tarea->id }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="infotask"></div>
                                                        </div>
                                                        @php($nombre += 1)
                                                    @endforeach
                                                @else
                                                    <div class="text-center">
                                                        <h3>No hay tareas en revisión</h3>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="side-column">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap">
                                        <div class="col-12 d-flex justify-content-center mb-4 align-items-center">
                                            <div class="mx-4 text-center">
                                                <h5 class="my-3">{{$user->name}}&nbsp;{{$user->surname}}</h5>
                                                <p class="text-muted mb-1">{{$user->departamento->name}}</p>
                                                <p class="text-muted mb-4">{{$user->acceso->name}}</p>
                                                <div class="d-flex align-items-center my-2">
                                                    <input type="color" class="form-control form-control-color" style="padding: 0.4rem" id="color">
                                                    <label for="color" class="form-label m-2">Color</label>
                                                </div>
                                            </div>
                                            <div class="mx-4">
                                                @if ($user->image == null)
                                                    <img alt="avatar" class="rounded-circle img-fluid m-auto" style="width: 150px;" src="{{asset('assets/images/guest.webp')}}" />
                                                @else
                                                    <img alt="avatar" class="rounded-circle img-fluid m-auto" style="width: 150px;" src="{{ asset('/storage/avatars/'.$user->image) }}" />
                                                @endif
                                            </div>
                                            <div>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="mx-4 text-center">
                                                        <h1 class="fs-5">Productividad</h1>
                                                        <div class="progress-circle" data-percentage="{{ number_format($productividadIndividual, 2) }}"></div>
                                                    </div>
                                                    <div class="mx-4 text-center">
                                                        <div class="card" style="border: 1px solid {{ $user->bono > 0 ? 'green' : 'gray' }}; padding: 10px;">
                                                            <h5 class="m-0" style="color: {{ $user->bono > 0 ? 'green' : 'gray' }};">
                                                                {{ $user->bono > 0 ? 'Bono: ' . $user->bono.' €' : 'Sin bono' }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mx-4 my-2">
                                                    <p style="color:#4D989E">Horas Producidas Hoy</p>
                                                    <p style="font-weight: bold;font-size: 2rem;">{{ $tiempoProducidoHoy['horas'] }}</p>
                                                    <p style="color:#4D989E">Horas Producidas Mensual</p>
                                                    <p style="font-weight: bold;font-size: 2rem;">{{ $horasMes }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex flex-wrap justify-content-center">
                                        <div class="my-2 text-center">
                                            <a class="btn btn-outline-secondary" href="{{route('contratos.index_user', $user->id)}}">Contrato</a>
                                            <a class="btn btn-outline-secondary" href="{{route('nominas.index_user', $user->id)}}">Nomina</a>
                                            <a class="btn btn-outline-secondary" href="{{route('holiday.index')}}">Vacaciones</a>
                                            <a class="btn btn-outline-secondary" href="{{route('passwords.index')}}">Contraseñas</a>
                                        </div>
                                        <div class="my-2 ml-4 text-center col-auto" role="tablist">
                                            <a class="btn btn-outline-secondary active" id="list-todo-list" data-bs-toggle="list" href="#list-todo" role="tab">TO-DO</a>
                                            <a class="btn btn-outline-danger" id="list-todo-list-finalizados" data-bs-toggle="list" href="#list-todo-finalizados" role="tab">Finalizados</a>
                                            <a class="btn btn-outline-secondary" id="list-agenda-list" data-bs-toggle="list" href="#list-agenda" role="tab">Agenda</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@section('scripts')
    @include('partials.toast')
    <script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales-all.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var multipleCancelButton = new Choices('#admin_user_ids', {
                removeItemButton: true, // Permite a los usuarios eliminar una selección
                searchEnabled: true,  // Habilita la búsqueda dentro del selector
                paste: false          // Deshabilita la capacidad de pegar texto en el campo
            });
        });
    </script> --}}
    <script>
        let timerState = '{{ $jornadaActiva ? "running" : "stopped" }}'
        let timerTime = {{ $timeWorkedToday }}; // In seconds, initialized with the time worked today
        function getTime() {
            fetch('/dashboard/timeworked', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        timerTime = data.time
                        updateTime()
                    }
                });
        }


        function updateTime() {
            let hours = Math.floor(timerTime / 3600);
            let minutes = Math.floor((timerTime % 3600) / 60);
            let seconds = timerTime % 60;

            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            document.getElementById('timer').textContent = `${hours}:${minutes}:${seconds}`;
        }

        function startTimer() {
                timerState = 'running';
                timerInterval = setInterval(() => {
                    timerTime++;
                    updateTime();
                }, 1000);
        }

        function stopTimer() {
                clearInterval(timerInterval);
                timerState = 'stopped';
        }

        function startJornada() {
            fetch('/start-jornada', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        startTimer();
                        document.getElementById('startJornadaBtn').style.display = 'none';
                        document.getElementById('startPauseBtn').style.display = 'block';
                        document.getElementById('endJornadaBtn').style.display = 'block';
                    }
                });
        }

        function endJornada() {
        finalizarJornada();
        }

        function finalizarJornada() {
            fetch('/end-jornada', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    stopTimer();
                    document.getElementById('startJornadaBtn').style.display = 'block';
                    document.getElementById('startPauseBtn').style.display = 'none';
                    document.getElementById('endJornadaBtn').style.display = 'none';
                    document.getElementById('endPauseBtn').style.display = 'none';
                }
            });
        }

        function startPause() {
            fetch('/start-pause', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        stopTimer();
                        document.getElementById('startPauseBtn').style.display = 'none';
                        document.getElementById('endPauseBtn').style.display = 'block';
                    }
                });
        }

        function endPause() {
            fetch('/end-pause', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        startTimer();
                        document.getElementById('startPauseBtn').style.display = 'block';
                        document.getElementById('endPauseBtn').style.display = 'none';
                    }
                });
        }

        function endLlamada() {
            fetch('/dashboard/llamadafin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('endllamadaBtn').style.display = 'none';
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.mensaje, // Aquí se muestra el mensaje del JSON
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateTime(); // Initialize the timer display

            setInterval(function() {
                getTime();
            }, 120000);

            // Initialize button states based on jornada and pause
            if ('{{ $jornadaActiva }}') {
                document.getElementById('startJornadaBtn').style.display = 'none';
                document.getElementById('endJornadaBtn').style.display = 'block';
                if ('{{ $pausaActiva }}') {
                    document.getElementById('startPauseBtn').style.display = 'none';
                    document.getElementById('endPauseBtn').style.display = 'block';
                } else {
                    document.getElementById('startPauseBtn').style.display = 'block';
                    document.getElementById('endPauseBtn').style.display = 'none';
                    startTimer(); // Start timer if not in pause
                }
            } else {
                document.getElementById('startJornadaBtn').style.display = 'block';
                document.getElementById('endJornadaBtn').style.display = 'none';
                document.getElementById('startPauseBtn').style.display = 'none';
                document.getElementById('endPauseBtn').style.display = 'none';
            }


            });
    </script>
    {{-- <script>
            document.querySelectorAll('#enviar').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.closest('form').submit();
                });
            });

            $('#todoboton').click(function(e){
                e.preventDefault(); // Esto previene que el enlace navegue a otra página.
                $('#todoform').submit(); // Esto envía el formulario.
            });

            var events = @json($events);
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var tooltip = document.getElementById('tooltip');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'listWeek',
                    locale: 'es',
                    navLinks: true,
                    nowIndicator: true,
                    businessHours: [
                        { daysOfWeek: [1], startTime: '08:00', endTime: '15:00' },
                        { daysOfWeek: [2], startTime: '08:00', endTime: '15:00' },
                        { daysOfWeek: [3], startTime: '08:00', endTime: '15:00' },
                        { daysOfWeek: [4], startTime: '08:00', endTime: '15:00' },
                        { daysOfWeek: [5], startTime: '08:00', endTime: '15:00' }
                    ],
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridDay,listWeek'
                    },
                    events: events,
                    eventClick: function(info) {
                        var event = info.event;
                        var clientId = event.extendedProps.client_id;
                        var budgetId = event.extendedProps.budget_id;
                        var projectId = event.extendedProps.project_id;
                        var clienteName = event.extendedProps.cliente_name || '';
                        var presupuestoRef = event.extendedProps.presupuesto_ref || '';
                        var presupuestoConp = event.extendedProps.presupuesto_conp || '';
                        var proyectoName = event.extendedProps.proyecto_name || '';
                        var descripcion = event.extendedProps.descripcion || '';

                        // Construye las rutas solo si los IDs existen
                        var ruta = clientId ? `{{ route("clientes.show", ":id") }}`.replace(':id', clientId) : '#';
                        var ruta2 = budgetId ? `{{ route("presupuesto.edit", ":id1") }}`.replace(':id1', budgetId) : '#';
                        var ruta3 = projectId ? `{{ route("campania.show", ":id2") }}`.replace(':id2', projectId) : '#';

                        // Construye el contenido del tooltip condicionalmente
                        var tooltipContent = '<div style="text-align: left;">' +
                            '<h5>' + event.title + '</h5>';

                        if (clienteName) {
                            tooltipContent += '<a href="' + ruta + '"><p><strong>Cliente:</strong> ' + clienteName + '</p></a>';
                        }

                        if (presupuestoRef || presupuestoConp) {
                            tooltipContent += '<a href="' + ruta2 + '"><p><strong>Presupuesto:</strong> ' +
                                (presupuestoRef ? 'Ref:' + presupuestoRef + '<br>' : '') +
                                (presupuestoConp ? 'Concepto: ' + presupuestoConp : '') +
                                '</p></a>';
                        }

                        if (proyectoName) {
                            tooltipContent += '<a href="' + ruta3 + '"><p><strong>Campaña:</strong> ' + proyectoName + '</p></a>';
                        }

                        if (descripcion) {
                            tooltipContent += '<p>' + descripcion + '</p>';
                        }

                        tooltipContent += '</div>';

                        var tooltip = new bootstrap.Tooltip(info.el, {
                            title: tooltipContent,
                            placement: 'top',
                            trigger: 'manual',
                            html: true,
                            container: 'body',
                            customClass: 'custom-tooltip', // Aplica una clase personalizada para el estilo
                            sanitize: false // Asegúrate de que el contenido HTML se procesa correctamente
                        });

                        // Cambia el color de fondo del tooltip
                        tooltip.show();
                        var tooltipElement = document.querySelector('.tooltip-inner');
                        if (tooltipElement) {
                            tooltipElement.style.backgroundColor = event.extendedProps.color || '#000'; // Usa el color del evento o negro por defecto
                        }

                        function handleClickOutside(event) {
                        if (!info.el.contains(event.target)) {
                            tooltip.dispose();
                            document.removeEventListener('click', handleClickOutside);
                        }
                    }
                    document.addEventListener('click', handleClickOutside);
                },
            });
                calendar.render();
            });

    </script>
    <script>
        function showTodoModal() {
            var todoModal = new bootstrap.Modal(document.getElementById('todoModal'));
            todoModal.show();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const progressCircles = document.querySelectorAll('.progress-circle');

            progressCircles.forEach(circle => {
                const percentage = circle.getAttribute('data-percentage');
                circle.style.setProperty('--percentage', percentage);

                let progressColor;

                if (percentage  >= 100) {
                    progressColor = '#28a745'; //Verde
                } else if (percentage >= 75) {
                    progressColor = '#ff9f00'; // Naranja
                } else {
                    progressColor = '#dc3545'; //Rojo
                }

                circle.style.setProperty('--progress-color', progressColor);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.clickable').forEach(function(element) {
                element.addEventListener('click', function(event) {
                    event.stopPropagation();


                    var info = this.nextElementSibling;
                    var isVisible = info.style.display === 'block';

                    if (!isVisible) {
                        document.querySelectorAll('.info').forEach(function(infoElement) {
                            infoElement.style.display = 'none';
                        });
                        info.style.display = 'block';
                        markMessagesAsRead(this.getAttribute('data-todo-id'));
                    } else {
                        info.style.display = 'none';
                    }
                });
            });

            // Función para marcar mensajes como leídos
            function markMessagesAsRead(todoId) {
                if (!todoId) return;  // Asegúrate de que todoId es válido

                fetch(`mark-as-read/${todoId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        let unreadCounter = document.querySelector(`[data-todo-id="${todoId}"] .pulse`);
                        if (unreadCounter) {
                            unreadCounter.textContent = ''; // Limpiar el texto
                            unreadCounter.style.display = 'none'; // Ocultar el elemento
                        }
                        console.log('Mensajes marcados como leídos.');
                        // Opcional: actualizar la interfaz de usuario aquí si es necesario, como remover notificaciones visuales de mensajes no leídos
                    }
                })
                .catch(error => console.error('Error al marcar mensajes como leídos:', error));
            }
        });
    </script>
    <script>
            document.querySelectorAll('#file-input').forEach(function(inputElement) {
                inputElement.addEventListener('change', function() {
                    console.log('File input changed'); // Verifica que el evento se activa
                    const fileIcon = this.closest('.input-group-text').querySelector('#file-icon');
                    const fileClip = this.closest('.input-group-text').querySelector('#file-clip');

                    if (this.files.length > 0) {
                        console.log('File selected'); // Verifica que se ha seleccionado un archivo
                        fileIcon.style.display = 'inline-block';
                        fileClip.style.display = 'none';
                    } else {
                        console.log('No file selected'); // Verifica que no hay archivo seleccionado
                        fileIcon.style.display = 'none';
                        fileClip.style.display = 'inline-block';
                    }
                });
            });

        function completeTask(event, todoId) {
            event.stopPropagation();  // Detiene la propagación del evento
            const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                    });
            fetch(`/todos/complete/${todoId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.getElementById(`todo-card-body-${todoId}`);
                    if (card) {
                        card.style.backgroundColor = '#CDFEA4'; // Color verde claro
                    }

                    // Encuentra y oculta el botón de completar
                    const completeButton = document.getElementById(`complete-button-${todoId}`);
                    if (completeButton) {
                        completeButton.style.display = 'none';
                    }
                    Toast.fire({
                        icon: "success",
                        title: "Tarea completada con éxito!"
                    });
                    return;
                }else{

                    Toast.fire({
                        icon: "error",
                        title: "Error el completar la tarea!"
                    });
                    return;
                }
            }).catch(error => console.error('Error:', error));
        }

        function finishTask(event, todoId) {
            event.stopPropagation();  // Detiene la propagación del evento
            const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                    });
            fetch(`/todos/finish/${todoId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {

                    const card = document.getElementById(`todo-card-${todoId}`);
                    if (card) {
                        card.style.display = 'none'; // Color verde claro
                    }
                    Toast.fire({
                        icon: "success",
                        title: "Tarea finalizada con éxito!"
                    });
                }else{
                    Toast.fire({
                        icon: "error",
                        title: "Error el finalizar la tarea!"
                    });
                }
            }).catch(error => console.error('Error:', error));
        }

        function updateUnreadMessagesCount(todoId) {
            fetch(`/todos/unread-messages-count/${todoId}`,{
                method: 'POST', // Cambiamos a POST
                headers: {
                    'Content-Type': 'application/json' // Indicamos que enviamos JSON
                },
                body: JSON.stringify({}) // Enviamos un cuerpo vacío o puedes agregar datos si es necesario

                })
                .then(response => response.json())
                .then(data => {
                    const pulseDiv = document.querySelector(`#todo-card-${todoId} .pulse`);

                    if (data.unreadCount > 0) {
                        pulseDiv.style.display = 'flex';
                        pulseDiv.textContent = data.unreadCount;
                    } else {
                        pulseDiv.style.display = 'none';
                        pulseDiv.textContent = '';
                    }
                });
        }

        function loadMessages(todoId) {
            $.ajax({
                url: `/todos/getMessages/${todoId}`,
                type: 'POST',
                success: function(data) {
                    let messagesContainer = $(`#todo-card-${todoId} .chat-container`);
                    messagesContainer.html(''); // Limpiamos el contenedor
                    data.forEach(function(message) {
                        let fileIcon = '';
                        if (message.archivo) {
                            fileIcon = `
                                <div class="file-icon">
                                    <a href="/storage/${message.archivo}" target="_blank">
                                        <i class="fa-regular fa-file-lines fa-2x"></i>
                                    </a>
                                </div>
                            `;
                        }
                        const messageClass = message.admin_user_id == {{ auth()->id() }} ? 'mine' : 'theirs';

                        messagesContainer.append(`
                            <div class="p-3 message ${messageClass}">
                                ${fileIcon}
                                <strong>${message.user.name}:</strong> ${message.mensaje}
                            </div>
                        `);
                    });
                }
            });
        }

        function startPolling() {
            @if (count($to_dos) > 0)
                @foreach ($to_dos as $to_do)
                    setInterval(function() {
                        updateUnreadMessagesCount('{{ $to_do->id }}');
                        loadMessages('{{ $to_do->id }}');
                    }, 5000);  // Polling cada 5 segundos para cada to-do
                @endforeach
            @else
                console.log('No hay to-dos activos.');
            @endif
        }

        $(document).ready(function() {
            startPolling();
        });

    </script>
    <script>
        var enRutaEspecifica = true;

            $(document).on("click", '.tarea-sing', function() {
                        var id = $(this).attr("id");
                        showTaskInfoNew(id);
            });

            function revisarTarea(id) {
                console.log(id);
                $.when(getDataTask(id)).then(function(data, textStatus, jqXHR) {
                    estado = "Revision";
                    $.when(setStatusTask(id, estado)).then(function(data, textStatus, jqXHR) {
                        if (data.estado == "OK") {
                            refreshTasks();
                            Swal.fire(
                                'Éxito',
                                'Tarea en revisión.',
                                'success',
                            ).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                'Error en la tarea.',
                                'error'
                            );
                        }
                    });
                });
            }

            function renaudarTarea(id) {
                console.log(id);
                $.when(getDataTask(id)).then(function(data, textStatus, jqXHR) {

                    estado = "Reanudar";
                    $.when(setStatusTask(id, estado)).then(function(data, textStatus, jqXHR) {
                        if (data.estado == "OK") {
                            refreshTasks();
                            Swal.fire(
                                'Éxito',
                                'Tarea Reanudada',
                                'success',
                            )
                            location.reload();
                            // showTaskInfoNew(id);
                        } else {
                            Swal.fire(
                                'Error',
                                'Error en la tarea.',
                                'error'
                            );
                        }
                    });
                });
            }

            function pausarTarea(id) {
                console.log(id);
                $.when(getDataTask(id)).then(function(data, textStatus, jqXHR) {
                    estado = "Pausada";
                    $.when(setStatusTask(id, estado)).then(function(data, textStatus, jqXHR) {
                        if (data.estado == "OK") {
                            refreshTasks();
                            Swal.fire(
                                'Éxito',
                                'Tarea Pausada',
                                'success',
                            )
                            location.reload();
                            // showTaskInfoNew(id);
                        } else {
                            Swal.fire(
                                'Error',
                                'Error en la tarea.',
                                'error'
                            );
                        }
                    });
                });
            }


            $(document).ready(function() {

                $.when(getTasksRefresh()).then(function(data, textStatus, jqXHR) {
                    if (data.taskPlay != null) {
                        var id = data.taskPlay.id;
                        $('.infotask').hide();

                        $('.tarea-sing').off('click').on('click', function() {
                            var infoContainer = $(this).next('.infotask');
                            if (infoContainer.is(':visible')){
                                infoContainer.slideUp();
                            } else {
                                $('.infotask').slideUp(); // Cierra otros contenedores abiertos
                                infoContainer.slideDown();
                            }
                        });
                    }else{
                        $('.infotask').hide();
                        $('.tarea-sing').off('click').on('click', function() {
                            var infoContainer = $(this).next('.infotask');
                            if (infoContainer.is(':visible')){
                                infoContainer.slideUp();
                            } else {
                                $('.infotask').slideUp(); // Cierra otros contenedores abiertos
                                infoContainer.slideDown();
                            }
                        });
                    }
                });

                // Evento change para el select2
                $('#selectTask').on('change', function() {
                    var selectedTaskId = $(this).val();
                    // Oculta todas las tareas
                    $('.task-item').hide();
                    // Muestra la tarea seleccionada
                    if (selectedTaskId > 0) {
                        $('#task-' + selectedTaskId).show();
                    } else {
                        // Si no hay tarea seleccionada, muestra todas
                        $('.task-item').show();
                    }
                });
                // Inicializa select2
                $('.js-select2').select2();


            })

            function decodeHTML(str) {
                return str.replace(/&#([0-9]+);/g, function(full, int) {
                    return String.fromCharCode(parseInt(int));
                });
            }

            function showTaskInfoNew(id) {
                $.when(getDataTask(id)).then(function(data) {
                    var contenedor = $('.infotask');
                    var descripcionFinal = '';
                    if (data.descripcion) {
                        var des = data.descripcion.split('.');
                        des.forEach(function(ele) {
                            var template = `<span class="descripcionLineas">${ele}</span>`;
                            descripcionFinal += template;
                        });
                    }

                    function colorClass(dataEstimada, dataReal) {
                        var tiempoEstimado = dataEstimada.split(":");
                        var tiempoReal = dataReal.split(":");
                        var diferencia = tiempoReal[0] - tiempoEstimado[0];
                        if (diferencia < 0) {
                            return 'bg-success';
                        } else if (diferencia === 0 || diferencia === 2) {
                            return 'bg-warning text-dark';
                        } else {
                            return 'bg-danger';
                        }
                    }

                    var activarDisabled = 'disabled';
                    var pausarDisabled = 'disabled';
                    var revisarDisabled = 'disabled';
                    // Control de botones según el estado de la tarea
                    switch (data.estado) {
                            case 'Reanudada':
                                pausarDisabled = ''; // Activar solo el botón de pausar
                                break;
                            case 'Pausada':
                                activarDisabled = '';
                                revisarDisabled = ''; // Activar los botones de activar y revisar
                                break;
                            case 'Revisión':
                                activarDisabled = ''; // Activar solo el botón de activar
                                break;
                        }
                        contenedor.html(
                            `<div class="container mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="head-proceso">PROCESO</h3>
                                    <span class="badge bg-secondary mx-2">TIEMPO ESTIMADO: ${data.estimado}</span>
                                    <span class="badge ${colorClass(data.estimado, data.real)} mx-2">LLEVAS: ${data.real}</span>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-sm-12 col-md-5">
                                        <h5 class="tarea-cliente text-primary fs-6">Cliente: ${data.cliente}</h5>
                                        <p class="tarea-nombre fw-bolder fs-3 mt-2">${data.titulo}</p>
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <h6 class="text-secondary">Gestor: ${data.gestor}</h6>
                                    </div>
                                    <div class="col-sm-12 col-md-5 text-right">
                                        <button id="btnActivar" type="button" class="btn btn-success btn-sm mx-1" ${activarDisabled} onclick="renaudarTarea(${data.id})">Activar</button>
                                        <button id="btnPausar" type="button" class="btn btn-warning btn-sm mx-1" ${pausarDisabled} onclick="pausarTarea(${data.id})">Pausar</button>
                                        <button id="btnRevisar" type="button" class="btn btn-info btn-sm mx-1" ${revisarDisabled} onclick="revisarTarea(${data.id})">Revisar</button>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <p class="text-muted">${descripcionFinal}</p>
                                    </div>
                                </div>
                                <div id="notas" class="mt-4">
                                    <!-- Notas dinámicas se insertarán aquí -->
                                </div>

                            </div>`
                        );

                    // Evento para manejar el despliegue de la información
                    $('.tarea-sing').off('click').on('click', function() {
                        var infoContainer = $(this).next('.infotask');
                        if (infoContainer.is(':visible')){
                            infoContainer.slideUp();
                        } else {
                            $('.infotask').slideUp(); // Cierra otros contenedores abiertos
                            infoContainer.slideDown();
                        }
                    });
                });
            }


            function refreshTasks() {
                $.when(getTasksRefresh()).then(function(data, textStatus, jqXHR) {
                    var datos = "";
                    $(".TareaActivada").empty();
                    $(".TareasPausadas").empty();
                    $(".TareasRevision").empty();


                    if (data.taskPlay != null) {
                        datos += "<li id=" + data.taskPlay.id + " class='tarea'>";
                        datos += "<p>" + data.taskPlay.title + "</p>";
                        datos += "</li>";
                        $(".TareaActivada").append(datos);
                    }

                    if (data.tasksPause != null) {
                        datos = "";
                        $.each(data.tasksPause, function(key, value) {
                            datos += "<li id=" + value.id + " class='tarea'>";
                            datos += "<p>" + value.title + "</p>";
                            datos += "</li>";
                        });
                        $(".TareasPausadas").append(datos);
                    }

                    if (data.tasksRevision != null) {
                        datos = "";
                        $.each(data.tasksRevision, function(key, value) {
                            datos += "<li id=" + value.id + " class='tarea'>";
                            datos += "<p>" + value.title + "</p>";
                            datos += "</li>";
                        });
                        $(".TareasRevision").append(datos);
                    }

                });
            }

            function getTasksRefresh() {
                return $.ajax({
                    type: "POST",
                    url: '/dashboard/getTasksRefresh',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: "json"
                });
            }

            function getDataTask(id) {
                return $.ajax({
                    type: "POST",
                    url: '/dashboard/getDataTask',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        'id': id
                    },
                    dataType: "json"
                });
            }

            function setStatusTask(id, estado) {
                return $.ajax({
                    type: "POST",
                    url: '/dashboard/setStatusTask',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        'id': id,
                        'estado': estado
                    },
                    dataType: "json"
                });
            }
    </script> --}}
@endsection
