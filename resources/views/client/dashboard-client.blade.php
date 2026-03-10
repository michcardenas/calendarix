{{-- CSS específico del dashboard cliente --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/cliente/cliente-dashboard.css') }}">

{{-- Fix: modales altos — card scrollable cuando excede el viewport --}}
<style>
    .fixed.inset-0.z-50 > div {
        max-height: 90vh !important;
        overflow-y: auto !important;
    }
</style>

{{-- Container principal del dashboard --}}
<div id="clx-dash-container" class="clx-container">

    <!-- Fondo animado -->
    <div class="clx-bg-shapes">
        <div class="clx-shape clx-shape-1"></div>
        <div class="clx-shape clx-shape-2"></div>
        <div class="clx-shape clx-shape-3"></div>
        <div class="clx-shape clx-shape-4"></div>
        <div class="clx-shape clx-shape-5"></div>
    </div>

    <!-- Sidebar -->
    <aside class="clx-sidebar">
        <div class="clx-logo">
            <h1>
                <img src="{{ asset('images/morado.png') }}" alt="Calendarix Logo" style="height: 64px; vertical-align: middle; margin-right: 8px;">
                Calendarix
            </h1>
        </div>

        <div class="clx-user-info">
            @if(auth()->user()->foto)
                <img src="{{ asset(auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" style="width:60px;height:60px;min-width:60px;min-height:60px;max-width:60px;max-height:60px;border-radius:50%;object-fit:cover;border:2px solid rgba(90,49,215,0.15);background:#fff;flex-shrink:0;">
            @else
                <div class="clx-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            @endif
            <div class="clx-user-name">{{ auth()->user()->name }}</div>
            <div class="clx-user-email">{{ auth()->user()->email }}</div>
            @if($plan ?? false)
                <div style="margin-top:6px;">
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:10px;font-size:0.65rem;font-weight:700;background:linear-gradient(135deg,#5a31d7,#7b5ce0);color:#fff;">
                        <i class="fas fa-gem" style="font-size:0.55rem;"></i> {{ $plan->name }}
                    </span>
                </div>
            @endif
        </div>

        <nav>
            <ul class="clx-nav">
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link active" data-clx-page="dashboard">
                        <i class="fas fa-home clx-nav-icon"></i>
                        Dashboard
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="favorites">
                        <i class="fas fa-heart clx-nav-icon"></i>
                        Favoritos
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="history">
                        <i class="fas fa-history clx-nav-icon"></i>
                        Historial
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="profile">
                        <i class="fas fa-user-cog clx-nav-icon"></i>
                        Mi Perfil
                    </a>
                </li>
                <li class="clx-nav-item">
                    @if($misEmpresas->isEmpty())
                        <a href="{{ route('negocio.create') }}" class="clx-nav-link">
                            <i class="fas fa-briefcase clx-nav-icon"></i>
                            Mi Empresa
                        </a>
                    @else
                        <a href="#" class="clx-nav-link" data-clx-toggle="empresa-submenu">
                            <i class="fas fa-briefcase clx-nav-icon"></i>
                            Mi Empresa <i class="fas fa-chevron-down float-end"></i>
                        </a>

                        <ul id="empresa-submenu" class="clx-submenu" style="display: none; padding-left: 1rem;">
                            @foreach ($misEmpresas as $empresa)
                            <li>
                                <a href="{{ route('empresa.dashboard', $empresa->id) }}" class="clx-submenu-link">
                                    {{ $empresa->neg_nombre_comercial ?? 'Sin nombre comercial' }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        </nav>

        <div class="clx-sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="clx-logout-btn">
                    <i class="fas fa-sign-out-alt clx-nav-icon"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenido principal -->
    <main class="clx-main">

        {{-- ===== SECCION: DASHBOARD ===== --}}
        <div data-clx-section="dashboard">

        <!-- Header de bienvenida -->
        <header class="clx-header">
            <div class="clx-welcome">
                <div>
                    <h2>¡Bienvenido de vuelta, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                    <p>
                        Tienes
                        <span id="clx-pending-count">{{ number_format($citasPendientes ?? 0) }} {{ ($citasPendientes ?? 0) === 1 ? 'cita' : 'citas' }}</span>
                        pendientes para esta semana
                    </p>
                </div>
                <div class="clx-quick-actions">
                    <a href="{{ route('negocios.explorar') }}" class="clx-btn clx-btn-primary">
                        <i class="fas fa-plus"></i>
                        Agendar Cita
                    </a>
                </div>
                <div class="text-center mt-6">
                    <a href="{{ route('negocio.create') }}" class="clx-btn clx-btn-primary">
                        Registra tu negocio
                    </a>
                </div>
            </div>
        </header>

        <!-- Estadísticas -->
        <section class="clx-stats">
            <div class="clx-stat-card">
                <div class="clx-stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-appointments">{{ number_format($citosMes ?? $citasMes ?? 0) }}</div>
                <div class="clx-stat-label">Citas este mes</div>
            </div>
            <div class="clx-stat-card">
                <div class="clx-stat-icon success">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-favorites">{{ number_format($favoritosCount ?? 0) }}</div>
                <div class="clx-stat-label">Negocios favoritos</div>
            </div>
            <div class="clx-stat-card">
                <div class="clx-stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-pending">{{ number_format($citasPendientes ?? 0) }}</div>
                <div class="clx-stat-label">Citas pendientes</div>
            </div>
        </section>

        <!-- Contenido principal -->
        <section class="clx-content-grid">
            <!-- Próximas citas -->
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Próximas Citas</h3>
                    @if($misEmpresas->count() > 0)
                        @php
                            $primeraEmpresa = $misEmpresas->first();
                        @endphp
                        <a href="{{ route('empresa.configuracion.citas', $primeraEmpresa->id) }}" class="clx-btn clx-btn-ghost">Ver todas</a>
                    @else
                        <button class="clx-btn clx-btn-ghost" onclick="alert('Aún no tienes empresas registradas')">Ver todas</button>
                    @endif
                </div>
                <div class="clx-card-body">
                    <div id="clx-appointments-list">
                        <!-- Se llena dinámicamente con JS -->
                    </div>
                </div>
            </div>

            <!-- Negocios recomendados -->
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Recomendados</h3>
                    <button class="clx-btn clx-btn-ghost">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
                <div class="clx-card-body">
                    <div id="clx-recommendations-list">
                        <!-- Se llena dinámicamente con JS -->
                    </div>
                </div>
            </div>
        </section>

        {{-- Citas completadas + Dejar reseña --}}
        @if(isset($citasCompletadas) && $citasCompletadas->count())
        <section style="margin-top: 1.5rem;">
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Citas Completadas</h3>
                </div>
                <div class="clx-card-body">
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($citasCompletadas as $citaComp)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.75rem;">
                                <div>
                                    <p style="font-weight: 600; color: #1f2937; font-size: 0.9rem;">
                                        {{ $citaComp->negocio->neg_nombre_comercial ?? 'Negocio' }}
                                    </p>
                                    <p style="font-size: 0.8rem; color: #6b7280;">
                                        {{ $citaComp->servicio->nombre ?? 'Servicio' }}
                                        @if($citaComp->trabajador) &middot; {{ $citaComp->trabajador->nombre }} @endif
                                        &middot; {{ \Carbon\Carbon::parse($citaComp->fecha)->format('d/m/Y') }}
                                    </p>
                                </div>
                                @if(in_array($citaComp->id, $resenasExistentes ?? []))
                                    <span style="font-size: 0.75rem; color: #32ccbc; font-weight: 500;">
                                        <i class="fas fa-check-circle"></i> Reseñada
                                    </span>
                                @else
                                    <button onclick="document.getElementById('modalResena{{ $citaComp->id }}').classList.remove('hidden')"
                                            style="background-color: #5a31d7; color: white; border: none; padding: 0.4rem 1rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 500; cursor: pointer;">
                                        <i class="fas fa-star"></i> Dejar reseña
                                    </button>
                                @endif
                            </div>

                            {{-- Modal de reseña --}}
                            @if(!in_array($citaComp->id, $resenasExistentes ?? []))
                            <div id="modalResena{{ $citaComp->id }}" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
                                <div style="background: white; border-radius: 1rem; padding: 1.5rem; width: 100%; max-width: 28rem; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                                    <button onclick="document.getElementById('modalResena{{ $citaComp->id }}').classList.add('hidden')"
                                            style="position: absolute; top: 0.75rem; right: 0.75rem; background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 1.1rem;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <h2 style="font-size: 1.1rem; font-weight: 700; color: #5a31d7; margin-bottom: 0.25rem;">Dejar Reseña</h2>
                                    <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 1rem;">
                                        {{ $citaComp->negocio->neg_nombre_comercial ?? '' }} &middot; {{ $citaComp->servicio->nombre ?? '' }}
                                    </p>
                                    <form action="{{ route('resenas.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cita_id" value="{{ $citaComp->id }}">

                                        <div style="margin-bottom: 1rem;">
                                            <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Calificación</label>
                                            <div class="star-rating-input" data-cita="{{ $citaComp->id }}" style="display: flex; gap: 0.25rem;">
                                                @for($s = 1; $s <= 5; $s++)
                                                    <label style="cursor: pointer;">
                                                        <input type="radio" name="rating" value="{{ $s }}" required style="display: none;">
                                                        <i class="fas fa-star star-icon" data-value="{{ $s }}" style="font-size: 1.5rem; color: #d1d5db; transition: color 0.15s;"></i>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 1rem;">
                                            <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Comentario</label>
                                            <textarea name="comentario" rows="3" required maxlength="1000" placeholder="Contanos tu experiencia..."
                                                      style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.9rem; resize: vertical; outline: none; font-family: inherit;"></textarea>
                                        </div>

                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                            <button type="button" onclick="document.getElementById('modalResena{{ $citaComp->id }}').classList.add('hidden')"
                                                    style="padding: 0.5rem 1rem; background: #e5e7eb; color: #374151; border: none; border-radius: 0.5rem; font-size: 0.85rem; cursor: pointer;">
                                                Cancelar
                                            </button>
                                            <button type="submit"
                                                    style="padding: 0.5rem 1rem; background: #5a31d7; color: white; border: none; border-radius: 0.5rem; font-size: 0.85rem; cursor: pointer; font-weight: 500;">
                                                Enviar Reseña
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif

        </div>{{-- /data-clx-section="dashboard" --}}

        {{-- ===== SECCION: MI PERFIL ===== --}}
        <div data-clx-section="profile" style="display:none;">

            <style>
                .profile-section { max-width: 720px; }
                .profile-card {
                    background: #fff;
                    border: 1px solid #ece9f8;
                    border-radius: 18px;
                    padding: 1.75rem;
                    box-shadow: 0 1px 4px rgba(90,49,215,0.06);
                    margin-bottom: 1.25rem;
                }
                .profile-card-title {
                    font-size: 1.1rem;
                    font-weight: 800;
                    color: #5a31d7;
                    margin: 0 0 1.25rem 0;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .plan-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 5px 14px;
                    border-radius: 20px;
                    font-size: 0.78rem;
                    font-weight: 700;
                }
                .plan-badge-free {
                    background: #f0ecfb;
                    color: #5a31d7;
                }
                .plan-badge-pro {
                    background: linear-gradient(135deg, #5a31d7, #7b5ce0);
                    color: #fff;
                }
                .plan-features {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    margin-top: 12px;
                }
                .plan-feature {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    font-size: 0.75rem;
                    font-weight: 600;
                    color: #6b7280;
                    background: #f9fafb;
                    padding: 4px 10px;
                    border-radius: 8px;
                    border: 1px solid #f3f4f6;
                }
                .plan-feature i { font-size: 0.65rem; }
                .plan-feature-on i { color: #10b981; }
                .plan-feature-off i { color: #d1d5db; }

                .profile-avatar-wrapper {
                    display: flex;
                    align-items: center;
                    gap: 1.25rem;
                    margin-bottom: 1.5rem;
                }
                .profile-avatar-img {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    object-fit: cover;
                    border: 3px solid #ece9f8;
                }
                .profile-avatar-placeholder {
                    width: 80px;
                    height: 80px;
                    min-width: 80px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #5a31d7, #7b5ce0);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    font-weight: 800;
                    font-size: 1.6rem;
                }
                .profile-avatar-info h3 {
                    font-size: 1.15rem;
                    font-weight: 700;
                    color: #1f2937;
                    margin: 0 0 2px 0;
                }
                .profile-avatar-info p {
                    font-size: 0.82rem;
                    color: #9ca3af;
                    margin: 0;
                }

                .profile-form-group {
                    margin-bottom: 1rem;
                }
                .profile-form-group label {
                    display: block;
                    font-size: 0.82rem;
                    font-weight: 700;
                    color: #374151;
                    margin-bottom: 5px;
                }
                .profile-form-group input[type="text"],
                .profile-form-group input[type="email"],
                .profile-form-group input[type="tel"] {
                    width: 100%;
                    padding: 10px 14px;
                    border: 1px solid #e5e7eb;
                    border-radius: 10px;
                    font-size: 0.88rem;
                    color: #374151;
                    font-family: inherit;
                    outline: none;
                    transition: all 0.2s;
                    background: #fff;
                }
                .profile-form-group input:focus {
                    border-color: #5a31d7;
                    box-shadow: 0 0 0 3px rgba(90,49,215,0.1);
                }
                .profile-form-row {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 1rem;
                }
                @media (max-width: 600px) {
                    .profile-form-row { grid-template-columns: 1fr; }
                }
                .profile-btn-save {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 10px 24px;
                    font-size: 0.88rem;
                    font-weight: 700;
                    color: #fff;
                    background: #5a31d7;
                    border: none;
                    border-radius: 10px;
                    cursor: pointer;
                    transition: all 0.2s;
                    box-shadow: 0 2px 8px rgba(90,49,215,0.25);
                }
                .profile-btn-save:hover {
                    background: #7b5ce0;
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(90,49,215,0.35);
                }
                .profile-alert {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding: 12px 16px;
                    border-radius: 12px;
                    font-size: 0.82rem;
                    margin-bottom: 1rem;
                }
                .profile-alert-success {
                    background: #ecfdf5;
                    border: 1px solid #a7f3d0;
                    color: #065f46;
                }
                .profile-alert-error {
                    background: #fef2f2;
                    border: 1px solid #fecaca;
                    color: #991b1b;
                }
            </style>

            <div class="profile-section">

                {{-- Header --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <div>
                        <h1 style="font-size:1.5rem;font-weight:800;color:#5a31d7;margin:0;">
                            <i class="fas fa-user-cog" style="margin-right:8px;opacity:0.7;"></i>Mi Perfil
                        </h1>
                        <p style="font-size:0.82rem;color:#9ca3af;margin:4px 0 0 0;">Administra tu informacion personal y plan.</p>
                    </div>
                </div>

                {{-- Alerta exito --}}
                @if(session('profile_success'))
                    <div class="profile-alert profile-alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('profile_success') }}
                    </div>
                @endif

                {{-- Errores de validacion --}}
                @if($errors->any())
                    <div class="profile-alert profile-alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Plan card --}}
                <div class="profile-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                        <div>
                            <div class="profile-card-title" style="margin-bottom:8px;">
                                <i class="fas fa-crown"></i> Tu Plan
                            </div>

                            @if($plan ?? false)
                                {{-- PLAN PAGO --}}
                                <span class="plan-badge plan-badge-pro">
                                    <i class="fas fa-gem"></i> {{ $plan->name }}
                                </span>
                                @if($subscription)
                                    <span style="font-size:0.72rem;color:#9ca3af;margin-left:8px;">
                                        Desde {{ $subscription->starts_at->format('d/m/Y') }}
                                        @if($subscription->ends_at)
                                            — Hasta {{ $subscription->ends_at->format('d/m/Y') }}
                                        @endif
                                    </span>
                                @endif
                            @else
                                {{-- SIN PLAN --}}
                                <span class="plan-badge plan-badge-free">
                                    <i class="fas fa-tag"></i> Sin plan activo
                                </span>
                                <span style="font-size:0.72rem;color:#9ca3af;margin-left:8px;">
                                    Elegi un plan para acceder a todas las funciones
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($plan ?? false)
                        <div class="plan-features">
                            <span class="plan-feature {{ $plan->crm_ia_enabled ? 'plan-feature-on' : 'plan-feature-off' }}">
                                <i class="fas {{ $plan->crm_ia_enabled ? 'fa-check-circle' : 'fa-times-circle' }}"></i> CRM & IA
                            </span>
                            <span class="plan-feature {{ $plan->multi_branch_enabled ? 'plan-feature-on' : 'plan-feature-off' }}">
                                <i class="fas {{ $plan->multi_branch_enabled ? 'fa-check-circle' : 'fa-times-circle' }}"></i> Multi-sucursal
                            </span>
                            <span class="plan-feature {{ $plan->whatsapp_reminders ? 'plan-feature-on' : 'plan-feature-off' }}">
                                <i class="fas {{ $plan->whatsapp_reminders ? 'fa-check-circle' : 'fa-times-circle' }}"></i> Recordatorios WhatsApp
                            </span>
                            <span class="plan-feature {{ $plan->email_reminders ? 'plan-feature-on' : 'plan-feature-off' }}">
                                <i class="fas {{ $plan->email_reminders ? 'fa-check-circle' : 'fa-times-circle' }}"></i> Recordatorios Email
                            </span>
                            @if($plan->max_professionals)
                                <span class="plan-feature plan-feature-on">
                                    <i class="fas fa-users"></i> {{ $plan->max_professionals }} profesionales
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="plan-features">
                            <span class="plan-feature plan-feature-on"><i class="fas fa-check-circle"></i> Agendar citas</span>
                            <span class="plan-feature plan-feature-on"><i class="fas fa-check-circle"></i> Perfil de negocio</span>
                            <span class="plan-feature plan-feature-on"><i class="fas fa-check-circle"></i> Calendario</span>
                        </div>
                    @endif
                </div>

                {{-- Profile form --}}
                <div class="profile-card">
                    <div class="profile-card-title">
                        <i class="fas fa-id-card"></i> Informacion Personal
                    </div>

                    <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- Avatar --}}
                        <div class="profile-avatar-wrapper">
                            @if(auth()->user()->foto)
                                <img src="{{ asset(auth()->user()->foto) }}" alt="Foto" class="profile-avatar-img" id="profileAvatarPreview">
                            @else
                                <div class="profile-avatar-placeholder" id="profileAvatarPlaceholder">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <img src="" alt="Foto" class="profile-avatar-img" id="profileAvatarPreview" style="display:none;">
                            @endif
                            <div class="profile-avatar-info">
                                <h3>{{ auth()->user()->name }}</h3>
                                <p>{{ auth()->user()->email }}</p>
                                <label style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:5px 12px;font-size:0.75rem;font-weight:600;color:#5a31d7;background:#f0ecfb;border-radius:8px;cursor:pointer;">
                                    <i class="fas fa-camera" style="font-size:0.65rem;"></i> Cambiar foto
                                    <input type="file" name="foto" accept="image/*" style="display:none;" onchange="previewProfilePhoto(this)">
                                </label>
                            </div>
                        </div>

                        {{-- Fields --}}
                        <div class="profile-form-row">
                            <div class="profile-form-group">
                                <label for="profile-name">Nombre completo</label>
                                <input type="text" id="profile-name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="profile-form-group">
                                <label for="profile-email">Correo electronico</label>
                                <input type="email" id="profile-email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                        </div>

                        <div class="profile-form-row">
                            <div class="profile-form-group">
                                <label for="profile-dni">Documento (CI / DNI)</label>
                                <input type="text" id="profile-dni" name="dni" value="{{ old('dni', auth()->user()->dni) }}" placeholder="Ej: 12345678">
                            </div>
                            <div class="profile-form-group">
                                <label for="profile-celular">Celular</label>
                                <input type="tel" id="profile-celular" name="celular" value="{{ old('celular', auth()->user()->celular) }}" placeholder="Ej: 099 123 456">
                            </div>
                        </div>

                        <div class="profile-form-row">
                            <div class="profile-form-group">
                                <label for="profile-pais">Pais</label>
                                <input type="text" id="profile-pais" name="pais" value="{{ old('pais', auth()->user()->pais) }}" placeholder="Ej: Uruguay">
                            </div>
                            <div class="profile-form-group">
                                <label for="profile-ciudad">Ciudad</label>
                                <input type="text" id="profile-ciudad" name="ciudad" value="{{ old('ciudad', auth()->user()->ciudad) }}" placeholder="Ej: Montevideo">
                            </div>
                        </div>

                        <div style="margin-top:0.5rem;">
                            <button type="submit" class="profile-btn-save">
                                <i class="fas fa-save"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>{{-- /data-clx-section="profile" --}}

    </main>
</div>

{{-- Star rating interactive JS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.star-rating-input').forEach(function(container) {
        const stars = container.querySelectorAll('.star-icon');
        stars.forEach(function(star) {
            star.addEventListener('mouseenter', function() {
                const val = parseInt(this.dataset.value);
                stars.forEach(function(s) {
                    s.style.color = parseInt(s.dataset.value) <= val ? '#facc15' : '#d1d5db';
                });
            });
            star.addEventListener('click', function() {
                const val = parseInt(this.dataset.value);
                this.closest('label').querySelector('input').checked = true;
                stars.forEach(function(s) {
                    s.dataset.selected = parseInt(s.dataset.value) <= val ? '1' : '0';
                    s.style.color = parseInt(s.dataset.value) <= val ? '#facc15' : '#d1d5db';
                });
            });
        });
        container.addEventListener('mouseleave', function() {
            stars.forEach(function(s) {
                s.style.color = s.dataset.selected === '1' ? '#facc15' : '#d1d5db';
            });
        });
    });
});
</script>

{{-- Photo preview + auto-navigate to profile on success --}}
<script>
function previewProfilePhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('profileAvatarPreview');
            var placeholder = document.getElementById('profileAvatarPlaceholder');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = '';
            }
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// If redirected back with profile_success, auto-navigate to profile section
document.addEventListener('DOMContentLoaded', function() {
    @if(session('profile_success'))
        setTimeout(function() {
            var profileLink = document.querySelector('[data-clx-page="profile"]');
            if (profileLink) profileLink.click();
        }, 100);
    @endif

    @if($errors->any())
        setTimeout(function() {
            var profileLink = document.querySelector('[data-clx-page="profile"]');
            if (profileLink) profileLink.click();
        }, 100);
    @endif
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleLink = document.querySelector('[data-clx-toggle="empresa-submenu"]');
        const submenu = document.getElementById('empresa-submenu');

        if (toggleLink && submenu) {
            toggleLink.addEventListener('click', function(e) {
                e.preventDefault();
                submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
            });
        }
    });
</script>

{{-- JavaScript específico del dashboard cliente --}}
<script src="{{ asset('js/cliente/cliente-dashboard.js') }}"></script>

<script>
/**
 * 1) Empaquetar datos del servidor -> window.clxData
 */
@php
  // --- Citas próximas (sin normalizar estados) ---
  $currentUserId = auth()->id();
  $appointmentsData = ($proximasCitas ?? collect())->map(function($c) use ($currentUserId) {
      // Fecha (string -> Carbon)
      $fechaStr = $c->fecha ?? null;
      $fecha    = $fechaStr ? \Illuminate\Support\Carbon::parse($fechaStr) : null;

      // Formateo de horas HH:mm desde TIME (HH:mm:ss) o string
      $fmtTime = function ($t) {
          if (!$t) return null;
          $s = (string) $t;
          return substr($s, 0, 5); // "16:10:00" -> "16:10"
      };
      $inicio = $fmtTime($c->hora_inicio ?? null);
      $fin    = $fmtTime($c->hora_fin ?? null);

      // Estado: solo 4 canónicos; fallback a 'pendiente'
      $raw       = strtolower(trim((string)($c->estado ?? '')));
      $permitidos = ['pendiente','confirmada','cancelada','completada'];
      $statusEs  = in_array($raw, $permitidos, true) ? $raw : 'pendiente';

      // Información del servicio y trabajador
      $servicioNombre = optional($c->servicio)->nombre ?? null;
      $trabajadorNombre = optional($c->trabajador)->nombre ?? null;

      // 🎯 Identificar tipo de cita
      $esMiCita = $c->user_id == $currentUserId;
      $tipo = $esMiCita ? 'cliente' : 'negocio'; // soy cliente vs es en mi negocio

      // Construir descripción del servicio con trabajador
      $serviceDescription = $servicioNombre ?? $c->notas ?? 'Cita';
      if ($trabajadorNombre) {
          $serviceDescription .= ' con ' . $trabajadorNombre;
      }

      // Nombre del cliente (solo para citas de negocio)
      $clienteNombre = !$esMiCita ? (optional($c->user)->name ?? $c->nombre_cliente ?? 'Cliente') : null;

      return [
          // Texto compacto para cards/listas
          'time'     => ($fecha ? $fecha->format('d/m/Y') : '—') . ($inicio ? ' • '.$inicio.($fin ? '–'.$fin : '') : ''),
          // Datos desglosados
          'date'     => $fecha ? $fecha->format('Y-m-d') : null,
          'start'    => $inicio,
          'end'      => $fin,
          'client'   => $clienteNombre, // Nombre del cliente (solo para citas de negocio)
          'service'  => $serviceDescription,
          'business' => $c->negocio?->neg_nombre_comercial ?? '—',
          'status'   => $statusEs,   // ES canónico
          'trabajador' => $trabajadorNombre,
          'type'     => $tipo, // 'cliente' o 'negocio'
      ];
  })->values();

  // --- Recomendados (sin los del usuario autenticado) ---
  $currentUserId = auth()->id();
  $recomendadosFiltrados = ($recomendados ?? collect())
      ->filter(function ($n) use ($currentUserId) {
          $ownerId =
              $n->user_id
              ?? $n->owner_id
              ?? $n->usuario_id
              ?? optional($n->owner)->id
              ?? optional($n->usuario)->id
              ?? null;
          return (int) $ownerId !== (int) $currentUserId;
      })
      ->unique('id');

  $recoData = $recomendadosFiltrados->map(function($n) {
      return [
          'id'       => $n->id,
          'slug'     => $n->slug ?? \Illuminate\Support\Str::slug($n->neg_nombre_comercial ?? 'negocio'),
          'name'     => $n->neg_nombre_comercial ?? 'Negocio',
          'service'  => $n->neg_categoria ?? 'Servicio',
          'rating'   => $n->rating ?? '4.8',
          'distance' => $n->distance ?? 'cerca',
      ];
  })->values();

  // --- Stats desde servidor ---
  // Nota: "pending" = activas (pendiente + confirmada) según tu controller
  $serverStats = [
      'appointmentsMonth' => (int)($citasMes ?? 0),
      'favorites'         => (int)($favoritosCount ?? 0),
      'pending'           => (int)($citasPendientes ?? 0),
  ];

  // 🔍 LOG PHP: Debug de datos antes de pasar a JavaScript
  \Log::info('Dashboard Cliente - Datos a JavaScript', [
      'user_id' => auth()->id(),
      'appointments_count' => $appointmentsData->count(),
      'recommendations_count' => $recoData->count(),
      'stats' => $serverStats,
      'appointments_sample' => $appointmentsData->take(3)->toArray(), // Primeras 3 citas como muestra
  ]);
@endphp


window.clxData = {
  appointments: @json($appointmentsData),
  recommendations: @json($recoData),
  stats: @json($serverStats),
};

// 🔍 LOG: Debug en consola (solo en desarrollo o cuando haya problemas)
if (window.location.hostname === 'localhost' || window.location.search.includes('debug=1')) {
  console.group('🔍 Dashboard Cliente - Debug Data');
  console.log('📊 Stats:', window.clxData.stats);
  console.log('📅 Appointments Count:', window.clxData.appointments?.length || 0);
  console.log('📅 Appointments Data:', window.clxData.appointments);
  console.log('🏪 Recommendations Count:', window.clxData.recommendations?.length || 0);
  console.log('🏪 Recommendations Data:', window.clxData.recommendations);
  console.groupEnd();
}

/**
 * 2) Helper para animar números (por si no existe en cliente-dashboard.js)
 */
window.clxAnimateNumber = window.clxAnimateNumber || function(el, finalValue, duration = 600) {
  if (!el) return;
  const start = 0;
  const startTime = performance.now();
  const fmt = new Intl.NumberFormat('es-CO');
  function tick(now) {
    const p = Math.min(1, (now - startTime) / duration);
    const val = Math.round(start + (finalValue - start) * p);
    el.textContent = fmt.format(val);
    if (p < 1) requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);
};

/** 3) Navegación a /negocios/{id}-{slug} */
const NEGOCIOS_BASE = @json(url('/negocios'));
window.clxViewBusiness = function(id, slug) {
  const s = (slug ?? '').toString();
  window.location.href = `${NEGOCIOS_BASE}/${id}-${encodeURIComponent(s)}`;
};

/**
 * 4) Llamar las funciones cuando el DOM esté listo
 */
document.addEventListener('DOMContentLoaded', () => {
  if (window.clxData) {
    if (typeof clxLoadAppointments === 'function') clxLoadAppointments();
    if (typeof clxLoadRecommendations === 'function') clxLoadRecommendations();

    // Stats desde el servidor
    const s = window.clxData.stats || {appointmentsMonth: 0, favorites: 0, pending: 0};
    const statAppointments = document.getElementById('clx-stat-appointments');
    const statFavorites    = document.getElementById('clx-stat-favorites');
    const statPending      = document.getElementById('clx-stat-pending');
    const pendingCount     = document.getElementById('clx-pending-count');

    if (statAppointments) clxAnimateNumber(statAppointments, s.appointmentsMonth);
    if (statFavorites)    clxAnimateNumber(statFavorites,    s.favorites);
    if (statPending)      clxAnimateNumber(statPending,      s.pending);
    if (pendingCount)     pendingCount.textContent = `${s.pending} ${s.pending === 1 ? 'cita' : 'citas'}`;
  }
});
</script>
