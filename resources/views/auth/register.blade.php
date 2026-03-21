<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta — Calendarix</title>
    <link rel="icon" href="{{ asset('images/morado.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'IBM Plex Sans', system-ui, sans-serif; }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        /* ===== LEFT ===== */
        .login-left {
            width: 50%;
            background: linear-gradient(135deg, #5a31d7, #32ccbc);
            color: #fff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-left h1 {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        .login-left p {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.6;
        }
        .login-features {
            margin-top: 40px;
            list-style: none;
            padding: 0;
        }
        .login-features li {
            margin-bottom: 12px;
            font-size: 15px;
            opacity: 0.95;
        }

        /* ===== RIGHT ===== */
        .login-right {
            width: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .login-box {
            width: 360px;
        }

        /* Brand */
        .login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        .login-brand img {
            height: 32px;
            width: auto;
        }
        .login-brand span {
            font-size: 26px;
            font-weight: 600;
            color: #0f172a;
        }

        /* Inputs */
        .login-input {
            width: 100%;
            padding: 14px;
            margin-bottom: 6px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .login-input:focus {
            border-color: #5a31d7;
            box-shadow: 0 0 0 3px rgba(90,49,215,0.12);
        }

        /* Errors */
        .login-error {
            color: #dc2626;
            font-size: 12px;
            margin-bottom: 8px;
        }
        .login-error-global {
            color: #dc2626;
            font-size: 13px;
            margin-bottom: 12px;
        }

        /* Spacer after input without error */
        .login-input-spacer { margin-bottom: 9px; }

        /* Buttons */
        .login-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s;
            margin-bottom: 10px;
        }
        .login-btn-primary {
            background: #5a31d7;
            color: #fff;
        }
        .login-btn-primary:hover {
            background: #4a22b5;
        }
        .login-btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            background: #f1f1f1;
            color: #0f172a;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s;
            text-decoration: none;
        }
        .login-btn-google:hover {
            background: #e5e5e5;
        }
        .login-btn-google svg {
            width: 20px;
            height: 20px;
        }

        /* Bottom link */
        .login-bottom {
            margin-top: 20px;
            font-size: 14px;
            color: #64748b;
        }
        .login-bottom a {
            color: #5a31d7;
            text-decoration: none;
            font-weight: 600;
        }
        .login-bottom a:hover {
            text-decoration: underline;
        }

        /* Footer */
        .login-footer {
            position: absolute;
            bottom: 20px;
            font-size: 12px;
            color: #aaa;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .login-container { flex-direction: column; }
            .login-left { display: none; }
            .login-right { width: 100%; min-height: 100vh; }
            .login-box { width: 90%; max-width: 360px; }
        }
    </style>
</head>
<body>
    <div class="login-container">

        {{-- LEFT --}}
        <div class="login-left">
            <h1>Empieza a recibir reservas hoy</h1>
            <p>Crea tu cuenta gratis y configura tu negocio en minutos. Sin tarjeta de crédito, sin compromisos.</p>
            <ul class="login-features">
                <li>✔ Perfil público para tu negocio</li>
                <li>✔ Agenda online 24/7</li>
                <li>✔ Gestión de servicios y equipo</li>
                <li>✔ Recordatorios automáticos</li>
                <li>✔ Gratis para siempre en el plan básico</li>
            </ul>
        </div>

        {{-- RIGHT --}}
        <div class="login-right">
            <div class="login-box">

                <div class="login-brand">
                    <img src="{{ asset('images/morado.png') }}" alt="Calendarix">
                    <span>Calendarix</span>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <input type="text"
                           name="name"
                           class="login-input"
                           placeholder="Nombre completo"
                           value="{{ old('name') }}"
                           required
                           autofocus>
                    @error('name')
                        <div class="login-error">{{ $message }}</div>
                    @else
                        <div class="login-input-spacer"></div>
                    @enderror

                    <input type="email"
                           name="email"
                           class="login-input"
                           placeholder="Correo electrónico"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <div class="login-error">{{ $message }}</div>
                    @else
                        <div class="login-input-spacer"></div>
                    @enderror

                    <input type="password"
                           name="password"
                           class="login-input"
                           placeholder="Contraseña (mín. 8 caracteres)"
                           required>
                    @error('password')
                        <div class="login-error">{{ $message }}</div>
                    @else
                        <div class="login-input-spacer"></div>
                    @enderror

                    <input type="password"
                           name="password_confirmation"
                           class="login-input"
                           placeholder="Confirmar contraseña"
                           required>
                    <div class="login-input-spacer"></div>

                    <button type="submit" class="login-btn login-btn-primary">
                        Crear mi cuenta gratis
                    </button>

                    <a href="{{ route('google.login') }}" class="login-btn-google">
                        <svg viewBox="0 0 24 24">
                            <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Registrarse con Google
                    </a>
                </form>

                <div class="login-bottom">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
                </div>
            </div>

            <div class="login-footer">
                &copy; {{ date('Y') }} Calendarix &middot; Todos los derechos reservados
            </div>
        </div>

    </div>
</body>
</html>
