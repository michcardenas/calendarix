// ============================================
// AUTH CARD PROFESIONAL - JAVASCRIPT COMPLETO
// Soporta Login y Register automáticamente
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ Auth Card inicializado correctamente');
    
    // ============================================
    // DETECCIÓN DE PÁGINA
    // ============================================
    
    const isLoginPage = document.getElementById('auth-login') !== null;
    const isRegisterPage = document.getElementById('auth-register') !== null;
    
    console.log(`📍 Página actual: ${isLoginPage ? 'LOGIN' : isRegisterPage ? 'REGISTRO' : 'DESCONOCIDA'}`);
    
    // ============================================
    // CONFIGURACIÓN INICIAL
    // ============================================
    
    const config = {
        login: {
            submitText: 'Entrar',
            submitIcon: 'fas fa-sign-in-alt',
            loadingText: 'Entrando...',
            loadingIcon: 'fas fa-spinner fa-spin',
            successText: '¡Éxito!',
            successIcon: 'fas fa-check'
        },
        register: {
            submitText: 'Crear Cuenta',
            submitIcon: 'fas fa-user-plus',
            loadingText: 'Creando cuenta...',
            loadingIcon: 'fas fa-spinner fa-spin',
            successText: '¡Cuenta creada!',
            successIcon: 'fas fa-check'
        }
    };
    
    const currentConfig = isLoginPage ? config.login : config.register;
    
    // ============================================
    // ELEMENTOS DEL DOM
    // ============================================
    
    const form = document.querySelector('.auth-form');
    const submitBtn = document.querySelector('.auth-btn-primary');
    const inputs = document.querySelectorAll('.auth-input');
    const emailInput = document.querySelector('input[type="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordConfirmInput = document.querySelector('input[name="password_confirmation"]');
    const nameInput = document.querySelector('input[name="name"]');
    const termsCheckbox = document.querySelector('input[name="terms"]');
    
    // ============================================
    // INICIALIZACIÓN DE INPUTS
    // ============================================
    
    function initializeInputs() {
        inputs.forEach(input => {
            // Animaciones de focus
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
                this.setAttribute('data-focus-visible', '');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
                this.removeAttribute('data-focus-visible');
            });
            
            // Limpiar errores al escribir
            input.addEventListener('input', function() {
                clearFieldError(this);
                
                // Validación en tiempo real para ciertos campos
                if (this.type === 'email' && this.value.length > 0) {
                    validateEmail(this);
                } else if (this.name === 'password' && isRegisterPage) {
                    validatePassword(this);
                } else if (this.name === 'password_confirmation') {
                    validatePasswordConfirmation();
                } else if (this.name === 'name' && this.value.length > 0) {
                    validateName(this);
                }
            });
            
            // Validación al perder focus
            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    validateField(this);
                }
            });
        });
    }
    
    // ============================================
    // FUNCIONES DE VALIDACIÓN
    // ============================================
    
    function validateField(input) {
        const value = input.value.trim();
        
        switch (input.type) {
            case 'email':
                return validateEmail(input);
            case 'password':
                if (input.name === 'password') {
                    return validatePassword(input);
                } else if (input.name === 'password_confirmation') {
                    return validatePasswordConfirmation();
                }
                break;
            case 'text':
                if (input.name === 'name') {
                    return validateName(input);
                }
                break;
        }
        
        return true;
    }
    
    function validateEmail(input) {
        const email = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            showFieldError(input, 'El correo electrónico es requerido');
            return false;
        } else if (!emailRegex.test(email)) {
            showFieldError(input, 'Por favor, ingresa un correo válido');
            return false;
        } else {
            clearFieldError(input);
            return true;
        }
    }
    
    function validatePassword(input) {
        const password = input.value;
        
        if (!password) {
            showFieldError(input, 'La contraseña es requerida');
            return false;
        } else if (password.length < 8) {
            showFieldError(input, 'La contraseña debe tener al menos 8 caracteres');
            return false;
        } else {
            clearFieldError(input);
            return true;
        }
    }
    
    function validatePasswordConfirmation() {
        if (!passwordInput || !passwordConfirmInput) return true;
        
        const password = passwordInput.value;
        const confirmation = passwordConfirmInput.value;
        
        if (!confirmation) {
            showFieldError(passwordConfirmInput, 'Por favor, confirma tu contraseña');
            return false;
        } else if (password !== confirmation) {
            showFieldError(passwordConfirmInput, 'Las contraseñas no coinciden');
            return false;
        } else {
            clearFieldError(passwordConfirmInput);
            return true;
        }
    }
    
    function validateName(input) {
        const name = input.value.trim();
        
        if (!name) {
            showFieldError(input, 'El nombre es requerido');
            return false;
        } else if (name.length < 2) {
            showFieldError(input, 'El nombre debe tener al menos 2 caracteres');
            return false;
        } else {
            clearFieldError(input);
            return true;
        }
    }
    
    function validateTerms() {
        if (!termsCheckbox || !isRegisterPage) return true;
        
        if (!termsCheckbox.checked) {
            showAlert('Debes aceptar los términos y condiciones', 'error');
            termsCheckbox.focus();
            return false;
        }
        
        return true;
    }
    
    // ============================================
    // MANEJO DE ERRORES VISUALES
    // ============================================
    
    function showFieldError(input, message) {
        clearFieldError(input);
        
        const inputGroup = input.closest('.auth-input-group');
        if (!inputGroup) return;
        
        // Crear elemento de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'auth-error-msg';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        `;
        
        // Añadir al DOM
        inputGroup.appendChild(errorDiv);
        
        // Cambiar estilo del input
        input.parentElement.style.borderColor = '#dc2626';
        input.parentElement.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
        
        // Hacer scroll al error si es necesario
        setTimeout(() => {
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }
    
    function clearFieldError(input) {
        const inputGroup = input.closest('.auth-input-group');
        if (!inputGroup) return;
        
        // Remover mensaje de error existente
        const existingError = inputGroup.querySelector('.auth-error-msg');
        if (existingError) {
            existingError.remove();
        }
        
        // Restaurar estilo del input
        input.parentElement.style.borderColor = '#e2e8f0';
        input.parentElement.style.boxShadow = '';
    }
    
    function clearAllErrors() {
        const errors = document.querySelectorAll('.auth-error-msg');
        errors.forEach(error => error.remove());
        
        inputs.forEach(input => {
            input.parentElement.style.borderColor = '#e2e8f0';
            input.parentElement.style.boxShadow = '';
        });
    }
    
    // ============================================
    // ALERTAS Y NOTIFICACIONES
    // ============================================
    
    function showAlert(message, type = 'info') {
        // Remover alerta existente
        const existingAlert = document.querySelector('.auth-dynamic-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        const alertClass = type === 'error' ? 'auth-error-alert' : 'auth-success-alert';
        const iconClass = type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `${alertClass} auth-dynamic-alert`;
        alertDiv.innerHTML = `
            <i class="${iconClass}"></i>
            <span>${message}</span>
        `;
        
        // Insertar al inicio del formulario
        const formWrapper = document.querySelector('.auth-form-wrapper');
        formWrapper.insertBefore(alertDiv, formWrapper.firstChild);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.style.transition = 'opacity 0.3s ease';
                alertDiv.style.opacity = '0';
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
    
    // ============================================
    // MANEJO DEL FORMULARIO
    // ============================================
    
    function initializeFormHandling() {
        if (!form || !submitBtn) return;
        
        let isSubmitting = false;
        
        form.addEventListener('submit', function(e) {
            // Prevenir doble envío
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            
            // Limpiar errores previos
            clearAllErrors();
            
            // Validar todos los campos
            let isValid = true;
            const fieldsToValidate = [];
            
            // Recopilar campos a validar según la página
            if (isRegisterPage) {
                if (nameInput) fieldsToValidate.push(nameInput);
                if (emailInput) fieldsToValidate.push(emailInput);
                if (passwordInput) fieldsToValidate.push(passwordInput);
                if (passwordConfirmInput) fieldsToValidate.push(passwordConfirmInput);
            } else {
                if (emailInput) fieldsToValidate.push(emailInput);
                if (passwordInput) fieldsToValidate.push(passwordInput);
            }
            
            // Validar campos uno por uno
            fieldsToValidate.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });
            
            // Validar términos en registro
            if (isRegisterPage && !validateTerms()) {
                isValid = false;
            }
            
            // Si hay errores de validación, no enviar
            if (!isValid) {
                e.preventDefault();
                
                // Focus en el primer campo con error
                const firstError = document.querySelector('.auth-error-msg');
                if (firstError) {
                    const input = firstError.closest('.auth-input-group')?.querySelector('.auth-input');
                    if (input) {
                        input.focus();
                    }
                }
                
                showAlert('Por favor, corrige los errores en el formulario', 'error');
                return false;
            }
            
            // Si todo está bien, mostrar estado de loading
            isSubmitting = true;
            setButtonState('loading');
            
            // Permitir que el formulario se envíe a Laravel
            // (El estado se restablecerá si hay errores del servidor)
        });
    }
    
    // ============================================
    // ESTADOS DEL BOTÓN
    // ============================================
    
    function setButtonState(state) {
        if (!submitBtn) return;
        
        switch (state) {
            case 'loading':
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="${currentConfig.loadingIcon}"></i> ${currentConfig.loadingText}`;
                submitBtn.classList.add('auth-loading');
                break;
                
            case 'success':
                submitBtn.innerHTML = `<i class="${currentConfig.successIcon}"></i> ${currentConfig.successText}`;
                break;
                
            case 'normal':
            default:
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="${currentConfig.submitIcon}"></i> ${currentConfig.submitText}`;
                submitBtn.classList.remove('auth-loading');
                break;
        }
    }
    
    // ============================================
    // EFECTOS VISUALES
    // ============================================
    
    function addRippleEffect(e) {
        const button = e.target.closest('button, a');
        if (!button) return;
        
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        const ripple = document.createElement('span');
        ripple.className = 'auth-ripple-effect';
        ripple.style.cssText = `
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
        `;
        
        button.appendChild(ripple);
        
        // Remover después de la animación
        setTimeout(() => {
            if (ripple.parentNode) {
                ripple.parentNode.removeChild(ripple);
            }
        }, 800);
    }
    
    // ============================================
    // ACCESIBILIDAD
    // ============================================
    
    function initializeAccessibility() {
        // Navegación por teclado
        const focusableElements = document.querySelectorAll(
            'input, button, a, [tabindex]:not([tabindex="-1"])'
        );
        
        focusableElements.forEach((element, index) => {
            element.addEventListener('keydown', function(e) {
                // Enter en enlaces y botones
                if ((e.key === 'Enter' || e.key === ' ') && 
                    (this.tagName === 'A' || this.tagName === 'BUTTON')) {
                    e.preventDefault();
                    this.click();
                }
                
                // Navegación con Tab mejorada
                if (e.key === 'Tab') {
                    // Lógica personalizada si es necesaria
                }
            });
            
            // Indicadores visuales de focus
            element.addEventListener('focus', function() {
                this.setAttribute('data-focus-visible', '');
            });
            
            element.addEventListener('blur', function() {
                this.removeAttribute('data-focus-visible');
            });
        });
    }
    
    // ============================================
    // MANEJO DE ERRORES DEL SERVIDOR
    // ============================================
    
    function handleServerErrors() {
        // Si hay errores de Laravel, enfocar el primer campo con error
        const serverErrors = document.querySelectorAll('.auth-error-msg');
        if (serverErrors.length > 0) {
            const firstError = serverErrors[0];
            const input = firstError.closest('.auth-input-group')?.querySelector('.auth-input');
            if (input) {
                setTimeout(() => {
                    input.focus();
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
            
            // Restaurar estado del botón si hay errores del servidor
            setButtonState('normal');
        }
        
        // Manejar alertas de éxito
        const successAlert = document.querySelector('.auth-success-alert');
        if (successAlert) {
            // Auto-ocultar después de 5 segundos
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.3s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    if (successAlert.parentNode) {
                        successAlert.remove();
                    }
                }, 300);
            }, 5000);
        }
    }
    
    // ============================================
    // RESPONSIVE Y ORIENTACIÓN
    // ============================================
    
    function handleResponsive() {
        let resizeTimeout;
        
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                // Verificar que el formulario sea visible
                const container = document.querySelector('.auth-main-container');
                if (container) {
                    const isOverflowing = container.scrollHeight > container.clientHeight;
                    if (isOverflowing) {
                        console.log('📱 Formulario con scroll activado');
                    }
                }
            }, 250);
        });
        
        // Manejar cambio de orientación en móviles
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                const activeInput = document.activeElement;
                if (activeInput && activeInput.classList.contains('auth-input')) {
                    activeInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 500);
        });
    }
    
    // ============================================
    // FUNCIONES GLOBALES ÚTILES
    // ============================================
    
    // Funciones disponibles globalmente para integraciones
    window.authCard = {
        clearForm: function() {
            if (form) {
                form.reset();
                clearAllErrors();
                const firstInput = form.querySelector('.auth-input');
                if (firstInput) firstInput.focus();
            }
        },
        
        showAlert: showAlert,
        
        validateForm: function() {
            clearAllErrors();
            let isValid = true;
            
            inputs.forEach(input => {
                if (input.value.trim() !== '' && !validateField(input)) {
                    isValid = false;
                }
            });
            
            if (isRegisterPage && !validateTerms()) {
                isValid = false;
            }
            
            return isValid;
        },
        
        setButtonState: setButtonState,
        
        getFormData: function() {
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            return data;
        }
    };
    
    // ============================================
    // INICIALIZACIÓN PRINCIPAL
    // ============================================
    
    // Ejecutar todas las inicializaciones
    initializeInputs();
    initializeFormHandling();
    initializeAccessibility();
    handleServerErrors();
    handleResponsive();
    
    // Añadir efectos visuales
    document.addEventListener('mousedown', addRippleEffect);
    
    // Detectar preferencias de usuario
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) {
        console.log('♿ Modo de movimiento reducido detectado');
        document.documentElement.style.setProperty('--transition', '0.1s ease');
    }
    
    // Detectar modo oscuro
    const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (prefersDarkMode) {
        console.log('🌙 Modo oscuro detectado');
        document.body.classList.add('dark-mode');
    }
    
    // ============================================
    // LOGGING FINAL
    // ============================================
    
    console.log('🚀 Todas las funcionalidades cargadas correctamente');
    console.log('🎯 Funcionalidades activas:');
    console.log('   • Validación en tiempo real');
    console.log('   • Manejo de errores del servidor');
    console.log('   • Navegación por teclado');
    console.log('   • Responsive automático');
    console.log('   • Prevención de doble envío');
    console.log('   • Efectos visuales y animaciones');
    console.log('   • Soporte para accesibilidad');
    console.log('   • API global disponible en window.authCard');
    
    // Auto-focus en el primer input al cargar
    setTimeout(() => {
        const firstInput = document.querySelector('.auth-input');
        if (firstInput && !document.activeElement.classList.contains('auth-input')) {
            firstInput.focus();
        }
    }, 500);
});