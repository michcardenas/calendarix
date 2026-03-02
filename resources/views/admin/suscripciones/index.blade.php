@extends('layouts.admin')

@section('title', 'Suscripciones')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-suscripciones.css') }}">
@endpush

@section('admin-content')

<header class="admin-header">
    <div>
        <h1>
            <i class="fas fa-credit-card icon"></i>
            Suscripciones
        </h1>
        <div class="admin-date">{{ $counts['all'] }} suscripciones en total</div>
    </div>
</header>

{{-- Alertas --}}
@if(session('success'))
    <div class="admin-alert admin-alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

{{-- FILTROS --}}
<div class="sub-filters">
    <div class="sub-tabs">
        @php
            $tabs = [
                'all' => ['label' => 'Todas', 'icon' => 'fas fa-list'],
                'active' => ['label' => 'Activas', 'icon' => 'fas fa-check-circle'],
                'trial' => ['label' => 'Trial', 'icon' => 'fas fa-clock'],
                'cancelled' => ['label' => 'Canceladas', 'icon' => 'fas fa-ban'],
                'payment_failed' => ['label' => 'Fallidas', 'icon' => 'fas fa-exclamation-triangle'],
                'expired' => ['label' => 'Expiradas', 'icon' => 'fas fa-calendar-times'],
            ];
        @endphp
        @foreach($tabs as $key => $tab)
            <a href="{{ route('admin.suscripciones.index', ['status' => $key, 'search' => $search]) }}"
               class="sub-tab {{ $currentStatus === $key ? 'active' : '' }}">
                <i class="{{ $tab['icon'] }}"></i>
                {{ $tab['label'] }}
                <span class="sub-tab-count">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.suscripciones.index') }}" class="sub-search">
        <input type="hidden" name="status" value="{{ $currentStatus }}">
        <div class="sub-search-input">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o email...">
        </div>
        <button type="submit" class="sub-search-btn">Buscar</button>
    </form>
</div>

{{-- TABLA --}}
<section class="admin-recent">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Plan</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>Inicio</th>
                <th>Vence</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suscripciones as $sub)
                @php
                    $statusClass = match($sub->status) {
                        'active' => 'confirmed',
                        'trial' => 'pending',
                        'cancelled' => 'cancelled',
                        'payment_failed' => 'cancelled',
                        'expired' => 'cancelled',
                        default => 'pending',
                    };
                    $statusLabel = match($sub->status) {
                        'active' => 'Activa',
                        'trial' => 'Trial',
                        'cancelled' => 'Cancelada',
                        'payment_failed' => 'Pago Fallido',
                        'expired' => 'Expirada',
                        default => ucfirst($sub->status),
                    };
                    $vencida = $sub->ends_at && \Carbon\Carbon::parse($sub->ends_at)->isPast();
                @endphp
                <tr>
                    <td>
                        <div class="admin-client">
                            @php
                                $nombre = $sub->user?->name ?? 'Usuario eliminado';
                                $iniciales = collect(explode(' ', $nombre))->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->join('');
                            @endphp
                            <div class="admin-avatar">{{ $iniciales }}</div>
                            <div>
                                <span style="display:block;font-weight:600;">{{ $nombre }}</span>
                                <small style="color:var(--admin-text-light);">{{ $sub->user?->email ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight:600;">{{ $sub->plan?->name ?? 'Sin plan' }}</span>
                        @if($sub->plan)
                            <br><small style="color:var(--admin-text-light);">${{ number_format($sub->plan->price) }} {{ $sub->plan->currency }}</small>
                        @endif
                    </td>
                    <td><span class="admin-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    <td>{{ $sub->is_trial ? 'Trial' : 'Pago' }}</td>
                    <td>{{ $sub->starts_at ? \Carbon\Carbon::parse($sub->starts_at)->format('d/m/Y') : '-' }}</td>
                    <td>
                        {{ $sub->ends_at ? \Carbon\Carbon::parse($sub->ends_at)->format('d/m/Y') : '-' }}
                        @if($vencida)
                            <br><small style="color:var(--admin-danger);font-weight:600;">Vencida</small>
                        @endif
                    </td>
                    <td>
                        <div class="sub-actions">
                            <a href="{{ route('admin.suscripciones.show', $sub) }}" class="sub-btn sub-btn-view" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <div class="sub-dropdown">
                                <button class="sub-btn sub-btn-status" title="Cambiar estado" onclick="this.nextElementSibling.classList.toggle('show')">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                                <div class="sub-dropdown-menu">
                                    @foreach(['active' => 'Activa', 'trial' => 'Trial', 'cancelled' => 'Cancelada', 'expired' => 'Expirada', 'payment_failed' => 'Pago Fallido'] as $st => $label)
                                        @if($st !== $sub->status)
                                            <form method="POST" action="{{ route('admin.suscripciones.update-status', $sub) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $st }}">
                                                <button type="submit" class="sub-dropdown-item">{{ $label }}</button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--admin-text-light);">
                        No se encontraron suscripciones.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($suscripciones->hasPages())
        <div class="sub-pagination">
            {{ $suscripciones->links() }}
        </div>
    @endif
</section>

@endsection

@push('scripts')
<script>
// Cerrar dropdowns al hacer click fuera
document.addEventListener('click', function(e) {
    if (!e.target.closest('.sub-dropdown')) {
        document.querySelectorAll('.sub-dropdown-menu.show').forEach(function(m) {
            m.classList.remove('show');
        });
    }
});
</script>
@endpush
