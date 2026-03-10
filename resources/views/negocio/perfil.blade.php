@extends('layouts.perfil-negocio')
@section('title', $negocio->neg_nombre_comercial ?? 'Perfil del Negocio')

@section('content')
<style>
    :root {
        --primary: #5a31d7;
        --primary-light: #7b5ce0;
        --primary-dark: #4a22b8;
        --accent: #32ccbc;
        --accent-light: #5ee0d4;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-400: #9ca3af;
        --gray-600: #4b5563;
        --gray-800: #1f2937;
        --gray-900: #111827;
    }

    /* ===== RESET BASE ===== */
    * { box-sizing: border-box; }

    /* ===== FULLCALENDAR ===== */
    .fc-day-past { background-color: #f3f4f6 !important; opacity: 0.6; cursor: not-allowed !important; }
    .fc-day-past .fc-daygrid-day-number { color: #9ca3af !important; text-decoration: line-through; }
    .fc-day-blocked { background-color: #fee2e2 !important; cursor: not-allowed !important; }
    .fc-day-blocked .fc-daygrid-day-number { color: #dc2626 !important; font-weight: bold; }
    .fc .fc-daygrid-day:not(.fc-day-past):not(.fc-day-blocked):hover { background-color: #ede9fe !important; cursor: pointer; }
    .fc-event { border-radius: 4px; padding: 2px 4px; font-size: 0.75rem; font-weight: 500; cursor: pointer; }
    .fc .fc-toolbar-title { font-size: 1rem !important; font-weight: 700 !important; color: var(--gray-800); }
    .fc .fc-button { background: var(--primary) !important; border-color: var(--primary) !important; font-size: 0.75rem !important; padding: 4px 10px !important; border-radius: 6px !important; }
    .fc .fc-button:hover { background: var(--primary-light) !important; }
    .fc .fc-col-header-cell { background: #faf9ff; }
    .fc .fc-col-header-cell-cushion { color: var(--primary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; }

    /* ===== SCROLLBAR ===== */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* ===== COVER ===== */
    .cover-wrap {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: #e5e7eb;
        height: 220px;
    }
    @media (min-width: 640px) { .cover-wrap { height: 280px; } }
    @media (min-width: 1024px) { .cover-wrap { height: 340px; } }
    .cover-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 20px 20px;
        z-index: 1;
        pointer-events: none;
    }

    /* ===== INFO BAR ===== */
    .infobar-row {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding-bottom: 18px;
    }
    @media (min-width: 640px) {
        .infobar-row {
            flex-direction: row;
            align-items: flex-end;
            gap: 20px;
            padding-bottom: 20px;
        }
    }
    .infobar-text { text-align: center; flex: 1; min-width: 0; }
    @media (min-width: 640px) { .infobar-text { text-align: left; } }
    .infobar-tags { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 6px; margin-top: 6px; }
    @media (min-width: 640px) { .infobar-tags { justify-content: flex-start; } }
    .infobar-meta { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 8px 14px; margin-top: 8px; font-size: 0.78rem; color: var(--gray-600); }
    @media (min-width: 640px) { .infobar-meta { justify-content: flex-start; } }

    /* ===== AVATAR ===== */
    .avatar-ring {
        width: 96px; height: 96px; min-width: 96px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(90,49,215,0.18), 0 2px 8px rgba(0,0,0,0.12);
        overflow: hidden;
        background: #fff;
        margin-top: -48px;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }
    @media (min-width: 640px) {
        .avatar-ring { width: 116px; height: 116px; min-width: 116px; margin-top: -58px; }
    }
    @media (min-width: 1024px) {
        .avatar-ring { width: 128px; height: 128px; min-width: 128px; margin-top: -64px; }
    }
    .avatar-ring img { width: 100%; height: 100%; object-fit: cover; display: block; }

    /* ===== BTN AGENDAR ===== */
    .btn-agendar {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 11px 24px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(90,49,215,0.35);
        transition: all 0.2s ease;
        text-decoration: none;
        flex-shrink: 0;
    }
    .btn-agendar:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(90,49,215,0.45);
    }
    .btn-agendar:active { transform: translateY(0); }
    .btn-agendar-desktop { display: none; }
    @media (min-width: 640px) { .btn-agendar-desktop { display: inline-flex; } }

    /* ===== LAYOUT GRID ===== */
    .perfil-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
        max-width: 1152px;
        margin: 0 auto;
        padding: 1.25rem 1rem 5.5rem;
    }
    @media (min-width: 1024px) {
        .perfil-grid {
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            padding: 1.5rem 1rem 2.5rem;
        }
    }

    /* ===== CARDS ===== */
    .perfil-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #ece9f8;
        box-shadow: 0 1px 4px rgba(90,49,215,0.06), 0 1px 2px rgba(0,0,0,0.04);
        padding: 1.25rem;
        transition: box-shadow 0.2s ease;
    }
    @media (min-width: 640px) { .perfil-card { padding: 1.5rem; } }
    .perfil-card-hover:hover {
        box-shadow: 0 4px 20px rgba(90,49,215,0.1), 0 2px 8px rgba(0,0,0,0.06);
    }
    .card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 14px 0;
    }
    .card-title i { color: var(--primary); font-size: 0.9rem; }

    /* ===== SIDEBAR STICKY ===== */
    .col-left { display: flex; flex-direction: column; gap: 1.25rem; }
    .col-right { display: flex; flex-direction: column; gap: 1.25rem; }
    @media (min-width: 1024px) {
        .col-right { position: sticky; top: 76px; align-self: start; }
    }

    /* ===== EQUIPO ===== */
    .team-scroll { display: flex; gap: 14px; overflow-x: auto; padding-bottom: 6px; }
    .team-item {
        display: flex; flex-direction: column; align-items: center;
        flex-shrink: 0; width: 68px;
        transition: transform 0.2s ease;
    }
    .team-item:hover { transform: translateY(-3px); }
    .team-avatar {
        width: 52px; height: 52px; border-radius: 50%;
        object-fit: cover; display: block;
        border: 2px solid rgba(90,49,215,0.15);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .team-avatar-initials {
        width: 52px; height: 52px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 0.85rem;
        box-shadow: 0 2px 8px rgba(90,49,215,0.25);
    }

    /* ===== GALERÍA ===== */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    @media (min-width: 480px) { .gallery-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 768px) { .gallery-grid { grid-template-columns: repeat(4, 1fr); } }
    .gallery-item {
        aspect-ratio: 1;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        position: relative;
    }
    .gallery-item img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform 0.35s ease;
    }
    .gallery-item:hover img { transform: scale(1.08); }
    .gallery-item::after {
        content: '';
        position: absolute; inset: 0;
        background: rgba(90,49,215,0);
        transition: background 0.25s ease;
        border-radius: 10px;
        pointer-events: none;
    }
    .gallery-item:hover::after { background: rgba(90,49,215,0.18); }

    /* ===== LIGHTBOX ===== */
    .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.88); align-items: center; justify-content: center; z-index: 999; animation: lbIn 0.2s ease; }
    @keyframes lbIn { from { opacity: 0; } to { opacity: 1; } }
    .lightbox.open { display: flex; }

    /* ===== SERVICIOS ===== */
    .servicio-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border: 1px solid #f0ecf8;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }
    .servicio-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 16px rgba(90,49,215,0.1);
        transform: translateY(-1px);
    }
    .servicio-img {
        width: 64px; height: 64px; min-width: 64px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .servicio-img img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }
    .servicio-img-placeholder {
        width: 64px; height: 64px; min-width: 64px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f0ecfb, #e8e3f7);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .servicio-img-placeholder i {
        color: var(--primary);
        font-size: 1.2rem;
        opacity: 0.6;
    }

    /* ===== RESEÑAS ===== */
    .resena-item { padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
    .resena-item:last-child { border-bottom: none; padding-bottom: 0; }
    .stars-display { display: inline-flex; gap: 2px; }
    .stars-display i { font-size: 0.62rem; }

    /* ===== HORARIOS ===== */
    .horario-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 0.82rem;
        border-bottom: 1px solid #f8f8fb;
    }
    .horario-row:last-child { border-bottom: none; }

    /* ===== CONTACTO ===== */
    .contacto-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.82rem;
        color: var(--gray-600);
        padding: 5px 0;
        text-decoration: none;
        transition: color 0.15s;
    }
    .contacto-row:hover { color: var(--primary); }
    .contacto-icon {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: #f0ecff;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .contacto-icon i { color: var(--primary); font-size: 0.75rem; }

    /* ===== MAPA ===== */
    .mapa-container {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        height: 200px;
        border: 1px solid #ece9f8;
    }
    .mapa-container #mapaUbicacion {
        width: 100%; height: 100%;
        z-index: 1;
    }
    .mapa-direccion-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        z-index: 2;
        background: linear-gradient(to top, rgba(255,255,255,0.97) 60%, transparent 100%);
        padding: 28px 14px 12px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 8px;
    }
    .mapa-direccion-text {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--gray-800);
        line-height: 1.35;
        flex: 1;
        min-width: 0;
    }
    .mapa-direccion-text i {
        color: var(--primary);
        margin-right: 5px;
        font-size: 0.72rem;
    }
    .mapa-btn-ir {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: var(--primary);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(90,49,215,0.25);
    }
    .mapa-btn-ir:hover {
        background: var(--primary-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(90,49,215,0.35);
        color: #fff;
    }

    /* ===== STICKY MÓVIL ===== */
    .sticky-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        z-index: 40;
        padding: 12px 16px;
        background: rgba(255,255,255,0.97);
        backdrop-filter: blur(10px);
        border-top: 1px solid #ece9f8;
        box-shadow: 0 -4px 20px rgba(90,49,215,0.1);
    }
    @media (min-width: 1024px) { .sticky-bar { display: none; } }
    .btn-agendar-full {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        border-radius: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(90,49,215,0.35);
        transition: all 0.2s;
    }
    .btn-agendar-full:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(90,49,215,0.45); }

    /* ===== BADGE VIRTUAL ===== */
    .badge-virtual {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(50,204,188,0.12);
        color: #0ea5a0;
        font-size: 0.7rem; font-weight: 700;
        padding: 3px 10px; border-radius: 999px;
    }
    .badge-cat {
        display: inline-flex; align-items: center;
        background: rgba(90,49,215,0.1);
        color: var(--primary);
        font-size: 0.7rem; font-weight: 600;
        padding: 3px 10px; border-radius: 999px;
    }
    .badge-open {
        display: inline-flex; align-items: center; gap: 5px;
        background: #ecfdf5;
        color: #059669;
        font-size: 0.7rem; font-weight: 700;
        padding: 3px 10px; border-radius: 999px;
        border: 1px solid #a7f3d0;
    }
    .badge-open .pulse-dot {
        width: 6px; height: 6px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse-dot 1.5s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }
    .badge-closed {
        display: inline-flex; align-items: center; gap: 5px;
        background: #fef2f2;
        color: #dc2626;
        font-size: 0.7rem; font-weight: 700;
        padding: 3px 10px; border-radius: 999px;
        border: 1px solid #fecaca;
    }

    /* ===== PRECIO ===== */
    .precio-tag {
        font-weight: 800;
        font-size: 0.9rem;
        color: var(--primary);
        background: rgba(90,49,215,0.07);
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* ===== RATING GRANDE ===== */
    .rating-summary {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 4px 12px;
        font-size: 0.82rem;
        color: #92400e;
        font-weight: 600;
    }
</style>

{{-- Leaflet CSS --}}
@if($negocio->neg_latitud && $negocio->neg_longitud)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Custom marker */
    .marker-pin {
        width: 36px; height: 36px;
        border-radius: 50% 50% 50% 0;
        background: var(--primary);
        position: absolute;
        transform: rotate(-45deg);
        left: 50%; top: 50%;
        margin: -36px 0 0 -18px;
        box-shadow: 0 4px 12px rgba(90,49,215,0.4);
    }
    .marker-pin::after {
        content: '';
        width: 20px; height: 20px;
        margin: 8px 0 0 8px;
        background: #fff;
        position: absolute;
        border-radius: 50%;
    }
    .marker-pin-icon {
        position: absolute;
        width: 36px;
        font-size: 14px;
        left: 0; top: 3px;
        text-align: center;
        color: var(--primary);
        z-index: 1;
    }
    .leaflet-control-zoom a {
        border-radius: 8px !important;
        border: none !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
        color: var(--gray-800) !important;
        font-weight: 700 !important;
    }
    .leaflet-control-zoom { border: none !important; border-radius: 10px !important; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.12) !important; }
    .leaflet-control-attribution { font-size: 9px !important; opacity: 0.5; }
