// public/js/empresa/configuracion-empresa.js

document.addEventListener('DOMContentLoaded', function() {
    // Manejo de pestañas
    initTabs();
    
    // Manejo de tarjetas de configuración
    initConfigCards();
    
    // Animaciones de entrada
    initAnimations();
});

// Inicializar sistema de pestañas
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remover clase active de todos los botones y contenidos
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Agregar clase active al botón clickeado y su contenido correspondiente
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
            
            // Animación suave
            animateTabTransition(targetTab);
        });
    });
}

// Animación de transición entre pestañas
function animateTabTransition(tabId) {
    const targetContent = document.getElementById(tabId);
    const cards = targetContent.querySelectorAll('.config-card');
    
    // Resetear animaciones
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
    });
    
    // Animar cada tarjeta con delay escalonado
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Inicializar tarjetas de configuración
function initConfigCards() {
    const configCards = document.querySelectorAll('.config-card');

    configCards.forEach(card => {
        card.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            handleCardClick(section, this);
        });

        // Efecto hover mejorado
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Manejar click en tarjetas de configuración
function handleCardClick(section, cardElement) {
    // Efecto de click
    cardElement.style.transform = 'scale(0.98)';
    setTimeout(() => {
        cardElement.style.transform = 'translateY(-5px) scale(1.02)';
    }, 150);

    // Aquí puedes agregar la lógica específica para cada sección
    switch(section) {
        case 'negocio':
            handleNegocioConfig();
            break;
        case 'citas':
            handleCitasConfig();
            break;
        case 'ventas':
            handleVentasConfig();
            break;
        case 'facturacion':
            handleFacturacionConfig();
            break;
        case 'equipo':
            handleEquipoConfig();
            break;
        case 'formularios':
            handleFormulariosConfig();
            break;
        case 'pagos':
            handlePagosConfig();
            break;
        case 'website':
            handleWebsiteConfig();
            break;
        case 'redes':
            handleRedesConfig();
            break;
        case 'promociones':
            handlePromocionesConfig();
            break;
        case 'email-marketing':
            handleEmailMarketingConfig();
            break;
        case 'notificaciones':
            handleNotificacionesConfig();
            break;
        case 'integraciones':
            handleIntegracionesConfig();
            break;
        default:
            console.log('Sección no reconocida:', section);
    }
}

// Suponiendo que ya tienes esta variable en JS
const empresaId = window.empresaId || 1; // ⚠️ Ajusta el fallback si es necesario

function handleNegocioConfig() {
    showNotification('Abriendo configuración del negocio...', 'info');
    window.location.href = `/empresa/${empresaId}/configuracion/negocio`;
}

function handleCitasConfig() {
    showNotification('Abriendo gestión de citas...', 'info');
    window.location.href = `/empresa/${empresaId}/configuracion/citas`;
}

function handleVentasConfig() {
    showNotification('Abriendo configuración de ventas...', 'info');
    window.location.href = `/empresa/${empresaId}/configuracion/ventas`;
}

function handleFacturacionConfig() {
    showNotification('Abriendo configuración de facturación...', 'info');
    window.location.href = `/empresa/${empresaId}/configuracion/facturacion`;
}

function handleEquipoConfig() {
    showNotification('Abriendo gestión de equipo...', 'info');
    window.location.href = `/empresa/${empresaId}/configuracion/equipo`;
}

function handleFormulariosConfig() {
    showNotification('Abriendo configuración de formularios...', 'info');
    window.location.href = `/empresa/${empresaId}/configuracion/formularios`;
}

function handlePagosConfig() {
    showNotification('Abriendo configuración de pagos...', 'info');
    window.location.href = `/empresa/${empresaId}/configuracion/pagos`;
}

function handleWebsiteConfig() {
    showNotification('Abriendo configuración del sitio web...', 'info');
    // ⚠️ Ajustar si existe esa ruta
    window.location.href = `/empresa/${empresaId}/configuracion/website`;
}

function handleRedesConfig() {
    showNotification('Abriendo configuración de redes sociales...', 'info');
    // ⚠️ Ajustar si existe esa ruta
    window.location.href = `/empresa/${empresaId}/configuracion/redes`;
}

function handlePromocionesConfig() {
    showNotification('Abriendo configuración de promociones...', 'info');
    // ⚠️ Ajustar si existe esa ruta
    window.location.href = `/empresa/${empresaId}/configuracion/promociones`;
}

function handleEmailMarketingConfig() {
    showNotification('Abriendo configuración de email marketing...', 'info');
    // ⚠️ Ajustar si existe esa ruta
    window.location.href = `/empresa/${empresaId}/configuracion/email-marketing`;
}

function handleNotificacionesConfig() {
    showNotification('Abriendo configuración de notificaciones...', 'info');
    // ⚠️ Ajustar si existe esa ruta
    window.location.href = `/empresa/${empresaId}/configuracion/notificaciones`;
}

function handleIntegracionesConfig() {
    showNotification('Abriendo configuración de integraciones...', 'info');
    // ⚠️ Ajustar si existe esa ruta
    window.location.href = `/empresa/${empresaId}/configuracion/integraciones`;
}


// Sistema de notificaciones
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">&times;</button>
    `;

    // Estilos de la notificación
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getNotificationColor(type)};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        transform: translateX(100%);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-width: 300px;
    `;

    // Agregar al DOM
    document.body.appendChild(notification);

    // Animar entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Manejar cierre
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        removeNotification(notification);
    });

    // Auto-remover después de 3 segundos
    setTimeout(() => {
        removeNotification(notification);
    }, 3000);
}

function removeNotification(notification) {
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

function getNotificationIcon(type) {
    const icons = {
        'info': 'info-circle',
        'success': 'check-circle',
        'warning': 'exclamation-triangle',
        'error': 'times-circle'
    };
    return icons[type] || 'info-circle';
}

function getNotificationColor(type) {
    const colors = {
        'info': 'linear-gradient(135deg, #667eea, #764ba2)',
        'success': 'linear-gradient(135deg, #48bb78, #38a169)',
        'warning': 'linear-gradient(135deg, #ed8936, #dd6b20)',
        'error': 'linear-gradient(135deg, #e53e3e, #c53030)'
    };
    return colors[type] || colors['info'];
}

// Inicializar animaciones de entrada
function initAnimations() {
    const cards = document.querySelectorAll('.config-card');
    
    // Animar tarjetas al cargar la página
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Función auxiliar para debugging
function debugConfigSection(section) {
    console.log('Configuración clickeada:', section);
    console.log('Timestamp:', new Date().toISOString());
}