// ============================================
// FORMULARIO DE EDICIÓN DE USUARIO - JS
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
            debounceTime: 500
        },
        messages: {
            nameRequired: 'El nombre es obligatorio',
            nameMinLength: 'El nombre debe tener al menos 2 caracteres',
            emailRequired: 'El email es obligatorio',
            emailInvalid: 'Por favor ingresa un email válido',
            roleRequired: 'Debes seleccionar un rol',
            updateSuccess: 'Usuario actualizado correctamente',
            updateError: 'Error al actualizar el usuario'
        }
    };

    // ============================================
    // CREAR MANCHITAS DINÁMICAS DE FONDO
    // ============================================
    
    function createFloatingBlobs() {
        const container = document.querySelector('.form-container');
        
        if (!container) {
            console.warn('Contenedor del formulario no encontrado');
            return;
        }

        const colors = [
            'linear-gradient(45deg, #3b82f6, #8b5cf6)',
            'linear-gradient(135deg, #10b981, #06b6d4)',
            'linear-gradient(225deg, #f59e0b, #ef4444)',
            'linear-gradient(315deg, #ec4899, #8b5cf6)',
            'linear-gradient(45deg, #06b6d4, #3b82f6)',
            'linear-gradient(135deg, #10b981, #f59e0b)'
        ];
        
        // Crear 8 manchitas dinámicas para el formulario
        for (let i = 0; i < 8; i++) {
            const blob = document.createElement('div');
            blob.className = 'floating-blob-form';
            
            // Tamaños aleatorios más pequeños para el formulario
            const width = Math.random() * 80 + 40;   // 40-120px
            const height = Math.random() * 70 + 50;   // 50-120px
            
            // Posiciones aleatorias
            const top = Math.random() * 100;         // 0-100%
            const left = Math.random() * 100;        // 0-100%
            
            // Delay de animación aleatorio
            const delay = Math.random() * -15;       // 0 a -15s
            
            // Duración de animación aleatoria
            const duration = Math.random() * 5 + 10; // 10-15s
            
            blob.style.cssText = `
                position: absolute;
                width: ${width}px;
                height: ${height}px;
                background: ${colors[i % colors.length]};
                top: ${top}%;
                left: ${left}%;
                opacity: 0.04;
                z-index: 0;
                pointer-events: none;
                border-radius: ${Math.random() * 30 + 40}% ${Math.random() * 30 + 30}% ${Math.random() * 30 + 50}% ${Math.random() * 30 + 35}%;
                animation: floatBlobForm ${duration}s ease-in-out infinite;
                animation-delay: ${delay}s;
            `;
            
            container.appendChild(blob);
        }
        
        console.log('✨ Manchitas de fondo del formulario creadas');
    }

    // CSS para animación de manchitas
    const blobAnimationCSS = `
        @keyframes floatBlobForm {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-20px) rotate(5deg) scale(1.1);
            }
            50% {
                transform: translateY(-10px) rotate(-5deg) scale(0.9);
            }
            75% {
                transform: translateY(-25px) rotate(3deg) scale(1.05);
            }
        }
    `;
    
    const style = document.createElement('style');
    style.textContent = blobAnimationCSS;
    document.head.appendChild(style);

    // ============================================
    // VALIDACIÓN EN TIEMPO REAL
    // ============================================
    
    function initFormValidation() {
        const form = document.getElementById('edit-user-form');
        const nameInput = document.getElementById('usr_name');
        const emailInput = document.getElementById('usr_email');
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

        if (roleSelect) {
            roleSelect.addEventListener('change', validateRole);
        }

        // Validación completa del formulario
        function validateForm() {
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isRoleValid = validateRole();
            
            return isNameValid && isEmailValid && isRoleValid;
        }

        // Manejar envío del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                handleFormSubmission(this);
            } else {
                showNotification('Por favor corrige los errores antes de continuar', 'error');
                
                // Hacer focus en el primer campo con error
                const firstError = form.querySelector('.form-control.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        console.log('✅ Validación del formulario inicializada');
    }

    // ============================================
    // FUNCIONES DE VALIDACIÓN UI
    // ============================================
    
    function showFieldError(input, feedback, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        
        if (feedback) {
            feedback.textContent = message;
            feedback.className = 'feedback-message invalid';
            feedback.style.display = 'block';
        }
        
        // Efecto de vibración sutil
        input.style.animation = 'shake 0.3s ease-in-out';
        setTimeout(() => {
            input.style.animation = '';
        }, 300);
    }

    function showFieldSuccess(input, feedback, message) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        
        if (feedback) {
            feedback.textContent = message;
            feedback.className = 'feedback-message valid';
            feedback.style.display = 'block';
        }
    }

    // CSS para animación de shake
    const shakeCSS = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    
    const shakeStyle = document.createElement('style');
    shakeStyle.textContent = shakeCSS;
    document.head.appendChild(shakeStyle);

    // ============================================
    // MANEJO DEL ENVÍO DEL FORMULARIO
    // ============================================
    
    function handleFormSubmission(form) {
        const submitButton = document.getElementById('usr_update_submit');
        
        try {
            // Mostrar estado de carga
            setButtonLoading(submitButton, true);
            
            // Simular delay para mostrar loading (remover en producción)
            setTimeout(() => {
                // Enviar formulario
                form.submit();
                
                // En una aplicación SPA, aquí harías la petición AJAX
                // const formData = new FormData(form);
                // 
                // fetch(form.action, {
                //     method: 'POST',
                //     body: formData,
                //     headers: {
                //         'X-Requested-With': 'XMLHttpRequest'
                //     }
                // })
                // .then(response => response.json())
                // .then(data => {
                //     if (data.success) {
                //         showNotification(config.messages.updateSuccess, 'success');
                //         // Redireccionar o actualizar UI
                //     } else {
                //         throw new Error(data.message || 'Error desconocido');
                //     }
                // })
                // .catch(error => {
                //     console.error('Error:', error);
                //     showNotification(config.messages.updateError, 'error');
                // })
                // .finally(() => {
                //     setButtonLoading(submitButton, false);
                // });
                
            }, 800);
            
        } catch (error) {
            console.error('Error al enviar formulario:', error);
            showNotification(config.messages.updateError, 'error');
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
        const notification = document.createElement('div');
        notification.className = `notification-temp alert-${type}`;
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
                max-width: 400px;
                font-weight: 500;
            ">
                <strong>${type === 'success' ? '✅' : '❌'}</strong> ${message}
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
        }, 3000);
    }

    // ============================================
    // EFECTOS VISUALES ADICIONALES
    // ============================================
    
    function initVisualEffects() {
        // Efecto de focus en los campos
        const formControls = document.querySelectorAll('.form-control');
        
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
        const buttons = document.querySelectorAll('.btn');
        
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
                    animation: ripple 0.6s ease-out;
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
        
        console.log('✨ Efectos visuales inicializados');
    }

    // CSS para efecto ripple
    const rippleCSS = `
        @keyframes ripple {
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
        console.log('🚀 Inicializando formulario de edición de usuario...');
        
        try {
            createFloatingBlobs();
            initFormValidation();
            initVisualEffects();
            displayLaravelErrors();
            
            console.log('✅ Formulario de edición inicializado correctamente');
        } catch (error) {
            console.error('❌ Error al inicializar formulario:', error);
        }
    }

    // Ejecutar inicialización
    init();

    // ============================================
    // EXPORT PARA USO EXTERNO
    // ============================================
    
    window.EditUserForm = {
        showNotification,
        setButtonLoading,
        validateForm: () => {
            const form = document.getElementById('edit-user-form');
            return form ? validateForm() : false;
        },
        config
    };
});