@extends('layouts.app_b')

@section('title', 'Explorar Negocios - Calendarix')

@section('styles')
<style>
    /* ============================================
       EXPLORAR NEGOCIOS
       ============================================ */

    .explorar-hero {
        background: linear-gradient(135deg, rgba(90, 49, 215, 0.88) 0%, rgba(50, 204, 188, 0.82) 100%);
        padding: 2.5rem 1.5rem 3rem;
        text-align: center;
    }

    .explorar-hero h1 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: clamp(1.5rem, 4vw, 2.25rem);
        font-weight: 700;
        color: var(--white);
        margin-bottom: 0.5rem;
    }

    .explorar-hero p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }

    /* Barra de búsqueda */
    .explorar-search {
        display: flex;
        gap: 0;
        max-width: 600px;
        margin: 0 auto;
        background: var(--white);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .explorar-search input {
        flex: 1;
        border: none;
        padding: 0.875rem 1.25rem;
        font-size: 0.95rem;
        font-family: inherit;
        outline: none;
        color: var(--gray-800);
    }

    .explorar-search input::placeholder {
        color: var(--gray-400);
    }

    .explorar-search button {
        background: var(--secondary);
        color: var(--white);
        border: none;
        padding: 0.875rem 1.75rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        font-family: inherit;
    }

    .explorar-search button:hover {
        background: #28b3a5;
    }

    /* Categorías pills */
    .explorar-categorias {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        max-width: 700px;
        margin: 1.25rem auto 0;
    }

    .explorar-cat-pill {
        background: rgba(255, 255, 255, 0.15);
        color: var(--white);
        padding: 0.375rem 1rem;
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .explorar-cat-pill:hover,
    .explorar-cat-pill.active {
        background: var(--white);
        color: var(--primary-500);
        border-color: var(--white);
    }

    /* Contenido principal */
    .explorar-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
    }

    .explorar-results-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .explorar-results-info p {
        color: var(--gray-600);
        font-size: 0.9rem;
    }

    .explorar-results-info span {
        font-weight: 600;
        color: var(--primary-500);
    }

    /* Grid de negocios */
    .explorar-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .neg-card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .neg-card:hover {
        box-shadow: 0 8px 30px rgba(90, 49, 215, 0.12);
        transform: translateY(-4px);
        border-color: var(--primary-300);
    }

    .neg-card-img {
        position: relative;
        height: 180px;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-100), var(--primary-50));
    }

    .neg-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .neg-card:hover .neg-card-img img {
        transform: scale(1.05);
    }

    .neg-card-img .neg-card-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--primary-300);
    }

    .neg-card-cat-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        background: var(--primary-500);
        color: var(--white);
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .neg-card-body {
        padding: 1rem 1.25rem;
    }

    .neg-card-name {
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--gray-900);
        margin-bottom: 0.2rem;
    }

    .neg-card-category {
        font-size: 0.8125rem;
        color: var(--gray-500);
        margin-bottom: 0.5rem;
    }

    .neg-card-rating {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8125rem;
        margin-bottom: 0.5rem;
    }

    .neg-card-rating i {
        color: #fbbf24;
    }

    .neg-card-rating .rating-val {
        font-weight: 600;
        color: var(--gray-800);
    }

    .neg-card-rating .rating-count {
        color: var(--gray-500);
    }

    .neg-card-location {
        font-size: 0.8rem;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .neg-card-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .neg-card-btn {
        color: var(--primary-500);
        font-size: 0.8125rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
    }

    .neg-card-btn:hover {
        color: var(--primary-600);
    }

    .neg-card-services-count {
        font-size: 0.75rem;
        color: var(--gray-400);
    }

    /* Empty state */
    .explorar-empty {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--gray-500);
    }

    .explorar-empty i {
        font-size: 3rem;
        color: var(--primary-200);
        margin-bottom: 1rem;
    }

    .explorar-empty h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }

    /* Paginación */
    .explorar-pagination {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }

    .explorar-pagination nav {
        display: flex;
        gap: 0.25rem;
    }

    .explorar-pagination .page-link {
        padding: 0.5rem 0.875rem;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        color: var(--gray-700);
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .explorar-pagination .page-link:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        color: var(--primary-500);
    }

    .explorar-pagination .page-item.active .page-link {
        background: var(--primary-500);
        border-color: var(--primary-500);
        color: var(--white);
    }

    .explorar-pagination .page-item.disabled .page-link {
        color: var(--gray-300);
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .explorar-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .explorar-grid {
            grid-template-columns: 1fr;
        }

        .explorar-hero {
            padding: 2rem 1rem 2.5rem;
        }

        .explorar-content {
            padding: 1.5rem 1rem 3rem;
        }
    }
</style>
@endsection

