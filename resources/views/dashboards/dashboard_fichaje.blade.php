<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts y estilos -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/1.1.6/choices.min.js" integrity="sha512-7PQ3MLNFhvDn/IQy12+1+jKcc1A/Yx4KuL62Bn6+ztkiitRVW1T/7ikAh675pOs3I+8hyXuRknDpTteeptw4Bw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @yield('css')
        <link rel="stylesheet" href="{{ asset('build/assets/app-d2e38ed8.css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">
        <script src="{{ asset('build/assets/app-bf7e6802.js') }}"></script>
        @laravelViewsStyles
</head>
<body class="" style="overflow-x: hidden">
    <div id="app">
        <div id="loadingOverlay" style="display: block; position: fixed; width: 100%; height: 100%; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255,255,255,0.5); z-index: 50000; cursor: pointer;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div class="spinner-border text-black" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </div>
        <div class="css-96uzu9"></div>


        <main id="main">
            @include('layouts.topBar')
            <div class="contenedor p-4">
                <div class="page-heading card" style="box-shadow: none !important" >
                    <div class="page-title card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 order-md-1 order-last">
                                <h3>Dashboard</h3>
                            </div>

                            <div class="col-12 col-md-8 order-md-2 order-s">
                                <div class="row justify-end">
                                    <button id="endllamadaBtn" class="btn jornada btn-danger mx-2 col-2" onclick="endLlamada()" style="display:none;">Finalizar llamada</button>
                                     <h2 id="timer" class="display-6 font-weight-bold col-3">00:00:00</h2>
                                    <button id="startJornadaBtn" class="btn jornada btn-primary mx-2 col-2" onclick="startJornada()">Inicio Jornada</button>
                                    <button id="startPauseBtn" class="btn jornada btn-secondary mx-2 col-2" onclick="startPause()" style="display:none;">Iniciar Pausa</button>
                                    <button id="endPauseBtn" class="btn jornada btn-dark mx-2 col-2" onclick="endPause()" style="display:none;">Finalizar Pausa</button>
                                    <button id="endJornadaBtn" class="btn jornada btn-danger mx-2 col-2" onclick="endJornada()" style="display:none;">Fin de Jornada</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    @include('partials.toast')

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @laravelViewsScripts
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var loader = document.getElementById('loadingOverlay');
            if (loader) {
                    loader.style.display = 'none';
            }
        });
    </script>
    <script>
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
            // Obtener el tiempo actualizado
            getTime();

            let now = new Date();
            let currentHour = now.getHours();
            let currentMinute = now.getMinutes();

            // Convertir los segundos trabajados a horas
            let workedHours = timerTime / 3600;

            // Verificar si es antes de las 18:00 o si ha trabajado menos de 8 horas
            if (currentHour < 18 || workedHours < 8) {
                let title = '';
                let text = '';

                if (currentHour < 18) {
                    title = 'Horario de Salida Prematuro';
                    text = 'Es menos de las 18:00.  ';
                }else{
                    if(workedHours < 8) {
                    title = ('Jornada Incompleta');
                    text = 'Has trabajado menos de 8 horas. Si no compensas el tiempo faltante,';
                    }
                }

                text += 'Se te descontará de tus vacaciones al final del mes.';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Finalizar Jornada',
                    cancelButtonText: 'Continuar Jornada'
                }).then((result) => {
                    if (result.isConfirmed) {
                        finalizarJornada();
                    }
                    // Si elige continuar, no hacemos nada, simplemente mantiene la jornada activa
                });
            } else {
                // Si el tiempo es mayor o igual a 8 horas y es después de las 18:00, finalizamos directamente la jornada
                finalizarJornada();
            }
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

    </script>
</body>
</html>
