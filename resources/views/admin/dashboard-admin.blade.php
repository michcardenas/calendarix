@extends('layouts.admin')

@section('title', 'Dashboard')

@push('scripts-before')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
@endpush

@section('admin-content')

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

@endsection

@push('scripts')
<script>
    setTimeout(() => {
        if (typeof window.adminShowToast === 'function') {
            window.adminShowToast('🚀 Dashboard cargado correctamente. Explora las opciones del menú lateral!', 'info');
        }
    }, 1500);
</script>
@endpush