</style>
@endif

@include('empresa.partials.modal-agendar', [
    'negocio'      => $negocio,
    'servicios'    => $negocio->servicios,
    'trabajadores' => $trabajadores
])

@php
    $resolveImg = function($path) {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        if (str_starts_with($path, '/')) return $path;
        return asset('storage/' . $path);
    };
    $imgPortada = $resolveImg($negocio->neg_portada);
    $imgPerfil  = $resolveImg($negocio->neg_imagen) ?? asset('images/default-user.png');
@endphp

{{-- ===== PORTADA ===== --}}
<div class="cover-wrap">
    <div id="portadaFallback"
         style="position:absolute;inset:0;background:linear-gradient(135deg,#4a22b8 0%,#5a31d7 30%,#7b5ce0 60%,#32ccbc 100%);{{ $imgPortada ? 'display:none;' : '' }}">
    </div>
    @if($imgPortada)
        <img src="{{ $imgPortada }}"
             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;display:block;"
             alt="Portada"
             onerror="this.style.display='none';document.getElementById('portadaFallback').style.display='block';">
    @endif
    <div class="cover-pattern"></div>
    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.55) 0%,rgba(0,0,0,0.15) 40%,transparent 70%);"></div>
    {{-- Nombre sobre portada para mobile --}}
    <div style="position:absolute;bottom:16px;left:1rem;right:1rem;z-index:2;display:none;" id="coverNameMobile">
        <h1 style="font-size:1.3rem;font-weight:800;color:#fff;text-shadow:0 2px 8px rgba(0,0,0,0.4);margin:0;">
            {{ $negocio->neg_nombre_comercial ?? $negocio->neg_nombre }}
        </h1>
    </div>
