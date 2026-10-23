<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingeniería Mecánica Rubio</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="{{ asset('css/bootstrap_custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" />
    <style>
        html, body {
            height: 100%;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            font-family: 'Inter', sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-brand-logo {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .login-brand-logo svg {
            width: 28px;
            height: 28px;
            color: #fff;
        }

        .login-brand h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .login-brand p {
            font-size: 13px;
            color: #94a3b8;
            margin: 0;
        }

        .login-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.25rem 0;
            color: #cbd5e1;
            font-size: 12px;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.35rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 14px;
            transition: opacity 0.2s;
            width: 100%;
        }

        .btn-login:hover {
            opacity: 0.9;
            color: #fff;
        }

        .btn-google {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.55rem 1rem;
            width: 100%;
            transition: background 0.15s;
        }

        .btn-google:hover {
            background: #f8fafc;
        }

        .register-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.25rem;
        }

        .register-section p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
<div class="login-wrapper">
    <div class="login-card">

        <div class="login-brand">
            <div class="login-brand-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
            </div>
            <h1>Ingeniería Mecánica Rubio</h1>
            <p>Inicia sesión para continuar</p>
        </div>

        <div class="register-section">
            <p>¿Aún no tienes cuenta? Crea una para visualizar tus servicios.</p>
            <a class="btn btn-sm btn-outline-secondary w-100" href="{{ route('user.register') }}">
                Crear cuenta
            </a>
        </div>

        <a href="{{ route('google-redirect') }}" class="btn btn-google mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="margin-right:8px;margin-top:-2px;">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuar con Google
        </a>

        <div class="login-divider">o ingresa con tu correo</div>

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="tu@correo.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
            </div>

            <div class="text-center mb-3" style="min-height: 24px;">
                <img src="{{ asset('images/image.gif') }}" width="20px" height="20px" style="display:none;" id="loader">
            </div>

            <button type="submit" class="btn btn-login" id="submit">
                Iniciar sesión
            </button>
        </form>

        <p class="text-center mt-3 mb-0">
            <a href="" style="font-size:13px; color:#94a3b8;">¿Olvidaste tu contraseña?</a>
        </p>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" id="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $("#submit").on('click', function () {
        $("#loader").show();
    });
</script>
</body>
</html>
