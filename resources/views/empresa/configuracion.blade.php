<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/empresa/configuracion-empresa.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 15; $i++)
        <div class="particle"></div>
    @endfor
</div>

<!-- Contenedor general con sidebar y contenido -->
<div class="d-flex" style="min-height: 100vh; position: relative;">
    
    {{-- Sidebar fijo --}}
    @include('empresa.partials.sidebar', ['empresa' => $empresa, 'currentPage' => 'configuracion', 'currentSubPage' => null])



 
    <a href="{{ url('dashboard') }}" class="logout-btn">Salir</a>

    <main class="flex-grow-1" style="margin-left: 240px; padding: 2rem; position: relative; z-index: 1;">

        <div class="header">
            <h1>Configuración de {{ $empresa->neg_nombre_comercial }}</h1>
            <p class="subtitle">Gestiona todos los aspectos de tu negocio desde un solo lugar</p>
        </div>

        <!-- Pestañas de navegación -->
        <div class="config-tabs">
            <button class="tab-btn active" data-tab="ajustes">
                <i class="fas fa-sliders-h"></i> Ajustes
            </button>
            <button class="tab-btn" data-tab="presencia">
                <i class="fas fa-globe"></i> Presencia online
            </button>
            <button class="tab-btn" data-tab="marketing">
                <i class="fas fa-bullhorn"></i> Marketing
            </button>
            <button class="tab-btn" data-tab="otros">
                <i class="fas fa-ellipsis-h"></i> Otros
            </button>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="tab-content active" id="ajustes">
            <div class="config-grid">
                <div class="config-card" data-section="negocio">
                    <div class="card-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="card-content">
                        <h3>Configuración del negocio</h3>
                        <p>Personaliza los datos del negocio y gestiona los centros y las opciones de procedencia de los clientes.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="citas">
                    <div class="card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-content">
                        <h3>Gestión de citas</h3>
                        <p>Configura tu disponibilidad, gestiona los recursos para reservar y las preferencias de las reservas online.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="ventas">
                    <div class="card-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="card-content">
                        <h3>Ventas</h3>
                        <p>Configura los métodos de pago, impuestos, recibos, cargos por servicio y tarjetas regalo.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="facturacion">
                    <div class="card-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="card-content">
                        <h3>Facturación</h3>
                        <p>Administra las facturas de Fresha, los SMS, los complementos y la facturación.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="equipo">
                    <div class="card-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="card-content">
                        <h3>Equipo</h3>
                        <p>Gestiona los permisos, las remuneraciones y los días libres.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="formularios">
                    <div class="card-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="card-content">
                        <h3>Formularios</h3>
                        <p>Configura plantillas para formularios de clientes.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="pagos">
                    <div class="card-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="card-content">
                        <h3>Pagos</h3>
                        <p>Configura los métodos de pago, los datáfonos y la política de pago.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="presencia">
            <div class="config-grid">
                <div class="config-card" data-section="website">
                    <div class="card-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="card-content">
                        <h3>Sitio web</h3>
                        <p>Diseña y personaliza tu sitio web profesional.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="redes">
                    <div class="card-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div class="card-content">
                        <h3>Redes sociales</h3>
                        <p>Conecta y gestiona tus perfiles de redes sociales.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="marketing">
            <div class="config-grid">
                <div class="config-card" data-section="promociones">
                    <div class="card-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="card-content">
                        <h3>Promociones</h3>
                        <p>Crea y gestiona ofertas y descuentos especiales.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="email-marketing">
                    <div class="card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="card-content">
                        <h3>Email Marketing</h3>
                        <p>Configura campañas de email automáticas.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="otros">
            <div class="config-grid">
                <div class="config-card" data-section="notificaciones">
                    <div class="card-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="card-content">
                        <h3>Notificaciones</h3>
                        <p>Gestiona las preferencias de notificaciones.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="config-card" data-section="integraciones">
                    <div class="card-icon">
                        <i class="fas fa-plug"></i>
                    </div>
                    <div class="card-content">
                        <h3>Integraciones</h3>
                        <p>Conecta con aplicaciones y servicios externos.</p>
                    </div>
                    <div class="card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('js/empresa/configuracion-empresa.js') }}"></script>