</div>

{{-- ===== INFO BAR ===== --}}
<div style="background:#fff;border-bottom:1px solid #ece9f8;box-shadow:0 2px 8px rgba(90,49,215,0.06);">
    <div style="max-width:1152px;margin:0 auto;padding:0 1rem;">
        <div class="infobar-row">

            <div class="avatar-ring">
                <img src="{{ $imgPerfil }}"
                     alt="{{ $negocio->neg_nombre_comercial }}"
                     onerror="this.src='{{ asset('images/default-user.png') }}'">
            </div>

            <div class="infobar-text">
                <h1 style="font-size:clamp(1.3rem,3.5vw,2rem);font-weight:800;color:var(--gray-900);line-height:1.15;margin:0;letter-spacing:-0.02em;">
                    {{ $negocio->neg_nombre_comercial ?? $negocio->neg_nombre }}
                </h1>

                <div class="infobar-tags">
                    @php
                        $hoyDia = now()->dayOfWeekIso;
                        $horarioHoy = $negocio->horarios->where('dia_semana', $hoyDia)->where('activo', 1)->first();
                        $abierto = false;
                        if ($horarioHoy && $horarioHoy->hora_inicio && $horarioHoy->hora_fin) {
                            $ahora = now()->format('H:i:s');
                            $abierto = $ahora >= $horarioHoy->hora_inicio && $ahora <= $horarioHoy->hora_fin;
                        }
                    @endphp
                    @if($horarioHoy)
                        @if($abierto)
                            <span class="badge-open"><span class="pulse-dot"></span> Abierto ahora</span>
                        @else
                            <span class="badge-closed"><i class="fas fa-clock" style="font-size:0.55rem;"></i> Cerrado</span>
                        @endif
                    @endif
                    @if(is_array($negocio->neg_categorias))
                        @foreach($negocio->neg_categorias as $cat)
                            <span class="badge-cat">{{ $cat }}</span>
                        @endforeach
                    @endif
                    @if($promedioRating)
                        <div class="rating-summary">
                            <i class="fas fa-star" style="color:#f59e0b;font-size:0.75rem;"></i>
                            <strong>{{ $promedioRating }}</strong>
                            <span style="font-weight:400;color:#b45309;">({{ $totalResenas }} reseñas)</span>
                        </div>
                    @endif
                </div>

                @if($negocio->neg_descripcion)
                    <p style="font-size:0.85rem;color:var(--gray-600);line-height:1.55;margin:8px 0 0 0;">{{ $negocio->neg_descripcion }}</p>
                @endif

                <div class="infobar-meta">
                    @if($negocio->neg_direccion)
                        <span><i class="fas fa-map-marker-alt" style="color:var(--primary);margin-right:4px;"></i>{{ $negocio->neg_direccion }}</span>
                    @endif
                    @if($negocio->neg_telefono)
                        <a href="tel:{{ $negocio->neg_telefono }}" style="color:inherit;text-decoration:none;">
                            <i class="fas fa-phone" style="color:var(--primary);margin-right:4px;"></i>{{ $negocio->neg_telefono }}
                        </a>
                    @endif
                    @if($negocio->neg_virtual)
                        <span class="badge-virtual"><i class="fas fa-laptop"></i>Atención virtual</span>
                    @endif
                    @if($negocio->neg_facebook)
                        <a href="{{ $negocio->neg_facebook }}" target="_blank" style="color:#9ca3af;font-size:1.05rem;text-decoration:none;" onmouseover="this.style.color='#1877F2'" onmouseout="this.style.color='#9ca3af'"><i class="fab fa-facebook"></i></a>
                    @endif
                    @if($negocio->neg_instagram)
                        <a href="{{ $negocio->neg_instagram }}" target="_blank" style="color:#9ca3af;font-size:1.05rem;text-decoration:none;" onmouseover="this.style.color='#E4405F'" onmouseout="this.style.color='#9ca3af'"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if($negocio->neg_sitio_web)
                        <a href="{{ $negocio->neg_sitio_web }}" target="_blank" style="color:#9ca3af;font-size:1.05rem;text-decoration:none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='#9ca3af'"><i class="fas fa-globe"></i></a>
                    @endif
                </div>
            </div>

            <button onclick="window.openAgendarModal()" class="btn-agendar btn-agendar-desktop">
                <i class="fas fa-calendar-plus"></i> Agendar Cita
            </button>
        </div>
    </div>
