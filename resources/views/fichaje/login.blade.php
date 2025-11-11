<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Fichaje - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container img {
            max-height: 80px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }

        .title {
            color: #2c3e50;
            font-weight: 700;
            margin-top: 1rem;
            font-size: 1.8rem;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-top: 0.5rem;
        }

        .method-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 15px;
        }

        .method-btn {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid transparent;
            background: white;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            position: relative;
            overflow: hidden;
        }

        .method-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .method-btn:hover::before {
            left: 100%;
        }

        .method-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .method-btn i {
            font-size: 1.2rem;
            margin-bottom: 5px;
            display: block;
        }

        .form-section {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        .pin-input {
            font-size: 2.5rem;
            text-align: center;
            letter-spacing: 1rem;
            font-weight: 700;
            color: #2c3e50;
            background: white !important;
        }

        .pin-input::placeholder {
            letter-spacing: 1rem;
            color: #adb5bd;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .alert-success {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: white;
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
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .loading-spinner {
            display: none;
        }

        .btn-login.loading .loading-spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 2rem;
                margin: 10px;
            }
            
            .pin-input {
                font-size: 2rem;
                letter-spacing: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Formas flotantes de fondo -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" class="animate__animated animate__fadeInDown">
                <h3 class="title animate__animated animate__fadeInUp">Sistema de Fichaje</h3>
                <p class="subtitle animate__animated animate__fadeInUp animate__delay-1s">Acceso seguro al control de jornada</p>
            </div>

            <!-- Alertas -->
            @if ($errors->any())
                <div class="alert alert-danger animate__animated animate__shakeX">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success animate__animated animate__fadeInDown">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('fichaje.login.post') }}" id="loginForm" onsubmit="return validateForm()">
                @csrf
                
                <!-- Selector de método -->
                <div class="method-selector">
                    <div class="method-btn active" data-method="pin">
                        <i class="fas fa-key"></i>
                        <div>PIN</div>
                    </div>
                    <div class="method-btn" data-method="password">
                        <i class="fas fa-lock"></i>
                        <div>Contraseña</div>
                    </div>
                </div>

                <!-- Campo PIN -->
                <div id="pin-section" class="form-section">
                    <label class="form-label">
                        <i class="fas fa-fingerprint me-2"></i>PIN de Acceso
                    </label>
                    <input type="text" name="identificador" id="pinInput" class="form-control pin-input" 
                           maxlength="4" placeholder="0000" required
                           autocomplete="off" inputmode="numeric">
                    <small class="text-muted">Ingresa tu PIN de 4 dígitos</small>
                </div>

                <!-- Campo Contraseña -->
                <div id="password-section" class="form-section" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user me-2"></i>Usuario o Email
                        </label>
                        <input type="text" name="identificador" class="form-control" 
                               placeholder="Usuario o email" autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-lock me-2"></i>Contraseña
                        </label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Contraseña" autocomplete="current-password">
                    </div>
                </div>

                <input type="hidden" name="metodo" value="pin">

                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt me-2"></i>Acceder al Sistema
                    </span>
                    <span class="loading-spinner">
                        <i class="fas fa-spinner fa-spin me-2"></i>Verificando...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodBtns = document.querySelectorAll('.method-btn');
            const pinSection = document.getElementById('pin-section');
            const passwordSection = document.getElementById('password-section');
            const metodoInput = document.querySelector('input[name="metodo"]');
            const pinInput = document.getElementById('pinInput');
            const loginBtn = document.getElementById('loginBtn');
            const loginForm = document.getElementById('loginForm');

            // Cambiar método de login
            methodBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    methodBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const method = this.dataset.method;
                    metodoInput.value = method;
                    
                    if (method === 'pin') {
                        pinSection.style.display = 'block';
                        passwordSection.style.display = 'none';
                        pinInput.focus();
                    } else {
                        pinSection.style.display = 'none';
                        passwordSection.style.display = 'block';
                    }
                });
            });

            // Validación de PIN en tiempo real
            pinInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, ''); // Solo números
                if (value.length > 4) value = value.substring(0, 4);
                this.value = value;
                
                // Debug: mostrar el valor actual
                console.log('PIN actual:', this.value, 'Longitud:', this.value.length);
            });

            // Enfoque automático en el PIN
            pinInput.focus();

            // Manejo del envío del formulario
            loginForm.addEventListener('submit', function(e) {
                // Asegurar que el token esté actualizado antes de enviar
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const csrfInput = loginForm.querySelector('input[name="_token"]');
                if (csrfInput && csrfToken) {
                    csrfInput.value = csrfToken.getAttribute('content');
                }
                
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;
            });
            
            // Actualizar token periódicamente cada 4 minutos (antes de que expire)
            setInterval(function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                fetch('{{ route("fichaje.csrf-token") }}', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        // Actualizar el meta tag
                        csrfToken.setAttribute('content', data.token);
                        // Actualizar el input del formulario
                        const csrfInput = loginForm.querySelector('input[name="_token"]');
                        if (csrfInput) {
                            csrfInput.value = data.token;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar token CSRF:', error);
                });
            }, 240000); // 4 minutos

            // Efectos de hover en botones
            methodBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateY(-1px)';
                    }
                });
                
                btn.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateY(0)';
                    }
                });
            });
        });

        // Función de validación del formulario
        function validateForm() {
            const metodo = document.querySelector('input[name="metodo"]').value;
            const identificador = document.querySelector('input[name="identificador"]').value;
            
            console.log('Validando formulario - Método:', metodo, 'Identificador:', identificador);
            
            if (metodo === 'pin') {
                if (!/^\d{4}$/.test(identificador)) {
                    alert('El PIN debe tener exactamente 4 dígitos');
                    return false;
                }
            }
            
            return true;
        }
    </script>
</body>
</html>
