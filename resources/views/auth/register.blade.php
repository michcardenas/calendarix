
    <!-- Auth Card Styles -->
    <link rel="stylesheet" href="{{ asset('css/auth-card.css') }}">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <div class="auth-main-container" id="auth-register">
        <div class="auth-card-content">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="auth-title">Crear Cuenta</h1>
                <p class="auth-subtitle">Comienza tu viaje con nosotros</p>
            </div>

            <div class="auth-form-wrapper">
                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <!-- Nombre -->
                    <div class="auth-input-group">
                        <label for="auth-register-name" class="auth-label">Nombre completo</label>
                        <div class="auth-input-container">
                            <input id="auth-register-name" type="text" name="name" value="{{ old('name') }}" required autofocus class="auth-input" placeholder="Tu nombre completo" />
                            <i class="fas fa-user auth-input-icon"></i>
                        </div>
                        @error('name')
                            <div class="auth-error-msg">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="auth-input-group">
                        <label for="auth-register-email" class="auth-label">Correo electrónico</label>
                        <div class="auth-input-container">
                            <input id="auth-register-email" type="email" name="email" value="{{ old('email') }}" required class="auth-input" placeholder="tu@email.com" />
                            <i class="fas fa-envelope auth-input-icon"></i>
                        </div>
                        @error('email')
                            <div class="auth-error-msg">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="auth-input-group">
                        <label for="auth-register-password" class="auth-label">Contraseña</label>
                        <div class="auth-input-container">
                            <input id="auth-register-password" type="password" name="password" required class="auth-input" placeholder="Mínimo 8 caracteres" />
                            <i class="fas fa-lock auth-input-icon"></i>
                        </div>
                        @error('password')
                            <div class="auth-error-msg">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirmar -->
                    <div class="auth-input-group">
                        <label for="auth-register-password-confirmation" class="auth-label">Confirmar contraseña</label>
                        <div class="auth-input-container">
                            <input id="auth-register-password-confirmation" type="password" name="password_confirmation" required class="auth-input" placeholder="Repite tu contraseña" />
                            <i class="fas fa-shield-alt auth-input-icon"></i>
                        </div>
                    </div>

                    <!-- Términos -->
                    <div class="auth-options-row">
                        <div class="auth-checkbox-group">
                            <input id="auth-register-terms" type="checkbox" name="terms" required class="auth-checkbox">
                            <label for="auth-register-terms" class="auth-checkbox-label">
                                Acepto los <a href="#" class="auth-link">términos y condiciones</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="auth-btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Crear Cuenta
                    </button>

                    <!-- Divider -->
                   <!-- Google Register -->
                    <div class="auth-google-section">
                        <div class="auth-divider"><span>o regístrate con</span></div>
                        <a href="{{ route('google.login') }}" class="auth-btn-google" aria-label="Registrarse con Google">
                            <svg class="auth-google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Google
                        </a>
                    </div>
                </form>

                <div class="auth-bottom-link">
                    <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Auth Card JavaScript -->
    <script src="{{ asset('js/auth-card.js') }}"></script>
