// public/js/negocio-verificacion.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS DEL DOM =====
    const form = document.querySelector('form[action*="negocio.verificacion.store"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    const mapDiv = document.querySelector('div[style*="width: 100%"][style*="max-width: 600px"][style*="height: 300px"]');
    const mapText = document.querySelector('div[style*="width: 100%"] p[style*="color: #777"]');

    // ===== INICIALIZACIÓN =====
    initializeForm();

    function initializeForm() {
        setupEventListeners();
        startParticleAnimation();
        simulateMapLoading();
        
        // Mensaje de bienvenida
        setTimeout(() => {
            showNotification('Verifica que la ubicación mostrada sea correcta', 'info');
        }, 1000);
        
        console.log('✨ Formulario de verificación inicializado');
    }

    function setupEventListeners() {
        // Eventos del formulario
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

        // Eventos del botón
        if (submitBtn) {
            submitBtn.addEventListener('mouseenter', handleButtonHover);
            submitBtn.addEventListener('mouseleave', handleButtonLeave);
            submitBtn.addEventListener('click', handleButtonClick);
        }

        // Eventos del mapa
        if (mapDiv) {
            mapDiv.addEventListener('click', handleMapClick);
            mapDiv.addEventListener('mouseenter', handleMapHover);
            mapDiv.addEventListener('mouseleave', handleMapLeave);
        }

        // Navegación con teclado
        document.addEventListener('keydown', handleKeyboardNavigation);
    }

    // ===== MANEJO DE EVENTOS =====
    function handleFormSubmit(e) {
        e.preventDefault();
        
        // Simular verificación
        showVerificationProcess();
    }

    function handleButtonHover() {
        // Efecto de brillo en el botón
        submitBtn.style.boxShadow = '0 12px 35px rgba(139, 95, 191, 0.25)';
        
        // Crear efecto de ondas
        createRippleEffect(submitBtn);
    }

    function handleButtonLeave() {
        submitBtn.style.boxShadow = '';
    }

    function handleButtonClick() {
        // Efecto de click
        submitBtn.style.transform = 'translateY(-2px) scale(0.98)';
        setTimeout(() => {
            if (submitBtn.style) {
                submitBtn.style.transform = '';
            }
        }, 150);
        
        // Vibración en móviles
        if (navigator.vibrate) {
            navigator.vibrate([50, 30, 50]);
        }
    }

    function handleMapClick() {
        // Simular colocación de pin
        simulatePinPlacement();
        
        // Efecto visual de click
        mapDiv.style.transform = 'translateY(-2px) scale(1.02)';
        setTimeout(() => {
            if (mapDiv.style) {
                mapDiv.style.transform = '';
            }
        }, 200);
    }

    function handleMapHover() {
        // Cambiar cursor
        mapDiv.style.cursor = 'pointer';
        
        // Efecto de hover más pronunciado
        if (mapText) {
            mapText.style.transform = 'scale(1.05)';
            mapText.style.color = 'var(--primary)';
        }
    }

    function handleMapLeave() {
        mapDiv.style.cursor = 'default';
        
        if (mapText) {
            mapText.style.transform = '';
            mapText.style.color = '';
        }
    }

    function handleKeyboardNavigation(e) {
        // Enter para confirmar ubicación
        if (e.key === 'Enter' && e.target !== submitBtn) {
            e.preventDefault();
            if (submitBtn) {
                submitBtn.focus();
            }
        }
        
        // Espacio en el mapa para simular click
        if (e.key === ' ' && document.activeElement === mapDiv) {
            e.preventDefault();
            handleMapClick();
        }
    }

    // ===== SIMULACIONES =====
    function simulateMapLoading() {
        if (!mapText) return;
        
        const loadingTexts = [
            'Cargando mapa...',
            'Localizando dirección...',
            'Verificando ubicación...',
            'Aquí iría el mapa'
        ];
        
        let currentIndex = 0;
        
        const interval = setInterval(() => {
            if (currentIndex < loadingTexts.length - 1) {
                mapText.textContent = loadingTexts[currentIndex];
                currentIndex++;
            } else {
                clearInterval(interval);
                // Hacer el mapa interactivo
                if (mapDiv) {
                    mapDiv.setAttribute('tabindex', '0');
                    mapDiv.style.cursor = 'pointer';
                }
            }
        }, 800);
    }

    function simulatePinPlacement() {
        if (!mapDiv) return;
        
        // Crear pin temporal
        const pin = document.createElement('div');
        pin.innerHTML = '📍';
        pin.style.cssText = `
            position: absolute;
            font-size: 24px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            z-index: 10;
            animation: pinDrop 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        `;
        
        mapDiv.style.position = 'relative';
        mapDiv.appendChild(pin);
        
        // Crear ondas alrededor del pin
        setTimeout(() => {
            createMapRipples(pin);
        }, 300);
        
        // Remover pin después de un tiempo
        setTimeout(() => {
            if (pin.parentNode) {
                pin.remove();
            }
        }, 3000);
        
        // Feedback al usuario
        showNotification('¡Pin actualizado! La ubicación se ve correcta', 'success');
    }

    function showVerificationProcess() {
        // Deshabilitar botón y mostrar proceso
        showLoadingState();
        
        const steps = [
            { text: 'Verificando ubicación...', delay: 0 },
            { text: 'Validando dirección...', delay: 1500 },
            { text: 'Confirmando coordenadas...', delay: 3000 },
            { text: 'Procesando información...', delay: 4500 }
        ];
        
        steps.forEach((step, index) => {
            setTimeout(() => {
                if (submitBtn) {
                    submitBtn.innerHTML = `
                        <span>${step.text}</span>
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
                
                // Mostrar progreso en el mapa
                if (mapText) {
                    mapText.textContent = step.text;
                }
            }, step.delay);
        });
        
        // Completar verificación
        setTimeout(() => {
            showNotification('¡Ubicación verificada correctamente!', 'success');
            
            // Crear efecto de confeti
            createSuccessConfetti();
            
            // Enviar formulario real después de la animación
            setTimeout(() => {
                form.submit();
            }, 1000);
        }, 6000);
    }

    // ===== EFECTOS VISUALES =====
    function createRippleEffect(element) {
        const ripple = document.createElement('div');
        const rect = element.getBoundingClientRect();
        
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
            width: 20px;
            height: 20px;
            left: 50%;
            top: 50%;
            z-index: 1;
        `;
        
        element.style.position = 'relative';
        element.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    }

    function createMapRipples(centerElement) {
        const mapRect = mapDiv.getBoundingClientRect();
        const centerRect = centerElement.getBoundingClientRect();
        
        for (let i = 0; i < 3; i++) {
            setTimeout(() => {
                const ripple = document.createElement('div');
                ripple.style.cssText = `
                    position: absolute;
                    border: 2px solid var(--primary, #8B5FBF);
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    animation: mapRipple 1.5s ease-out forwards;
                    pointer-events: none;
                    z-index: 5;
                `;
                
                mapDiv.appendChild(ripple);
                
                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.remove();
                    }
                }, 1500);
            }, i * 200);
        }
    }

    function createSuccessConfetti() {
        const colors = ['#8B5FBF', '#A67FCF', '#E8D5F2', '#FFD700', '#FF69B4'];
        
        for (let i = 0; i < 20; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    width: 8px;
                    height: 8px;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    top: 50%;
                    left: 50%;
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 9999;
                    animation: confettiFall 2s ease-out forwards;
                `;
                
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 2000);
            }, i * 50);
        }
    }

    // ===== ESTADOS DE CARGA =====
    function showLoadingState() {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.8';
            form.classList.add('loading');
        }
        
        if (mapDiv) {
            mapDiv.style.pointerEvents = 'none';
            mapDiv.style.opacity = '0.7';
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
        
        const size = Math.random() * 6 + 8;
        const left = Math.random() * 100;
        const duration = Math.random() * 8 + 18;
        
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
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }
        
        @keyframes pinDrop {
            0% { transform: translate(-50%, -50%) scale(0) rotate(-180deg); }
            100% { transform: translate(-50%, -50%) scale(1) rotate(0deg); }
        }
        
        @keyframes mapRipple {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(4); opacity: 0; }
        }
        
        @keyframes confettiFall {
            0% { 
                transform: translate(-50%, -50%) rotate(0deg);
                opacity: 1;
            }
            100% { 
                transform: translate(-50%, -50%) 
                         translateX(${Math.random() * 200 - 100}px) 
                         translateY(${Math.random() * 200 + 100}px) 
                         rotate(${Math.random() * 360}deg);
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
        
        /* Hacer el mapa focusable */
        div[style*="width: 100%"][style*="max-width: 600px"][style*="height: 300px"]:focus {
            outline: 3px solid var(--primary, #8B5FBF);
            outline-offset: 3px;
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);

    console.log('🎉 Formulario de verificación con efectos interactivos cargado');
});