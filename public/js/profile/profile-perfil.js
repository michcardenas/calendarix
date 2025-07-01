// public/js/profile-perfil.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS DEL DOM =====
    const formDatos = document.getElementById('form_datos_perfil');
    const formPassword = document.getElementById('form_password_perfil');
    const formsEliminar = document.querySelectorAll('.form_eliminar_perfil');
    
    // Inputs de datos personales
    const photoInput = document.getElementById('photo_perfil');
    const previewPhoto = document.getElementById('preview_photo_perfil');
    const nameInput = document.getElementById('name_perfil');
    const emailInput = document.getElementById('email_perfil');
    const telefonoInput = document.getElementById('telefono_perfil');
    
    // Inputs de contraseñas
    const currentPasswordInput = document.getElementById('current_password_perfil');
    const passwordInput = document.getElementById('password_perfil');
    const passwordConfirmationInput = document.getElementById('password_confirmation_perfil');
    
    // Botones de toggle de contraseñas
    const toggleCurrent = document.getElementById('toggle_current_perfil');
    const togglePassword = document.getElementById('toggle_password_perfil');
    const toggleConfirmation = document.getElementById('toggle_confirmation_perfil');
    
    // Botones
    const btnGuardar = document.getElementById('btn_guardar_perfil');
    const btnCambiarPassword = document.getElementById('btn_cambiar_password_perfil');
    const btnsEliminar = document.querySelectorAll('.btn_eliminar_perfil');
    
    // Modal
    const modal = document.getElementById('modal_confirmar_perfil');
    const empresaNombre = document.getElementById('empresa_nombre_perfil');
    const btnConfirmarEliminar = document.getElementById('btn_confirmar_eliminar_perfil');
    
    // ===== VARIABLES DE ESTADO =====
    let formToSubmit = null;
    let passwordStrength = 0;

    // ===== INICIALIZACIÓN =====
    initializePerfil();

    function initializePerfil() {
        setupEventListeners();
        startParticleAnimation();
        setupPhotoPreview();
        setupPasswordToggles();
        setupPasswordValidation();
        setupFormValidations();
        setupDeleteConfirmations();
        
        // Mensaje de bienvenida
        setTimeout(() => {
            showNotification('👋 ¡Mantén tu perfil actualizado!', 'info');
        }, 1000);
        
        console.log('✨ Perfil Calendarix inicializado');
    }

    function setupEventListeners() {
        // Eventos de formularios
        if (formDatos) {
            formDatos.addEventListener('submit', handleDataFormSubmit);
        }
        
        if (formPassword) {
            formPassword.addEventListener('submit', handlePasswordFormSubmit);
        }
        
        // Eventos de inputs de datos
        if (photoInput) {
            photoInput.addEventListener('change', handlePhotoChange);
        }
        
        if (nameInput) {
            nameInput.addEventListener('input', () => validateField(nameInput, validateName));
            nameInput.addEventListener('blur', () => validateField(nameInput, validateName));
        }
        
        if (emailInput) {
            emailInput.addEventListener('input', () => validateField(emailInput, validateEmail));
            emailInput.addEventListener('blur', () => validateField(emailInput, validateEmail));
        }
        
        if (telefonoInput) {
            telefonoInput.addEventListener('input', () => validateField(telefonoInput, validateTelefono));
            telefonoInput.addEventListener('blur', () => validateField(telefonoInput, validateTelefono));
        }
        
        // Eventos de contraseñas
        if (passwordInput) {
            passwordInput.addEventListener('input', handlePasswordInput);
            passwordInput.addEventListener('focus', showPasswordTips);
        }
        
        if (passwordConfirmationInput) {
            passwordConfirmationInput.addEventListener('input', validatePasswordConfirmation);
        }
        
        // Eventos de botones de eliminar
        btnsEliminar.forEach((btn, index) => {
            btn.addEventListener('click', (e) => handleDeleteClick(e, btn));
        });
        
        if (btnConfirmarEliminar) {
            btnConfirmarEliminar.addEventListener('click', handleConfirmDelete);
        }
        
        // Navegación con teclado
        document.addEventListener('keydown', handleKeyboardNavigation);
    }

    // ===== PREVIEW DE FOTO =====
    function setupPhotoPreview() {
        if (!photoInput) return;
        
        // Crear elemento de preview si no existe
        if (!previewPhoto) {
            const preview = document.createElement('img');
            preview.id = 'preview_photo_perfil';
            preview.className = 'rounded mb-2';
            preview.style.width = '120px';
            preview.style.height = '120px';
            preview.style.objectFit = 'cover';
            preview.style.display = 'none';
            photoInput.parentNode.insertBefore(preview, photoInput);
        }
    }
    
    function handlePhotoChange(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validar tipo de archivo
        if (!file.type.startsWith('image/')) {
            showFieldError(photoInput, 'Por favor selecciona una imagen válida');
            return;
        }
        
        // Validar tamaño (5MB máximo)
        if (file.size > 5 * 1024 * 1024) {
            showFieldError(photoInput, 'La imagen debe ser menor a 5MB');
            return;
        }
        
        // Crear preview
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('preview_photo_perfil');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'preview_photo_perfil';
                preview.className = 'rounded mb-2';
                preview.style.width = '120px';
                preview.style.height = '120px';
                preview.style.objectFit = 'cover';
                photoInput.parentNode.insertBefore(preview, photoInput);
            }
            
            preview.src = e.target.result;
            preview.style.display = 'block';
            
            // Efecto de aparición
            preview.style.animation = 'photoAppear 0.5s ease-out';
            
            clearFieldErrors(photoInput);
            showNotification('📷 Foto cargada correctamente', 'success');
        };
        reader.readAsDataURL(file);
    }

    // ===== TOGGLE DE CONTRASEÑAS =====
    function setupPasswordToggles() {
        setupToggle(toggleCurrent, currentPasswordInput);
        setupToggle(togglePassword, passwordInput);
        setupToggle(toggleConfirmation, passwordConfirmationInput);
    }
    
    function setupToggle(button, input) {
        if (!button || !input) return;
        
        button.addEventListener('click', function() {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            
            const icon = button.querySelector('i');
            if (icon) {
                icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
            }
            
            // Efecto visual
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                button.style.transform = '';
            }, 150);
        });
    }

    // ===== VALIDACIONES =====
    function setupPasswordValidation() {
        if (!passwordInput) return;
        
        // Crear indicador de fortaleza
        const strengthIndicator = document.createElement('div');
        strengthIndicator.id = 'password_strength_perfil';
        strengthIndicator.className = 'password-strength mt-2';
        strengthIndicator.innerHTML = `
            <div class="strength-bars">
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
            </div>
            <small class="strength-text">Ingresa tu contraseña</small>
        `;
        
        passwordInput.parentNode.appendChild(strengthIndicator);
    }
    
    function handlePasswordInput() {
        const password = passwordInput.value;
        passwordStrength = calculatePasswordStrength(password);
        updatePasswordStrengthIndicator(passwordStrength);
        
        // Validar confirmación si ya hay texto
        if (passwordConfirmationInput && passwordConfirmationInput.value) {
            validatePasswordConfirmation();
        }
    }
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        return Math.min(strength, 4);
    }
    
    function updatePasswordStrengthIndicator(strength) {
        const indicator = document.getElementById('password_strength_perfil');
        if (!indicator) return;
        
        const bars = indicator.querySelectorAll('.strength-bar');
        const text = indicator.querySelector('.strength-text');
        
        const levels = ['Muy débil', 'Débil', 'Regular', 'Fuerte', 'Muy fuerte'];
        const colors = ['#e74c3c', '#e67e22', '#f39c12', '#27ae60', '#2ecc71'];
        
        bars.forEach((bar, index) => {
            if (index < strength) {
                bar.style.backgroundColor = colors[strength - 1];
                bar.style.animation = 'barFill 0.3s ease-out';
            } else {
                bar.style.backgroundColor = '#ddd';
                bar.style.animation = '';
            }
        });
        
        if (text) {
            text.textContent = levels[strength] || 'Ingresa tu contraseña';
            text.style.color = colors[strength - 1] || '#6c757d';
        }
    }
    
    function showPasswordTips() {
        if (document.getElementById('password_tips_perfil')) return;
        
        const tips = document.createElement('div');
        tips.id = 'password_tips_perfil';
        tips.className = 'password-tips mt-2';
        tips.innerHTML = `
            <small>
                <strong>Tu contraseña debe tener:</strong><br>
                • Al menos 8 caracteres<br>
                • Una letra minúscula<br>
                • Una letra mayúscula<br>
                • Un número<br>
                • Un carácter especial
            </small>
        `;
        
        passwordInput.parentNode.appendChild(tips);
        
        // Remover tips después de 10 segundos
        setTimeout(() => {
            if (tips.parentNode) {
                tips.remove();
            }
        }, 10000);
    }

    // ===== VALIDACIONES DE CAMPOS =====
    function validateField(input, validator) {
        const isValid = validator(input.value);
        
        if (isValid.valid) {
            showFieldSuccess(input, isValid.message);
        } else {
            showFieldError(input, isValid.message);
        }
        
        return isValid.valid;
    }
    
    function validateName(name) {
        if (!name.trim()) {
            return { valid: false, message: 'El nombre es obligatorio' };
        }
        if (name.length < 2) {
            return { valid: false, message: 'El nombre debe tener al menos 2 caracteres' };
        }
        if (name.length > 100) {
            return { valid: false, message: 'El nombre es demasiado largo' };
        }
        return { valid: true, message: '✓ Nombre válido' };
    }
    
    function validateEmail(email) {
        if (!email.trim()) {
            return { valid: false, message: 'El email es obligatorio' };
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return { valid: false, message: 'Ingresa un email válido' };
        }
        return { valid: true, message: '✓ Email válido' };
    }
    
    function validateTelefono(telefono) {
        if (!telefono.trim()) {
            return { valid: true, message: '' }; // Campo opcional
        }
        
        // Remover espacios y caracteres especiales para validación
        const cleanPhone = telefono.replace(/[\s\-\(\)]/g, '');
        
        if (cleanPhone.length < 7) {
            return { valid: false, message: 'El teléfono debe tener al menos 7 dígitos' };
        }
        if (cleanPhone.length > 15) {
            return { valid: false, message: 'El teléfono es demasiado largo' };
        }
        if (!/^[\+]?[0-9]+$/.test(cleanPhone)) {
            return { valid: false, message: 'El teléfono solo puede contener números y el símbolo +' };
        }
        
        return { valid: true, message: '✓ Teléfono válido' };
    }
    
    function validatePasswordConfirmation() {
        if (!passwordInput || !passwordConfirmationInput) return;
        
        const password = passwordInput.value;
        const confirmation = passwordConfirmationInput.value;
        
        if (!confirmation) {
            clearFieldErrors(passwordConfirmationInput);
            return;
        }
        
        if (password !== confirmation) {
            showFieldError(passwordConfirmationInput, 'Las contraseñas no coinciden');
            return false;
        } else {
            showFieldSuccess(passwordConfirmationInput, '✓ Las contraseñas coinciden');
            return true;
        }
    }

    // ===== MANEJO DE FORMULARIOS =====
    function setupFormValidations() {
        // Validación en tiempo real mejorada
        const inputs = [nameInput, emailInput, telefonoInput];
        inputs.forEach(input => {
            if (!input) return;
            
            input.addEventListener('input', debounce(() => {
                if (input === nameInput) validateField(input, validateName);
                if (input === emailInput) validateField(input, validateEmail);
                if (input === telefonoInput) validateField(input, validateTelefono);
            }, 300));
        });
    }
    
    function handleDataFormSubmit(e) {
    e.preventDefault();

    if (nameInput) validateField(nameInput, validateName);
    if (emailInput) validateField(emailInput, validateEmail);
    if (telefonoInput) validateField(telefonoInput, validateTelefono);

    const isValid = document.querySelectorAll('.is-invalid').length === 0;

    if (isValid) {
        showLoadingState(formDatos);
        showNotification('💾 Guardando cambios...', 'info');

        setTimeout(() => {
            // ✅ Agregar manualmente el input _method
            let methodInput = formDatos.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                formDatos.appendChild(methodInput);
            }

            // ✅ Asegúrate que CSRF esté también
            let tokenInput = formDatos.querySelector('input[name="_token"]');
            if (!tokenInput) {
                tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = document.querySelector('meta[name="csrf-token"]').content;
                formDatos.appendChild(tokenInput);
            }

            formDatos.submit(); // ✅ Esto ya lo hará bien
        }, 800);
    } else {
        showFormErrors();
    }
}

    
    function handlePasswordFormSubmit(e) {
        e.preventDefault();
        
        const currentPassword = currentPasswordInput?.value;
        const newPassword = passwordInput?.value;
        const confirmation = passwordConfirmationInput?.value;
        
        // Validaciones
        if (!currentPassword) {
            showFieldError(currentPasswordInput, 'Ingresa tu contraseña actual');
            return;
        }
        
        if (!newPassword) {
            showFieldError(passwordInput, 'Ingresa tu nueva contraseña');
            return;
        }
        
        if (passwordStrength < 3) {
            showFieldError(passwordInput, 'La contraseña debe ser más fuerte');
            return;
        }
        
        if (!validatePasswordConfirmation()) {
            return;
        }
        
        showLoadingState(formPassword);
        showNotification('🔐 Cambiando contraseña...', 'info');
        
        setTimeout(() => {
            formPassword.submit();
        }, 800);
    }

    // ===== CONFIRMACIÓN DE ELIMINACIÓN =====
    function setupDeleteConfirmations() {
        formsEliminar.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                // El manejo se hace en handleDeleteClick
            });
        });
    }
    
    function handleDeleteClick(e, button) {
        e.preventDefault();
        
        const form = button.closest('.form_eliminar_perfil');
        const empresaName = form.getAttribute('data-empresa');
        
        if (empresaNombre) {
            empresaNombre.textContent = empresaName;
        }
        
        formToSubmit = form;
        
        // Mostrar modal con Bootstrap
        if (typeof bootstrap !== 'undefined' && modal) {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            // Fallback si no hay Bootstrap
            const confirmed = confirm(`¿Estás seguro de eliminar la empresa "${empresaName}"?\n\nEsta acción no se puede deshacer.`);
            if (confirmed) {
                handleConfirmDelete();
            }
        }
    }
    
    function handleConfirmDelete() {
        if (formToSubmit) {
            showNotification('🗑️ Eliminando empresa...', 'warning');
            
            setTimeout(() => {
                formToSubmit.submit();
            }, 500);
        }
        
        // Cerrar modal
        if (typeof bootstrap !== 'undefined' && modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
    }

    // ===== MANEJO DE ERRORES Y ÉXITOS =====
    function showFieldError(input, message) {
        if (!input) return;
        
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        
        // Buscar mensaje de error existente
        let errorDiv = input.parentNode.querySelector('.error-message.js-error');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message js-error';
            input.parentNode.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;

        // Efecto shake
        input.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            if (input.style) {
                input.style.animation = '';
            }
        }, 500);
    }

    function showFieldSuccess(input, message) {
        if (!input || !message) return;
        
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
        
        // Remover errores anteriores
        const existingMessages = input.parentNode.querySelectorAll('.error-message.js-error, .success-message');
        existingMessages.forEach(msg => msg.remove());

        if (message.startsWith('✓')) {
            // Crear mensaje de éxito
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.textContent = message;
            successDiv.style.cssText = `
                color: #27ae60;
                font-size: 12px;
                margin-top: 4px;
                animation: slideDown 0.3s ease-out;
            `;
            input.parentNode.appendChild(successDiv);

            // Remover después de 2 segundos
            setTimeout(() => {
                input.classList.remove('is-valid');
                if (successDiv.parentNode) {
                    successDiv.remove();
                }
            }, 2000);
        }
    }

    function clearFieldErrors(input) {
        if (!input) return;
        
        input.classList.remove('is-invalid', 'is-valid');
        
        const jsErrors = input.parentNode.querySelectorAll('.error-message.js-error, .success-message');
        jsErrors.forEach(error => error.remove());
    }

    function showFormErrors() {
        const formContent = document.querySelector('.container');
        if (formContent) {
            formContent.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                if (formContent.style) {
                    formContent.style.animation = '';
                }
            }, 500);
        }
        
        showNotification('❌ Por favor corrige los errores del formulario', 'error');
    }

    // ===== ESTADOS DE CARGA =====
    function showLoadingState(form) {
        if (!form) return;
        
        form.classList.add('loading');
        
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span>Procesando...</span>
                <div style="
                    display: inline-block;
                    width: 16px;
                    height: 16px;
                    border: 2px solid rgba(255,255,255,0.3);
                    border-top-color: white;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin-left: 8px;
                "></div>
            `;
        }
        
        // Deshabilitar todos los inputs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.disabled = true;
        });
    }

    // ===== NAVEGACIÓN CON TECLADO =====
    function handleKeyboardNavigation(e) {
        // Esc para cerrar modal
        if (e.key === 'Escape' && modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
        
        // Enter para enviar formularios (excepto en textareas)
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            const form = e.target.closest('form');
            if (form && !e.target.closest('.modal')) {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.click();
                }
            }
        }
    }

    // ===== NOTIFICACIONES =====
    function showNotification(message, type = 'info') {
        // Remover notificación anterior
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = 'notification';
        
        const icons = {
            info: 'ℹ️',
            success: '✅',
            error: '❌',
            warning: '⚠️'
        };
        
        const colors = {
            info: '#8B5FBF',
            success: '#27ae60',
            error: '#e74c3c',
            warning: '#f39c12'
        };
        
        notification.innerHTML = `
            <span style="margin-right: 8px;">${icons[type] || icons.info}</span>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="
                background: none;
                border: none;
                color: white;
                margin-left: 10px;
                cursor: pointer;
                font-size: 18px;
                padding: 0;
            ">×</button>
        `;
        
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '16px 20px',
            backgroundColor: colors[type] || colors.info,
            color: 'white',
            borderRadius: '12px',
            boxShadow: '0 8px 30px rgba(0,0,0,0.15)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            maxWidth: '400px',
            animation: 'slideInRight 0.4s ease-out'
        });

        document.body.appendChild(notification);

        // Auto-remover
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.4s ease-out';
                setTimeout(() => notification.remove(), 400);
            }
        }, 5000);
    }

    // ===== ANIMACIÓN DE PARTÍCULAS DE FONDO =====
    function startParticleAnimation() {
        setInterval(createDynamicParticle, 3500);
    }

    function createDynamicParticle() {
        const backgroundAnimation = document.querySelector('.background-animation');
        if (!backgroundAnimation) return;
        
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 8 + 8;
        const left = Math.random() * 100;
        const duration = Math.random() * 10 + 22;
        
        Object.assign(particle.style, {
            left: left + '%',
            width: size + 'px',
            height: size + 'px',
            animationDuration: duration + 's'
        });
        
        backgroundAnimation.appendChild(particle);
        
        setTimeout(() => {
            if (particle.parentNode) {
                particle.remove();
            }
        }, duration * 1000);
    }

    // ===== UTILIDADES =====
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ===== ESTILOS DINÁMICOS =====
    const additionalStyles = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        
        @keyframes photoAppear {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes barFill {
            from { transform: scaleX(0); }
            to { transform: scaleX(1); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Estilos para indicador de fortaleza de contraseña */
        .password-strength {
            margin-top: 8px;
        }
        
        .strength-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }
        
        .strength-bar {
            height: 4px;
            flex: 1;
            background-color: #ddd;
            border-radius: 2px;
            transition: background-color 0.3s ease;
        }
        
        .password-tips {
            background: rgba(139, 95, 191, 0.05);
            border: 1px solid rgba(139, 95, 191, 0.2);
            border-radius: 8px;
            padding: 12px;
            color: var(--text-secondary, #6B5B7B);
            animation: slideDown 0.3s ease-out;
        }
        
        /* Estados de validación */
        .form-control.is-invalid {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
        }
        
        .form-control.is-valid {
            border-color: #27ae60 !important;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1) !important;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);

    console.log('🎉 Perfil con funcionalidades avanzadas completamente cargado');
});