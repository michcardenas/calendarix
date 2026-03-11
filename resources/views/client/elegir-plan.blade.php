@extends('layouts.app_b')

@section('title', 'Elige tu plan - Calendarix')

@section('styles')
<style>
    .elegir-plan-page {
        min-height: 100vh;
        background: linear-gradient(135deg, rgba(90,49,215,0.04) 0%, rgba(50,204,188,0.04) 100%);
        padding: 2rem 1.5rem 4rem;
    }

    .elegir-plan-container {
        max-width: 1020px;
        margin: 0 auto;
    }

    .elegir-plan-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .elegir-plan-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .elegir-plan-logo img {
        height: 48px;
    }

    .elegir-plan-logo span {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
    }

    .elegir-plan-welcome {
        font-size: 0.95rem;
        color: var(--gray-500);
        margin-bottom: 0.5rem;
    }

    .elegir-plan-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.75rem;
    }

    .elegir-plan-subtitle {
        color: var(--gray-600);
        font-size: 1.0625rem;
        max-width: 520px;
        margin: 0 auto;
    }

    /* Pricing grid — reusa estilos del welcome */
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
        gap: 2rem;
    }

    .pricing-card {
        background: #fff;
        border-radius: 16px;
        border: 2px solid var(--gray-200);
        padding: 2rem;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .pricing-card:hover {
        box-shadow: 0 20px 40px -12px rgba(90,49,215,0.15);
        border-color: var(--primary-300);
        transform: translateY(-4px);
    }

    .pricing-card--featured {
        border-color: var(--primary-500);
        background: linear-gradient(160deg, rgba(90,49,215,0.03) 0%, rgba(50,204,188,0.05) 100%);
    }

    .pricing-popular-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: linear-gradient(135deg, var(--primary-500), var(--secondary));
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.3rem 0.875rem;
        border-radius: 100px;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }

    .pricing-name {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.375rem;
    }

    .pricing-description {
        color: var(--gray-500);
        font-size: 0.9375rem;
        margin-bottom: 1.5rem;
        line-height: 1.55;
    }

    .pricing-price-wrap {
        margin-bottom: 1.5rem;
    }

    .pricing-price {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 3rem;
        font-weight: 800;
        color: var(--gray-900);
        line-height: 1;
    }

    .pricing-price sup {
        font-size: 1.25rem;
        font-weight: 700;
        vertical-align: super;
        line-height: 1;
    }

    .pricing-interval {
        color: var(--gray-500);
        font-size: 0.875rem;
        margin-top: 0.3rem;
    }

    .pricing-professionals {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--gray-50, #f9fafb);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: var(--gray-700);
        font-weight: 500;
    }

    .pricing-professionals i {
        color: var(--primary-500);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .pricing-features-label {
        font-size: 0.6875rem;
        font-weight: 700;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: 0.09em;
        margin-bottom: 0.625rem;
    }

    .pricing-features {
        list-style: none;
        margin: 0 0 2rem;
        padding: 0;
        flex: 1;
    }

    .pricing-features li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        font-size: 0.9375rem;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100, #f3f4f6);
    }

    .pricing-features li:last-child {
        border-bottom: none;
    }

    .pricing-features .feat-check {
        color: #10b981;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .pricing-features .feat-x {
        color: var(--gray-300);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .pricing-features .feat-disabled {
        color: var(--gray-400);
    }

    .pricing-cta {
        display: block;
        text-align: center;
        background: var(--primary-500);
        color: #fff;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.15s ease;
        margin-top: auto;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .pricing-cta:hover {
        background: var(--primary-600);
        transform: translateY(-1px);
        color: #fff;
    }

    .pricing-trial-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #ecfdf5;
        color: #059669;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid #a7f3d0;
    }

    .elegir-plan-logout {
        text-align: center;
        margin-top: 2rem;
    }

    .elegir-plan-logout a {
        color: var(--gray-500);
        font-size: 0.875rem;
        text-decoration: none;
    }

    .elegir-plan-logout a:hover {
        color: var(--primary-500);
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        .pricing-grid {
            grid-template-columns: 1fr;
        }
        .elegir-plan-title {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="elegir-plan-page">
    <div class="elegir-plan-container">

        {{-- Header --}}
        <div class="elegir-plan-header">
            <div class="elegir-plan-logo">
                <img src="{{ asset('images/morado.png') }}" alt="Calendarix">
                <span>Calendarix</span>
            </div>
            <p class="elegir-plan-welcome">
                Hola, <strong>{{ auth()->user()->name }}</strong>
            </p>
            <h1 class="elegir-plan-title">Elige tu plan para continuar</h1>
            <p class="elegir-plan-subtitle">
                Selecciona el plan que mejor se adapte a tus necesidades para acceder a todas las funciones de Calendarix
            </p>
        </div>

        {{-- Alerta si ya usó el periodo de prueba --}}
        @if($trialUsed)
            <div style="display:flex;align-items:center;gap:10px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:14px 18px;border-radius:14px;margin-bottom:1.5rem;font-size:0.9rem;">
                <i class="fas fa-info-circle" style="font-size:1.1rem;flex-shrink:0;"></i>
                <div>
                    Tu periodo de prueba ya fue utilizado. Al elegir un plan se cobrara de inmediato.
                </div>
            </div>
        @endif

        @if(session('error'))
            <div style="display:flex;align-items:center;gap:10px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:1.5rem;font-size:0.9rem;">
                <i class="fas fa-exclamation-circle" style="font-size:1.1rem;flex-shrink:0;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        {{-- Plans grid --}}
        <div class="pricing-grid">
            @foreach($plans as $plan)
                @php
                    $isFeatured = $loop->last && $plans->count() > 1;
                    $intervalLabel = $plan->interval === 'monthly' ? '/mes' : '/año';
                    $currencySymbol = match($plan->currency) {
                        'UYU' => '$',
                        'USD' => '$',
                        'CLP' => '$',
                        'ARS' => '$',
                        'COP' => '$',
                        'MXN' => '$',
                        default => $plan->currency . ' ',
                    };
                    $features = [
                        ['enabled' => $plan->crm_ia_enabled,       'label' => 'Agenda inteligente + CRM + IA'],
                        ['enabled' => $plan->multi_branch_enabled, 'label' => 'Multi-sucursal'],
                        ['enabled' => $plan->whatsapp_reminders,   'label' => 'Recordatorios WhatsApp'],
                        ['enabled' => $plan->email_reminders,      'label' => 'Recordatorios Email'],
                    ];
                @endphp
                <div class="pricing-card {{ $isFeatured ? 'pricing-card--featured' : '' }}">
                    @if(!$trialUsed)
                        <span class="pricing-trial-badge">
                            <i class="fas fa-gift"></i> 15 dias gratis
                        </span>
                    @elseif($isFeatured)
                        <span class="pricing-popular-badge">
                            <i class="fas fa-star"></i> Mas popular
                        </span>
                    @endif

                    <h3 class="pricing-name">{{ $plan->name }}</h3>

                    @if($plan->description)
                        <p class="pricing-description">{{ $plan->description }}</p>
                    @endif

                    <div class="pricing-price-wrap">
                        <div class="pricing-price">
                            <sup>{{ $currencySymbol }}</sup>{{ number_format($plan->price, 0) }}
                        </div>
                        <div class="pricing-interval">{{ $plan->currency }} {{ $intervalLabel }}</div>
                    </div>

                    <div class="pricing-professionals">
                        <i class="fas fa-users"></i>
                        @if($plan->max_professionals)
                            Hasta {{ $plan->max_professionals }} profesional{{ $plan->max_professionals > 1 ? 'es' : '' }}
                            @if($plan->price_per_additional_professional)
                                &nbsp;· {{ $currencySymbol }}{{ number_format($plan->price_per_additional_professional, 0) }} por adicional
                            @endif
                        @else
                            Profesionales ilimitados
                        @endif
                    </div>

                    <p class="pricing-features-label">Funcionalidades incluidas</p>
                    <ul class="pricing-features">
                        @foreach($features as $feature)
                            <li>
                                @if($feature['enabled'])
                                    <i class="fas fa-check-circle feat-check"></i>
                                    <span>{{ $feature['label'] }}</span>
                                @else
                                    <i class="fas fa-times-circle feat-x"></i>
                                    <span class="feat-disabled">{{ $feature['label'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <form method="POST" action="{{ route('suscripcion.iniciar') }}">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" class="pricing-cta">
                            @if(!$trialUsed)
                                Comenzar 15 dias gratis
                            @else
                                Elegir este plan
                            @endif
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Logout link --}}
        <div class="elegir-plan-logout">
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <a href="#" onclick="this.closest('form').submit(); return false;">
                    Cerrar sesión
                </a>
            </form>
        </div>

    </div>
</div>
@endsection
