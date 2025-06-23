// public/js/dashboard.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS DEL DOM =====
    const sidebar = document.querySelector('.sidebar');
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    const statsCards = document.querySelectorAll('.stats .card');
    const mainCard = document.querySelector('.content > .card');
    const statNumbers = document.querySelectorAll('.stats .card p');

    // ===== INICIALIZACIÓN =====
    initializeDashboard();

    function initializeDashboard() {
        setupEventListeners();
        startParticleAnimation();
        animateCounters();
        
        // Mensaje de bienvenida después de que todo esté cargado
        setTimeout(() => {
            showWelcomeNotification();
        }, 1500);
        
        console.log('✨ Dashboard Calendarix inicializado');
    }

    function setupEventListeners() {
        // Eventos del sidebar
        sidebarLinks.forEach((link, index) => {
            link.addEventListener('click', (e) => handleSidebarClick(e, link, index));
            link.addEventListener('mouseenter', (e) => handleSidebarHover(e, link));
            link.addEventListener('mouseleave', (e) => handleSidebarLeave(e, link));
        });

        // Eventos de las tarjetas de estadísticas
        statsCards.forEach((card, index) => {
            card.addEventListener('click', (e) => handleStatsCardClick(e, card, index));
            card.addEventListener('mouseenter', (e) => handleCardHover(e, card));
            card.addEventListener('mouseleave', (e) => handleCardLeave(e, card));
        });

        // Eventos de la tarjeta principal
        if (mainCard) {
            mainCard.addEventListener('click', (e) => handleMainCardClick(e, mainCard));
            mainCard.addEventListener('mouseenter', (e) => handleCardHover(e, mainCard));
            mainCard.addEventListener('mouseleave', (e) => handleCardLeave(e, mainCard));
        }

        // Navegación con teclado
        document.addEventListener('keydown', handleKeyboardNavigation);

        // Responsive: toggle sidebar en móvil
        setupMobileInteractions();
    }

    // ===== MANEJO DE EVENTOS DEL SIDEBAR =====
    function handleSidebarClick(e, link, index) {
        e.preventDefault();
        
        // Remover clase active de todos los enlaces
        sidebarLinks.forEach(l => l.classList.remove('active'));
        
        // Agregar clase active al enlace clickeado
        link.classList.add('active');
        
        // Efectos visuales
        createRippleEffect(link);
        
        // Vibración en móviles
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
        
        // Simular navegación
        const section = link.textContent.trim();
        showNotification(`Navegando a: ${section}`, 'info');
        
        console.log(`Navegando a: ${section}`);
    }

    function handleSidebarHover(e, link) {
        // Efecto de brillo en el icono
        const icon = link.querySelector('i');
        if (icon) {
            icon.style.transform = 'scale(1.2) rotate(5deg)';
            icon.style.textShadow = '0 0 10px var(--primary-light)';
        }
        
        // Efecto de onda
        createHoverWave(link);
    }

    function handleSidebarLeave(e, link) {
        const icon = link.querySelector('i');
        if (icon) {
            icon.style.transform = '';
            icon.style.textShadow = '';
        }
    }

    // ===== MANEJO DE EVENTOS DE TARJETAS =====
    function handleStatsCardClick(e, card, index) {
        // Efecto de click
        card.style.transform = 'translateY(-2px) scale(0.98)';
        setTimeout(() => {
            if (card.style) {
                card.style.transform = '';
            }
        }, 150);
        
        // Crear efecto de explosión de partículas
        createCardClickEffect(card);
        
        // Simular acción
        const title = card.querySelector('h4').textContent;
        showNotification(`Ver detalles: ${title}`, 'success');
        
        // Vibración
        if (navigator.vibrate) {
            navigator.vibrate([30, 20, 30]);
        }
    }

    function handleMainCardClick(e, card) {
        // Efecto similar pero más suave
        card.style.transform = 'translateY(-1px) scale(0.99)';
        setTimeout(() => {
            if (card.style) {
                card.style.transform = '';
            }
        }, 150);
        
        createCardClickEffect(card);
        showNotification('Ver todas las citas próximas', 'info');
    }

    function handleCardHover(e, card) {
        // Efecto de brillo más pronunciado
        card.style.boxShadow = '0 12px 40px rgba(139, 95, 191, 0.2)';
        
        // Animar el número si es una tarjeta de estadísticas
        const number = card.querySelector('p');
        if (number && card.closest('.stats')) {
            number.style.transform = 'scale(1.1)';
            number.style.textShadow = '0 0 20px var(--primary-light)';
        }
    }

    function handleCardLeave(e, card) {
        card.style.boxShadow = '';
        
        const number = card.querySelector('p');
        if (number && card.closest('.stats')) {
            number.style.transform = '';
            number.style.textShadow = '';
        }
    }

    function handleKeyboardNavigation(e) {
        // Navegación con flechas en el sidebar
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            const focusedLink = document.activeElement;
            if (focusedLink && focusedLink.closest('.sidebar')) {
                e.preventDefault();
                const links = Array.from(sidebarLinks);
                const currentIndex = links.indexOf(focusedLink);
                
                let nextIndex;
                if (e.key === 'ArrowDown') {
                    nextIndex = (currentIndex + 1) % links.length;
                } else {
                    nextIndex = (currentIndex - 1 + links.length) % links.length;
                }
                
                links[nextIndex].focus();
            }
        }
    }

    // ===== ANIMACIONES Y EFECTOS =====
    function animateCounters() {
        statNumbers.forEach((numberElement, index) => {
            const finalNumber = parseInt(numberElement.textContent);
            let currentNumber = 0;
            const increment = finalNumber / 30; // 30 frames para la animación
            
            const timer = setInterval(() => {
                currentNumber += increment;
                if (currentNumber >= finalNumber) {
                    currentNumber = finalNumber;
                    clearInterval(timer);
                    
                    // Efecto final
                    numberElement.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        numberElement.style.transform = '';
                    }, 200);
                }
                numberElement.textContent = Math.floor(currentNumber);
            }, 50);
            
            // Delay diferente para cada contador
            setTimeout(() => {
                // El timer ya está corriendo
            }, index * 200);
        });
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
            width: 30px;
            height: 30px;
            left: 50%;
            top: 50%;
            z-index: 10;
        `;
        
        element.style.position = 'relative';
        element.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    }

    function createHoverWave(element) {
        const wave = document.createElement('div');
        wave.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(139, 95, 191, 0.1), transparent);
            transform: translateX(-100%);
            animation: wave 0.8s ease-out;
            pointer-events: none;
            z-index: 1;
        `;
        
        element.style.position = 'relative';
        element.appendChild(wave);
        
        setTimeout(() => wave.remove(), 800);
    }

    function createCardClickEffect(card) {
        const rect = card.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        // Crear múltiples partículas
        for (let i = 0; i < 8; i++) {
            setTimeout(() => {
                createClickParticle(centerX, centerY);
            }, i * 50);
        }
    }

    function createClickParticle(x, y) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 6px;
            height: 6px;
            background: var(--primary, #8B5FBF);
            border-radius: 50%;
            left: ${x}px;
            top: ${y}px;
            pointer-events: none;
            z-index: 9999;
            animation: cardExplosion 1s ease-out forwards;
        `;
        
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 1000);
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

    function showWelcomeNotification() {
        showNotification('¡Bienvenido a tu Dashboard de Calendarix! 🎉', 'success');
    }

    // ===== INTERACCIONES MÓVILES =====
    function setupMobileInteractions() {
        // Detección de dispositivo móvil
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // Agregar comportamiento de tap mejorado
            statsCards.forEach(card => {
                card.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                });
                
                card.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
        }
        
        // Responsive: reorganizar sidebar en móvil
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                sidebar.style.flexDirection = 'row';
                sidebar.style.overflowX = 'auto';
            } else {
                sidebar.style.flexDirection = '';
                sidebar.style.overflowX = '';
            }
        });
    }

    // ===== ANIMACIÓN DE PARTÍCULAS DE FONDO =====
    function startParticleAnimation() {
        setInterval(createDynamicParticle, 3000);
    }

    function createDynamicParticle() {
        const backgroundAnimation = document.querySelector('.background-animation');
        if (!backgroundAnimation) return;
        
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 8 + 6;
        const left = Math.random() * 100;
        const duration = Math.random() * 10 + 20;
        
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
        
        @keyframes wave {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        @keyframes cardExplosion {
            0% { 
                transform: scale(1) translate(0, 0);
                opacity: 1;
            }
            100% { 
                transform: scale(0) translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px);
                opacity: 0;
            }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        /* Mejorar scrollbar para móvil */
        .sidebar::-webkit-scrollbar {
            height: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(139, 95, 191, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary, #8B5FBF);
            border-radius: 2px;
        }
        
        /* Efectos adicionales */
        .card:hover .card-icon {
            animation: bounce 0.5s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);

    console.log('🎉 Dashboard con efectos interactivos completamente cargado');
});