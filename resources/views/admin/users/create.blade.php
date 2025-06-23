{{-- CSS específico del dashboard admin --}}
      
        <link rel="stylesheet" href="{{ asset('css/admin-ucreate.css') }}">

<div class="create-form-container">
    <div class="create-form-card">
        <h2 class="create-form-title">
            <svg class="title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            Crear Nuevo Usuario
        </h2>
        
        <form action="{{ route('admin.users.store') }}" method="POST" id="create-user-form">
            @csrf
            
            <!-- Campo Nombre -->
            <div class="create-form-group">
                <label for="usr_name" class="create-form-label">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Nombre Completo
                </label>
                <input 
                    type="text" 
                    id="usr_name" 
                    name="name" 
                    class="create-form-control" 
                    required 
                    value="{{ old('name') }}"
                    placeholder="Ingresa el nombre completo del usuario"
                    autocomplete="name"
                >
                <div class="create-feedback-message" id="name-feedback"></div>
            </div>
            
            <!-- Campo Email -->
            <div class="create-form-group">
                <label for="usr_email" class="create-form-label">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    id="usr_email" 
                    name="email" 
                    class="create-form-control" 
                    required 
                    value="{{ old('email') }}"
                    placeholder="usuario@ejemplo.com"
                    autocomplete="email"
                >
                <div class="create-feedback-message" id="email-feedback"></div>
            </div>
            
            <!-- Campo Contraseña -->
            <div class="create-form-group">
                <label for="usr_password" class="create-form-label">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <circle cx="12" cy="16" r="1"></circle>
                        <path d="m7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Contraseña
                </label>
                <div class="password-field">
                    <input 
                        type="password" 
                        id="usr_password" 
                        name="password" 
                        class="create-form-control" 
                        required
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                    >
                    <button type="button" class="password-toggle" id="toggle-password">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                
                <!-- Indicador de fortaleza de contraseña -->
                <div class="password-strength" id="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strength-fill"></div>
                    </div>
                    <div class="strength-text" id="strength-text">Ingresa una contraseña</div>
                </div>
                
                <div class="create-feedback-message" id="password-feedback"></div>
            </div>
            
            <!-- Campo Rol -->
            <div class="create-form-group">
                <label for="usr_role" class="create-form-label">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"></path>
                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
                    </svg>
                    Rol del Usuario
                </label>
                <select name="role" id="usr_role" class="create-form-control" required>
                    <option value="">Selecciona un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                <div class="create-feedback-message" id="role-feedback"></div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="create-form-actions">
                <button type="submit" class="create-btn create-btn-success create-btn-with-icon" id="usr_create_submit">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Crear Usuario
                </button>
                
                <a href="{{ route('admin.users.index') }}" class="create-btn create-btn-secondary create-btn-with-icon">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15,18 9,12 15,6"></polyline>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Mostrar errores de validación de Laravel -->
@if ($errors->any())
    <div class="validation-errors" style="display: none;">
        @foreach ($errors->all() as $error)
            <div class="error-item" data-field="{{ $loop->index }}">{{ $error }}</div>
        @endforeach
    </div>
@endif



{{-- JavaScript Admin Dashboard --}}
<script src="{{ asset('js/admin-ucreate.js') }}"></script>