
/**
 * ===============================================
 * INICIALIZACIÓN PRINCIPAL
 * ===============================================
 */

// Inicialización cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    clxInitialize();
});

function clxInitialize() {
    console.log('🚀 Inicializando Calendarix Dashboard...');
    
    clxLoadAppointments();
    clxLoadRecommendations();
    clxSetupEventListeners();
    clxUpdateStats();
    clxAddInteractiveEffects();
    
    console.log('✅ Dashboard Calendarix cargado exitosamente');
}

/**
 * ===============================================
 * CARGA DE DATOS DINÁMICOS
 * ===============================================
 */

function clxLoadAppointments() {
  const container = document.getElementById('clx-appointments-list');
  if (!container) {
    console.warn('⚠️ Contenedor de citas no encontrado');
    return;
  }

  container.innerHTML = '';

  const list = (window.clxData && Array.isArray(window.clxData.appointments))
    ? window.clxData.appointments : [];

  const labels = {
    pendiente: 'Pendiente',
    confirmada: 'Confirmada',
    cancelada: 'Cancelada',
    completada: 'Completada',
  };

  list.forEach(appointment => {
    const raw = (appointment.status ?? '').toString().toLowerCase();
    const status = (raw === 'pendiente' || raw === 'confirmada' || raw === 'cancelada' || raw === 'completada')
      ? raw : 'pendiente';

    // 🎯 Tipo de cita: 'cliente' (yo agendé) o 'negocio' (en mi negocio)
    const tipo = appointment.type ?? 'cliente';
    const esNegocio = tipo === 'negocio';

    // Badge de tipo
    const tipoBadge = esNegocio
      ? '<span class="clx-appointment-type-badge" style="background: #6366f1; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-right: 6px;">Mi Negocio</span>'
      : '';

    // Info adicional para citas de negocio
    const clienteInfo = esNegocio && appointment.client
      ? `<div class="clx-appointment-client" style="font-size: 12px; color: #64748b;">Cliente: ${appointment.client}</div>`
      : '';

    const appointmentEl = document.createElement('div');
    appointmentEl.className = 'clx-appointment-item';
    appointmentEl.innerHTML = `
      <div class="clx-appointment-time">${appointment.time ?? ''}</div>
      <div class="clx-appointment-info">
        <div class="clx-appointment-service">${tipoBadge}${appointment.service ?? 'Cita'}</div>
        <div class="clx-appointment-business">${appointment.business ?? '—'}</div>
        ${clienteInfo}
      </div>
      <div class="clx-appointment-status clx-status-es-${status}">
        ${labels[status]}
      </div>
    `;

    // Efecto de entrada suave
    appointmentEl.style.opacity = '0';
    appointmentEl.style.transform = 'translateY(20px)';
    container.appendChild(appointmentEl);

    // Animar entrada
    requestAnimationFrame(() => {
      appointmentEl.style.transition = 'all 0.5s ease';
      appointmentEl.style.opacity = '1';
      appointmentEl.style.transform = 'translateY(0)';
    });
  });
}



function clxLoadRecommendations() {
    const container = document.getElementById('clx-recommendations-list');
    if (!container) {
        console.warn('⚠️ Contenedor de recomendaciones no encontrado');
        return;
    }
    
    container.innerHTML = '';

    clxData.recommendations.forEach((business, index) => {
        const businessEl = document.createElement('div');
        businessEl.className = 'clx-appointment-item';
        businessEl.innerHTML = `
            <div class="clx-appointment-info">
                <div class="clx-appointment-service">${business.name}</div>
                <div class="clx-appointment-business">
                    ${business.service} • ${business.rating} ⭐ • ${business.distance}
                </div>
            </div>
<button class="clx-btn clx-btn-ghost" style="padding: 0.5rem 1rem; font-size: 0.875rem;"
        onclick="clxViewBusiness(${business.id}, '${business.slug ?? ''}')">
  <i class="fas fa-eye"></i>
  Ver
</button>
        `;
        
        // Agregar efecto de entrada escalonado
        businessEl.style.opacity = '0';
        businessEl.style.transform = 'translateX(30px)';
        container.appendChild(businessEl);
        
        setTimeout(() => {
            businessEl.style.transition = 'all 0.5s ease';
            businessEl.style.opacity = '1';
            businessEl.style.transform = 'translateX(0)';
        }, 150 * index);
    });
}

