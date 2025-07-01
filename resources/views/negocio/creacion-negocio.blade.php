<form action="{{ route('negocio.store') }}" method="POST" id="neg_form_creacion" enctype="multipart/form-data">

<link rel="stylesheet" href="{{ asset('css/negocios/creacion-negocios.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 10; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="container">
    <div class="form-wrapper">
        <form action="{{ route('negocio.store') }}" method="POST" id="neg_form_creacion" enctype="multipart/form-data" class="elegant-form">
            @csrf
            
            <div class="form-header">
                <h2>Revisar y confirmar</h2>
                <p>Importamos tus datos de Google, pero cualquier cambio que hagas se limitará a tu perfil de Calendarix.</p>
            </div>

            <!-- Sección de imagen de perfil -->
            <div class="profile-section">
                <div class="image-container">
                    <img src="{{ asset('images/default-user.png') }}" id="neg_preview_img" alt="Foto de perfil" class="profile-image">
                    <div class="image-overlay">
                        <i class="camera-icon">📷</i>
                    </div>
                </div>
                <input type="file" name="neg_imagen" id="neg_imagen" accept="image/*" class="file-input">
                <label for="neg_imagen" class="file-label">Cambiar foto</label>
            </div>

            <!-- Campos del formulario -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="neg_nombre" class="form-label">Nombre</label>
                    <input type="text" name="neg_nombre" id="neg_nombre" required class="form-input" value="{{ old('neg_nombre', explode(' ', $user->name)[0] ?? '') }}"
>
                    @error('neg_nombre')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="neg_apellido" class="form-label">Apellido</label>
                    <input type="text" name="neg_apellido" id="neg_apellido" required class="form-input" value="{{ old('neg_apellido', explode(' ', $user->name)[1] ?? '') }}">
                    @error('neg_apellido')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="neg_email" class="form-label">Email</label>
                    <input type="email" name="neg_email" id="neg_email" required class="form-input" value="{{ old('neg_email', $user->email ?? '') }}">
                    @error('neg_email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="neg_telefono" class="form-label">Número de teléfono móvil</label>
                    <input type="text" name="neg_telefono" id="neg_telefono" required class="form-input" value="{{ old('neg_telefono', $user->phone ?? '') }}">
                    @error('neg_telefono')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="neg_pais" class="form-label">País</label>
                    <input type="text" name="neg_pais" id="neg_pais" value="{{ old('neg_pais', 'Colombia') }}" required class="form-input">
                    @error('neg_pais')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Checkbox de términos -->
            <div class="checkbox-container">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="neg_acepto" id="neg_acepto" required {{ old('neg_acepto') ? 'checked' : '' }}>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-text">Estoy de acuerdo con la política de privacidad y condiciones.</span>
                </label>
                @error('neg_acepto')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botón de envío -->
            <div class="button-container">
                <button type="submit" class="submit-btn">
                    <span>Unirse a Calendarix</span>
                    <div class="btn-shimmer"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/negocios/creacion-negocios.js') }}"></script>