</div>

{{-- ===== EQUIPO ===== --}}
@if($trabajadores->count())
<div style="background:linear-gradient(180deg,#faf9ff 0%,#f9fafb 100%);border-bottom:1px solid #ece9f8;">
    <div style="max-width:1152px;margin:0 auto;padding:16px 1rem 18px;">
        <p style="font-size:0.68rem;font-weight:800;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin:0 0 12px 0;">
            <i class="fas fa-users" style="color:var(--primary);margin-right:5px;"></i>Nuestro equipo
            <span style="font-weight:600;color:#c4b5fd;margin-left:6px;">{{ $trabajadores->count() }} profesionales</span>
        </p>
        <div class="team-scroll scrollbar-hide">
            @foreach($trabajadores as $trab)
                <div class="team-item">
                    @if($trab->foto)
                        <img src="{{ asset('storage/' . $trab->foto) }}" alt="{{ $trab->nombre }}" class="team-avatar">
                    @else
                        <div class="team-avatar-initials">{{ strtoupper(substr($trab->nombre, 0, 2)) }}</div>
                    @endif
                    <p style="font-size:0.68rem;color:var(--gray-800);font-weight:600;margin-top:6px;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100%;">{{ $trab->nombre }}</p>
                    @if($trab->especialidades)
                        <p style="font-size:0.62rem;color:var(--gray-400);text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100%;margin-top:1px;">{{ $trab->especialidades }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ===== MAIN GRID ===== --}}