function clxUpdateStats() {
    const statAppointments = document.getElementById('clx-stat-appointments');
    const statFavorites = document.getElementById('clx-stat-favorites');
    const statPending = document.getElementById('clx-stat-pending');
    const pendingCount = document.getElementById('clx-pending-count');

    if (statAppointments) {
        clxAnimateNumber(statAppointments, clxData.appointments.length + 5);
    }
    
    if (statFavorites) {
        clxAnimateNumber(statFavorites, clxData.recommendations.length);
    }
    
    const pendingAppointments = clxData.appointments.filter(a => a.status === 'pending').length;
    
    if (statPending) {
        clxAnimateNumber(statPending, pendingAppointments);
    }
    
    if (pendingCount) {
        pendingCount.textContent = pendingAppointments + ' citas';
    }
}

/**
 * ===============================================
 * CONFIGURACIÓN DE EVENTOS
 * ===============================================
 */

function clxSetupEventListeners() {
    // Navegación del sidebar
    // Navegación del sidebar
document.querySelectorAll('.clx-nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        const page = this.dataset.clxPage;

        if (page) {
            e.preventDefault();

            // Remover clase active de todos los links
            document.querySelectorAll('.clx-nav-link').forEach(l => l.classList.remove('active'));

            // Agregar clase active al link clickeado
            this.classList.add('active');

            console.log(`📍 Navegando a: ${page}`);
            clxNavigateTo(page);
        }
        // ⚠️ Si no hay data-clx-page, dejar que el link funcione normalmente
    });
});
;

    // Botones de acción rápida
    const btnBook = document.getElementById('clx-btn-book');
    const btnSearch = document.getElementById('clx-btn-search');

    if (btnBook) {
        btnBook.addEventListener('click', function() {
            console.log('📅 Abriendo modal de agendamiento');
            clxShowBookingModal();
        });
    }

    if (btnSearch) {
        btnSearch.addEventListener('click', function() {
            console.log('🔍 Abriendo búsqueda de servicios');
            clxShowSearchModal();
        });
    }

    // Eventos de teclado para navegación
    document.addEventListener('keydown', function(e) {
        // Ctrl + K para búsqueda rápida
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            clxShowSearchModal();
        }
        
        // Ctrl + N para nueva cita
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            clxShowBookingModal();
        }
    });
}

/**
 * ===============================================
 * EFECTOS INTERACTIVOS
 * ===============================================
 */

function clxAddInteractiveEffects() {
    // Efectos de hover suaves para las tarjetas de estadísticas
    document.querySelectorAll('.clx-stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-6px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Efectos de ripple para los botones
    document.querySelectorAll('.clx-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('clx-ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.remove();
                }
            }, 600);
        });
    });

    // Efecto parallax sutil en las manchas de fondo
    if (window.DeviceMotionEvent) {
        window.addEventListener('deviceorientation', clxHandleDeviceOrientation);
    } else {
        document.addEventListener('mousemove', clxHandleMouseParallax);
    }
}

/**
 * ===============================================
 * NAVEGACIÓN Y MODALES
 * ===============================================
 */


function clxShowBookingModal() {
    // En producción, aquí abririás un modal real
    clxShowToast('Función de agendamiento en desarrollo...', 'info');
    
    // Ejemplo de integración con modal de Bootstrap:
    // $('#bookingModal').modal('show');
}

function clxShowSearchModal() {
    // En producción, aquí abririas un modal de búsqueda
    clxShowToast('Función de búsqueda en desarrollo...', 'info');
    
    // Ejemplo de integración con modal de búsqueda:
    // $('#searchModal').modal('show');
}

function clxViewBusiness(businessId) {
    console.log(`👁️ Viendo negocio ID: ${businessId}`);
    clxShowToast(`Cargando información del negocio...`, 'info');
    
    // En producción, redirigir a la página del negocio
    // window.location.href = `/business/${businessId}`;
}

/**
 * ===============================================
 * SISTEMA DE NOTIFICACIONES TOAST
 * ===============================================
 */

function clxShowToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `clx-toast clx-toast-${type}`;
    toast.innerHTML = `
        <div class="clx-toast-content">
            <i class="fas ${clxGetToastIcon(type)}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        toast.style.animation = 'clx-slide-out 0.3s ease-in forwards';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

function clxGetToastIcon(type) {
    const icons = {
        'success': 'fa-check-circle',
        'info': 'fa-info-circle',
        'warning': 'fa-exclamation-triangle',
        'error': 'fa-times-circle'
    };
    return icons[type] || icons['info'];
}

/**
 * ===============================================
 * EFECTOS VISUALES Y ANIMACIONES
 * ===============================================
 */

function clxAnimateNumber(element, targetNumber) {
    const startNumber = 0;
    const duration = 1000; // 1 segundo
    const startTime = performance.now();
    
    function animate(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Función de easing suave
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const currentNumber = Math.floor(startNumber + (targetNumber - startNumber) * easeOutQuart);
        
        element.textContent = currentNumber;
        
        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            element.textContent = targetNumber;
        }
    }
    
    requestAnimationFrame(animate);
}

function clxHandleMouseParallax(e) {
    const shapes = document.querySelectorAll('.clx-shape');
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;
    
    shapes.forEach((shape, index) => {
        const speed = (index + 1) * 0.5;
        const xMove = (x - 0.5) * speed;
        const yMove = (y - 0.5) * speed;
        
        shape.style.transform += ` translate(${xMove}px, ${yMove}px)`;
    });
}

function clxHandleDeviceOrientation(e) {
    const shapes = document.querySelectorAll('.clx-shape');
    const tiltX = e.beta || 0; // -90 a 90
    const tiltY = e.gamma || 0; // -180 a 180
    
    shapes.forEach((shape, index) => {
        const speed = (index + 1) * 0.1;
        const xMove = (tiltY / 180) * speed * 10;
        const yMove = (tiltX / 90) * speed * 10;
        
        shape.style.transform += ` translate(${xMove}px, ${yMove}px)`;
    });
}

/**
 * ===============================================
 * FUNCIONES DE DESARROLLO Y DEBUG
 * ===============================================
 */

function clxRefreshData() {
    console.log('🔄 Refrescando datos del dashboard...');
    
    // Simular nuevas citas desde el servidor
    const newAppointment = {
        id: Date.now(),
        time: '16:00',
        service: 'Tratamiento facial',
        business: 'Centro de Belleza',
        status: 'pending',
        date: '2025-06-23'
    };

    clxData.appointments.push(newAppointment);
    clxLoadAppointments();
    clxUpdateStats();
    
    clxShowToast('Datos actualizados correctamente', 'success');
}

function clxToggleTheme() {
    // Función futura para cambio de tema
    console.log('🎨 Cambio de tema próximamente...');
    clxShowToast('Función de tema en desarrollo', 'info');
}

/**
 * ===============================================
 * AUTO-REFRESH Y FUNCIONES PERIÓDICAS
 * ===============================================
 */

// Auto-refresh opcional cada 5 minutos
setInterval(function() {
    console.log('🔄 Verificando actualizaciones automáticas...');
    
    // En producción, hacer petición AJAX a Laravel:
    // fetch('/api/dashboard/refresh')
    //     .then(response => response.json())
    //     .then(data => {
    //         clxData.appointments = data.appointments;
    //         clxData.recommendations = data.recommendations;
    //         clxLoadAppointments();
    //         clxLoadRecommendations();
    //         clxUpdateStats();
    //     })
    //     .catch(error => console.error('Error al actualizar datos:', error));
    
}, 300000); // 5 minutos

/**
 * ===============================================
 * EXPOSICIÓN DE FUNCIONES PARA DEBUG
 * ===============================================
 */

// Exposar funciones globalmente para debugging en desarrollo
window.clxDebug = {
    refreshData: clxRefreshData,
    showToast: clxShowToast,
    toggleTheme: clxToggleTheme,
    data: clxData,
    version: '1.0.0'
};

// Log de inicialización
console.log(`
🎉 Calendarix Dashboard v${window.clxDebug.version}
📋 Funciones de debug disponibles en: window.clxDebug
⌨️  Atajos de teclado:
   • Ctrl+K: Búsqueda rápida
   • Ctrl+N: Nueva cita
`);

/**
 * ===============================================
 * MANEJO DE ERRORES GLOBALES
 * ===============================================
 */

window.addEventListener('error', function(e) {
    console.error('❌ Error en Calendarix Dashboard:', e.error);
    clxShowToast('Ha ocurrido un error inesperado', 'error');
});

// Manejo de promesas rechazadas
window.addEventListener('unhandledrejection', function(e) {
    console.error('❌ Promise rechazada en Calendarix:', e.reason);
    clxShowToast('Error de conexión, reintentando...', 'warning');
});