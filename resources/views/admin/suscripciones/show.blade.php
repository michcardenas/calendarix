@extends('layouts.admin')

@section('title', 'Detalle Suscripcion')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-suscripciones.css') }}">
@endpush

@section('admin-content')

<header class="admin-header">
    <div>
        <h1>
            <i class="fas fa-credit-card icon"></i>
            Suscripcion #{{ $suscripcion->id }}
        </h1>
        <div class="admin-date">
            <a href="{{ route('admin.suscripciones.index') }}" style="color:var(--admin-primary);text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Volver a suscripciones
            </a>
        </div>
    </div>
</header>

{{-- Alertas --}}
@if(session('success'))
    <div class="admin-alert admin-alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<div class="sub-detail-grid">
    {{-- INFO SUSCRIPCION --}}
    <div class="sub-detail-card">
        <h3 class="sub-detail-title"><i class="fas fa-info-circle"></i> Informacion de la Suscripcion</h3>

        <div class="sub-detail-row">
            <span class="sub-detail-label">Estado:</span>
            @php
                $statusClass = match($suscripcion->status) {
                    'active' => 'confirmed',
                    'trial' => 'pending',
                    'cancelled', 'payment_failed', 'expired' => 'cancelled',
                    default => 'pending',
                };
                $statusLabel = match($suscripcion->status) {
                    'active' => 'Activa',
                    'trial' => 'Trial',
                    'cancelled' => 'Cancelada',
                    'payment_failed' => 'Pago Fallido',
                    'expired' => 'Expirada',
                    default => ucfirst($suscripcion->status),
                };
            @endphp
            <span class="admin-status {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        <div class="sub-detail-row">
            <span class="sub-detail-label">Plan:</span>
            <span>{{ $suscripcion->plan?->name ?? 'Sin plan' }}</span>
        </div>

        <div class="sub-detail-row">
            <span class="sub-detail-label">Precio:</span>
            <span>
                @if($suscripcion->plan)
                    ${{ number_format($suscripcion->plan->price) }} {{ $suscripcion->plan->currency }}
                    / {{ $suscripcion->plan->interval === 'monthly' ? 'mes' : 'año' }}
                @else
                    -
                @endif
            </span>
        </div>

        <div class="sub-detail-row">
            <span class="sub-detail-label">Tipo:</span>
            <span>{{ $suscripcion->is_trial ? 'Trial (prueba)' : 'Pago' }}</span>
        </div>

        <div class="sub-detail-row">
            <span class="sub-detail-label">Inicio:</span>
            <span>{{ $suscripcion->starts_at ? \Carbon\Carbon::parse($suscripcion->starts_at)->format('d/m/Y') : '-' }}</span>
        </div>

        <div class="sub-detail-row">
            <span class="sub-detail-label">Vencimiento:</span>
            <span>
                {{ $suscripcion->ends_at ? \Carbon\Carbon::parse($suscripcion->ends_at)->format('d/m/Y') : '-' }}
                @if($suscripcion->ends_at && \Carbon\Carbon::parse($suscripcion->ends_at)->isPast())
                    <span style="color:var(--admin-danger);font-weight:600;margin-left:8px;">VENCIDA</span>
                @else
                    <small style="color:var(--admin-text-light);margin-left:8px;">({{ $suscripcion->daysRemaining() }} dias restantes)</small>
                @endif
            </span>
        </div>

        @if($suscripcion->cancelled_at)
        <div class="sub-detail-row">
            <span class="sub-detail-label">Cancelada el:</span>
            <span>{{ \Carbon\Carbon::parse($suscripcion->cancelled_at)->format('d/m/Y H:i') }}</span>
        </div>
        @endif

        @if($suscripcion->bamboo_token)
        <div class="sub-detail-row">
            <span class="sub-detail-label">Token Bamboo:</span>
            <span style="font-family:monospace;font-size:0.8rem;color:var(--admin-text-light);">{{ substr($suscripcion->bamboo_token, 0, 20) }}...</span>
        </div>
        @endif

        {{-- CAMBIAR ESTADO --}}
        <div class="sub-detail-status-change">
            <h4>Cambiar Estado Manualmente</h4>
            <form method="POST" action="{{ route('admin.suscripciones.update-status', $suscripcion) }}" class="sub-status-form">
                @csrf
                @method('PATCH')
                <select name="status" class="sub-select">
                    @foreach(['active' => 'Activa', 'trial' => 'Trial', 'cancelled' => 'Cancelada', 'expired' => 'Expirada', 'payment_failed' => 'Pago Fallido'] as $st => $label)
                        <option value="{{ $st }}" {{ $suscripcion->status === $st ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="sub-btn-save">Guardar</button>
            </form>
        </div>
    </div>

    {{-- INFO USUARIO --}}
    <div class="sub-detail-card">
        <h3 class="sub-detail-title"><i class="fas fa-user"></i> Usuario</h3>

        @if($suscripcion->user)
            @php $u = $suscripcion->user; @endphp
            <div class="sub-user-info">
                <div class="sub-user-avatar">
                    {{ collect(explode(' ', $u->name))->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->join('') }}
                </div>
                <div>
                    <div style="font-weight:700;font-size:1.1rem;">{{ $u->name }}</div>
                    <div style="color:var(--admin-text-light);">{{ $u->email }}</div>
                </div>
            </div>

            <div class="sub-detail-row">
                <span class="sub-detail-label">Telefono:</span>
                <span>{{ $u->celular ?? '-' }}</span>
            </div>
            <div class="sub-detail-row">
                <span class="sub-detail-label">Pais:</span>
                <span>{{ $u->pais ?? '-' }}</span>
            </div>
            <div class="sub-detail-row">
                <span class="sub-detail-label">Ciudad:</span>
                <span>{{ $u->ciudad ?? '-' }}</span>
            </div>
            <div class="sub-detail-row">
                <span class="sub-detail-label">Bamboo Customer ID:</span>
                <span style="font-family:monospace;">{{ $u->bamboo_customer_id ?? '-' }}</span>
            </div>
            <div class="sub-detail-row">
                <span class="sub-detail-label">Registrado:</span>
                <span>{{ $u->created_at?->format('d/m/Y H:i') ?? '-' }}</span>
            </div>
        @else
            <p style="color:var(--admin-text-light);padding:1rem 0;">Usuario eliminado.</p>
        @endif
    </div>
</div>

{{-- LOGS DE PAGO --}}
<section class="admin-recent" style="margin-top:1.5rem;">
    <div class="admin-recent-header">
        <h3 class="admin-recent-title">
            <i class="fas fa-history"></i>
            Logs de Pago (Bamboo)
        </h3>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Accion</th>
                <th>HTTP</th>
                <th>Resultado</th>
                <th>Error</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td><span style="font-weight:600;">{{ $log->action }}</span></td>
                    <td>{{ $log->http_status }}</td>
                    <td>
                        @if($log->success)
                            <span class="admin-status confirmed">OK</span>
                        @else
                            <span class="admin-status cancelled">Error</span>
                        @endif
                    </td>
                    <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $log->error_message ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:2rem;color:var(--admin-text-light);">
                        No hay logs de pago para esta suscripcion.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

@endsection
