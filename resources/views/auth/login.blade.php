<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión — {{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2.5rem 2.5rem 2rem;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo img {
            height: 52px;
            width: auto;
        }

        .login-title {
            font-size: 1.35rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
            text-align: center;
        }

        .login-subtitle {
            font-size: 0.875rem;
            color: #94a3b8;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap svg {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            height: 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            padding: 0 0.875rem 0 2.625rem;
            font-size: 0.9rem;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4f46e5;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #4f46e5;
            cursor: pointer;
        }

        .remember-row label {
            margin: 0;
            font-size: 0.8125rem;
            color: #64748b;
            cursor: pointer;
        }

        .btn-login {
            display: block;
            width: 100%;
            height: 46px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
        }

        .btn-login:hover {
            background: #4338ca;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-login:active {
            transform: scale(0.99);
        }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #dc2626;
            font-size: 0.8125rem;
            margin-bottom: 1.25rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.75rem;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-logo">
                <img src="{{ asset('assets/images/logo/LogoHera.svg') }}" alt="Logo">
            </div>

            <p class="login-title">Bienvenido</p>
            <p class="login-subtitle">Introduce tus credenciales para continuar</p>

            @if ($errors->any())
                <div class="error-msg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Usuario</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <input id="username" type="text" name="username"
                               value="{{ old('username') }}"
                               required autocomplete="username" autofocus
                               placeholder="Tu usuario">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="Tu contraseña">
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordar sesión</label>
                </div>

                <button type="submit" class="btn-login">Iniciar sesión</button>
            </form>

        </div>
        <div class="login-footer">{{ config('app.name') }} &copy; {{ date('Y') }}</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</body>
</html>
