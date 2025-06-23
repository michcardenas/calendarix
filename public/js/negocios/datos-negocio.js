// public/js/negocio-nombre.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS EXACTOS DEL USUARIO =====
    const form = document.getElementById('neg_form_nombre');
    const nombreInput = document.getElementById('neg_nombre_comercial');
    const sitioWebInput = document.getElementById('neg_sitio_web');
    const submitBtn = form.querySelector('button[type="submit"]');

    // ===== INICIALIZACIÓN =====
    initializeForm();

    function initializeForm() {
        // Configurar eventos
        setupEventListeners();
        
        // Iniciar animaciones de fondo
        startParticleAnimation();
        
        // Mensaje de bienvenida
        setTimeout(() => {
            console.log('✨ Formulario de negocio inicializado');
        }, 500);
    }

    function setupEventListeners() {
        // Eventos del formulario
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }
        
        // Eventos del input nombre
        if (nombreInput) {
            nombreInput.addEventListener('input', handleNombreInput);
            nombreInput.addEventListener('blur', validateNombre);
            nombreInput.addEventListener('focus', handleInputFocus);
            nombreInput.addEventListener('keydown', handleKeyDown);
        }
        
        // Eventos del input sitio web
        if (sitioWebInput) {
            sitioWebInput.addEventListener('input', handleSitioWebInput);
            sitioWebInput.addEventListener('blur', validateSitioWeb);
            sitioWebInput.addEventListener('focus', handleInputFocus);
            sitioWebInput.addEventListener('keydown', handleKeyDown);
        }
    }

    // ===== MANEJO DE EVENTOS =====
    function handleNombreInput(e) {
        const value = e.target.value;
        
        // Limpiar errores mientras escribe
        clearFieldErrors(e.target);
        
        // Capitalizar primera letra de cada palabra
        const capitalizedValue = value.replace(/\b\w/g, l => l.toUpperCase());
        if (capitalizedValue !== value) {
            e.target.value = capitalizedValue;
        }

        // Validación en tiempo real (después de 2 caracteres)
        if (value.length >= 2) {
            debounce(() => validateNombre(), 500)();
        }
    }

    function handleSitioWebInput(e) {
        const value = e.target.value;
        
        clearFieldErrors(e.target);
        
        // Validación en tiempo real para URLs
        if (value.length > 3) {
            debounce(() => validateSitioWeb(), 800)();
        }
    }

    function handleInputFocus(e) {
        const input = e.target;
        input.style.transform = 'translateY(-2px)';
    }

    function handleKeyDown(e) {
        // Enter para navegar al siguiente campo
        if (e.key === 'Enter' && e.target === nombreInput) {
            e.preventDefault();
            if (sitioWebInput) {
                sitioWebInput.focus();
            }
        }
        
        // Enter en el último campo para enviar
        if (e.key === 'Enter' && e.target === sitioWebInput) {
            e.preventDefault();
            if (validateForm()) {
                form.submit();
            }
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        
        if (validateForm()) {
            showLoadingState();
            // Simular un pequeño delay para mostrar el loading
            setTimeout(() => {
                form.submit();
            }, 500);
        } else {
            showNotification('Por favor corrige los errores en el formulario.', 'error');
        }
    }

    // ===== VALIDACIONES =====
    function validateNombre() {
        if (!nombreInput) return true;
        
        const value = nombreInput.value.trim();
        
        clearFieldErrors(nombreInput);

        if (!value) {
            showFieldError(nombreInput, 'El nombre del negocio es obligatorio');
            return false;
        }

        if (value.length < 2) {
            showFieldError(nombreInput, 'El nombre debe tener al menos 2 caracteres');
            return false;
        }

        if (value.length > 100) {
            showFieldError(nombreInput, 'El nombre no puede exceder 100 caracteres');
            return false;
        }

        return true;
    }

    function validateSitioWeb() {
        if (!sitioWebInput) return true;
        
        const value = sitioWebInput.value.trim();
        
        clearFieldErrors(sitioWebInput);

        // Campo opcional
        if (!value) {
            return true;
        }

        // Agregar protocolo si no existe
        let url = value;
        if (!url.match(/^https?:\/\//)) {
            url = 'https://' + url.replace(/^(www\.)?/, 'www.');
            sitioWebInput.value = url;
        }

        // Validar formato básico de URL
        const urlPattern = /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/;
        if (!urlPattern.test(url)) {
            showFieldError(sitioWebInput, 'Por favor ingresa una URL válida');
            return false;
        }

        return true;
    }

    function validateForm() {
        const nombreValid = validateNombre();
        const sitioWebValid = validateSitioWeb();
        
        return nombreValid && sitioWebValid;
    }

    // ===== MANEJO DE ERRORES =====
    function showFieldError(input, message) {
        if (!input) return;
        
        input.classList.add('error');
        
        // Buscar si ya existe un mensaje de error
        let errorDiv = input.parentNode.querySelector('.error-message.js-error');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message js-error';
            // Insertar después del input
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }
        
        errorDiv.textContent = message;

        // Efecto de shake
        input.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            if (input.style) {
                input.style.animation = '';
            }
        }, 500);
    }

    function clearFieldErrors(input) {
        if (!input) return;
        
        input.classList.remove('error');
        
        // Remover solo los errores creados por JS, no los del servidor
        const jsErrors = input.parentNode.querySelectorAll('.error-message.js-error');
        jsErrors.forEach(error => error.remove());
    }

    // ===== ESTADOS DE CARGA =====
    function showLoadingState() {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Procesando...';
            submitBtn.style.opacity = '0.7';
        }
    }

    // ===== NOTIFICACIONES =====
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icons = {
            info: 'ℹ️',
            success: '✅',
            error: '❌'
        };
        
        notification.innerHTML = `
            <span>${icons[type] || icons.info}</span>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="background:none;border:none;color:white;margin-left:10px;cursor:pointer;">×</button>
        `;
        
        // Estilos
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '16px 20px',
            backgroundColor: type === 'error' ? '#e74c3c' : '#8B5FBF',
            color: 'white',
            borderRadius: '12px',
            boxShadow: '0 8px 30px rgba(139, 95, 191, 0.15)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            gap: '8px',
            animation: 'slideInRight 0.4s ease-out'
        });

        document.body.appendChild(notification);

        // Auto-remover
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.4s ease-out';
                setTimeout(() => notification.remove(), 400);
            }
        }, 4000);
    }

    // ===== ANIMACIÓN DE PARTÍCULAS =====
    function startParticleAnimation() {
        // Crear partículas adicionales dinámicamente
        setInterval(createDynamicParticle, 4000);
    }

    function createDynamicParticle() {
        const backgroundAnimation = document.querySelector('.background-animation');
        if (!backgroundAnimation) return;
        
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 8 + 12;
        const left = Math.random() * 100;
        const duration = Math.random() * 8 + 18;
        const delay = Math.random() * 3;
        
        Object.assign(particle.style, {
            left: left + '%',
            width: size + 'px',
            height: size + 'px',
            animationDuration: duration + 's',
            animationDelay: delay + 's'
        });
        
        backgroundAnimation.appendChild(particle);
        
        // Remover después de la animación
        setTimeout(() => {
            if (particle.parentNode) {
                particle.remove();
            }
        }, (duration + delay) * 1000);
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
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .notification {
            animation: slideInRight 0.4s ease-out;
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);

    console.log('🎉 Formulario mejorado cargado correctamente');
});