<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Fichaje - {{ config('app.name', 'Laravel') }}</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/1.1.6/choices.min.js" integrity="sha512-7PQ3MLNFhvDn/IQy12+1+jKcc1A/Yx4KuL62Bn6+ztkiitRVW1T/7ikAh675pOs3I+8hyXuRknDpTteeptw4Bw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('build/assets/app-d2e38ed8.css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="{{ asset('build/assets/app-bf7e6802.js') }}"></script>
    @laravelViewsStyles

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-section img {
            height: 50px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .logo-text {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
            color: white;
        }

        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 100px);
        }

        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .user-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .user-card:hover::before {
            left: 100%;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .user-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .user-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .user-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .user-department {
            color: #7f8c8d;
            font-size: 0.9rem;
            background: #f8f9fa;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            display: inline-block;
        }

        .fichaje-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .fichaje-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .fichaje-btn:active {
            transform: translateY(0);
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .btn-close {
            filter: invert(1);
        }

        .timer-display {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin: 2rem 0;
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 1rem;
            border-radius: 15px;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        .action-btn {
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .btn-start {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: white;
        }

        .btn-pause {
            background: linear-gradient(135deg, #ffd43b, #fab005);
            color: white;
        }

        .btn-end {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .btn-end-pause {
            background: linear-gradient(135deg, #868e96, #6c757d);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 70%;
            right: 5%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 10%;
            left: 15%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .main-content {
                padding: 1rem;
            }

            .users-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .user-card {
                padding: 1.5rem;
            }

            .timer-display {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                min-width: 100%;
            }
        }
    </style>
</head>
<body class="" style="overflow-x: hidden">
    <!-- Formas flotantes de fondo -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div id="app">
        <div id="loadingOverlay" style="display: block; position: fixed; width: 100%; height: 100%; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255,255,255,0.5); z-index: 50000; cursor: pointer;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div class="spinner-border text-black" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="logo-section">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo">
                    <span class="logo-text">Sistema de Fichaje</span>
                </div>
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->surname, 0, 1) }}
                        </div>
                        <div>
                            <div>{{ Auth::user()->name }} {{ Auth::user()->surname }}</div>
                            <small class="text-muted">{{ Auth::user()->departamento->name ?? 'Sin departamento' }}</small>
                        </div>
                    </div>
                    <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </button>
                </div>
            </div>
        </header>

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="users-grid">
                @foreach($users as $usuario)
                    <div class="user-card animate__animated animate__fadeInUp" data-user-id="{{ $usuario->id }}">
                        <div class="user-header">
                            <div class="user-avatar-large">
                                {{ substr($usuario->name, 0, 1) }}{{ substr($usuario->surname, 0, 1) }}
                            </div>
                            <h4 class="user-name">{{ $usuario->name }} {{ $usuario->surname }}</h4>
                            <span class="user-department">{{ $usuario->departamento->name ?? 'Sin departamento' }}</span>
                        </div>
                        <button class="fichaje-btn" data-bs-toggle="modal" data-bs-target="#modalUsuario{{ $usuario->id }}">
                            <i class="fas fa-clock me-2"></i>Control de Jornada
                        </button>
                    </div>

                    <!-- Modal de acciones por usuario -->
                    <div class="modal fade" id="modalUsuario{{ $usuario->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $usuario->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $usuario->id }}">
                                        <i class="fas fa-user me-2"></i>Control de Jornada - {{ $usuario->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="timer-display" id="timerUsuario{{ $usuario->id }}">00:00:00</div>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-start" onclick="accionUsuario({{ $usuario->id }}, 'start')" style="display: none">
                                            <i class="fas fa-play me-2"></i>Iniciar Jornada
                                        </button>
                                        <button class="action-btn btn-pause" onclick="accionUsuario({{ $usuario->id }}, 'pause')" style="display: none">
                                            <i class="fas fa-pause me-2"></i>Iniciar Pausa
                                        </button>
                                        <button class="action-btn btn-end-pause" onclick="accionUsuario({{ $usuario->id }}, 'endpause')" style="display: none">
                                            <i class="fas fa-play me-2"></i>Finalizar Pausa
                                        </button>
                                        <button class="action-btn btn-end" onclick="accionUsuario({{ $usuario->id }}, 'end')" style="display: none">
                                            <i class="fas fa-stop me-2"></i>Finalizar Jornada
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

        // Función para cerrar sesión
        function logout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que quieres cerrar la sesión?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ff6b6b',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("fichaje.logout") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        window.location.href = '{{ route("fichaje.login") }}';
                    }).catch(() => {
                        Swal.fire('Error', 'No se pudo cerrar la sesión', 'error');
                    });
                }
            });
        }
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
