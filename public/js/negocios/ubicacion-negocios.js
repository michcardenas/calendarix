// public/js/negocio-ubicacion.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS DEL DOM =====
    const form = document.querySelector('form[action*="negocio.ubicacion.store"]');
    const direccionInput = document.getElementById('neg_direccion');
    const virtualCheckbox = document.querySelector('input[name="neg_virtual"]');
    const virtualLabel = virtualCheckbox ? virtualCheckbox.closest('label') : null;
    const direccionLabel = document.querySelector('label[for="neg_direccion"]');
    const submitBtn = document.querySelector('button[type="submit"]');

    // ===== INICIALIZACIÓN =====
    initializeForm();

    function initializeForm() {
        setupEventListeners();
        startParticleAnimation();
        checkInitialState();
        
        console.log('✨ Formulario de ubicación inicializado');
    }

    function setupEventListeners() {
        // Eventos del input de dirección
        if (direccionInput) {
            direccionInput.addEventListener('input', handleDireccionInput);
            direccionInput.addEventListener('blur', validateDireccion);
            direccionInput.addEventListener('focus', handleInputFocus);
            direccionInput.addEventListener('keydown', handleKeyDown);
        }

        // Eventos del checkbox virtual
        if (virtualCheckbox) {
            virtualCheckbox.addEventListener('change', handleVirtualToggle);
        }

        // Eventos del formulario
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

        // Navegación con teclado
        document.addEventListener('keydown', handleKeyboardNavigation);
    }

    // ===== MANEJO DE EVENTOS =====
    function handleDireccionInput(e) {
        const value = e.target.value;
        
        // Limpiar errores mientras escribe
        clearFieldErrors(e.target);
        
        // Validación en tiempo real
        if (value.length >= 5) {
            debounce(() => validateDireccion(), 500)();
        }
        
        // Efecto visual de escritura
        if (value.length > 0) {
            e.target.style.borderColor = 'var(--primary-light)';
        }
    }

    function handleVirtualToggle(e) {
        const isVirtual = e.target.checked;
        
        if (isVirtual) {
            // Negocio virtual seleccionado
            handleVirtualModeOn();
            playToggleAnimation(virtualLabel, true);
        } else {
            // Negocio físico seleccionado
            handleVirtualModeOff();
            playToggleAnimation(virtualLabel, false);
        }
        
        // Vibración en móviles
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
        
        console.log(`Modo ${isVirtual ? 'virtual' : 'físico'} seleccionado`);
    }

    function handleVirtualModeOn() {
        if (direccionInput && direccionLabel) {
            // Deshabilitar campo de dirección
            direccionInput.disabled = true;
            direccionInput.style.opacity = '0.5';
            direccionInput.style.backgroundColor = '#f5f5f5';
            direccionInput.placeholder = 'No se requiere dirección física';
            direccionInput.value = '';
            
            // Estilo visual para el label
            direccionLabel.style.opacity = '0.6';
            direccionLabel.style.color = 'var(--text-muted)';
            
            showNotification('Perfecto! Tu negocio operará de forma virtual', 'success');
        }
    }

    function handleVirtualModeOff() {
        if (direccionInput && direccionLabel) {
            // Habilitar campo de dirección
            direccionInput.disabled = false;
            direccionInput.style.opacity = '1';
            direccionInput.style.backgroundColor = '';
            direccionInput.placeholder = 'Ej: Carrera 12 #34-56, Bogotá';
            
            // Restaurar estilo del label
            direccionLabel.style.opacity = '1';
            direccionLabel.style.color = '';
            
            // Enfocar el input
            setTimeout(() => {
                direccionInput.focus();
            }, 100);
            
            showNotification('Ingresa la dirección de tu negocio', 'info');
        }
    }

    function handleInputFocus(e) {
        const input = e.target;
        input.style.transform = 'translateY(-2px)';
        
        // Efecto de onda
        createRippleEffect(input);
    }

    function handleKeyDown(e) {
        // Enter para ir al siguiente campo o enviar
        if (e.key === 'Enter') {
            e.preventDefault();
            if (virtualCheckbox) {
                virtualCheckbox.focus();
            } else if (validateForm()) {
                form.submit();
            }
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        
        if (validateForm()) {
            showLoadingState();
            
            // Simular delay
            setTimeout(() => {
                form.submit();
            }, 800);
        } else {
            showValidationErrors();
        }
    }

    function handleKeyboardNavigation(e) {
        // Esc para limpiar campos
        if (e.key === 'Escape') {
            if (direccionInput && !direccionInput.disabled) {
                direccionInput.value = '';
                direccionInput.focus();
            }
        }
    }

    // ===== VALIDACIONES =====
    function validateDireccion() {
        if (!direccionInput) return true;
        
        // Si es virtual, no es necesario validar dirección
        if (virtualCheckbox && virtualCheckbox.checked) {
            return true;
        }
        
        const value = direccionInput.value.trim();
        
        clearFieldErrors(direccionInput);

        if (!value) {
            showFieldError(direccionInput, 'La dirección es obligatoria para negocios físicos');
            return false;
        }

        if (value.length < 5) {
            showFieldError(direccionInput, 'La dirección debe tener al menos 5 caracteres');
            return false;
        }

        if (value.length > 200) {
            showFieldError(direccionInput, 'La dirección es demasiado larga');
            return false;
        }

        // Validación básica de formato
        if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ]/.test(value)) {
            showFieldError(direccionInput, 'La dirección debe contener letras');
            return false;
        }

        showFieldSuccess(direccionInput, '✓ Dirección válida');
        return true;
    }

    function validateForm() {
        const isVirtual = virtualCheckbox && virtualCheckbox.checked;
        
        if (isVirtual) {
            // Para negocios virtuales, no se requiere dirección
            return true;
        } else {
            // Para negocios físicos, validar dirección
            return validateDireccion();
        }
    }

    function checkInitialState() {
        // Verificar si el checkbox viene marcado (old values)
        if (virtualCheckbox && virtualCheckbox.checked) {
            handleVirtualModeOn();
        }
    }

    // ===== MANEJO DE ERRORES =====
    function showFieldError(input, message) {
        if (!input) return;
        
        input.classList.add('error');
        
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
        if (!input) return;
        
        input.classList.remove('error');
        input.classList.add('success');
        
        // Remover errores anteriores
        const existingMessages = input.parentNode.querySelectorAll('.error-message.js-error, .success-message');
        existingMessages.forEach(msg => msg.remove());

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
            input.classList.remove('success');
            if (successDiv.parentNode) {
                successDiv.remove();
            }
        }, 2000);
    }

    function clearFieldErrors(input) {
        if (!input) return;
        
        input.classList.remove('error', 'success');
        input.style.borderColor = '';
        
        const jsErrors = input.parentNode.querySelectorAll('.error-message.js-error, .success-message');
        jsErrors.forEach(error => error.remove());
    }

    function showValidationErrors() {
        if (!validateForm()) {
            const formContent = document.querySelector('.form-content');
            if (formContent) {
                formContent.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    if (formContent.style) {
                        formContent.style.animation = '';
                    }
                }, 500);
            }
            
            showNotification('Por favor completa los campos requeridos', 'error');
        }
    }

    // ===== EFECTOS VISUALES =====
    function playToggleAnimation(element, isChecked) {
        if (!element) return;
        
        if (isChecked) {
            element.style.animation = 'pulse 0.4s ease-out';
            createCelebrationEffect(element);
        } else {
            element.style.animation = 'bounce 0.3s ease-out';
        }
        
        setTimeout(() => {
            if (element.style) {
                element.style.animation = '';
            }
        }, 400);
    }

    function createRippleEffect(element) {
        const ripple = document.createElement('div');
        const rect = element.getBoundingClientRect();
        
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(139, 95, 191, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
            width: 10px;
            height: 10px;
            left: 50%;
            top: 50%;
            z-index: 1;
        `;
        
        element.parentNode.style.position = 'relative';
        element.parentNode.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    }

    function createCelebrationEffect(element) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        for (let i = 0; i < 4; i++) {
            setTimeout(() => {
                createCelebrationParticle(centerX, centerY);
            }, i * 100);
        }
    }

    function createCelebrationParticle(x, y) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 5px;
            height: 5px;
            background: var(--primary, #8B5FBF);
            border-radius: 50%;
            left: ${x}px;
            top: ${y}px;
            pointer-events: none;
            z-index: 9999;
            animation: miniExplosion 0.8s ease-out forwards;
        `;
        
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 800);
    }

    // ===== ESTADOS DE CARGA =====
    function showLoadingState() {
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
        
        // Deshabilitar inputs
        if (direccionInput) direccionInput.disabled = true;
        if (virtualCheckbox) virtualCheckbox.disabled = true;
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
        setInterval(createDynamicParticle, 4000);
    }

    function createDynamicParticle() {
        const backgroundAnimation = document.querySelector('.background-animation');
        if (!backgroundAnimation) return;
        
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 6 + 10;
        const left = Math.random() * 100;
        const duration = Math.random() * 8 + 20;
        
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
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }
        
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(0.97); }
        }
        
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }
        
        @keyframes miniExplosion {
            0% { 
                transform: scale(1) translate(0, 0);
                opacity: 1;
            }
            100% { 
                transform: scale(0) translate(${Math.random() * 80 - 40}px, ${Math.random() * 80 - 40}px);
                opacity: 0;
            }
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        #neg_direccion.success {
            border-color: #27ae60 !important;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1) !important;
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);

    console.log('🎉 Formulario de ubicación con funcionalidades avanzadas cargado');
});