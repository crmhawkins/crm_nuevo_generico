<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Fichaje - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 400px;
            width: 100%;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-section h1 {
            color: #333;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .logo-section p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .method-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .method-btn {
            flex: 1;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .method-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .method-btn:hover {
            transform: translateY(-2px);
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 15px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <h1>LOGO</h1>
            <h2>Sistema de Fichaje</h2>
            <p>Acceso seguro al control de jornada</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('fichaje.login.post') }}">
            @csrf
            
            <!-- Selector de método -->
            <div class="method-selector">
                <div class="method-btn active" onclick="selectMethod('pin')">
                    <i class="fas fa-key"></i>
                    <div>PIN</div>
                </div>
                <div class="method-btn" onclick="selectMethod('password')">
                    <i class="fas fa-lock"></i>
                    <div>Contraseña</div>
                </div>
            </div>

            <!-- Campo PIN -->
            <div id="pin-section" class="form-section">
                <label class="form-label">
                    <i class="fas fa-fingerprint me-2"></i>PIN de Acceso
                </label>
                <input type="text" name="pin_code" id="pinInput" class="form-control" 
                       maxlength="4" placeholder="0000" required
                       autocomplete="new-password" inputmode="numeric" value=""
                       style="background-color: white !important;">
                <small class="text-muted">Ingresa tu PIN de 4 dígitos</small>
            </div>

            <!-- Campo Contraseña -->
            <div id="password-section" class="form-section" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-user me-2"></i>Usuario o Email
                    </label>
                    <input type="text" name="identificador" class="form-control" 
                           placeholder="usuario@ejemplo.com" autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>Contraseña
                    </label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Contraseña" autocomplete="current-password">
                </div>
            </div>

            <input type="hidden" name="metodo" value="pin" id="metodoInput">

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Acceder al Sistema
            </button>
        </form>
    </div>

    <script>
        function selectMethod(method) {
            // Actualizar botones
            document.querySelectorAll('.method-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.method-btn').classList.add('active');
            
            // Actualizar campo oculto
            document.getElementById('metodoInput').value = method;
            
            // Mostrar/ocultar secciones
            const pinSection = document.getElementById('pin-section');
            const passwordSection = document.getElementById('password-section');
            
            if (method === 'pin') {
                pinSection.style.display = 'block';
                passwordSection.style.display = 'none';
                document.getElementById('pinInput').value = '';
                document.getElementById('pinInput').focus();
            } else {
                pinSection.style.display = 'none';
                passwordSection.style.display = 'block';
            }
        }
        
        // Limpiar el campo PIN al cargar la página
        const pinInput = document.getElementById('pinInput');
        pinInput.value = '';
        
        // Limpiar el campo cada vez que se enfoque
        pinInput.addEventListener('focus', function() {
            this.value = '';
        });
        
        // Limpiar el campo cada vez que se haga clic
        pinInput.addEventListener('click', function() {
            this.value = '';
        });
        
        // Limpiar el campo cada vez que se escriba
        pinInput.addEventListener('input', function() {
            if (this.value.includes('adminHawkins') || this.value.includes('admin')) {
                this.value = '';
            }
        });
        
        // Enfoque automático en el PIN
        pinInput.focus();
    </script>
</body>
</html>
