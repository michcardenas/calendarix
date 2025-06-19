
/**
 * ============================================
 * ADMIN DASHBOARD JS - AGENDAMIENTOS
 * public/js/admin-dashboard.js
 * ============================================
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // 🌳 FUNCIONALIDAD DEL ÁRBOL DE NAVEGACIÓN
    initSidebarNavigation();
    
    // 📈 INICIALIZAR GRÁFICOS
    initCharts();
    
    // 🔢 ANIMACIONES DE CONTEO
    setTimeout(animateNumbers, 1000);
    
    // 🎯 FUNCIONES DEL SIDEBAR
    function initSidebarNavigation() {
        // Expandir/colapsar secciones
        document.querySelectorAll('.admin-nav-category').forEach(category => {
            category.addEventListener('click', function() {
                const section = this.closest('.admin-nav-section');
                const isExpanded = section.classList.contains('expanded');
                
                // Cerrar todas las secciones
                document.querySelectorAll('.admin-nav-section').forEach(s => {
                    s.classList.remove('expanded');
                });
                
                // Abrir la sección clickeada si no estaba expandida
                if (!isExpanded) {
                    section.classList.add('expanded');
                }
            });
        });

        // Expandir la primera sección por defecto
        const firstSection = document.querySelector('.admin-nav-section');
        if (firstSection) {
            firstSection.classList.add('expanded');
        }

        // Manejar clicks en links demo
        document.querySelectorAll('[data-demo]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const feature = this.getAttribute('data-demo');
                showDemoAlert(feature);
            });
        });
    }
    
    // 🚨 MOSTRAR ALERTAS DEMO
    function showDemoAlert(feature) {
        const messages = {
            'seo': '🔍 Gestión SEO: Configurar meta tags, keywords, generar sitemaps y conectar con Analytics.',
            'page-editor': '✏️ Editor de Páginas: Editor visual para personalizar el contenido de todas las páginas.',
            'media': '📷 Biblioteca de Medios: Gestor centralizado para subir y organizar archivos multimedia.',
            'users': '👥 Gestionar Usuarios: Panel completo para administrar usuarios registrados en la plataforma.',
            'roles': '🛡️ Roles y Permisos: Sistema granular para configurar qué puede hacer cada tipo de usuario.',
            'businesses': '🏪 Gestionar Empresas: Aprobar, supervisar y gestionar las empresas registradas.',
            'analytics': '📊 Análisis Avanzado: Métricas detalladas sobre el uso y rendimiento de la plataforma.',
            'reports': '📋 Reportes: Generar y exportar reportes personalizados en diferentes formatos.',
            'settings': '⚙️ Configuración: Ajustes generales de la plataforma, pagos, notificaciones, etc.',
            'maintenance': '🔧 Mantenimiento: Respaldos automáticos, logs del sistema y herramientas de debug.'
        };
        
        const message = messages[feature] || '🎯 Esta funcionalidad estará disponible próximamente.';
        
        // Crear toast notification
        showToast(message, 'info');
    }
    
    // 🍞 TOAST NOTIFICATIONS
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `admin-toast admin-toast-${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'info' ? 'info' : 'check'}-circle"></i>
            <span>${message}</span>
            <button class="admin-toast-close">&times;</button>
        `;
        
        toast.style.cssText = `
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-left: 4px solid ${type === 'info' ? '#6366f1' : '#10b981'};
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #1f2937;
            font-weight: 500;
            max-width: 400px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            font-size: 0.875rem;
        `;
        
        // Estilo del icono
        const icon = toast.querySelector('i');
        icon.style.cssText = `
            color: ${type === 'info' ? '#6366f1' : '#10b981'};
            font-size: 1rem;
        `;
        
        // Estilo del botón cerrar
        const closeBtn = toast.querySelector('.admin-toast-close');
        closeBtn.style.cssText = `
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            margin-left: auto;
        `;
        
        // Agregar al DOM
        document.body.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Función para cerrar
        const closeToast = () => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        };
        
        // Event listeners
        closeBtn.addEventListener('click', closeToast);
        
        // Auto cerrar después de 4 segundos
        setTimeout(closeToast, 4000);
    }
    
    // 📈 INICIALIZAR GRÁFICOS
    function initCharts() {
        // 📈 GRÁFICO DE LÍNEAS - CITAS POR DÍA
        const lineCtx = document.getElementById('admin-line-chart');
        if (lineCtx) {
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Esta Semana',
                        data: [65, 89, 112, 95, 78, 145, 167],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Semana Anterior',
                        data: [58, 76, 85, 89, 72, 123, 134],
                        borderColor: '#e5e7eb',
                        backgroundColor: 'rgba(229, 231, 235, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // 🍩 GRÁFICO CIRCULAR - TIPOS DE EMPRESAS
        const doughnutCtx = document.getElementById('admin-doughnut-chart');
        if (doughnutCtx) {
            new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Salones de Belleza', 'Spas', 'Barberías', 'Centros Médicos', 'Fitness', 'Otros'],
                    datasets: [{
                        data: [35, 20, 15, 12, 10, 8],
                        backgroundColor: [
                            '#6366f1',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                            '#6b7280'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // 🎯 ANIMACIONES DE CONTEO
    function animateNumbers() {
        document.querySelectorAll('.admin-stat-value').forEach((element, index) => {
            const text = element.textContent.replace(/[^\d]/g, '');
            const target = parseInt(text);
            if (isNaN(target)) return;
            
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                let formatted = Math.floor(current).toLocaleString();
                if (element.textContent.includes('$')) {
                    formatted = '$' + formatted;
                }
                element.textContent = formatted;
            }, 40);
        });
    }
    
    // 🎉 Mensaje de bienvenida
    setTimeout(() => {
        showToast('🚀 Dashboard cargado correctamente. Explora las opciones del menú lateral!', 'info');
    }, 1500);
});
