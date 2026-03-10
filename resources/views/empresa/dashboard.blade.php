@extends('layouts.base')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/empresa/dashboard-empresa.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 15; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="layout" style="display: flex;">
    {{-- Sidebar a la izquierda --}}
    @include('empresa.partials.sidebar', [
        'empresa' => $empresa,
        'currentPage' => $currentPage ?? 'dashboard',
        'currentSubPage' => $currentSubPage ?? null
    ])

    <main class="content" style="padding: 2rem;">

        {{-- Saludo y fecha --}}
        <div class="dash-welcome">
            <div>
                <div class="header">{{ $empresa->neg_nombre_comercial ?? $empresa->neg_nombre }}</div>
                <p class="dash-date">{{ \Illuminate\Support\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <a href="{{ route('empresa.agenda', ['id' => $empresa->id]) }}" class="dash-btn-agenda">
                <i class="fas fa-calendar-plus"></i> Ir a la Agenda
            </a>
        </div>

        {{-- Tarjetas de estadísticas principales --}}
        <div class="stats">
            <div class="stat-card stat-card--primary">
                <div class="stat-card__icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-card__info">
                    <span class="stat-card__number">{{ number_format($citasHoy ?? 0) }}</span>
                    <span class="stat-card__label">Citas hoy</span>
                </div>
            </div>
            <div class="stat-card stat-card--success">
                <div class="stat-card__icon"><i class="fas fa-calendar-week"></i></div>
                <div class="stat-card__info">
                    <span class="stat-card__number">{{ number_format($citasSemana ?? 0) }}</span>
                    <span class="stat-card__label">Esta semana</span>
                </div>
            </div>
            <div class="stat-card stat-card--info">
                <div class="stat-card__icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-card__info">
                    <span class="stat-card__number">{{ number_format($citasMes ?? 0) }}</span>
                    <span class="stat-card__label">Este mes</span>
                </div>
            </div>
            <div class="stat-card stat-card--accent">
                <div class="stat-card__icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-card__info">
                    <span class="stat-card__number">{{ number_format($totalCitas ?? 0) }}</span>
                    <span class="stat-card__label">Total historico</span>
                </div>
            </div>
        </div>

        {{-- Resumen rápido --}}
        <div class="dash-metrics">
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(90, 49, 215, 0.15); color: #5a31d7;">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ number_format($serviciosActivos ?? 0) }}</span>
                    <span class="metric-label">Servicios</span>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(50, 204, 188, 0.15); color: #32ccbc;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ number_format($miembrosEquipo ?? 0) }}</span>
                    <span class="metric-label">Equipo</span>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(255, 168, 215, 0.15); color: #e91e8c;">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ number_format($totalClientes ?? 0) }}</span>
                    <span class="metric-label">Clientes</span>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(255, 152, 0, 0.15); color: #ff9800;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ number_format($citasPendientes ?? 0) }}</span>
                    <span class="metric-label">Pendientes</span>
                </div>
            </div>
        </div>

        {{-- Dos columnas: Próximas citas + Estado de citas --}}
        <div class="dash-grid">
            {{-- Próximas citas --}}
            <div class="dash-card">
                <div class="dash-card__header">
                    <h3><i class="fas fa-clock"></i> Proximas citas</h3>
                    <a href="{{ route('empresa.agenda', ['id' => $empresa->id]) }}" class="dash-card__link">Ver agenda <i class="fas fa-arrow-right"></i></a>
                </div>

                @if(($proximasCitas ?? collect())->isEmpty())
                    <div class="dash-empty">
                        <i class="fas fa-calendar-times"></i>
                        <p>No hay citas proximas programadas</p>
                        <a href="{{ route('empresa.agenda', ['id' => $empresa->id]) }}" class="dash-empty__btn">Agendar cita</a>
                    </div>
                @else
                    <div class="dash-citas-list">
                        @foreach($proximasCitas as $cita)
                            <div class="cita-item">
                                <div class="cita-item__date">
                                    <span class="cita-item__day">{{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d') }}</span>
                                    <span class="cita-item__month">{{ \Illuminate\Support\Carbon::parse($cita->fecha)->locale('es')->isoFormat('MMM') }}</span>
                                </div>
                                <div class="cita-item__details">
                                    <span class="cita-item__name">{{ $cita->nombre_cliente ?? 'Sin nombre' }}</span>
                                    <span class="cita-item__meta">
                                        <i class="fas fa-clock"></i> {{ \Illuminate\Support\Str::of($cita->hora_inicio)->limit(5,'') }}
                                        @if($cita->hora_fin) - {{ \Illuminate\Support\Str::of($cita->hora_fin)->limit(5,'') }} @endif
                                        @if($cita->servicio)
                                            &middot; {{ $cita->servicio->nombre }}
                                        @endif
                                    </span>
                                    @if($cita->trabajador)
                                        <span class="cita-item__worker"><i class="fas fa-user"></i> {{ $cita->trabajador->nombre }}</span>
                                    @endif
                                </div>
                                <span class="cita-badge cita-badge--{{ $cita->estado ?? 'pendiente' }}">
                                    {{ ucfirst($cita->estado ?? 'pendiente') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Estado de citas + Accesos rápidos --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                {{-- Estado de citas --}}
                <div class="dash-card">
                    <div class="dash-card__header">
                        <h3><i class="fas fa-chart-pie"></i> Estado de citas</h3>
                    </div>
                    <div class="status-bars">
                        @php
                            $total = max(($citasConfirmadas ?? 0) + ($citasPendientes ?? 0) + ($citasCanceladas ?? 0), 1);
                        @endphp
                        <div class="status-bar-item">
                            <div class="status-bar-label">
                                <span><span class="status-dot" style="background: #32ccbc;"></span> Confirmadas</span>
                                <span class="status-bar-count">{{ $citasConfirmadas ?? 0 }}</span>
                            </div>
                            <div class="status-bar-track">
                                <div class="status-bar-fill" style="width: {{ (($citasConfirmadas ?? 0) / $total) * 100 }}%; background: #32ccbc;"></div>
                            </div>
                        </div>
                        <div class="status-bar-item">
                            <div class="status-bar-label">
                                <span><span class="status-dot" style="background: #ff9800;"></span> Pendientes</span>
                                <span class="status-bar-count">{{ $citasPendientes ?? 0 }}</span>
                            </div>
                            <div class="status-bar-track">
                                <div class="status-bar-fill" style="width: {{ (($citasPendientes ?? 0) / $total) * 100 }}%; background: #ff9800;"></div>
                            </div>
                        </div>
                        <div class="status-bar-item">
                            <div class="status-bar-label">
                                <span><span class="status-dot" style="background: #ef4444;"></span> Canceladas</span>
                                <span class="status-bar-count">{{ $citasCanceladas ?? 0 }}</span>
                            </div>
                            <div class="status-bar-track">
                                <div class="status-bar-fill" style="width: {{ (($citasCanceladas ?? 0) / $total) * 100 }}%; background: #ef4444;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Accesos rápidos --}}
                <div class="dash-card">
                    <div class="dash-card__header">
                        <h3><i class="fas fa-bolt"></i> Accesos rapidos</h3>
                    </div>
                    <div class="quick-actions">
                        <a href="{{ route('empresa.agenda', ['id' => $empresa->id]) }}" class="quick-action">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Agenda</span>
                        </a>
                        <a href="{{ route('empresa.catalogo.servicios', ['id' => $empresa->id]) }}" class="quick-action">
                            <i class="fas fa-concierge-bell"></i>
                            <span>Servicios</span>
                        </a>
                        <a href="{{ route('empresa.trabajadores.index', ['empresa' => $empresa->id]) }}" class="quick-action">
                            <i class="fas fa-users-cog"></i>
                            <span>Equipo</span>
                        </a>
                        <a href="{{ route('empresa.clientes.index', ['empresa' => $empresa->id]) }}" class="quick-action">
                            <i class="fas fa-user-friends"></i>
                            <span>Clientes</span>
                        </a>
                        <a href="{{ route('empresa.configuracion.negocio', ['id' => $empresa->id]) }}" class="quick-action">
                            <i class="fas fa-cog"></i>
                            <span>Config</span>
                        </a>
                        <a href="{{ route('empresa.configuracion.negocio', ['id' => $empresa->id]) }}" class="quick-action">
                            <i class="fas fa-store"></i>
                            <span>Perfil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