<div style="background:var(--gray-50);">
    <div class="perfil-grid">

        {{-- COLUMNA IZQUIERDA --}}
        <div class="col-left">

            {{-- Calendario --}}
            <div class="perfil-card fade-up">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:8px;">
                    <div>
                        <h2 class="card-title" style="margin:0 0 4px 0;">
                            <i class="fas fa-calendar-alt"></i>Reserva tu cita
                        </h2>
                        <p style="font-size:0.75rem;color:var(--gray-400);margin:0;">Selecciona un dia disponible en el calendario</p>
                    </div>
                    <span style="font-size:0.72rem;color:var(--primary);background:#f0ecfb;padding:5px 12px;border-radius:20px;font-weight:600;">
                        <i class="fas fa-hand-pointer" style="margin-right:4px;font-size:0.65rem;"></i>Toca un dia
                    </span>
                </div>
                <div id="calendarioCitas" style="border-radius:10px;overflow:hidden;"></div>
                <button onclick="window.openAgendarModal()" class="btn-agendar-full" style="margin-top:1rem;">
                    <i class="fas fa-calendar-plus"></i> Agendar Cita
                </button>
            </div>

            {{-- Reseñas --}}
            @if($resenas->count())
            <div class="perfil-card fade-up">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:8px;">
                    <h2 class="card-title" style="margin:0;">
                        <i class="fas fa-star"></i>Reseñas
                    </h2>
                    @if($promedioRating)
                        <div class="rating-summary">
                            <i class="fas fa-star" style="color:#f59e0b;font-size:0.75rem;"></i>
                            <strong>{{ $promedioRating }}</strong>
                            <span style="font-weight:400;">({{ $totalResenas }})</span>
                        </div>
                    @endif
                </div>
                @foreach($resenas->take(5) as $resena)
                    <div class="resena-item">
                        <div style="display:flex;align-items:flex-start;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-light));display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($resena->user->name ?? 'C', 0, 2)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:4px;margin-bottom:3px;">
                                    <span style="font-weight:700;color:var(--gray-800);font-size:0.85rem;">{{ $resena->user->name ?? 'Cliente' }}</span>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <div class="stars-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star" style="color:{{ $i <= $resena->rating ? '#f59e0b' : '#e5e7eb' }};"></i>
                                            @endfor
                                        </div>
                                        <span style="font-size:0.72rem;color:var(--gray-400);">{{ $resena->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <p style="font-size:0.85rem;color:var(--gray-600);line-height:1.55;margin:0;">{{ $resena->comentario }}</p>
                                @if($resena->respuesta_negocio)
                                    <div style="margin-top:8px;padding:8px 12px;border-left:3px solid var(--primary);background:linear-gradient(135deg,#faf9ff,#f5f3ff);border-radius:0 8px 8px 0;">
                                        <p style="font-size:0.72rem;font-weight:700;color:var(--primary);margin:0 0 3px 0;"><i class="fas fa-reply" style="margin-right:4px;"></i>Respuesta del negocio</p>
                                        <p style="font-size:0.82rem;color:var(--gray-600);margin:0;">{{ $resena->respuesta_negocio }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Galería --}}
            @if($negocio->fotos->count())
            <div class="perfil-card fade-up">
                <h2 class="card-title">
                    <i class="fas fa-images"></i>Galería
                    <span style="font-size:0.72rem;font-weight:500;color:var(--gray-400);margin-left:auto;">{{ $negocio->fotos->count() }} fotos</span>
                </h2>
                <div class="gallery-grid">
                    @foreach($negocio->fotos as $foto)
                        <div class="gallery-item" onclick="document.getElementById('lb{{ $foto->id }}').classList.add('open')">
                            <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto">
                        </div>
                        <div id="lb{{ $foto->id }}" class="lightbox" onclick="this.classList.remove('open')">
                            <img src="{{ asset('storage/' . $foto->ruta) }}"
                                 style="max-width:92vw;max-height:88vh;border-radius:14px;box-shadow:0 30px 60px rgba(0,0,0,0.6);display:block;"
                                 alt="Foto">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- SIDEBAR --}}
        <div class="col-right">

            {{-- Servicios --}}
            @if($negocio->servicios->count())
            <div class="perfil-card perfil-card-hover fade-up">
                <h2 class="card-title"><i class="fas fa-concierge-bell"></i>Servicios</h2>
                <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($negocio->servicios as $servicio)
                    <div class="servicio-card" onclick="window.openAgendarModal({serviceId: {{ $servicio->id }}})">
                        @if($servicio->imagen)
                            <div class="servicio-img">
                                <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="{{ $servicio->nombre }}">
                            </div>
                        @else
                            <div class="servicio-img-placeholder">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:700;color:var(--gray-800);font-size:0.85rem;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $servicio->nombre }}</p>
                            @if($servicio->duracion)
                                <p style="font-size:0.72rem;color:var(--gray-400);margin:3px 0 0 0;">
                                    <i class="far fa-clock" style="margin-right:3px;"></i>{{ $servicio->duracion }} min
                                </p>
                            @endif
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;">
                                <span class="precio-tag">${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                <span style="font-size:0.68rem;font-weight:700;color:var(--accent);"><i class="fas fa-calendar-plus" style="margin-right:3px;font-size:0.6rem;"></i>Reservar</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            @endif

            {{-- Horarios --}}
            @if($negocio->horarios->count())
            <div class="perfil-card perfil-card-hover fade-up">
                <h2 class="card-title"><i class="far fa-clock"></i>Horarios</h2>
                @foreach($negocio->horarios as $h)
                    @php $esHoy = $h->dia_semana == now()->dayOfWeekIso; @endphp
                    <div class="horario-row" style="{{ $h->activo ? '' : 'opacity:0.38;' }}{{ $esHoy ? 'background:#f8f6ff;border-radius:8px;padding:6px 8px;margin:0 -8px;' : '' }}">
                        <span style="font-weight:{{ $esHoy ? '700' : '600' }};color:{{ $esHoy ? 'var(--primary)' : 'var(--gray-800)' }};font-size:0.82rem;text-transform:capitalize;display:flex;align-items:center;gap:6px;">
                            @if($esHoy)<i class="fas fa-circle" style="font-size:5px;color:var(--primary);"></i>@endif
                            {{ \Carbon\Carbon::create()->startOfWeek()->addDays($h->dia_semana - 1)->locale('es')->isoFormat('dddd') }}
                        </span>
                        @if($h->activo && $h->hora_inicio && $h->hora_fin)
                            <span style="font-size:0.8rem;color:{{ $esHoy ? 'var(--primary)' : 'var(--gray-600)' }};font-weight:{{ $esHoy ? '700' : '500' }};">
                                {{ substr($h->hora_inicio,0,5) }} – {{ substr($h->hora_fin,0,5) }}
                            </span>
                        @else
                            <span style="font-size:0.72rem;color:#ef4444;font-weight:700;background:#fef2f2;padding:2px 8px;border-radius:6px;">Cerrado</span>
                        @endif
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Contacto --}}
            <div class="perfil-card perfil-card-hover fade-up">
                <h2 class="card-title"><i class="fas fa-address-card"></i>Contacto</h2>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    @if($negocio->neg_telefono)
                        <a href="tel:{{ $negocio->neg_telefono }}" class="contacto-row">
                            <div class="contacto-icon"><i class="fas fa-phone"></i></div>
                            <span style="padding-top:5px;">{{ $negocio->neg_telefono }}</span>
                        </a>
                    @endif
                    @if($negocio->neg_email)
                        <a href="mailto:{{ $negocio->neg_email }}" class="contacto-row">
                            <div class="contacto-icon"><i class="fas fa-envelope"></i></div>
                            <span style="padding-top:5px;word-break:break-all;">{{ $negocio->neg_email }}</span>
                        </a>
                    @endif
                    @if($negocio->neg_direccion)
                        <div class="contacto-row">
                            <div class="contacto-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <span style="padding-top:5px;">{{ $negocio->neg_direccion }}</span>
                        </div>
                    @endif
                    @if($negocio->neg_pais)
                        <div class="contacto-row">
                            <div class="contacto-icon"><i class="fas fa-globe-americas"></i></div>
                            <span style="padding-top:5px;">{{ $negocio->neg_pais }}</span>
                        </div>
                    @endif
                </div>
                @if($negocio->neg_telefono)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $negocio->neg_telefono) }}"
                   target="_blank"
                   style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:14px;padding:10px;background:#25D366;color:#fff;font-size:0.82rem;font-weight:700;border-radius:10px;text-decoration:none;transition:all 0.2s;box-shadow:0 2px 8px rgba(37,211,102,0.25);"
                   onmouseover="this.style.background='#20bd5a';this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.background='#25D366';this.style.transform='none'">
                    <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i> Escribir por WhatsApp
                </a>
                @endif

                @if($negocio->neg_facebook || $negocio->neg_instagram || $negocio->neg_sitio_web)
                <div style="display:flex;gap:10px;margin-top:14px;padding-top:14px;border-top:1px solid #f3f4f6;">
                    @if($negocio->neg_facebook)
                        <a href="{{ $negocio->neg_facebook }}" target="_blank"
                           style="width:36px;height:36px;border-radius:10px;background:#f0f0f8;display:flex;align-items:center;justify-content:center;color:#9ca3af;text-decoration:none;transition:all 0.2s;font-size:1rem;"
                           onmouseover="this.style.background='#1877F2';this.style.color='#fff'"
                           onmouseout="this.style.background='#f0f0f8';this.style.color='#9ca3af'">
                            <i class="fab fa-facebook"></i>
                        </a>
                    @endif
                    @if($negocio->neg_instagram)
                        <a href="{{ $negocio->neg_instagram }}" target="_blank"
                           style="width:36px;height:36px;border-radius:10px;background:#f0f0f8;display:flex;align-items:center;justify-content:center;color:#9ca3af;text-decoration:none;transition:all 0.2s;font-size:1rem;"
                           onmouseover="this.style.background='#E4405F';this.style.color='#fff'"
                           onmouseout="this.style.background='#f0f0f8';this.style.color='#9ca3af'">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if($negocio->neg_sitio_web)
                        <a href="{{ $negocio->neg_sitio_web }}" target="_blank"
                           style="width:36px;height:36px;border-radius:10px;background:#f0f0f8;display:flex;align-items:center;justify-content:center;color:#9ca3af;text-decoration:none;transition:all 0.2s;font-size:1rem;"
                           onmouseover="this.style.background='var(--primary)';this.style.color='#fff'"
                           onmouseout="this.style.background='#f0f0f8';this.style.color='#9ca3af'">
                            <i class="fas fa-globe"></i>
                        </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Ubicacion / Mapa --}}
            @if($negocio->neg_latitud && $negocio->neg_longitud)
            <div class="perfil-card fade-up" style="padding:0;overflow:hidden;">
                <div style="padding:1.25rem 1.25rem 10px;">
                    <h2 class="card-title" style="margin-bottom:10px;"><i class="fas fa-map-marker-alt"></i>Ubicacion</h2>
                </div>
                <div class="mapa-container">
                    <div id="mapaUbicacion"></div>
                    <div class="mapa-direccion-overlay">
                        <div class="mapa-direccion-text">
                            <i class="fas fa-location-dot"></i>
                            {{ $negocio->neg_direccion ?? 'Ver en el mapa' }}
                        </div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $negocio->neg_latitud }},{{ $negocio->neg_longitud }}"
                           target="_blank" class="mapa-btn-ir">
                            <i class="fas fa-directions"></i> Como llegar
                        </a>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- STICKY MÓVIL --}}
