@extends('layouts.app_b')

@section('title', 'Confirmar metodo de pago - Calendarix')

@section('styles')
<style>
    .tokenizacion-page {
        min-height: 100vh;
        background: linear-gradient(135deg, rgba(90,49,215,0.04) 0%, rgba(50,204,188,0.04) 100%);
        padding: 2rem 1.5rem 4rem;
        display: flex;
        align-items: flex-start;
        justify-content: center;
    }

    .tokenizacion-container {
        max-width: 520px;
        width: 100%;
        margin-top: 2rem;
    }

    .tokenizacion-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .tokenizacion-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .tokenizacion-logo img {
        height: 42px;
    }

    .tokenizacion-logo span {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--gray-900);
    }

    .tokenizacion-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }

    .tokenizacion-plan-info {
        background: #fff;
        border: 2px solid var(--gray-200);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tokenizacion-plan-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--gray-900);
    }

    .tokenizacion-plan-price {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 800;
        font-size: 1.35rem;
        color: var(--primary-500);
    }

    .tokenizacion-plan-interval {
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    .tokenizacion-info-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .tokenizacion-info-box i {
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .tokenizacion-form-card {
        background: #fff;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        min-height: 300px;
    }

    .tokenizacion-form-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--gray-700);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tokenizacion-form-title i {
        color: var(--primary-500);
    }

    #bamboo-form-container {
        min-height: 250px;
    }

    .tokenizacion-error {
        display: none;
        align-items: center;
        gap: 10px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }

    .tokenizacion-loading {
        display: none;
        text-align: center;
        padding: 2rem;
        color: var(--gray-500);
        font-size: 0.9rem;
    }

    .tokenizacion-loading i {
        font-size: 1.5rem;
        color: var(--primary-500);
        animation: spin 1s linear infinite;
        display: block;
        margin-bottom: 0.75rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .tokenizacion-secure {
        text-align: center;
        color: var(--gray-400);
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-top: 1rem;
    }

    .tokenizacion-back {
        text-align: center;
        margin-top: 1.5rem;
    }

    .tokenizacion-back a {
        color: var(--gray-500);
        font-size: 0.875rem;
        text-decoration: none;
    }

    .tokenizacion-back a:hover {
        color: var(--primary-500);
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        .tokenizacion-container {
            margin-top: 1rem;
        }
        .tokenizacion-title {
            font-size: 1.25rem;
        }
        .tokenizacion-plan-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="tokenizacion-page">
    <div class="tokenizacion-container">

        {{-- Header --}}
        <div class="tokenizacion-header">
            <div class="tokenizacion-logo">
                <img src="{{ asset('images/morado.png') }}" alt="Calendarix">
                <span>Calendarix</span>
            </div>
            <h1 class="tokenizacion-title">Confirma tu metodo de pago</h1>
        </div>

        {{-- Plan info --}}
        <div class="tokenizacion-plan-info">
            <div>
                <div class="tokenizacion-plan-name">{{ $plan->name }}</div>
                @php
                    $isFree = (float) $plan->price == 0;
                    $currencySymbol = match($plan->currency) {
                        'UYU' => '$', 'USD' => '$', 'CLP' => '$', 'ARS' => '$',
                        'COP' => '$', 'MXN' => '$', default => $plan->currency . ' ',
                    };
                @endphp
                <span class="tokenizacion-plan-interval">
                    @if($isFree)
                        Gratis por 15 dias
                    @else
                        {{ $plan->currency }} / {{ $plan->interval === 'monthly' ? 'mes' : 'año' }}
                    @endif
                </span>
            </div>
            <div class="tokenizacion-plan-price">
                @if($isFree)
                    $0
                @else
                    {{ $currencySymbol }}{{ number_format($plan->price, 0) }}
                @endif
            </div>
        </div>

        {{-- Info box for free plan --}}
        @if($isFree)
            <div class="tokenizacion-info-box">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>No se te cobrara ahora.</strong>
                    Tu tarjeta sera utilizada al finalizar los 15 dias de prueba para activar tu plan mensual. Podes cancelar en cualquier momento antes.
                </div>
            </div>
        @endif

        {{-- Error message --}}
        <div class="tokenizacion-error" id="tokenizacion-error">
            <i class="fas fa-exclamation-circle"></i>
            <span id="tokenizacion-error-text">Error al procesar la tarjeta.</span>
        </div>

        {{-- Datos personales --}}
        @if(!auth()->user()->dni)
        <div class="tokenizacion-form-card" style="min-height:auto;margin-bottom:1rem;">
            <div class="tokenizacion-form-title">
                <i class="fas fa-id-card"></i>
                Datos personales
            </div>
            <div style="margin-bottom:0.5rem;">
                <label for="cedula-input" style="font-size:0.85rem;font-weight:500;color:var(--gray-700);display:block;margin-bottom:0.4rem;">
                    Cedula de identidad (CI)
                </label>
                <input type="text" id="cedula-input" placeholder="Ej: 12345678"
                    style="width:100%;padding:0.7rem 1rem;border:2px solid var(--gray-200);border-radius:10px;font-size:0.95rem;font-family:inherit;outline:none;transition:border-color 0.2s;"
                    onfocus="this.style.borderColor='var(--primary-500)'" onblur="this.style.borderColor='var(--gray-200)'"
                    value="{{ auth()->user()->dni ?? '' }}">
                <small style="color:var(--gray-500);font-size:0.75rem;">Requerida para procesar el pago en Uruguay.</small>
            </div>
        </div>
        @endif

        {{-- Bamboo form --}}
        <div class="tokenizacion-form-card">
            <div class="tokenizacion-form-title">
                <i class="fas fa-credit-card"></i>
                Datos de la tarjeta
            </div>
            <div id="bamboo-form-container"></div>
        </div>

        {{-- Loading state --}}
        <div class="tokenizacion-loading" id="tokenizacion-loading">
            <i class="fas fa-spinner"></i>
            Procesando tu pago...
        </div>

        {{-- Secure badge --}}
        <div class="tokenizacion-secure">
            <i class="fas fa-lock"></i>
            Pago seguro procesado por Bamboo Payment
        </div>

        {{-- Back link --}}
        <div class="tokenizacion-back">
            <a href="{{ route('client.elegir-plan') }}">
                <i class="fas fa-arrow-left"></i> Volver a elegir plan
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Bamboo Capture Script --}}
<script src="{{ $captureScriptUrl }}"
    integrity="{{ $captureIntegrity }}"
    crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var errorDiv = document.getElementById('tokenizacion-error');
    var errorText = document.getElementById('tokenizacion-error-text');
    var loadingDiv = document.getElementById('tokenizacion-loading');

    try {
        BambooForm.renderTokenizationForm({
            containerId: 'bamboo-form-container',
            metadata: {
                publicKey: '{{ $publicKey }}',
                targetCountryISO: '{{ $targetCountry }}',
                customer: {
                    email: '{{ auth()->user()->email }}',
                    cardHolderName: '{{ auth()->user()->name }}'
                },
                locale: 'es'
            },
            hooks: {
                onOperationSuccess: function(token) {
                    // Validar cedula si el campo existe
                    var cedulaInput = document.getElementById('cedula-input');
                    if (cedulaInput && !cedulaInput.value.trim()) {
                        errorDiv.style.display = 'flex';
                        errorText.textContent = 'Por favor ingresa tu cedula de identidad.';
                        cedulaInput.focus();
                        cedulaInput.style.borderColor = '#ef4444';
                        return;
                    }

                    // Ocultar error, mostrar loading
                    errorDiv.style.display = 'none';
                    loadingDiv.style.display = 'block';

                    // Crear form y enviar token al backend
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("suscripcion.procesar-token") }}';
                    var html = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                        '<input type="hidden" name="plan_id" value="{{ $plan->id }}">' +
                        '<input type="hidden" name="token_id" value="' + token.TokenId + '">';
                    if (cedulaInput) {
                        html += '<input type="hidden" name="dni" value="' + cedulaInput.value.trim() + '">';
                    }
                    form.innerHTML = html;
                    document.body.appendChild(form);
                    form.submit();
                },
                onOperationError: function(error) {
                    loadingDiv.style.display = 'none';
                    errorDiv.style.display = 'flex';
                    var msg = 'Error al procesar la tarjeta.';
                    if (error && error.message) msg += ' ' + error.message;
                    else if (error && typeof error === 'string') msg += ' ' + error;
                    else if (error) msg += ' ' + JSON.stringify(error);
                    errorText.textContent = msg;
                    console.error('Bamboo tokenization error:', JSON.stringify(error, null, 2));
                },
                onApplicationLoaded: function() {
                    console.log('Bamboo form loaded successfully');
                }
            }
        });
    } catch (e) {
        errorDiv.style.display = 'flex';
        errorText.textContent = 'No se pudo cargar el formulario de pago. Recarga la pagina e intenta nuevamente.';
        console.error('Bamboo form init error:', e);
    }
});
</script>
@endpush
