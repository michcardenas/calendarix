@extends('layouts.app_b')

@section('title', 'Califica tu experiencia - Calendarix')

@section('styles')
<style>
    .calificar-page {
        min-height: 100vh;
        background: linear-gradient(135deg, rgba(90,49,215,0.04) 0%, rgba(50,204,188,0.04) 100%);
        padding: 2rem 1.5rem 4rem;
        display: flex;
        align-items: flex-start;
        justify-content: center;
    }

    .calificar-container {
        max-width: 520px;
        width: 100%;
        margin-top: 2rem;
    }

    .calificar-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .calificar-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .calificar-logo img {
        height: 42px;
    }

    .calificar-logo span {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--gray-900);
    }

    .calificar-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }

    .calificar-subtitle {
        color: var(--gray-500);
        font-size: 0.9rem;
    }

    .calificar-cita-info {
        background: #fff;
        border: 2px solid var(--gray-200);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .calificar-cita-info .info-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.4rem 0;
        font-size: 0.875rem;
        color: var(--gray-700);
    }

    .calificar-cita-info .info-row i {
        color: var(--primary-500);
        width: 16px;
        text-align: center;
        flex-shrink: 0;
    }

    .calificar-cita-info .info-row strong {
        color: var(--gray-900);
    }

    .calificar-card {
        background: #fff;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Star rating */
    .star-rating {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin: 1.5rem 0;
    }

    .star-rating .star {
        font-size: 2.5rem;
        cursor: pointer;
        color: #e5e7eb;
        transition: all 0.15s ease;
    }

    .star-rating .star:hover,
    .star-rating .star.active {
        color: #f59e0b;
        transform: scale(1.1);
    }

    .star-rating .star:hover ~ .star {
        color: #e5e7eb;
    }

    .star-label {
        text-align: center;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--gray-500);
        min-height: 1.5rem;
        margin-bottom: 1rem;
    }

    .calificar-textarea {
        width: 100%;
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 0.9rem;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
        outline: none;
        transition: border-color 0.2s;
    }

    .calificar-textarea:focus {
        border-color: var(--primary-500);
    }

    .calificar-textarea::placeholder {
        color: var(--gray-400);
    }

    .calificar-btn {
        display: block;
        width: 100%;
        text-align: center;
        background: var(--primary-500);
        color: #fff;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.15s ease;
        border: none;
        cursor: pointer;
        margin-top: 1rem;
    }

    .calificar-btn:hover {
        background: var(--primary-600);
        transform: translateY(-1px);
    }

    .calificar-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .calificar-error {
        display: flex;
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

    .calificar-success-card {
        background: #fff;
        border: 2px solid #a7f3d0;
        border-radius: 16px;
        padding: 2.5rem 1.5rem;
        text-align: center;
    }

    .calificar-success-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #ecfdf5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .calificar-success-icon i {
        font-size: 1.75rem;
        color: #059669;
    }

    .calificar-negocio-link {
        text-align: center;
        margin-top: 1.5rem;
    }

    .calificar-negocio-link a {
        color: var(--primary-500);
        font-size: 0.875rem;
        text-decoration: none;
        font-weight: 600;
    }

    .calificar-negocio-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        .calificar-container {
            margin-top: 1rem;
        }
        .calificar-title {
            font-size: 1.25rem;
        }
        .star-rating .star {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="calificar-page">
    <div class="calificar-container">

        {{-- Header --}}
        <div class="calificar-header">
            <div class="calificar-logo">
                <img src="{{ asset('images/morado.png') }}" alt="Calendarix">
                <span>Calendarix</span>
            </div>
            @if($yaCalificada)
                <h1 class="calificar-title">Ya calificaste esta cita</h1>
                <p class="calificar-subtitle">Gracias por tu opinion, {{ $cita->nombre_cliente }}.</p>
            @else
                <h1 class="calificar-title">Como fue tu experiencia?</h1>
                <p class="calificar-subtitle">Hola {{ $cita->nombre_cliente }}, contanos como te fue.</p>
            @endif
        </div>

        {{-- Cita info --}}
        <div class="calificar-cita-info">
            <div class="info-row">
                <i class="fas fa-store"></i>
                <strong>{{ $negocio->neg_nombre }}</strong>
            </div>
            @if($cita->servicio)
            <div class="info-row">
                <i class="fas fa-concierge-bell"></i>
                <span>{{ $cita->servicio->nombre }}</span>
            </div>
            @endif
            @if($cita->trabajador)
            <div class="info-row">
                <i class="fas fa-user"></i>
                <span>{{ $cita->trabajador->nombre }}</span>
            </div>
            @endif
            <div class="info-row">
                <i class="fas fa-calendar"></i>
                <span>{{ $cita->fecha->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <i class="fas fa-clock"></i>
                <span>{{ substr($cita->hora_inicio, 0, 5) }} - {{ substr($cita->hora_fin, 0, 5) }}</span>
            </div>
        </div>

        @if($yaCalificada)
            {{-- Already reviewed --}}
            <div class="calificar-success-card">
                <div class="calificar-success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:0.5rem;">
                    Gracias por tu reseña!
                </h2>
                <p style="color:var(--gray-500);font-size:0.875rem;">
                    Tu calificacion de {{ $resenaExistente->rating }} estrella{{ $resenaExistente->rating > 1 ? 's' : '' }}
                    ayuda a otros clientes a elegir.
                </p>
            </div>
        @else
            {{-- Review form --}}
            @if($errors->any())
                <div class="calificar-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="calificar-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="calificar-card">
                <form action="{{ route('resena.calificar.store', ['cita' => $cita->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="signature" value="{{ request('signature') }}">

                    <p style="text-align:center;font-weight:600;color:var(--gray-700);font-size:0.95rem;">
                        Que calificacion le das?
                    </p>

                    {{-- Stars --}}
                    <div class="star-rating" id="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star" data-value="{{ $i }}" onclick="setRating({{ $i }})">
                                <i class="fas fa-star"></i>
                            </span>
                        @endfor
                    </div>
                    <div class="star-label" id="star-label"></div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}">

                    {{-- Comment --}}
                    <label style="display:block;font-size:0.85rem;font-weight:600;color:var(--gray-700);margin-bottom:0.4rem;">
                        Tu comentario
                    </label>
                    <textarea name="comentario" class="calificar-textarea"
                              placeholder="Contanos como fue tu experiencia..."
                              maxlength="1000">{{ old('comentario') }}</textarea>
                    <small style="color:var(--gray-400);font-size:0.75rem;">Maximo 1000 caracteres</small>

                    <button type="submit" class="calificar-btn" id="submit-btn" disabled>
                        <i class="fas fa-paper-plane"></i> Enviar reseña
                    </button>
                </form>
            </div>
        @endif

        {{-- Link to business --}}
        <div class="calificar-negocio-link">
            <a href="{{ route('negocios.show', $negocio->slug) }}">
                <i class="fas fa-arrow-left"></i> Ver perfil de {{ $negocio->neg_nombre }}
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    var labels = {1: 'Muy malo', 2: 'Malo', 3: 'Regular', 4: 'Bueno', 5: 'Excelente'};
    var currentRating = {{ old('rating', 0) }};

    function setRating(value) {
        currentRating = value;
        document.getElementById('rating-input').value = value;
        document.getElementById('star-label').textContent = labels[value] || '';
        document.getElementById('submit-btn').disabled = false;

        var stars = document.querySelectorAll('#star-rating .star');
        stars.forEach(function(star) {
            var v = parseInt(star.getAttribute('data-value'));
            if (v <= value) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    // Restore old rating
    if (currentRating > 0) {
        setRating(currentRating);
    }
</script>
@endpush