<div class="sticky-bar">
    <button onclick="window.openAgendarModal()" class="btn-agendar-full">
        <i class="fas fa-calendar-plus"></i> Agendar Cita
    </button>
</div>

@php
    $horariosMap = $negocio->horarios
        ->where('activo', 1)
        ->groupBy('dia_semana')
        ->map(function ($items) {
            return $items->map(function ($h) {
                return [
                    'inicio' => $h->hora_inicio ? substr($h->hora_inicio, 0, 5) : null,
                    'fin'    => $h->hora_fin    ? substr($h->hora_fin, 0, 5) : null,
                ];
            })->values();
        })->toArray();
@endphp
<div id="horariosData" data-map='@json($horariosMap)' style="display:none;"></div>
<div id="agendaData" data-negocio-id="{{ $negocio->id }}" style="display:none;"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const horariosEl = document.getElementById('horariosData');
  let HORARIOS = {};
  if (horariosEl && typeof horariosEl.dataset.map === 'string') {
    try { HORARIOS = JSON.parse(horariosEl.dataset.map || '{}'); } catch { HORARIOS = {}; }
  }
  const fix = v => (v||'').toString().slice(0,5);
  const normalizeRangeArray = arr => (Array.isArray(arr) ? arr : [])
    .map(r => ({ inicio: fix(r.inicio ?? r.hora_inicio), fin: fix(r.fin ?? r.hora_fin) }))
    .filter(r => r.inicio && r.fin);
  const NORM = {};
  Object.keys(HORARIOS).forEach(k => { NORM[k] = normalizeRangeArray(HORARIOS[k]); });
  window.NEGOCIO_HORARIOS = NORM;

  const agendaEl = document.getElementById('agendaData');
  const NEGOCIO_ID = agendaEl?.dataset?.negocioId || null;
  window.TRABAJADOR_HORARIOS = window.TRABAJADOR_HORARIOS || {};
  window.TRABAJADOR_BLOQUEOS = window.TRABAJADOR_BLOQUEOS || {};
  window.RESERVAS = window.RESERVAS || {};

  const pad2 = n => String(n).padStart(2, '0');
  const toYMD = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;

  window.t2m = function(hhmm) { const [h,m]=(hhmm||'0:0').split(':').map(Number); return h*60+m; };
  window.m2t = function(total) { const h=Math.floor(total/60),m=total%60; return `${pad2(h)}:${pad2(m)}`; };
  window.normalizeIntervals = function(raw) {
    return (raw||[]).map(r=>({inicio:fix(r.inicio??r.hora_inicio),fin:fix(r.fin??r.hora_fin)})).filter(r=>r.inicio&&r.fin&&r.fin>r.inicio);
  };
  window.overlapsAny = function(aStart,aEnd,intervals) {
    const A=t2m(aStart),B=t2m(aEnd);
    for(const it of (intervals||[])){const C=t2m(it.inicio),D=t2m(it.fin);if(A<D&&C<B)return true;}
    return false;
  };
  window.generateFreeQuarterSlots = function(minHHMM,maxHHMM,reservas) {
    const out=[],min=t2m(minHHMM),max=t2m(maxHHMM);
    for(let t=min;t<=max;t+=15){const s=m2t(t);if(!overlapsAny(s,m2t(t+15),reservas))out.push(s);}
    return out;
  };
  window.generateValidEnds = function(startHHMM,maxHHMM,reservas) {
    const out=[],start=t2m(startHHMM),max=t2m(maxHHMM);
    for(let t=start+15;t<=max;t+=15){const cand=m2t(t);if(!overlapsAny(startHHMM,cand,reservas))out.push(cand);}
    return out;
  };

  window.cargarCitasDelDia = async function(fecha) {
    if(!NEGOCIO_ID)return;
    try {
      const res=await fetch(`/negocios/${NEGOCIO_ID}/agenda/citas-dia?fecha=${fecha}`,{headers:{'X-Requested-With':'XMLHttpRequest'}});
      const json=await res.json();
      window.RESERVAS[fecha]={};
      for(const c of (Array.isArray(json.items)?json.items:[])){
        const tid=c.trabajador_id??'0';
        if(!window.RESERVAS[fecha][tid])window.RESERVAS[fecha][tid]=[];
        window.RESERVAS[fecha][tid].push({inicio:(c.hora_inicio||'').toString().slice(0,5),fin:(c.hora_fin||'').toString().slice(0,5)});
      }
    } catch(e){console.error('Error cargando citas del día:',e);}
  };

  async function cargarCitasMes(year,month) {
    if(!NEGOCIO_ID)return;
    try {
      const res=await fetch(`/negocios/${NEGOCIO_ID}/agenda/citas-mes?year=${year}&month=${month}`,{headers:{'X-Requested-With':'XMLHttpRequest'}});
      const json=await res.json();
      if(json.ok&&Array.isArray(json.events)&&window.calendar){
        window.calendar.getEvents().filter(ev=>ev.extendedProps?.type==='cita').forEach(ev=>ev.remove());
        json.events.forEach(ev=>{window.calendar.addEvent(ev);});
      }
    } catch(e){console.error('Error cargando citas del mes:',e);}
  }

  const calendarEl=document.getElementById('calendarioCitas');
  if(calendarEl){
    const calendar=new FullCalendar.Calendar(calendarEl,{
      initialView:'dayGridMonth',
      locale:'es',
      height:520,
      headerToolbar:{left:'prev,next today',center:'title',right:''},
      events:[
        @foreach($negocio->bloqueos as $bloqueo)
        {title:'Bloqueado',start:'{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',allDay:true,color:'#dc2626',extendedProps:{blocked:true}},
        @endforeach
      ],
      dayCellClassNames:function(info){
        const today=new Date();today.setHours(0,0,0,0);
        const cell=new Date(info.date);cell.setHours(0,0,0,0);
        const ymd=toYMD(cell);
        const blocked=calendar.getEvents().some(ev=>ev.extendedProps?.blocked&&toYMD(ev.start)===ymd);
        if(cell<today)return['fc-day-past'];
        if(blocked)return['fc-day-blocked'];
        return[];
      },
      datesSet:function(di){cargarCitasMes(di.start.getFullYear(),di.start.getMonth()+1);},
      dateClick:async function(info){
        const ymd=toYMD(info.date);
        const today=new Date();today.setHours(0,0,0,0);
        const clicked=new Date(info.date);clicked.setHours(0,0,0,0);
        if(clicked<today)return;
        const blocked=calendar.getEvents().some(ev=>ev.extendedProps?.blocked&&toYMD(ev.start)===ymd);
        if(blocked)return;
        await window.cargarCitasDelDia(ymd);
        if(typeof window.openAgendarModal==='function')window.openAgendarModal({date:ymd});
      },
    });
    calendar.render();
    window.calendar=calendar;
  }
});
</script>

@if($negocio->neg_latitud && $negocio->neg_longitud)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapaEl = document.getElementById('mapaUbicacion');
    if (!mapaEl) return;

    const lat = {{ $negocio->neg_latitud }};
    const lng = {{ $negocio->neg_longitud }};

    const map = L.map('mapaUbicacion', {
        center: [lat, lng],
        zoom: 16,
        zoomControl: true,
        scrollWheelZoom: false,
        dragging: true,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(map);

    // Custom purple marker
    const markerIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div class="marker-pin"></div><i class="fas fa-store marker-pin-icon"></i>',
        iconSize: [36, 46],
        iconAnchor: [18, 46],
        popupAnchor: [0, -40],
    });

    const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
    marker.bindPopup(
        '<div style="text-align:center;padding:4px 2px;">' +
        '<strong style="color:#5a31d7;font-size:0.85rem;">{{ addslashes($negocio->neg_nombre_comercial ?? $negocio->neg_nombre) }}</strong>' +
        @if($negocio->neg_direccion)
        '<br><span style="font-size:0.75rem;color:#6b7280;">{{ addslashes($negocio->neg_direccion) }}</span>' +
        @endif
        '</div>',
        { closeButton: false, className: 'custom-popup' }
    );
});
</script>
@endif
@endpush