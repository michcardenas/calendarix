// ============================================
// TABLA DE USUARIOS - FUNCIONALIDADES JS
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
        messages: {
            deleteConfirm: '¿Estás seguro de eliminar este usuario?',
            deleteSuccess: 'Usuario eliminado correctamente',
            deleteError: 'Error al eliminar el usuario',
            loading: 'Procesando...'
        }
    };

    // ============================================
    // FUNCIONES UTILITARIAS
    // ============================================
    
    /**
     * Muestra una notificación temporal
     */
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification-temp`;
        notification.innerHTML = `<strong>${type === 'success' ? '¡Éxito!' : '¡Error!'}</strong> ${message}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            opacity: 0;
            transform: translateX(100%);
            transition: all ${config.animations.duration}ms ${config.animations.easing};
        `;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Animar salida y remover
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, config.animations.duration);
        }, 3000);
    }

    /**
     * Añade estado de carga a un botón
     */
    function setButtonLoading(button, loading = true) {
        if (loading) {
            button.classList.add('loading');
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = config.messages.loading;
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
     * Anima la eliminación de una fila
     */
    function animateRowRemoval(row) {
        return new Promise((resolve) => {
            row.style.transition = `all ${config.animations.duration}ms ${config.animations.easing}`;
            row.style.opacity = '0';
            row.style.transform = 'translateX(-100%)';
            
            setTimeout(() => {
                if (row.parentNode) {
                    row.parentNode.removeChild(row);
                }
                resolve();
            }, config.animations.duration);
        });
    }

    // ============================================
    // CONFIRMACIÓN DE ELIMINACIÓN MEJORADA
    // ============================================
    
    function initDeleteConfirmation() {
        const deleteForms = document.querySelectorAll('.delete-form');
        
        deleteForms.forEach(form => {
            const deleteButton = form.querySelector('.btn-danger');
            
            if (deleteButton) {
                // Remover el onclick anterior
                deleteButton.removeAttribute('onclick');
                
                deleteButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const userName = this.dataset.confirmMessage || config.messages.deleteConfirm;
                    
                    // Crear modal de confirmación personalizado
                    createConfirmModal(userName, () => {
                        handleDelete(form, this);
                    });
                });
            }
        });
    }

    /**
     * Crea un modal de confirmación personalizado
     */
    function createConfirmModal(message, onConfirm) {
        // Crear overlay
        const overlay = document.createElement('div');
        overlay.className = 'confirm-modal-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            transition: opacity ${config.animations.duration}ms ${config.animations.easing};
        `;

        // Crear modal
        const modal = document.createElement('div');
        modal.className = 'confirm-modal';
        modal.style.cssText = `
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            transform: scale(0.9);
            transition: transform ${config.animations.duration}ms ${config.animations.easing};
        `;

        modal.innerHTML = `
            <div class="modal-header" style="margin-bottom: 1.5rem;">
                <h3 style="margin: 0; color: #1e293b; font-size: 1.25rem;">Confirmar eliminación</h3>
            </div>
            <div class="modal-body" style="margin-bottom: 2rem;">
                <p style="margin: 0; color: #64748b; line-height: 1.5;">${message}</p>
            </div>
            <div class="modal-footer" style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button class="btn btn-secondary cancel-btn" style="background: #e2e8f0; color: #64748b; border: 1px solid #e2e8f0;">
                    Cancelar
                </button>
                <button class="btn btn-danger confirm-btn">
                    Eliminar
                </button>
            </div>
        `;

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Animar entrada
        setTimeout(() => {
            overlay.style.opacity = '1';
            modal.style.transform = 'scale(1)';
        }, 10);

        // Event listeners
        const cancelBtn = modal.querySelector('.cancel-btn');
        const confirmBtn = modal.querySelector('.confirm-btn');

        function closeModal() {
            overlay.style.opacity = '0';
            modal.style.transform = 'scale(0.9)';
            setTimeout(() => {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
            }, config.animations.duration);
        }

        cancelBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModal();
        });

        confirmBtn.addEventListener('click', () => {
            closeModal();
            onConfirm();
        });

        // Cerrar con ESC
        const handleEsc = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', handleEsc);
            }
        };
        document.addEventListener('keydown', handleEsc);
    }

    /**
     * Maneja la eliminación del usuario
     */
    async function handleDelete(form, button) {
        const row = form.closest('tr');
        
        try {
            setButtonLoading(button, true);
            
            // Simular delay para mostrar loading (remover en producción)
            await new Promise(resolve => setTimeout(resolve, 500));
            
            // Enviar formulario
            form.submit();
            
            // En una aplicación SPA, aquí harías la petición AJAX
            // const response = await fetch(form.action, {
            //     method: 'POST',
            //     body: new FormData(form),
            //     headers: {
            //         'X-Requested-With': 'XMLHttpRequest'
            //     }
            // });
            
            // if (response.ok) {
            //     await animateRowRemoval(row);
            //     showNotification(config.messages.deleteSuccess);
            // } else {
            //     throw new Error('Error en la petición');
            // }
            
        } catch (error) {
            console.error('Error al eliminar usuario:', error);
            showNotification(config.messages.deleteError, 'danger');
            setButtonLoading(button, false);
        }
    }

    // ============================================
    // CREAR MANCHITAS DINÁMICAS DE FONDO
    // ============================================
    
    function createFloatingBlobs() {
        const container = document.querySelector('.container');
        const colors = [
            'linear-gradient(45deg, #f59e0b, #ef4444)',
            'linear-gradient(135deg, #8b5cf6, #3b82f6)',
            'linear-gradient(225deg, #10b981, #06b6d4)',
            'linear-gradient(315deg, #ec4899, #8b5cf6)',
            'linear-gradient(45deg, #06b6d4, #10b981)',
            'linear-gradient(135deg, #f59e0b, #ec4899)'
        ];
        
        // Crear 6 manchitas dinámicas
        for (let i = 0; i < 6; i++) {
            const blob = document.createElement('div');
            blob.className = 'floating-blob';
            
            // Tamaños aleatorios
            const width = Math.random() * 100 + 60; // 60-160px
            const height = Math.random() * 80 + 70;  // 70-150px
            
            // Posiciones aleatorias
            const top = Math.random() * 80 + 10;    // 10-90%
            const left = Math.random() * 80 + 10;   // 10-90%
            
            // Delay de animación aleatorio
            const delay = Math.random() * -10;      // 0 a -10s
            
            blob.style.cssText = `
                width: ${width}px;
                height: ${height}px;
                background: ${colors[i]};
                top: ${top}%;
                left: ${left}%;
                animation-delay: ${delay}s;
                border-radius: ${Math.random() * 20 + 40}% ${Math.random() * 20 + 30}% ${Math.random() * 20 + 50}% ${Math.random() * 20 + 35}%;
            `;
            
            container.appendChild(blob);
        }
        
        console.log('✨ Manchitas de fondo creadas');
    }
    
    function initVisualEffects() {
        // Highlight de filas al hacer hover
        const tableRows = document.querySelectorAll('.table tbody tr');
        
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.boxShadow = '0 4px 12px rgba(59, 130, 246, 0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });

        // Efecto de pulso en botones al hacer click
        const allButtons = document.querySelectorAll('.btn');
        
        allButtons.forEach(button => {
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
                    background: rgba(255, 255, 255, 0.5);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                if (!this.style.position) {
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
    }

    // Agregar CSS para el efecto ripple
    const rippleCSS = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    
    const style = document.createElement('style');
    style.textContent = rippleCSS;
    document.head.appendChild(style);

    // ============================================
    // BÚSQUEDA EN TIEMPO REAL 
    // ============================================
    
    function initLiveSearch() {
        const searchInput = document.querySelector('.search-input');
        const tableRows = document.querySelectorAll('.table tbody tr');
        
        if (!searchInput) {
            console.warn('Campo de búsqueda no encontrado');
            return;
        }
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const shouldShow = searchTerm === '' || text.includes(searchTerm);
                
                if (shouldShow) {
                    row.style.display = '';
                    row.style.animation = 'fadeIn 0.3s ease-out';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Mostrar mensaje si no hay resultados
            updateNoResultsMessage(visibleCount, searchTerm);
        });
        
        console.log('🔍 Búsqueda en tiempo real inicializada');
    }
    
    /**
     * Muestra/oculta mensaje de "no hay resultados"
     */
    function updateNoResultsMessage(visibleCount, searchTerm) {
        const tableContainer = document.querySelector('.table-container');
        let noResultsMsg = document.querySelector('.no-results-message');
        
        if (visibleCount === 0 && searchTerm !== '') {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'no-results-message';
                noResultsMsg.style.cssText = `
                    text-align: center;
                    padding: 2rem;
                    color: var(--text-muted);
                    font-style: italic;
                    background: var(--bg-primary);
                    border: 1px solid var(--border-color);
                    border-radius: var(--border-radius);
                    margin-top: 1rem;
                `;
                tableContainer.parentNode.insertBefore(noResultsMsg, tableContainer.nextSibling);
            }
            noResultsMsg.innerHTML = `
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <p>No se encontraron usuarios que coincidan con "<strong>${searchTerm}</strong>"</p>
            `;
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }

    // ============================================
    // INICIALIZACIÓN
    // ============================================
    
    function init() {
        console.log('🚀 Inicializando funcionalidades de tabla de usuarios...');
        
        try {
            createFloatingBlobs();
            initDeleteConfirmation();
            initVisualEffects();
            initLiveSearch();
            
            console.log('✅ Tabla de usuarios inicializada correctamente');
        } catch (error) {
            console.error('❌ Error al inicializar tabla de usuarios:', error);
        }
    }

    // Ejecutar inicialización
    init();

    // ============================================
    // EXPORT PARA USO EXTERNO
    // ============================================
    
    window.UsersTable = {
        showNotification,
        setButtonLoading,
        animateRowRemoval,
        config
    };
});

// ============================================
// ESTILOS ADICIONALES PARA COMPONENTES JS
// ============================================

const additionalCSS = `
    .form-control {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #1e293b;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus {
        color: #1e293b;
        background-color: #ffffff;
        border-color: #3b82f6;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
    
    .btn-secondary {
        background-color: #e2e8f0;
        border-color: #e2e8f0;
        color: #64748b;
    }
    
    .btn-secondary:hover {
        background-color: #cbd5e1;
        border-color: #cbd5e1;
        color: #475569;
    }
`;

const additionalStyle = document.createElement('style');
additionalStyle.textContent = additionalCSS;
document.head.appendChild(additionalStyle);