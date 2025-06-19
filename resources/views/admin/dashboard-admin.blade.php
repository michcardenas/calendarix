
@auth
    @role('Administrador')

        {{-- CSS específico del dashboard admin --}}
      
        <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
        

<body>

{{-- DASHBOARD CONTENT --}}
<div class="admin-dashboard">
    <div class="admin-main-layout">
        
        {{-- SIDEBAR ÁRBOL DE NAVEGACIÓN --}}
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h3 class="admin-sidebar-title">
                    <i class="fas fa-sitemap"></i>
                    Panel de Administración
                </h3>
            </div>
            
            <nav class="admin-sidebar-nav">
                {{-- Gestión de Contenido --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-category">
                        <i class="fas fa-edit admin-nav-category-icon"></i>
                        <span class="admin-nav-category-title">Gestión de Contenido</span>
                        <i class="fas fa-chevron-down admin-nav-toggle"></i>
                    </div>
                    <ul class="admin-nav-items">
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="seo">
                                <i class="fas fa-search-plus admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Gestión SEO</span>
                                    <small>Meta tags, keywords, sitemap</small>
                                </div>
                            </a>
                        </li>
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="page-editor">
                                <i class="fas fa-palette admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Editor de Páginas</span>
                                    <small>Personalizar contenido</small>
                                </div>
                            </a>
                        </li>
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="media">
                                <i class="fas fa-images admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Biblioteca de Medios</span>
                                    <small>Imágenes y archivos</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Gestión de Usuarios --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-category">
                        <i class="fas fa-users-cog admin-nav-category-icon"></i>
                        <span class="admin-nav-category-title">Gestión de Usuarios</span>
                        <i class="fas fa-chevron-down admin-nav-toggle"></i>
                    </div>
                    <ul class="admin-nav-items">
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="users">
                                <i class="fas fa-users admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Gestionar Usuarios</span>
                                    <small>Ver y editar usuarios</small>
                                </div>
                            </a>
                        </li>
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="roles">
                                <i class="fas fa-user-shield admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Roles y Permisos</span>
                                    <small>Configurar accesos</small>
                                </div>
                            </a>
                        </li>
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="businesses">
                                <i class="fas fa-store admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Gestionar Empresas</span>
                                    <small>Aprobar y supervisar</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Reportes y Análisis --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-category">
                        <i class="fas fa-chart-bar admin-nav-category-icon"></i>
                        <span class="admin-nav-category-title">Reportes y Análisis</span>
                        <i class="fas fa-chevron-down admin-nav-toggle"></i>
                    </div>
                    <ul class="admin-nav-items">
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="analytics">
                                <i class="fas fa-chart-line admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Análisis Avanzado</span>
                                    <small>Métricas detalladas</small>
                                </div>
                            </a>
                        </li>
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="reports">
                                <i class="fas fa-file-chart-pie admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Reportes</span>
                                    <small>Exportar datos</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Sistema --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-category">
                        <i class="fas fa-cogs admin-nav-category-icon"></i>
                        <span class="admin-nav-category-title">Sistema</span>
                        <i class="fas fa-chevron-down admin-nav-toggle"></i>
                    </div>
                    <ul class="admin-nav-items">
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="settings">
                                <i class="fas fa-sliders-h admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Configuración</span>
                                    <small>Ajustes generales</small>
                                </div>
                            </a>
                        </li>
                        <li class="admin-nav-item">
                            <a href="#" class="admin-nav-link" data-demo="maintenance">
                                <i class="fas fa-tools admin-nav-icon"></i>
                                <div class="admin-nav-content">
                                    <span>Mantenimiento</span>
                                    <small>Respaldos y logs</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        {{-- DASHBOARD PRINCIPAL --}}
        <div class="admin-container">
            
            {{-- HEADER --}}
            <header class="admin-header">
                <div>
                    <h1>
                        <i class="fas fa-calendar-check icon"></i>
                        Dashboard Administrador
                    </h1>
                    <div class="admin-date">
                        <i class="fas fa-clock"></i>
                        {{ date('l, d F Y') }} - {{ date('H:i') }}
                    </div>
                </div>
            </header>

            {{-- STATS CARDS --}}
            <section class="admin-stats">
                <div class="admin-stat-card">
                    <div class="admin-stat-icon success">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="admin-stat-value">247</div>
                    <div class="admin-stat-label">Citas Hoy</div>
                    <div class="admin-stat-trend positive">



                    
                        <i class="fas fa-arrow-up"></i> +12%
                    </div>
                </div>

                <div class="admin-stat-card">
                    <div class="admin-stat-icon primary">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="admin-stat-value">156</div>
                    <div class="admin-stat-label">Empresas Activas</div>
                    <div class="admin-stat-trend positive">
                        <i class="fas fa-arrow-up"></i> +8%
                    </div>
                </div>

                <div class="admin-stat-card">
                    <div class="admin-stat-icon warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="admin-stat-value">3,420</div>
                    <div class="admin-stat-label">Usuarios Registrados</div>
                    <div class="admin-stat-trend positive">
                        <i class="fas fa-arrow-up"></i> +23%
                    </div>
                </div>

                <div class="admin-stat-card">
                    <div class="admin-stat-icon danger">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="admin-stat-value">$89,340</div>
                    <div class="admin-stat-label">Ingresos del Mes</div>
                    <div class="admin-stat-trend negative">
                        <i class="fas fa-arrow-down"></i> -3%
                    </div>
                </div>
            </section>

            {{-- CHARTS --}}
            <section class="admin-charts">
                <div class="admin-chart-card">
                    <div class="admin-chart-header">
                        <h3 class="admin-chart-title">Citas por Día (Última Semana)</h3>
                        <p class="admin-chart-subtitle">Comparativo con semana anterior</p>
                    </div>
                    <div class="admin-chart-container">
                        <canvas id="admin-line-chart"></canvas>
                    </div>
                </div>

                <div class="admin-chart-card">
                    <div class="admin-chart-header">
                        <h3 class="admin-chart-title">Tipos de Empresas</h3>
                        <p class="admin-chart-subtitle">Distribución por categoría</p>
                    </div>
                    <div class="admin-chart-container">
                        <canvas id="admin-doughnut-chart"></canvas>
                    </div>
                </div>
            </section>

            {{-- RECENT APPOINTMENTS --}}
            <section class="admin-recent">
                <div class="admin-recent-header">
                    <h3 class="admin-recent-title">
                        <i class="fas fa-clock"></i>
                        Citas Recientes
                    </h3>
                    <a href="#" class="admin-view-all">Ver todas →</a>
                </div>
                
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="admin-client">
                                    <div class="admin-avatar">MG</div>
                                    <span>María González</span>
                                </div>
                            </td>
                            <td>Salón Bella Vista</td>
                            <td>Corte + Peinado</td>
                            <td>Hoy, 14:30</td>
                            <td><span class="admin-status confirmed">Confirmada</span></td>
                            <td>$45.000</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="admin-client">
                                    <div class="admin-avatar">CR</div>
                                    <span>Carlos Rodríguez</span>
                                </div>
                            </td>
                            <td>Spa Relajación Total</td>
                            <td>Masaje Relajante</td>
                            <td>Hoy, 16:00</td>
                            <td><span class="admin-status pending">Pendiente</span></td>
                            <td>$80.000</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="admin-client">
                                    <div class="admin-avatar">AM</div>
                                    <span>Ana Martínez</span>
                                </div>
                            </td>
                            <td>Nails Studio Pro</td>
                            <td>Manicure Gel</td>
                            <td>Hoy, 11:15</td>
                            <td><span class="admin-status confirmed">Confirmada</span></td>
                            <td>$35.000</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="admin-client">
                                    <div class="admin-avatar">LT</div>
                                    <span>Luis Torres</span>
                                </div>
                            </td>
                            <td>Barbería Clásica</td>
                            <td>Corte + Barba</td>
                            <td>Ayer, 18:45</td>
                            <td><span class="admin-status cancelled">Cancelada</span></td>
                            <td>$25.000</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="admin-client">
                                    <div class="admin-avatar">SV</div>
                                    <span>Sofia Vargas</span>
                                </div>
                            </td>
                            <td>Centro Estético Bella</td>
                            <td>Facial Hidratante</td>
                            <td>Ayer, 10:30</td>
                            <td><span class="admin-status confirmed">Confirmada</span></td>
                            <td>$120.000</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

{{-- JavaScript Admin Dashboard --}}
<script src="{{ asset('js/admin-dashboard.js') }}"></script>

</body>

@endrole
@endauth