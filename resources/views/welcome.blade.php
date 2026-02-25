@extends('layouts.app_b')

@section('title', 'Calendarix - Reserva cualquier servicio cerca de ti')

@section('styles')
<style>
    /* ============================================
       WELCOME PAGE - HERO & SECTIONS
       ============================================ */

    /* HERO SECTION - Altura automática basada en contenido */
    .hero {
        background:
            linear-gradient(135deg, rgba(90, 49, 215, 0.88) 0%, rgba(50, 204, 188, 0.82) 100%),
            url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        padding: 4rem 1.5rem 5rem;
        text-align: center;
    }

    .hero-content {
        max-width: 900px;
        margin: 0 auto;
    }

    .hero-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: clamp(2rem, 5vw, 3.25rem);
        font-weight: 700;
        color: var(--white);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: 1.125rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 2.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    /* SEARCH BAR */
    .search-bar {
        background: var(--white);
        border-radius: 12px;
        padding: 0.5rem;
        display: flex;
        align-items: stretch;
        max-width: 900px;
        margin: 0 auto 2.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .search-field {
        flex: 1;
        display: flex;
        align-items: center;
        padding: 0.875rem 1rem;
        border-right: 1px solid var(--gray-200);
        min-width: 0;
    }

    .search-field:nth-child(3) {
        flex: 0.8;
        border-right: none;
    }

    .search-field i {
        color: var(--gray-400);
        font-size: 1rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .search-field input,
    .search-field select {
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.9375rem;
        color: var(--gray-800);
        width: 100%;
        font-family: inherit;
    }

    .search-field input::placeholder {
        color: var(--gray-400);
    }

    .search-field select {
        cursor: pointer;
        -webkit-appearance: none;
        appearance: none;
    }

    .search-btn {
        background: var(--secondary);
        color: var(--white);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
        font-family: inherit;
        flex-shrink: 0;
    }

    .search-btn:hover {
        background: #28b3a5;
    }

    /* CATEGORY PILLS */
    .category-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.625rem;
        justify-content: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .category-pill {
        background: rgba(255,255,255,0.15);
        color: var(--white);
        padding: 0.5rem 1.25rem;
        border-radius: 100px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.3);
        transition: all var(--transition-fast);
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .category-pill:hover {
        background: var(--white);
        color: var(--primary-500);
        border-color: var(--white);
        transform: translateY(-2px);
    }

    /* ============================================
       SECTIONS
       ============================================ */
    .section {
        padding: 4rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }

    .section-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
    }

    .section-link {
        color: var(--primary-500);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        transition: color var(--transition-fast);
    }

    .section-link:hover {
        color: var(--primary-600);
    }

    /* ============================================
       BUSINESS CARDS GRID
       ============================================ */
    .business-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .business-card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
        transition: all var(--transition-base);
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .business-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
        border-color: var(--primary-300);
    }

    .business-card-image {
        position: relative;
        height: 180px;
        overflow: hidden;
        background: var(--gray-100);
    }

    .business-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }

    .business-card:hover .business-card-image img {
        transform: scale(1.05);
    }

    .business-card-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        background: var(--secondary);
        color: var(--white);
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .business-card-badge--discount {
        background: var(--accent);
        color: var(--gray-900);
    }

    .business-card-content {
        padding: 1rem;
    }

    .business-card-name {
        font-weight: 600;
        font-size: 1rem;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
    }

    .business-card-category {
        font-size: 0.875rem;
        color: var(--gray-500);
        margin-bottom: 0.5rem;
    }

    .business-card-rating {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.875rem;
    }

    .business-card-rating i {
        color: #fbbf24;
    }

    .business-card-rating span {
        font-weight: 600;
        color: var(--gray-800);
    }

    .business-card-rating .reviews {
        color: var(--gray-500);
        font-weight: 400;
    }

    .business-card-location {
        font-size: 0.8125rem;
        color: var(--gray-500);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    /* ============================================
       FEATURES SECTION
       ============================================ */
    .features-section {
        background: var(--gray-50);
        padding: 5rem 1.5rem;
    }

    .features-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .features-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .features-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.75rem;
    }

    .features-subtitle {
        color: var(--gray-600);
        font-size: 1.0625rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .feature-card {
        background: var(--white);
        padding: 2rem;
        border-radius: 12px;
        text-align: center;
        border: 1px solid var(--gray-200);
        transition: all var(--transition-base);
    }

    .feature-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--secondary);
    }

    .feature-icon {
        width: 4rem;
        height: 4rem;
        background: var(--secondary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: var(--white);
        font-size: 1.5rem;
    }

    .feature-title {
        font-weight: 700;
        font-size: 1.125rem;
        color: var(--primary-500);
        margin-bottom: 0.5rem;
    }

    .feature-description {
        color: var(--gray-600);
        font-size: 0.9375rem;
        line-height: 1.6;
    }

    /* ============================================
       CTA SECTION
       ============================================ */
    .cta-section {
        padding: 5rem 1.5rem;
        text-align: center;
        background: linear-gradient(135deg, var(--primary-500), var(--secondary));
    }

    .cta-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .cta-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 1rem;
    }

    .cta-description {
        color: rgba(255,255,255,0.9);
        font-size: 1.0625rem;
        margin-bottom: 2rem;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-cta-white {
        background: var(--white);
        color: var(--primary-500);
    }

    .btn-cta-white:hover {
        background: var(--primary-50);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-cta-outline {
        background: transparent;
        color: var(--white);
        border: 2px solid rgba(255,255,255,0.5);
    }

    .btn-cta-outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: var(--white);
    }

    /* ============================================
       PRICING SECTION
       ============================================ */
    .pricing-section {
        padding: 5rem 1.5rem;
        background: var(--white);
    }

    .pricing-container {
        max-width: 980px;
        margin: 0 auto;
    }

    .pricing-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .pricing-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.75rem;
    }

    .pricing-subtitle {
        color: var(--gray-600);
        font-size: 1.0625rem;
        max-width: 520px;
        margin: 0 auto;
    }

    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .pricing-card {
        background: var(--white);
        border-radius: 16px;
        border: 2px solid var(--gray-200);
        padding: 2rem;
        transition: all var(--transition-base);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .pricing-card:hover {
        box-shadow: var(--shadow-lg);
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
        color: var(--white);
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
        background: var(--gray-50);
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
        border-bottom: 1px solid var(--gray-100);
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
        color: var(--white);
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all var(--transition-fast);
        margin-top: auto;
    }

    .pricing-cta:hover {
        background: var(--primary-600);
        transform: translateY(-1px);
        color: var(--white);
    }

    .pricing-card--featured .pricing-cta {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        box-shadow: 0 4px 14px rgba(90, 49, 215, 0.35);
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 1024px) {
        .business-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .pricing-section {
            padding: 3rem 1rem;
        }

        .pricing-grid {
            grid-template-columns: 1fr;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero {
            padding: 3rem 1rem 4rem;
        }

        .hero-title {
            font-size: 1.75rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .search-bar {
            flex-direction: column;
            padding: 1rem;
            gap: 0;
        }

        .search-field {
            border-right: none;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 0.5rem;
        }

        .search-field:nth-child(3) {
            border-bottom: none;
            flex: 1;
        }

        .search-btn {
            width: 100%;
            margin-top: 0.75rem;
            padding: 1rem;
        }

        .business-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .section {
            padding: 3rem 1rem;
        }

        .features-section {
            padding: 3rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .business-grid {
            grid-template-columns: 1fr;
        }

        .category-pills {
            gap: 0.5rem;
        }

        .category-pill {
            padding: 0.375rem 1rem;
            font-size: 0.8125rem;
        }

        .cta-buttons {
            flex-direction: column;
        }

        .cta-buttons .btn {
            width: 100%;
        }

        .hero-title {
            font-size: 1.5rem;
        }

        .features-title,
        .cta-title {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Reserva cualquier servicio cerca de ti</h1>
            <p class="hero-subtitle">
                Encuentra y agenda con los mejores profesionales y negocios cerca de vos
            </p>

            <!-- SEARCH BAR -->
            <form class="search-bar" action="{{ url('/buscar') }}" method="GET">
                <div class="search-field">
                    <i class="fas fa-search"></i>
                    <input type="text" name="negocio" placeholder="Negocio o ubicación">
                </div>
                <div class="search-field">
                    <i class="fas fa-scissors"></i>
                    <input type="text" name="servicio" placeholder="Buscar servicios">
                </div>
                <div class="search-field">
                    <i class="fas fa-calendar"></i>
                    <select name="fecha">
                        <option value="">Cualquier fecha</option>
                        <option value="hoy">Hoy</option>
                        <option value="manana">Mañana</option>
                        <option value="semana">Esta semana</option>
                        <option value="proxima">Próxima semana</option>
                    </select>
                </div>
                <button type="submit" class="search-btn">Buscar</button>
            </form>

            <!-- CATEGORY PILLS -->
            <div class="category-pills">
                <a href="{{ url('/categoria/belleza') }}" class="category-pill"><i class="fas fa-sparkles"></i> Belleza</a>
                <a href="{{ url('/categoria/bienestar') }}" class="category-pill"><i class="fas fa-heart"></i> Bienestar</a>
                <a href="{{ url('/categoria/cuidados') }}" class="category-pill"><i class="fas fa-shield-alt"></i> Cuidados</a>
                <a href="{{ url('/categoria/fitness') }}" class="category-pill"><i class="fas fa-bolt"></i> Fitness</a>
                <a href="{{ url('/categoria/deportes') }}" class="category-pill"><i class="fas fa-trophy"></i> Deportes</a>
                <a href="{{ url('/categoria/educacion') }}" class="category-pill"><i class="fas fa-graduation-cap"></i> Educación</a>
                <a href="{{ url('/categoria/hogar') }}" class="category-pill"><i class="fas fa-home"></i> Hogar</a>
                <a href="{{ url('/categoria/mascotas') }}" class="category-pill"><i class="fas fa-paw"></i> Mascotas</a>
            </div>
        </div>
    </section>

    <!-- POPULAR SECTION -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Popular en tu zona</h2>
            <a href="{{ url('/negocios') }}" class="section-link">
                Ver todos <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <div class="business-grid">
            <!-- Card 1 -->
            <a href="#" class="business-card">
                <div class="business-card-image">
                    <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Salón de belleza">
                    <span class="business-card-badge">Nuevo</span>
                </div>
                <div class="business-card-content">
                    <h3 class="business-card-name">Studio Hair & Beauty</h3>
                    <p class="business-card-category">Salón de belleza</p>
                    <div class="business-card-rating">
                        <i class="fas fa-star"></i>
                        <span>4.9</span>
                        <span class="reviews">(127 reseñas)</span>
                    </div>
                    <p class="business-card-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Chapinero, Bogotá
                    </p>
                </div>
            </a>

            <!-- Card 2 -->
            <a href="#" class="business-card">
                <div class="business-card-image">
                    <img src="https://images.unsplash.com/photo-1600948836101-f9ffda59d250?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Barbería">
                </div>
                <div class="business-card-content">
                    <h3 class="business-card-name">The Gentleman's Barber</h3>
                    <p class="business-card-category">Barbería</p>
                    <div class="business-card-rating">
                        <i class="fas fa-star"></i>
                        <span>4.8</span>
                        <span class="reviews">(89 reseñas)</span>
                    </div>
                    <p class="business-card-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Usaquén, Bogotá
                    </p>
                </div>
            </a>

            <!-- Card 3 -->
            <a href="#" class="business-card">
                <div class="business-card-image">
                    <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Spa">
                </div>
                <div class="business-card-content">
                    <h3 class="business-card-name">Zen Spa & Wellness</h3>
                    <p class="business-card-category">Spa y masajes</p>
                    <div class="business-card-rating">
                        <i class="fas fa-star"></i>
                        <span>5.0</span>
                        <span class="reviews">(203 reseñas)</span>
                    </div>
                    <p class="business-card-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Zona G, Bogotá
                    </p>
                </div>
            </a>

            <!-- Card 4 -->
            <a href="#" class="business-card">
                <div class="business-card-image">
                    <img src="https://images.unsplash.com/photo-1604654894610-df63bc536371?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Uñas">
                    <span class="business-card-badge business-card-badge--discount">-20%</span>
                </div>
                <div class="business-card-content">
                    <h3 class="business-card-name">Nail Art Studio</h3>
                    <p class="business-card-category">Manicure y pedicure</p>
                    <div class="business-card-rating">
                        <i class="fas fa-star"></i>
                        <span>4.7</span>
                        <span class="reviews">(156 reseñas)</span>
                    </div>
                    <p class="business-card-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Cedritos, Bogotá
                    </p>
                </div>
            </a>
        </div>
    </section>

    <!-- PRICING SECTION -->
    @if(isset($plans) && $plans->isNotEmpty())
    <section class="pricing-section">
        <div class="pricing-container">
            <div class="pricing-header">
                <h2 class="pricing-title">Planes para tu negocio</h2>
                <p class="pricing-subtitle">
                    Elige el plan que mejor se adapte al tamaño y necesidades de tu negocio
                </p>
            </div>

            <div class="pricing-grid">
                @foreach($plans as $plan)
                    @php
                        $isFeatured = $loop->last && $plans->count() > 1;
                        $intervalLabel = $plan->interval === 'monthly' ? '/mes' : '/año';
                        $currencySymbol = match($plan->currency) {
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
                        @if($isFeatured)
                            <span class="pricing-popular-badge">
                                <i class="fas fa-star"></i> Más popular
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

                        <a href="{{ route('register') }}" class="pricing-cta">
                            Comenzar con este plan
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- FEATURES SECTION -->
    <section class="features-section">
        <div class="features-container">
            <div class="features-header">
                <h2 class="features-title">¿Por qué elegir Calendarix?</h2>
                <p class="features-subtitle">
                    La plataforma más confiable para reservar servicios profesionales
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Reserva 24/7</h3>
                    <p class="feature-description">
                        Agenda citas en cualquier momento. Disponibilidad actualizada en tiempo real.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Pagos Seguros</h3>
                    <p class="feature-description">
                        Transacciones protegidas. Si algo sale mal, te devolvemos tu dinero.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="feature-title">Profesionales Verificados</h3>
                    <p class="feature-description">
                        Todos los profesionales tienen reseñas reales de clientes como tú.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="feature-title">Recordatorios</h3>
                    <p class="feature-description">
                        Recibe notificaciones por email y WhatsApp para no olvidar tu cita.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Gestión Fácil</h3>
                    <p class="feature-description">
                        Cancela o reprograma citas fácilmente desde cualquier dispositivo.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <h3 class="feature-title">Ofertas Exclusivas</h3>
                    <p class="feature-description">
                        Accede a descuentos especiales solo disponibles en nuestra plataforma.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="cta-container">
            <h2 class="cta-title">¿Tenés un negocio?</h2>
            <p class="cta-description">
                Únete a miles de profesionales que usan Calendarix para gestionar sus citas,
                atraer nuevos clientes y hacer crecer su negocio.
            </p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-cta-white btn-lg">
                    <i class="fas fa-rocket"></i>
                    Registrar mi Negocio
                </a>
                <a href="#" class="btn btn-cta-outline btn-lg">
                    <i class="fas fa-play-circle"></i>
                    Ver cómo funciona
                </a>
            </div>
        </div>
    </section>
@endsection