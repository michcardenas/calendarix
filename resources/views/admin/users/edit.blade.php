
{{-- CSS específico del dashboard admin --}}
      
        <link rel="stylesheet" href="{{ asset('css/admin-uedit.css') }}">

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title">Editar Usuario</h2>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" id="edit-user-form">
            @csrf 
            @method('PUT')
            
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="usr_name" class="form-label">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Nombre Completo
                </label>
                <input 
                    type="text" 
                    id="usr_name" 
                    name="name" 
                    class="form-control" 
                    required 
                    value="{{ old('name', $user->name) }}"
                    placeholder="Ingresa el nombre completo"
                    autocomplete="name"
                >
                <div class="feedback-message" id="name-feedback"></div>
            </div>
            
            <!-- Campo Email -->
            <div class="form-group">
                <label for="usr_email" class="form-label">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    id="usr_email" 
                    name="email" 
                    class="form-control" 
                    required 
                    value="{{ old('email', $user->email) }}"
                    placeholder="usuario@ejemplo.com"
                    autocomplete="email"
                >
                <div class="feedback-message" id="email-feedback"></div>
            </div>
            
            <!-- Campo Rol -->
            <div class="form-group">
                <label for="usr_role" class="form-label">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"></path>
                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
                    </svg>
                    Rol del Usuario
                </label>
                <select name="role" id="usr_role" class="form-control" required>
                    <option value="">Selecciona un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" 
                                {{ $user->roles->first()?->id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                <div class="feedback-message" id="role-feedback"></div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-with-icon" id="usr_update_submit">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Actualizar Usuario
                </button>
                
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-with-icon">
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
<script src="{{ asset('js/admin-uedit.js') }}"></script>
