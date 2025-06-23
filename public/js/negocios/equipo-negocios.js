// public/js/negocio-equipo.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS DEL DOM =====
    const form = document.querySelector('form[action*="negocio.equipo.store"]');
    const radioButtons = document.querySelectorAll('input[name="neg_equipo"]');
    const labels = document.querySelectorAll('label[style*="display: block"]');
    const submitBtn = document.querySelector('button[type="submit"]');

    // ===== INICIALIZACIÓN =====
    initializeForm();

    function initializeForm() {
        setupEventListeners();
        startParticleAnimation();
        checkInitialSelection();
        
        // Mensaje de bienvenida
        setTimeout(() => {
            console.log('✨ Formulario de equipo inicializado');
        }, 500);
    }

    function setupEventListeners() {
        // Eventos de radio buttons para efectos visuales
        radioButtons.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                handleRadioSelection(this, index);
            });
            
            radio.addEventListener('focus', function() {
                this.closest('label').style.transform = 'translateY(-1px)';
            });
            
            radio.addEventListener('blur', function() {
                this.closest('label').style.transform = '';
            });
        });

        // Eventos de labels para efectos de hover
        labels.forEach((label, index) => {
            label.addEventListener('mouseenter', function() {
                if (!this.querySelector('input').checked) {
                    this.style.transform = 'translateY(-2px)';
                }
            });
            
            label.addEventListener('mouseleave', function() {
                if (!this.querySelector('input').checked) {
                    this.style.transform = '';
                }
            });
            
            // Efecto de click
            label.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Eventos del formulario
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

        // Navegación con teclado
        document.addEventListener('keydown', handleKeyboardNavigation);
    }

    // ===== MANEJO DE EVENTOS =====
    function handleRadioSelection(radio, index) {
        // Efectos visuales cuando se selecciona una opción
        const label = radio.closest('label');
        
        // Efecto de selección con animación
        if (radio.checked) {
            // Remover efectos de otras opciones
            labels.forEach(otherLabel => {
                if (otherLabel !== label) {
                    otherLabel.style.transform = '';
                    otherLabel.style.boxShadow = '';
                }
            });
            
            // Agregar efecto a la opción seleccionada
            label.style.transform = 'translateY(-1px)';
            playSelectionAnimation(label);
            
            // Vibración en móviles
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
            
            // Actualizar botón
            updateSubmitButton();
            
            console.log(`Opción seleccionada: ${radio.value}`);
        }
    }

    function handleFormSubmit(e) {
        const selectedOption = document.querySelector('input[name="neg_equipo"]:checked');
        
        if (!selectedOption) {
            e.preventDefault();
            showValidationError();
            return false;
        }
        
        // Mostrar estado de carga
        showLoadingState();
        
        console.log(`Enviando formulario con: ${selectedOption.value}`);
    }

    function handleKeyboardNavigation(e) {
        const focusedElement = document.activeElement;
        
        // Navegación con flechas entre radio buttons
        if (focusedElement && focusedElement.name === 'neg_equipo') {
            const radios = Array.from(radioButtons);
            const currentIndex = radios.indexOf(focusedElement);
            
            let nextIndex = -1;
            
            switch(e.key) {
                case 'ArrowDown':
                case 'ArrowRight':
                    nextIndex = (currentIndex + 1) % radios.length;
                    break;
                case 'ArrowUp':
                case 'ArrowLeft':
                    nextIndex = (currentIndex - 1 + radios.length) % radios.length;
                    break;
            }
            
            if (nextIndex !== -1) {
                e.preventDefault();
                radios[nextIndex].focus();
            }
        }
    }

    // ===== FUNCIONES DE EFECTOS VISUALES =====
    function playSelectionAnimation(label) {
        // Efecto de pulso
        label.style.animation = 'pulse 0.4s ease-out';
        setTimeout(() => {
            if (label.style) {
                label.style.animation = '';
            }
        }, 400);
        
        // Crear partículas de celebración pequeñas
        createCelebrationEffect(label);
    }

    function createCelebrationEffect(element) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        // Crear 3 partículas pequeñas
        for (let i = 0; i < 3; i++) {
            setTimeout(() => {
                createCelebrationParticle(centerX, centerY);
            }, i * 100);
        }
    }

    function createCelebrationParticle(x, y) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 4px;
            height: 4px;
            background: var(--primary, #8B5FBF);
            border-radius: 50%;
            left: ${x}px;
            top: ${y}px;
            pointer-events: none;
            z-index: 9999;
            animation: miniExplosion 0.6s ease-out forwards;
        `;
        
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 600);
    }

    function updateSubmitButton() {
        if (!submitBtn) return;
        
        const selectedOption = document.querySelector('input[name="neg_equipo"]:checked');
        
        if (selectedOption) {
            submitBtn.style.transform = 'scale(1.02)';
            setTimeout(() => {
                if (submitBtn.style) {
                    submitBtn.style.transform = '';
                }
            }, 200);
        }
    }

    function checkInitialSelection() {
        // Verificar si hay una opción pre-seleccionada (old values)
        const selectedRadio = document.querySelector('input[name="neg_equipo"]:checked');
        if (selectedRadio) {
            const label = selectedRadio.closest('label');
            if (label) {
                label.style.transform = 'translateY(-1px)';
            }
        }
    }

    // ===== VALIDACIÓN VISUAL =====
    function showValidationError() {
        // Efecto shake en el formulario
        const formContent = document.querySelector('.form-content');
        if (formContent) {
            formContent.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                if (formContent.style) {
                    formContent.style.animation = '';
                }
            }, 500);
        }
        
        // Resaltar las opciones brevemente
        labels.forEach((label, index) => {
            setTimeout(() => {
                label.style.borderColor = 'var(--primary, #8B5FBF)';
                label.style.transform = 'translateY(-2px)';
                
                setTimeout(() => {
                    label.style.borderColor = '';
                    label.style.transform = '';
                }, 300);
            }, index * 100);
        });
        
        showNotification('Por favor selecciona el tamaño de tu equipo', 'error');
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
        
        // Deshabilitar radio buttons durante el envío
        radioButtons.forEach(radio => {
            radio.disabled = true;
        });
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
            maxWidth: '350px',
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

    // ===== ESTILOS DINÁMICOS =====
    const additionalStyles = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        @keyframes miniExplosion {
            0% { 
                transform: scale(1) translate(0, 0);
                opacity: 1;
            }
            100% { 
                transform: scale(0) translate(${Math.random() * 60 - 30}px, ${Math.random() * 60 - 30}px);
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
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);

    console.log('🎉 Formulario de equipo con efectos visuales cargado');
});