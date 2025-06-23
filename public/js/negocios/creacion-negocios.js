// public/js/negocio-form.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS DEL DOM =====
    const imageInput = document.getElementById('neg_imagen');
    const previewImage = document.getElementById('neg_preview_img');
    const imageContainer = document.querySelector('.image-container');
    const fileLabel = document.querySelector('.file-label');
    const form = document.getElementById('neg_form_creacion');
    const inputs = form.querySelectorAll('input[required]');

    // ===== FUNCIONALIDAD DE PREVIEW DE IMAGEN =====
    imageInput.addEventListener('change', handleImageChange);
    imageContainer.addEventListener('click', () => imageInput.click());

    function handleImageChange(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validaciones
        if (!file.type.startsWith('image/')) {
            showAlert('Por favor selecciona un archivo de imagen válido.', 'error');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            showAlert('La imagen debe ser menor a 5MB.', 'error');
            return;
        }

        // Procesar imagen
        const reader = new FileReader();
        reader.onload = function(event) {
            previewImage.style.opacity = '0';
            setTimeout(() => {
                previewImage.src = event.target.result;
                previewImage.style.opacity = '1';
            }, 150);
        };
        reader.readAsDataURL(file);
        fileLabel.textContent = 'Cambiar foto';
    }

    // ===== VALIDACIÓN DEL FORMULARIO =====
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldErrors);
        input.addEventListener('focus', handleInputFocus);
        input.addEventListener('blur', handleInputBlur);
    });

    function validateField(e) {
        const field = e.target;
        const value = field.value.trim();
        
        clearFieldErrors(e);

        if (!value) {
            showFieldError(field, 'Este campo es obligatorio');
            return false;
        }

        // Validaciones específicas
        switch(field.type) {
            case 'email':
                if (!isValidEmail(value)) {
                    showFieldError(field, 'Ingresa un email válido');
                    return false;
                }
                break;
            case 'text':
                if (field.name === 'neg_telefono' && !isValidPhone(value)) {
                    showFieldError(field, 'Ingresa un número de teléfono válido');
                    return false;
                }
                break;
        }

        return true;
    }

    function clearFieldErrors(e) {
        const field = e.target;
        field.classList.remove('error');
        const errorMsg = field.parentNode.querySelector('.error-message:not(.server-error)');
        if (errorMsg) {
            errorMsg.remove();
        }
    }

    function showFieldError(field, message) {
        field.classList.add('error');
        
        // Remover mensaje de error anterior
        const existingError = field.parentNode.querySelector('.error-message:not(.server-error)');
        if (existingError) {
            existingError.remove();
        }

        // Crear nuevo mensaje de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function handleInputFocus(e) {
        e.target.style.transform = 'translateY(-2px)';
    }

    function handleInputBlur(e) {
        e.target.style.transform = 'translateY(0)';
    }

    // ===== UTILIDADES DE VALIDACIÓN =====
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }

    // ===== SISTEMA DE ALERTAS =====
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()">&times;</button>
        `;
        
        // Estilos inline para la alerta
        Object.assign(alertDiv.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '12px 20px',
            backgroundColor: type === 'error' ? '#e74c3c' : '#27ae60',
            color: 'white',
            borderRadius: '8px',
            boxShadow: '0 4px 20px rgba(0,0,0,0.1)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            animation: 'slideInRight 0.3s ease-out'
        });

        alertDiv.querySelector('button').style.cssText = `
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
        `;

        document.body.appendChild(alertDiv);

        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, 5000);
    }

    // ===== ANIMACIONES DE PARTÍCULAS DINÁMICAS =====
    function createFloatingParticle() {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        // Propiedades aleatorias
        const size = Math.random() * 10 + 10;
        const left = Math.random() * 100;
        const duration = Math.random() * 10 + 15;
        const delay = Math.random() * 5;
        
        Object.assign(particle.style, {
            left: left + '%',
            width: size + 'px',
            height: size + 'px',
            animationDuration: duration + 's',
            animationDelay: delay + 's'
        });
        
        document.querySelector('.background-animation').appendChild(particle);
        
        // Remover después de la animación
        setTimeout(() => {
            if (particle.parentNode) {
                particle.remove();
            }
        }, (duration + delay) * 1000);
    }

    // Crear partículas cada 3 segundos
    setInterval(createFloatingParticle, 3000);

    // ===== VALIDACIÓN DEL FORMULARIO AL ENVIAR =====
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField({ target: input })) {
                isValid = false;
            }
        });

        // Validar checkbox
        const checkbox = document.getElementById('neg_acepto');
        if (!checkbox.checked) {
            showAlert('Debes aceptar los términos y condiciones.', 'error');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            showAlert('Por favor corrige los errores en el formulario.', 'error');
        }
    });

    // ===== EFECTOS ADICIONALES =====
    
    // Efecto parallax en las partículas al mover el mouse
    document.addEventListener('mousemove', function(e) {
        const particles = document.querySelectorAll('.particle');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        
        particles.forEach((particle, index) => {
            const speed = (index % 3 + 1) * 0.5;
            const x = (mouseX - 0.5) * speed;
            const y = (mouseY - 0.5) * speed;
            
            particle.style.transform += ` translate(${x}px, ${y}px)`;
        });
    });

    // Efecto de escritura en el título
    function typeWriter(element, text, speed = 100) {
        element.textContent = '';
        let i = 0;
        
        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }
        
        type();
    }

    // Aplicar efecto de escritura al título
    const title = document.querySelector('.form-header h2');
    if (title) {
        const originalText = title.textContent;
        setTimeout(() => typeWriter(title, originalText, 50), 500);
    }

    // ===== ACCESSIBILITY IMPROVEMENTS =====
    
    // Navegación con teclado mejorada
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });

    document.addEventListener('mousedown', function() {
        document.body.classList.remove('keyboard-navigation');
    });

    // ===== ANIMACIONES CSS DINÁMICAS =====
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .keyboard-navigation *:focus {
            outline: 2px solid var(--primary) !important;
            outline-offset: 2px !important;
        }
        
        .alert {
            animation: slideInRight 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);

    // ===== INICIALIZACIÓN FINAL =====
    console.log('🎉 Formulario de negocio inicializado correctamente');
    
    // Mostrar mensaje de bienvenida
    setTimeout(() => {
        showAlert('¡Bienvenido! Completa tu registro para continuar.', 'info');
    }, 1000);
});