@extends('layouts.empresa')

@section('title', 'Reseñas - ' . $empresa->neg_nombre_comercial)

@section('content')
<style>
    .resenas-page { max-width: 800px; }

    /* Summary card */
    .summary-card {
        background: #fff;
        border: 1px solid #ece9f8;
        border-radius: 18px;
        padding: 1.75rem;
        box-shadow: 0 1px 4px rgba(90,49,215,0.06);
    }
    .summary-left {
        text-align: center;
        min-width: 120px;
        padding-right: 1.75rem;
        border-right: 1px solid #f0ecf8;
    }
    .summary-score {
        font-size: 3rem;
        font-weight: 900;
        color: #5a31d7;
        line-height: 1;
        letter-spacing: -0.03em;
    }
    .summary-stars { display: flex; gap: 3px; justify-content: center; margin-top: 8px; }
    .summary-stars i { font-size: 0.85rem; }
    .summary-count {
        font-size: 0.78rem;
        font-weight: 600;
        color: #9ca3af;
        margin-top: 6px;
    }
    .bar-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 3px 0;
    }
    .bar-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #374151;
        min-width: 14px;
        text-align: right;
    }
    .bar-star { color: #f59e0b; font-size: 0.65rem; }
    .bar-track {
        flex: 1;
        height: 8px;
        background: #f3f4f6;
        border-radius: 99px;
        overflow: hidden;
    }
    .bar-fill {
        height: 100%;
        border-radius: 99px;
        background: linear-gradient(135deg, #5a31d7, #7b5ce0);
        transition: width 0.6s ease;
    }
    .bar-count {
        font-size: 0.75rem;
        font-weight: 600;
        color: #9ca3af;
        min-width: 24px;
        text-align: right;
    }

    /* Pendiente badge */
    .badge-sin-respuesta {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        background: #fff1f5;
        color: #be185d;
    }

    /* Resena card */
    .resena-card {
        background: #fff;
        border: 1px solid #f0ecf8;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        transition: all 0.2s;
    }
    .resena-card:hover {
        border-color: #e0d8f5;
        box-shadow: 0 4px 16px rgba(90,49,215,0.06);
    }
    .resena-card-pendiente {
        border-left: 3px solid #f472b6;
    }
    .resena-avatar {
        width: 40px; height: 40px; min-width: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #5a31d7, #7b5ce0);
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 0.82rem;
        flex-shrink: 0;
    }
    .resena-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .resena-name {
        font-weight: 700;
        color: #1f2937;
        font-size: 0.9rem;
    }
    .resena-date {
        font-size: 0.72rem;
        color: #9ca3af;
    }
    .resena-service {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #5a31d7;
        background: #f0ecfb;
        padding: 2px 8px;
        border-radius: 6px;
        margin-top: 2px;
    }
    .resena-stars {
        display: flex;
        gap: 2px;
        margin-left: auto;
        flex-shrink: 0;
    }
    .resena-stars i { font-size: 0.72rem; }
    .resena-comment {
        font-size: 0.88rem;
        color: #4b5563;
        line-height: 1.6;
        margin: 12px 0 0 52px;
    }

    /* Respuesta */
    .respuesta-box {
        margin: 14px 0 0 52px;
        background: linear-gradient(135deg, #faf9ff, #f5f3ff);
        border-left: 3px solid #5a31d7;
        border-radius: 0 12px 12px 0;
        padding: 14px 16px;
    }
    .respuesta-header {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }
    .respuesta-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #5a31d7;
    }
    .respuesta-date {
        font-size: 0.68rem;
        color: #9ca3af;
    }
    .respuesta-text {
        font-size: 0.82rem;
        color: #4b5563;
        line-height: 1.55;
    }

    /* Boton responder */
    .btn-responder {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #5a31d7;
        background: #f0ecfb;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        margin: 12px 0 0 52px;
    }
    .btn-responder:hover {
        background: #5a31d7;
        color: #fff;
    }

    /* Form respuesta */
    .form-respuesta {
        margin: 10px 0 0 52px;
        padding: 14px;
        background: #faf9ff;
        border-radius: 12px;
        border: 1px solid #ece9f8;
    }
    .form-respuesta textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 0.82rem;
        color: #374151;
        resize: none;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }
    .form-respuesta textarea:focus {
        border-color: #5a31d7;
        box-shadow: 0 0 0 3px rgba(90,49,215,0.1);
    }
</style>

<div class="resenas-page">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#5a31d7;margin:0;">
                <i class="fas fa-star" style="margin-right:8px;opacity:0.7;"></i>Resenas
            </h1>
            <p style="font-size:0.82rem;color:#9ca3af;margin:4px 0 0 0;">Ve lo que opinan tus clientes.</p>
        </div>
        @php $sinResponder = $resenas->whereNull('respuesta_negocio')->count(); @endphp
        @if($sinResponder > 0)
            <span class="badge-sin-respuesta">
                <i class="fas fa-exclamation-circle"></i>
                {{ $sinResponder }} sin responder
            </span>
        @endif
    </div>

    {{-- Alerta --}}
    @if(session('success'))
        <div style="display:flex;align-items:center;gap:8px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:12px 16px;border-radius:12px;margin-bottom:1rem;font-size:0.82rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Resumen --}}
    <div class="summary-card" style="margin-bottom:1.5rem;">
        <div style="display:flex;align-items:center;gap:0;">
            <div class="summary-left">
                <div class="summary-score">{{ $promedioRating ?? '—' }}</div>
                <div class="summary-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star" style="color:{{ $promedioRating && $i <= round($promedioRating) ? '#f59e0b' : '#e5e7eb' }};"></i>
                    @endfor
                </div>
                <div class="summary-count">{{ $resenas->count() }} {{ $resenas->count() === 1 ? 'resena' : 'resenas' }}</div>
            </div>

            <div style="flex:1;padding-left:1.75rem;">
                @for($star = 5; $star >= 1; $star--)
                    @php $count = $resenas->where('rating', $star)->count(); @endphp
                    <div class="bar-row">
                        <span class="bar-label">{{ $star }}</span>
                        <i class="fas fa-star bar-star"></i>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:{{ $resenas->count() ? ($count / $resenas->count() * 100) : 0 }}%;"></div>
                        </div>
                        <span class="bar-count">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Lista de resenas --}}
    @if($resenas->count())
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($resenas as $resena)
                <div class="resena-card {{ !$resena->respuesta_negocio ? 'resena-card-pendiente' : '' }}">

                    {{-- Header --}}
                    <div class="resena-header">
                        <div class="resena-avatar">
                            {{ strtoupper(substr($resena->user->name ?? 'C', 0, 2)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <span class="resena-name">{{ $resena->user->name ?? 'Cliente' }}</span>
                                <span class="resena-date">{{ $resena->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($resena->cita && $resena->cita->servicio)
                                <span class="resena-service">
                                    <i class="fas fa-concierge-bell"></i>
                                    {{ $resena->cita->servicio->nombre }}
                                </span>
                            @endif
                        </div>
                        <div class="resena-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="color:{{ $i <= $resena->rating ? '#f59e0b' : '#e5e7eb' }};"></i>
                            @endfor
                        </div>
                    </div>

                    {{-- Comentario --}}
                    <p class="resena-comment">{{ $resena->comentario }}</p>

                    {{-- Respuesta existente --}}
                    @if($resena->respuesta_negocio)
                        <div class="respuesta-box">
                            <div class="respuesta-header">
                                <i class="fas fa-reply" style="color:#5a31d7;font-size:0.7rem;"></i>
                                <span class="respuesta-label">Tu respuesta</span>
                                @if($resena->respuesta_fecha)
                                    <span class="respuesta-date">{{ $resena->respuesta_fecha->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            <p class="respuesta-text">{{ $resena->respuesta_negocio }}</p>
                        </div>
                    @else
                        {{-- Boton responder --}}
                        <button class="btn-responder"
                                onclick="document.getElementById('respForm{{ $resena->id }}').classList.toggle('hidden'); this.classList.toggle('hidden');">
                            <i class="fas fa-reply"></i> Responder a esta resena
                        </button>

                        {{-- Form respuesta --}}
                        <form id="respForm{{ $resena->id }}" action="{{ route('resenas.responder', $resena->id) }}" method="POST"
                              class="form-respuesta hidden">
                            @csrf
                            <textarea name="respuesta_negocio" rows="3" required maxlength="1000"
                                      placeholder="Escribi tu respuesta al cliente..."></textarea>
                            <div style="display:flex;gap:8px;margin-top:10px;justify-content:flex-end;">
                                <button type="button"
                                        onclick="this.closest('form').classList.add('hidden'); this.closest('form').previousElementSibling.classList.remove('hidden');"
                                        style="padding:7px 16px;font-size:0.78rem;font-weight:600;color:#6b7280;background:#f3f4f6;border:none;border-radius:8px;cursor:pointer;">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        style="padding:7px 18px;font-size:0.78rem;font-weight:700;color:#fff;background:#5a31d7;border:none;border-radius:8px;cursor:pointer;transition:all 0.2s;">
                                    <i class="fas fa-paper-plane" style="margin-right:4px;font-size:0.68rem;"></i> Enviar respuesta
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center;padding:3.5rem 1rem;background:#fff;border:1px solid #ece9f8;border-radius:18px;">
            <div style="width:60px;height:60px;border-radius:50%;background:#f0ecfb;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <i class="fas fa-star" style="color:#5a31d7;font-size:1.4rem;opacity:0.5;"></i>
            </div>
            <p style="font-weight:700;color:#374151;margin:0 0 4px 0;">Aun no hay resenas</p>
            <p style="font-size:0.82rem;color:#9ca3af;margin:0;">Cuando tus clientes dejen resenas, las vas a ver aca.</p>
        </div>
    @endif
</div>
@endsection
