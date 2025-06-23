// ============================================
// FORMULARIO DE CREACIÓN DE USUARIO - JS
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // CONFIGURACIÓN INICIAL
    // ============================================
    
    const config = {
        animations: {
            duration: 300,
            easing: 'ease-in-out'
        },
        validation: {
            emailRegex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            minNameLength: 2,
            minPasswordLength: 8,
            debounceTime: 500
        },
        password: {
            criteria: {
                minLength: 8,
                hasUppercase: /[A-Z]/,
                hasLowercase: /[a-z]/,
                hasNumbers: /\d/,
                hasSpecialChars: /[!@#$%^&*(),.?":{}|<>]/
            }
        },
        messages: {
            nameRequired: 'El nombre es obligatorio',
            nameMinLength: 'El nombre debe tener al menos 2 caracteres',
            emailRequired: 'El email es obligatorio',
            emailInvalid: 'Por favor ingresa un email válido',
            passwordRequired: 'La contraseña es obligatoria',
            passwordWeak: 'Contraseña muy débil',
            roleRequired: 'Debes seleccionar un rol',
            createSuccess: 'Usuario creado correctamente',
            createError: 'Error al crear el usuario'
        }
    };

    // ============================================
    // CREAR MANCHITAS DINÁMICAS DE FONDO VERDES
    // ============================================
    
    function createFloatingBlobs() {
        const container = document.querySelector('.create-form-container');
        
        if (!container) {
            console.warn('Contenedor del formulario no encontrado');
            return;
        }

        const colors = [
            'linear-gradient(45deg, #10b981, #34d399)',
            'linear-gradient(135deg, #059669, #10b981)',
            'linear-gradient(225deg, #06b6d4, #10b981)',
            'linear-gradient(315deg, #3b82f6, #10b981)',
            'linear-gradient(45deg, #10b981, #06b6d4)',
            'linear-gradient(135deg, #34d399, #059669)',
            'linear-gradient(225deg, #10b981, #3b82f6)',
            'linear-gradient(315deg, #059669, #34d399)'
        ];
        
        // Crear 10 manchitas dinámicas para el formulario de creación
        for (let i = 0; i < 10; i++) {
            const blob = document.createElement('div');
            blob.className = 'floating-blob-create';
            
            // Tamaños aleatorios
            const width = Math.random() * 90 + 50;   // 50-140px
            const height = Math.random() * 80 + 60;   // 60-140px
            
            // Posiciones aleatorias
            const top = Math.random() * 100;         // 0-100%
            const left = Math.random() * 100;        // 0-100%
            
            // Delay de animación aleatorio
            const delay = Math.random() * -20;       // 0 a -20s
            
            // Duración de animación aleatoria
            const duration = Math.random() * 6 + 12; // 12-18s
            
            blob.style.cssText = `
                position: absolute;
                width: ${width}px;
                height: ${height}px;
                background: ${colors[i % colors.length]};
                top: ${top}%;
                left: ${left}%;
                opacity: 0.05;
                z-index: 0;
                pointer-events: none;
                border-radius: ${Math.random() * 35 + 40}% ${Math.random() * 35 + 30}% ${Math.random() * 35 + 50}% ${Math.random() * 35 + 35}%;
                animation: floatBlobCreate ${duration}s ease-in-out infinite;
                animation-delay: ${delay}s;
            `;
            
            container.appendChild(blob);
        }
        
        console.log('✨ Manchitas verdes de fondo del formulario creadas');
    }

    // CSS para animación de manchitas
    const blobAnimationCSS = `
        @keyframes floatBlobCreate {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }
            20% {
                transform: translateY(-25px) rotate(8deg) scale(1.1);
            }
            40% {
                transform: translateY(-10px) rotate(-5deg) scale(0.9);
            }
            60% {
                transform: translateY(-30px) rotate(10deg) scale(1.05);
            }
            80% {
                transform: translateY(-15px) rotate(-8deg) scale(0.95);
            }
        }
    `;
    
    const style = document.createElement('style');
    style.textContent = blobAnimationCSS;
    document.head.appendChild(style);

    // ============================================
    // FUNCIONALIDAD DE MOSTRAR/OCULTAR CONTRASEÑA
    // ============================================
    
    function initPasswordToggle() {
        const passwordInput = document.getElementById('usr_password');
        const toggleButton = document.getElementById('toggle-password');
        
        if (!passwordInput || !toggleButton) return;
        
        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Cambiar ícono
            const icon = this.querySelector('svg');
            if (type === 'text') {
                // Ícono de ojo cerrado
                icon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            } else {
                // Ícono de ojo abierto
                icon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            }
        });
        
        console.log('👁️ Toggle de contraseña inicializado');
    }

    // ============================================
    // VALIDADOR DE FORTALEZA DE CONTRASEÑA
    // ============================================
    
    function initPasswordStrength() {
        const passwordInput = document.getElementById('usr_password');
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');
        
        if (!passwordInput || !strengthFill || !strengthText) return;
        
        function evaluatePasswordStrength(password) {
            if (!password) {
                return { score: 0, text: 'Ingresa una contraseña', class: '' };
            }
            
            let score = 0;
            const criteria = config.password.criteria;
            
            // Verificar criterios
            if (password.length >= criteria.minLength) score++;
            if (criteria.hasUppercase.test(password)) score++;
            if (criteria.hasLowercase.test(password)) score++;
            if (criteria.hasNumbers.test(password)) score++;
            if (criteria.hasSpecialChars.test(password)) score++;
            
            // Puntos extra por longitud
            if (password.length >= 12) score++;
            if (password.length >= 16) score++;
            
            // Determinar fortaleza
            if (score < 2) {
                return { score: 1, text: 'Muy débil - Agrega más caracteres', class: 'strength-weak' };
            } else if (score < 4) {
                return { score: 2, text: 'Débil - Agrega mayúsculas y números', class: 'strength-fair' };
            } else if (score < 6) {
                return { score: 3, text: 'Buena - Considera agregar símbolos', class: 'strength-good' };
            } else {
                return { score: 4, text: 'Excelente - Contraseña muy segura', class: 'strength-strong' };
            }
        }
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = evaluatePasswordStrength(password);
            
            // Actualizar barra de fortaleza
            strengthFill.className = `strength-fill ${strength.class}`;
            strengthText.textContent = strength.text;
            strengthText.className = `strength-text ${strength.class}`;
            
            // Validar para el feedback
            debounceValidation('password', () => validatePassword(password));
        });
        
        console.log('🔒 Validador de fortaleza de contraseña inicializado');
    }

    // ============================================
    // VALIDACIÓN EN TIEMPO REAL
    // ============================================
    
    function initFormValidation() {
        const form = document.getElementById('create-user-form');
        const nameInput = document.getElementById('usr_name');
        const emailInput = document.getElementById('usr_email');
        const passwordInput = document.getElementById('usr_password');
        const roleSelect = document.getElementById('usr_role');
        
        if (!form) {
            console.warn('Formulario no encontrado');
            return;
        }

        // Debounce para validación
        let validationTimeouts = {};

        function debounceValidation(fieldName, validationFunction, delay = config.validation.debounceTime) {
            clearTimeout(validationTimeouts[fieldName]);
            validationTimeouts[fieldName] = setTimeout(validationFunction, delay);
        }

        // Hacer la función available globalmente
        window.debounceValidation = debounceValidation;

        // Validar nombre
        function validateName() {
            const value = nameInput.value.trim();
            const feedback = document.getElementById('name-feedback');
            
            if (value === '') {
                showFieldError(nameInput, feedback, config.messages.nameRequired);
                return false;
            } else if (value.length < config.validation.minNameLength) {
                showFieldError(nameInput, feedback, config.messages.nameMinLength);
                return false;
            } else {
                showFieldSuccess(nameInput, feedback, '✓ Nombre válido');
                return true;
            }
        }

        // Validar email
        function validateEmail() {
            const value = emailInput.value.trim();
            const feedback = document.getElementById('email-feedback');
            
            if (value === '') {
                showFieldError(emailInput, feedback, config.messages.emailRequired);
                return false;
            } else if (!config.validation.emailRegex.test(value)) {
                showFieldError(emailInput, feedback, config.messages.emailInvalid);
                return false;
            } else {
                showFieldSuccess(emailInput, feedback, '✓ Email válido');
                return true;
            }
        }

        // Validar contraseña
        function validatePassword(password = passwordInput.value) {
            const feedback = document.getElementById('password-feedback');
            
            if (password === '') {
                showFieldError(passwordInput, feedback, config.messages.passwordRequired);
                return false;
            } else if (password.length < config.validation.minPasswordLength) {
                showFieldError(passwordInput, feedback, `La contraseña debe tener al menos ${config.validation.minPasswordLength} caracteres`);
                return false;
            } else {
                const strength = evaluatePasswordStrength(password);
                if (strength.score < 2) {
                    showFieldError(passwordInput, feedback, config.messages.passwordWeak);
                    return false;
                } else {
                    showFieldSuccess(passwordInput, feedback, '✓ Contraseña aceptable');
                    return true;
                }
            }
        }

        // Hacer validatePassword available globalmente
        window.validatePassword = validatePassword;

        // Función para evaluar fortaleza (compartida)
        function evaluatePasswordStrength(password) {
            if (!password) {
                return { score: 0, text: 'Ingresa una contraseña', class: '' };
            }
            
            let score = 0;
            const criteria = config.password.criteria;
            
            if (password.length >= criteria.minLength) score++;
            if (criteria.hasUppercase.test(password)) score++;
            if (criteria.hasLowercase.test(password)) score++;
            if (criteria.hasNumbers.test(password)) score++;
            if (criteria.hasSpecialChars.test(password)) score++;
            
            if (password.length >= 12) score++;
            if (password.length >= 16) score++;
            
            if (score < 2) {
                return { score: 1, text: 'Muy débil', class: 'strength-weak' };
            } else if (score < 4) {
                return { score: 2, text: 'Débil', class: 'strength-fair' };
            } else if (score < 6) {
                return { score: 3, text: 'Buena', class: 'strength-good' };
            } else {
                return { score: 4, text: 'Excelente', class: 'strength-strong' };
            }
        }

        // Validar rol
        function validateRole() {
            const value = roleSelect.value;
            const feedback = document.getElementById('role-feedback');
            
            if (value === '') {
                showFieldError(roleSelect, feedback, config.messages.roleRequired);
                return false;
            } else {
                showFieldSuccess(roleSelect, feedback, '✓ Rol seleccionado');
                return true;
            }
        }

        // Event listeners con debounce
        if (nameInput) {
            nameInput.addEventListener('input', () => {
                debounceValidation('name', validateName);
            });
            nameInput.addEventListener('blur', validateName);
        }

        if (emailInput) {
            emailInput.addEventListener('input', () => {
                debounceValidation('email', validateEmail);
            });
            emailInput.addEventListener('blur', validateEmail);
        }

        if (passwordInput) {
            // La validación de password se maneja en initPasswordStrength
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', validateRole);
        }

        // Validación completa del formulario
        function validateForm() {
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();
            const isRoleValid = validateRole();
            
            return isNameValid && isEmailValid && isPasswordValid && isRoleValid;
        }

        // Manejar envío del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                handleFormSubmission(this);
            } else {
                showNotification('Por favor corrige los errores antes de continuar', 'error');
                
                // Hacer focus en el primer campo con error
                const firstError = form.querySelector('.create-form-control.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        console.log('✅ Validación del formulario de creación inicializada');
    }

    // ============================================
    // FUNCIONES DE VALIDACIÓN UI
    // ============================================
    
    function showFieldError(input, feedback, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        
        if (feedback) {
            feedback.innerHTML = `
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                ${message}
            `;
            feedback.className = 'create-feedback-message invalid';
            feedback.style.display = 'flex';
        }
        
        // Efecto de vibración sutil
        input.style.animation = 'shakeCreate 0.4s ease-in-out';
        setTimeout(() => {
            input.style.animation = '';
        }, 400);
    }

    function showFieldSuccess(input, feedback, message) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        
        if (feedback) {
            feedback.innerHTML = `
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20,6 9,17 4,12"></polyline>
                </svg>
                ${message}
            `;
            feedback.className = 'create-feedback-message valid';
            feedback.style.display = 'flex';
        }
    }

    // ============================================
    // MANEJO DEL ENVÍO DEL FORMULARIO
    // ============================================
    
    function handleFormSubmission(form) {
        const submitButton = document.getElementById('usr_create_submit');
        
        try {
            // Mostrar estado de carga
            setButtonLoading(submitButton, true);
            
            // Simular delay para mostrar loading (remover en producción)
            setTimeout(() => {
                // Enviar formulario
                form.submit();
                
                // En una aplicación SPA, aquí harías la petición AJAX
                
            }, 1000);
            
        } catch (error) {
            console.error('Error al enviar formulario:', error);
            showNotification(config.messages.createError, 'error');
            setButtonLoading(submitButton, false);
        }
    }

    // ============================================
    // FUNCIONES UTILITARIAS
    // ============================================
    
    /**
     * Añade/quita estado de carga a un botón
     */
    function setButtonLoading(button, loading = true) {
        if (!button) return;
        
        if (loading) {
            button.classList.add('loading');
            button.disabled = true;
            button.dataset.originalText = button.textContent;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
            if (button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
                delete button.dataset.originalText;
            }
        }
    }

    /**
     * Muestra una notificación temporal
     */
    function showNotification(message, type = 'success') {
        const bgColor = type === 'success' ? '#10b981' : '#ef4444';
        const icon = type === 'success' ? '✅' : '❌';
        
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                background: ${bgColor};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
                max-width: 400px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            ">
                <span style="font-size: 1.2em;">${icon}</span>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        const notificationDiv = notification.firstElementChild;
        
        // Animar entrada
        setTimeout(() => {
            notificationDiv.style.transform = 'translateX(0)';
        }, 10);
        
        // Animar salida y remover
        setTimeout(() => {
            notificationDiv.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }

    // ============================================
    // EFECTOS VISUALES ADICIONALES
    // ============================================
    
    function initVisualEffects() {
        // Efecto de focus en los campos
        const formControls = document.querySelectorAll('.create-form-control');
        
        formControls.forEach(control => {
            control.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.2s ease-out';
            });
            
            control.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Efecto ripple en botones
        const buttons = document.querySelectorAll('.create-btn');
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: rippleCreate 0.6s ease-out;
                    pointer-events: none;
                `;
                
                if (!this.style.position || this.style.position === 'static') {
                    this.style.position = 'relative';
                }
                this.style.overflow = 'hidden';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.parentNode.removeChild(ripple);
                    }
                }, 600);
            });
        });
        
        console.log('✨ Efectos visuales del formulario de creación inicializados');
    }

    // CSS para efecto ripple
    const rippleCSS = `
        @keyframes rippleCreate {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = rippleCSS;
    document.head.appendChild(rippleStyle);

    // ============================================
    // MOSTRAR ERRORES DE LARAVEL
    // ============================================
    
    function displayLaravelErrors() {
        const errorsContainer = document.querySelector('.validation-errors');
        
        if (errorsContainer) {
            const errors = errorsContainer.querySelectorAll('.error-item');
            
            errors.forEach(error => {
                showNotification(error.textContent, 'error');
            });
        }
    }

    // ============================================
    // INICIALIZACIÓN
    // ============================================
    
    function init() {
        console.log('🚀 Inicializando formulario de creación de usuario...');
        
        try {
            createFloatingBlobs();
            initPasswordToggle();
            initPasswordStrength();
            initFormValidation();
            initVisualEffects();
            displayLaravelErrors();
            
            console.log('✅ Formulario de creación inicializado correctamente');
        } catch (error) {
            console.error('❌ Error al inicializar formulario:', error);
        }
    }

    // Ejecutar inicialización
    init();

    // ============================================
    // EXPORT PARA USO EXTERNO
    // ============================================
    
    window.CreateUserForm = {
        showNotification,
        setButtonLoading,
        config
    };
});