@extends('layouts.admin')
@section('title', 'Planes')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-plans.css') }}">
@endpush
@section('admin-content')
<div class="plans-container">

    <div class="plans-header">
        <div>
            <h2 class="plans-title">
                <i class="fas fa-layer-group"></i>
                Gestión de Planes
            </h2>
            <p class="plans-subtitle">Administra los planes de suscripción disponibles para los negocios</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="btn-new-plan">
            <i class="fas fa-plus"></i>
            Nuevo Plan
        </a>
    </div>

    @if(session('success'))
        <div class="plans-alert plans-alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($plans->isEmpty())
        <div class="plans-empty">
            <i class="fas fa-layer-group plans-empty-icon"></i>
            <p>No hay planes creados aún.</p>
            <a href="{{ route('admin.plans.create') }}" class="btn-new-plan">Crear primer plan</a>
        </div>
    @else
        <div class="plans-grid">
            @foreach($plans as $plan)
                <div class="plan-card {{ !$plan->is_active ? 'plan-card--inactive' : '' }}">

                    {{-- Badge estado --}}
                    <div class="plan-card__badge {{ $plan->is_active ? 'badge--active' : 'badge--inactive' }}">
                        {{ $plan->is_active ? 'Activo' : 'Inactivo' }}
                    </div>

                    {{-- Encabezado --}}
                    <div class="plan-card__header">
                        <h3 class="plan-card__name">{{ $plan->name }}</h3>
                        <div class="plan-card__price">
                            <span class="price-currency">{{ $plan->currency }}</span>
                            <span class="price-amount">${{ number_format($plan->price, 0) }}</span>
                            <span class="price-interval">/ {{ $plan->interval === 'monthly' ? 'mes' : 'año' }}</span>
                        </div>
                        @if($plan->description)
                            <p class="plan-card__desc">{{ $plan->description }}</p>
                        @endif
                    </div>

                    {{-- Profesionales --}}
                    <div class="plan-card__professionals">
                        <i class="fas fa-user-tie"></i>
                        @if($plan->max_professionals)
                            {{ $plan->max_professionals }} profesional{{ $plan->max_professionals > 1 ? 'es' : '' }} incluido{{ $plan->max_professionals > 1 ? 's' : '' }}
                        @else
                            Profesionales ilimitados
                        @endif
                        @if($plan->price_per_additional_professional)
                            <span class="plan-card__extra">
                                + ${{ number_format($plan->price_per_additional_professional, 0) }}/adicional
                            </span>
                        @endif
                    </div>

                    {{-- Features --}}
                    <ul class="plan-card__features">
                        <li class="{{ $plan->crm_ia_enabled ? 'feature--on' : 'feature--off' }}">
                            <i class="fas fa-{{ $plan->crm_ia_enabled ? 'check' : 'times' }}"></i>
                            Agenda inteligente + CRM + IA
                        </li>
                        <li class="{{ $plan->multi_branch_enabled ? 'feature--on' : 'feature--off' }}">
                            <i class="fas fa-{{ $plan->multi_branch_enabled ? 'check' : 'times' }}"></i>
                            Multi-sucursal
                        </li>
                        <li class="{{ $plan->whatsapp_reminders ? 'feature--on' : 'feature--off' }}">
                            <i class="fas fa-{{ $plan->whatsapp_reminders ? 'check' : 'times' }}"></i>
                            Recordatorios WhatsApp
                        </li>
                        <li class="{{ $plan->email_reminders ? 'feature--on' : 'feature--off' }}">
                            <i class="fas fa-{{ $plan->email_reminders ? 'check' : 'times' }}"></i>
                            Recordatorios Email
                        </li>
                    </ul>

                    {{-- Acciones --}}
                    <div class="plan-card__actions">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-plan btn-plan--edit">
                            <i class="fas fa-edit"></i> Editar
                        </a>

                        <form action="{{ route('admin.plans.toggle', $plan) }}" method="POST" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-plan {{ $plan->is_active ? 'btn-plan--toggle-off' : 'btn-plan--toggle-on' }}">
                                <i class="fas fa-{{ $plan->is_active ? 'eye-slash' : 'eye' }}"></i>
                                {{ $plan->is_active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST"
                              style="display:inline"
                              onsubmit="return confirm('¿Eliminar el plan «{{ $plan->name }}»? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-plan btn-plan--delete">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
