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
            <div class="mt-4" style="margin-left:50px; margin-right: 50px;">
                <div class="row">
                    @foreach($users as $usuario)
<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4">
                            <div class="card shadow-sm text-center">
                                <div class="card-body py-2 px-2"> {{-- Menos padding vertical y horizontal --}}
                                    <h4 class="card-title h4">{{ $usuario->name }} {{ $usuario->surname }}</h4>
                                    <p class="text-muted mb-3">{{ $usuario->departamento->name ?? 'Sin departamento' }}</p>
                                    <button class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario{{ $usuario->id }}">
                                        Control de Jornada
                                    </button>
                                </div>
                            </div>
                        </div>
                       <!-- Modal de acciones por usuario -->
                        <div class="modal fade" id="modalUsuario{{ $usuario->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $usuario->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel{{ $usuario->id }}">Acciones de {{ $usuario->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <h5 class="mb-3">Tiempo de jornada</h5>
                                        <div id="timerUsuario{{ $usuario->id }}" class="display-6 mb-4">00:00:00</div>

                                            <button class="btn btn-success m-2" onclick="accionUsuario({{ $usuario->id }}, 'start')" style="display: none">Iniciar Jornada</button>
                                            <button class="btn btn-secondary m-2" onclick="accionUsuario({{ $usuario->id }}, 'pause')" style="display: none">Iniciar Pausa</button>
                                            <button class="btn btn-danger m-2" onclick="accionUsuario({{ $usuario->id }}, 'end')" style="display: none">Finalizar Jornada</button>
                                            <button class="btn btn-dark m-2" onclick="accionUsuario({{ $usuario->id }}, 'endpause')" style="display: none">Finalizar Pausa</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
        document.addEventListener('DOMContentLoaded', function() {
            $("#sidebar").remove();
            $("#main").css("margin-left", "0px");
        });
    </script>
   <script>
    let userTimers = {};

    function iniciarContador(userId, segundosIniciales = 0) {
        if (userTimers[userId]?.interval) clearInterval(userTimers[userId].interval);

        userTimers[userId] = { tiempo: segundosIniciales };

        userTimers[userId].interval = setInterval(() => {
            userTimers[userId].tiempo++;
            actualizarContador(userId);
        }, 1000);

        actualizarContador(userId);
    }

    function pararContador(userId) {
        if (userTimers[userId]?.interval) clearInterval(userTimers[userId].interval);
    }

    function actualizarContador(userId, segundosForzados = null) {
        if (segundosForzados !== null) {
            userTimers[userId] = userTimers[userId] || {};
            userTimers[userId].tiempo = parseInt(segundosForzados);
        }

        const tiempo = userTimers[userId]?.tiempo || 0;
        const horas = String(Math.floor(tiempo / 3600)).padStart(2, '0');
        const minutos = String(Math.floor((tiempo % 3600) / 60)).padStart(2, '0');
        const segundos = String(tiempo % 60).padStart(2, '0');

        const el = document.getElementById('timerUsuario' + userId);
        if (el) el.textContent = `${horas}:${minutos}:${segundos}`;
    }

    function mostrarBotones(userId, jornadaActiva, pausaActiva) {
        const botonStart = document.querySelector(`#modalUsuario${userId} .btn-success`);
        const botonPause = document.querySelector(`#modalUsuario${userId} .btn-secondary`);
        const botonEndPause = document.querySelector(`#modalUsuario${userId} .btn-dark`);
        const botonEnd = document.querySelector(`#modalUsuario${userId} .btn-danger`);

        botonStart.style.display = (!jornadaActiva) ? 'inline-block' : 'none';
        botonPause.style.display = (jornadaActiva && !pausaActiva) ? 'inline-block' : 'none';
        botonEndPause.style.display = (jornadaActiva && pausaActiva) ? 'inline-block' : 'none';
        botonEnd.style.display = (jornadaActiva && !pausaActiva) ? 'inline-block' : 'none';
    }

    // Cuando se abre un modal, consultar el tiempo trabajado y estado
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[id^=modalUsuario]').forEach(modal => {
            modal.addEventListener('show.bs.modal', function () {
                const userId = this.id.replace('modalUsuario', '');
                console.log(userId);
                fetch(`/usuarios/${userId}/tiempo-hoy`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        mostrarBotones(userId, data.jornada_activa, data.pausa_activa);

                        if (data.jornada_activa && !data.pausa_activa) {
                            iniciarContador(userId, data.tiempo);
                        } else {
                            actualizarContador(userId, data.tiempo);
                        }
                    }
                });
            });
        });
    });

    function accionUsuario(userId, accion) {
        fetch(`/usuarios/${userId}/accion/${accion}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetch(`/usuarios/${userId}/tiempo-hoy`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(info => {
                    mostrarBotones(userId, info.jornada_activa, info.pausa_activa);

                    if (accion === 'start' || accion === 'endpause') {
                        iniciarContador(userId, info.tiempo);
                    }

                    if (accion === 'pause' || accion === 'end') {
                        pararContador(userId);
                    }
                });

                Swal.fire('Éxito', data.message, 'success');
            } else {
                Swal.fire('Error', data.message || 'Ocurrió un error', 'error');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        });
    }
</script>

</body>
</html>