@section('content')
    <!-- Hero con búsqueda -->
    <section class="explorar-hero">
        <h1>Explora negocios en Calendarix</h1>
        <p>Encuentra y agenda con los mejores profesionales</p>

        <form class="explorar-search" action="{{ route('negocios.explorar') }}" method="GET">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar negocio, servicio o ubicación...">
            <button type="submit"><i class="fas fa-search"></i> Buscar</button>
        </form>

        <div class="explorar-categorias">
            <a href="{{ route('negocios.explorar') }}" class="explorar-cat-pill {{ !request('categoria') ? 'active' : '' }}">Todos</a>
            <a href="{{ route('negocios.explorar', ['categoria' => 'belleza']) }}" class="explorar-cat-pill {{ request('categoria') === 'belleza' ? 'active' : '' }}"><i class="fas fa-spa"></i> Belleza</a>
            <a href="{{ route('negocios.explorar', ['categoria' => 'bienestar']) }}" class="explorar-cat-pill {{ request('categoria') === 'bienestar' ? 'active' : '' }}"><i class="fas fa-heart"></i> Bienestar</a>
            <a href="{{ route('negocios.explorar', ['categoria' => 'fitness']) }}" class="explorar-cat-pill {{ request('categoria') === 'fitness' ? 'active' : '' }}"><i class="fas fa-dumbbell"></i> Fitness</a>
            <a href="{{ route('negocios.explorar', ['categoria' => 'salud']) }}" class="explorar-cat-pill {{ request('categoria') === 'salud' ? 'active' : '' }}"><i class="fas fa-heartbeat"></i> Salud</a>
            <a href="{{ route('negocios.explorar', ['categoria' => 'educacion']) }}" class="explorar-cat-pill {{ request('categoria') === 'educacion' ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i> Educacion</a>
            <a href="{{ route('negocios.explorar', ['categoria' => 'mascotas']) }}" class="explorar-cat-pill {{ request('categoria') === 'mascotas' ? 'active' : '' }}"><i class="fas fa-paw"></i> Mascotas</a>
        </div>
    </section>

    <!-- Resultados -->
    <div class="explorar-content">
        <div class="explorar-results-info">
            <p>
                <span>{{ $negocios->total() }}</span>
                {{ $negocios->total() === 1 ? 'negocio encontrado' : 'negocios encontrados' }}
                @if(request('q'))
                    para "<strong>{{ request('q') }}</strong>"
                @endif
                @if(request('categoria'))
                    en <strong>{{ ucfirst(request('categoria')) }}</strong>
                @endif
            </p>
        </div>

        @if($negocios->count())
            <div class="explorar-grid">
                @foreach($negocios as $negocio)
                    @php
                        $imgSrc = null;
                        if ($negocio->neg_portada) {
                            $imgSrc = Str::startsWith($negocio->neg_portada, ['http://', 'https://'])
                                ? $negocio->neg_portada
                                : (Str::startsWith($negocio->neg_portada, '/')
                                    ? $negocio->neg_portada
                                    : asset('storage/' . $negocio->neg_portada));
                        } elseif ($negocio->neg_imagen) {
                            $imgSrc = Str::startsWith($negocio->neg_imagen, ['http://', 'https://'])
                                ? $negocio->neg_imagen
                                : (Str::startsWith($negocio->neg_imagen, '/')
                                    ? $negocio->neg_imagen
                                    : asset('storage/' . $negocio->neg_imagen));
                        }

                        $categorias = is_array($negocio->neg_categorias) ? $negocio->neg_categorias : [];
                        $primeraCat = count($categorias) ? $categorias[0] : null;
                        $rating = $negocio->resenas_avg_rating ? round($negocio->resenas_avg_rating, 1) : null;
                        $totalResenas = $negocio->resenas_count ?? 0;
                    @endphp
                    <a href="{{ route('negocios.show', ['slug' => $negocio->slug]) }}" class="neg-card">
                        <div class="neg-card-img">
                            @if($imgSrc)
                                <img src="{{ $imgSrc }}" alt="{{ $negocio->neg_nombre_comercial }}">
                            @else
                                <div class="neg-card-fallback">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            @if($primeraCat)
                                <span class="neg-card-cat-badge">{{ $primeraCat }}</span>
                            @endif
                        </div>
                        <div class="neg-card-body">
                            <h3 class="neg-card-name">{{ $negocio->neg_nombre_comercial }}</h3>
                            @if(count($categorias))
                                <p class="neg-card-category">{{ implode(' · ', $categorias) }}</p>
                            @endif
                            <div class="neg-card-rating">
                                @if($rating)
                                    <i class="fas fa-star"></i>
                                    <span class="rating-val">{{ $rating }}</span>
                                    <span class="rating-count">({{ $totalResenas }} {{ $totalResenas === 1 ? 'resena' : 'resenas' }})</span>
                                @else
                                    <i class="far fa-star"></i>
                                    <span class="rating-count">Sin resenas aun</span>
                                @endif
                            </div>
                            @if($negocio->neg_direccion)
                                <p class="neg-card-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ Str::limit($negocio->neg_direccion, 40) }}
                                </p>
                            @endif
                        </div>
                        <div class="neg-card-footer">
                            <span class="neg-card-btn">
                                Ver perfil <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($negocios->hasPages())
                <div class="explorar-pagination">
                    {{ $negocios->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="explorar-empty">
                <i class="fas fa-search"></i>
                <h3>No se encontraron negocios</h3>
                <p>Intenta con otros terminos de busqueda o explora todas las categorias.</p>
                <a href="{{ route('negocios.explorar') }}" style="display: inline-block; margin-top: 1rem; color: var(--primary-500); font-weight: 600; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Ver todos los negocios
                </a>
            </div>
        @endif
    </div>
@endsection
