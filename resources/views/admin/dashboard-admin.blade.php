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
        <div class="admin-stat-value">{{ number_format($citasHoy) }}</div>
        <div class="admin-stat-label">Citas Hoy</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon primary">
            <i class="fas fa-store"></i>
        </div>
        <div class="admin-stat-value">{{ number_format($empresasActivas) }}</div>
        <div class="admin-stat-label">Empresas Registradas</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon warning">
            <i class="fas fa-users"></i>
        </div>
        <div class="admin-stat-value">{{ number_format($totalUsuarios) }}</div>
        <div class="admin-stat-label">Usuarios Registrados</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon danger">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="admin-stat-value">{{ number_format($suscripcionesActivas) }}</div>
        <div class="admin-stat-label">Suscripciones Activas</div>
    </div>
</section>

{{-- CHARTS --}}
<section class="admin-charts">
    <div class="admin-chart-card">
        <div class="admin-chart-header">
            <h3 class="admin-chart-title">Citas por Dia (Ultima Semana)</h3>
            <p class="admin-chart-subtitle">Ultimos 7 dias</p>
        </div>
        <div class="admin-chart-container">
            <canvas id="admin-line-chart"></canvas>
        </div>
    </div>

    <div class="admin-chart-card">
        <div class="admin-chart-header">
            <h3 class="admin-chart-title">Tipos de Empresas</h3>
            <p class="admin-chart-subtitle">Distribucion por categoria</p>
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
            @forelse($citasRecientes as $cita)
                @php
                    $nombre = $cita->user?->name ?? $cita->nombre_cliente ?? 'Sin nombre';
                    $iniciales = collect(explode(' ', $nombre))->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->join('');
                    $statusClass = match($cita->estado) {
                        'confirmada' => 'confirmed',
                        'pendiente' => 'pending',
                        'cancelada' => 'cancelled',
                        'completada' => 'confirmed',
                        default => 'pending',
                    };
                @endphp
                <tr>
                    <td>
                        <div class="admin-client">
                            <div class="admin-avatar">{{ $iniciales }}</div>
                            <span>{{ $nombre }}</span>
                        </div>
                    </td>
                    <td>{{ $cita->negocio?->neg_nombre_comercial ?? '-' }}</td>
                    <td>{{ $cita->servicio?->nombre ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} {{ substr($cita->hora_inicio, 0, 5) }}</td>
                    <td><span class="admin-status {{ $statusClass }}">{{ ucfirst($cita->estado) }}</span></td>
                    <td>{{ $cita->precio_cerrado ? '$' . number_format($cita->precio_cerrado) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--admin-text-light);">
                        No hay citas registradas aun.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === LINE CHART: Citas por dia ===
    var citasSemanaData = @json($citasSemana);
    var dias = [];
    var valores = [];

    // Generar ultimos 7 dias
    for (var i = 6; i >= 0; i--) {
        var d = new Date();
        d.setDate(d.getDate() - i);
        var key = d.toISOString().split('T')[0];
        var label = d.toLocaleDateString('es', { weekday: 'short', day: 'numeric' });
        dias.push(label);
        valores.push(citasSemanaData[key] || 0);
    }

    var lineCtx = document.getElementById('admin-line-chart');
    if (lineCtx) {
        new Chart(lineCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: dias,
                datasets: [{
                    label: 'Citas',
                    data: valores,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    // === DOUGHNUT CHART: Categorias ===
    var categoriasData = @json($categorias);
    var catLabels = Object.keys(categoriasData);
    var catValues = Object.values(categoriasData);
    var catColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];

    var doughnutCtx = document.getElementById('admin-doughnut-chart');
    if (doughnutCtx && catLabels.length > 0) {
        new Chart(doughnutCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: catColors.slice(0, catLabels.length),
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 15, font: { size: 12 } } }
                }
            }
        });
    } else if (doughnutCtx) {
        doughnutCtx.parentElement.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9ca3af;font-size:0.9rem;">Sin datos de categorias</div>';
    }
});
</script>
@endpush
