@extends('layouts.admin')
@section('title', 'Editar Usuario')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-uedit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-suscripciones.css') }}">
@endpush
@section('admin-content')

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title">Editar Usuario</h2>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" id="edit-user-form">
            @csrf
            @method('PUT')

            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="usr_name" class="form-label">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Nombre Completo
                </label>
                <input
                    type="text"
                    id="usr_name"
                    name="name"
                    class="form-control"
                    required
                    value="{{ old('name', $user->name) }}"
                    placeholder="Ingresa el nombre completo"
                    autocomplete="name"
                >
                <div class="feedback-message" id="name-feedback"></div>
            </div>

            <!-- Campo Email -->
            <div class="form-group">
                <label for="usr_email" class="form-label">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    Correo Electrónico
                </label>
                <input
                    type="email"
                    id="usr_email"
                    name="email"
                    class="form-control"
                    required
                    value="{{ old('email', $user->email) }}"
                    placeholder="usuario@ejemplo.com"
                    autocomplete="email"
                >
                <div class="feedback-message" id="email-feedback"></div>
            </div>

            <!-- Campo Rol -->
            <div class="form-group">
                <label for="usr_role" class="form-label">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"></path>
                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
                    </svg>
                    Rol del Usuario
                </label>
                <select name="role" id="usr_role" class="form-control" required>
                    <option value="">Selecciona un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                                {{ $user->roles->first()?->id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                <div class="feedback-message" id="role-feedback"></div>
            </div>

            <!-- Botones de Acción -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-with-icon" id="usr_update_submit">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Actualizar Usuario
                </button>

                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-with-icon">
                    <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15,18 9,12 15,6"></polyline>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

{{-- SECCION DE SUSCRIPCION --}}
@php
    $user->load('subscription.plan');
    $sub = $user->subscription;
@endphp

@if($sub)
    <div class="form-card" style="margin-top:1.5rem;">
        <h2 class="form-title" style="font-size:1.1rem;">
            <i class="fas fa-credit-card" style="color:#6366f1;"></i>
            Suscripcion Actual
        </h2>

        @if(session('success'))
            <div class="admin-alert admin-alert-success" style="margin-bottom:1rem;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="sub-detail-row">
            <span class="sub-detail-label">Plan:</span>
            <span style="font-weight:600;">{{ $sub->plan?->name ?? 'Sin plan' }}</span>
        </div>
        <div class="sub-detail-row">
            <span class="sub-detail-label">Estado:</span>
            @php
                $statusClass = match($sub->status) {
                    'active' => 'confirmed',
                    'trial' => 'pending',
                    default => 'cancelled',
                };
                $statusLabel = match($sub->status) {
                    'active' => 'Activa',
                    'trial' => 'Trial',
                    'cancelled' => 'Cancelada',
                    'payment_failed' => 'Pago Fallido',
                    'expired' => 'Expirada',
                    default => ucfirst($sub->status),
                };
            @endphp
            <span class="admin-status {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>
        <div class="sub-detail-row">
            <span class="sub-detail-label">Vence:</span>
            <span>{{ $sub->ends_at ? \Carbon\Carbon::parse($sub->ends_at)->format('d/m/Y') : '-' }}</span>
        </div>

        <div class="sub-detail-status-change">
            <h4>Cambiar Estado</h4>
            <form method="POST" action="{{ route('admin.suscripciones.update-status', $sub) }}" class="sub-status-form">
                @csrf
                @method('PATCH')
                <select name="status" class="sub-select">
                    @foreach(['active' => 'Activa', 'trial' => 'Trial', 'cancelled' => 'Cancelada', 'expired' => 'Expirada', 'payment_failed' => 'Pago Fallido'] as $st => $label)
                        <option value="{{ $st }}" {{ $sub->status === $st ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="sub-btn-save">Guardar</button>
            </form>
        </div>

        <div style="margin-top:1rem;">
            <a href="{{ route('admin.suscripciones.show', $sub) }}" style="color:#6366f1;font-size:0.85rem;text-decoration:none;">
                <i class="fas fa-eye"></i> Ver detalle completo
            </a>
        </div>
    </div>
@else
    <div class="form-card" style="margin-top:1.5rem;">
        <h2 class="form-title" style="font-size:1.1rem;">
            <i class="fas fa-credit-card" style="color:#9ca3af;"></i>
            Suscripcion
        </h2>
        <p style="color:#6b7280;font-size:0.9rem;padding:0.5rem 0;">Este usuario no tiene una suscripcion activa.</p>
    </div>
@endif

</div>

<!-- Mostrar errores de validacion de Laravel -->
@if ($errors->any())
    <div class="validation-errors" style="display: none;">
        @foreach ($errors->all() as $error)
            <div class="error-item" data-field="{{ $loop->index }}">{{ $error }}</div>
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-uedit.js') }}"></script>
@endpush
