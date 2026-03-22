<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="icon" href="{{ asset('images/morado.png') }}" type="image/png">

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

{{-- Topbar móvil --}}
<div class="clx-topbar">
    <button type="button" class="clx-topbar__hamburger" id="btnHamburguerCliente">
        <i class="fas fa-bars"></i>
    </button>
    <span class="clx-topbar__title" id="clxTopbarTitle">Dashboard</span>
    @if(auth()->user()->foto)
        <img src="{{ asset(auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" class="clx-topbar__avatar">
    @else
        <div class="clx-topbar__avatar-placeholder">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
    @endif
</div>

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
                @if($misEmpresas->count() > 0)
                    @php $primeraEmpNav = $misEmpresas->first(); @endphp
                    <li class="clx-nav-item">
                        <a href="{{ route('empresa.agenda', ['id' => $primeraEmpNav->id]) }}" class="clx-nav-link">
                            <i class="fas fa-calendar-alt clx-nav-icon"></i>
                            Agenda
                        </a>
                    </li>
                    <li class="clx-nav-item">
                        <a href="{{ route('empresa.clientes.index', $primeraEmpNav->id) }}" class="clx-nav-link">
                            <i class="fas fa-users clx-nav-icon"></i>
                            Clientes
                        </a>
                    </li>
                    <li class="clx-nav-item">
                        <a href="{{ route('empresa.catalogo.servicios', $primeraEmpNav->id) }}" class="clx-nav-link">
                            <i class="fas fa-concierge-bell clx-nav-icon"></i>
                            Servicios
                        </a>
                    </li>
                    <li class="clx-nav-item">
                        <a href="{{ route('empresa.dashboard', $primeraEmpNav->id) }}" class="clx-nav-link">
                            <i class="fas fa-chart-bar clx-nav-icon"></i>
                            Panel Empresa
                        </a>
                    </li>
                @else
                    <li class="clx-nav-item">
                        <a href="{{ route('negocio.create') }}" class="clx-nav-link">
                            <i class="fas fa-store clx-nav-icon"></i>
                            Crear Negocio
                        </a>
                    </li>
                @endif
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="profile">
                        <i class="fas fa-user-cog clx-nav-icon"></i>
                        Mi Perfil
                    </a>
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

        @php
            $primeraEmpresa = $misEmpresas->first();
            $tieneNegocio = $misEmpresas->count() > 0;
            $nombreNegocio = $primeraEmpresa->neg_nombre_comercial ?? $primeraEmpresa->neg_nombre ?? null;
        @endphp

        <!-- Header de bienvenida -->
        <header class="clx-header">
            <div class="clx-welcome">
                <div>
                    @if($tieneNegocio)
                        <h2>{{ $nombreNegocio }}</h2>
                        <p style="color: #6b7280; font-size: 0.85rem;">
                            {{ \Illuminate\Support\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            &middot; <span id="clx-pending-count" style="color: #5a31d7; font-weight: 600;">{{ $citasNegocioPendientes ?? 0 }} {{ ($citasNegocioPendientes ?? 0) === 1 ? 'cita pendiente' : 'citas pendientes' }}</span>
                        </p>
                    @else
                        <h2>¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                        <p>Registra tu negocio para comenzar a recibir citas</p>
                    @endif
                </div>
                @if($tieneNegocio)
                    <a href="{{ route('empresa.agenda', ['id' => $primeraEmpresa->id]) }}" class="clx-btn clx-btn-primary" style="flex:none; width:auto; display:inline-flex; padding:10px 20px; font-size:0.85rem; border-radius:12px;">
                        <i class="fas fa-calendar-plus"></i>
                        Ir a la Agenda
                    </a>
                @else
                    <a href="{{ route('negocio.create') }}" class="clx-btn clx-btn-primary" style="flex:none; width:auto; display:inline-flex; padding:10px 20px; font-size:0.85rem; border-radius:12px;">
                        <i class="fas fa-store"></i>
                        Registra tu negocio
                    </a>
                @endif
            </div>
        </header>

        <!-- Estadísticas del negocio -->
        <section class="clx-stats">
            <div class="clx-stat-card">
                <div class="clx-stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-appointments">{{ number_format($citasNegocioMes ?? 0) }}</div>
                <div class="clx-stat-label">Citas este mes</div>
            </div>
            <div class="clx-stat-card">
                <div class="clx-stat-icon success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-clients">{{ number_format($clientesCount ?? 0) }}</div>
                <div class="clx-stat-label">Clientes</div>
            </div>
            <div class="clx-stat-card">
                <div class="clx-stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="clx-stat-number" id="clx-stat-pending">{{ number_format($citasNegocioPendientes ?? 0) }}</div>
                <div class="clx-stat-label">Pendientes</div>
            </div>
        </section>

        @if($tieneNegocio)
        <!-- Acciones rápidas -->
        <section style="margin-bottom: 1.25rem;">
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Acciones rápidas</h3>
                </div>
                <div class="clx-card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.75rem;">
                        <a href="{{ route('empresa.agenda', ['id' => $primeraEmpresa->id]) }}" style="display:flex; flex-direction:column; align-items:center; gap:8px; padding:1rem 0.75rem; background:#f8f6ff; border-radius:12px; text-decoration:none; transition: all 0.2s; border: 1px solid transparent;" onmouseover="this.style.borderColor='#5a31d7'; this.style.background='#f0ecfb'" onmouseout="this.style.borderColor='transparent'; this.style.background='#f8f6ff'">
                            <i class="fas fa-calendar-alt" style="font-size:1.25rem; color:#5a31d7;"></i>
                            <span style="font-size:0.8rem; font-weight:600; color:#374151;">Agenda</span>
                        </a>
                        <a href="{{ route('empresa.catalogo.servicios', $primeraEmpresa->id) }}" style="display:flex; flex-direction:column; align-items:center; gap:8px; padding:1rem 0.75rem; background:#f0fdf4; border-radius:12px; text-decoration:none; transition: all 0.2s; border: 1px solid transparent;" onmouseover="this.style.borderColor='#22c55e'; this.style.background='#dcfce7'" onmouseout="this.style.borderColor='transparent'; this.style.background='#f0fdf4'">
                            <i class="fas fa-concierge-bell" style="font-size:1.25rem; color:#22c55e;"></i>
                            <span style="font-size:0.8rem; font-weight:600; color:#374151;">Servicios</span>
                        </a>
                        <a href="{{ route('empresa.clientes.index', $primeraEmpresa->id) }}" style="display:flex; flex-direction:column; align-items:center; gap:8px; padding:1rem 0.75rem; background:#eff6ff; border-radius:12px; text-decoration:none; transition: all 0.2s; border: 1px solid transparent;" onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#dbeafe'" onmouseout="this.style.borderColor='transparent'; this.style.background='#eff6ff'">
                            <i class="fas fa-users" style="font-size:1.25rem; color:#3b82f6;"></i>
                            <span style="font-size:0.8rem; font-weight:600; color:#374151;">Clientes</span>
                        </a>
                        <a href="{{ route('empresa.configuracion.negocio', $primeraEmpresa->id) }}" style="display:flex; flex-direction:column; align-items:center; gap:8px; padding:1rem 0.75rem; background:#fef3c7; border-radius:12px; text-decoration:none; transition: all 0.2s; border: 1px solid transparent;" onmouseover="this.style.borderColor='#f59e0b'; this.style.background='#fde68a'" onmouseout="this.style.borderColor='transparent'; this.style.background='#fef3c7'">
                            <i class="fas fa-cog" style="font-size:1.25rem; color:#f59e0b;"></i>
                            <span style="font-size:0.8rem; font-weight:600; color:#374151;">Configurar</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Contenido principal -->
        <section class="clx-content-grid">
            <!-- Próximas citas del negocio -->
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Próximas Citas</h3>
                    @if($tieneNegocio)
                        <a href="{{ route('empresa.configuracion.citas', $primeraEmpresa->id) }}" class="clx-btn clx-btn-ghost">Ver todas</a>
                    @endif
                </div>
                <div class="clx-card-body">
                    <div id="clx-appointments-list">
                        <!-- Se llena dinámicamente con JS -->
                    </div>
                </div>
            </div>

            <!-- Resumen del negocio -->
            <div class="clx-card">
                <div class="clx-card-header">
                    <h3 class="clx-card-title">Tu Negocio</h3>
                    @if($tieneNegocio)
                        <a href="{{ route('empresa.dashboard', $primeraEmpresa->id) }}" class="clx-btn clx-btn-ghost">
                            Panel completo
                        </a>
                    @endif
                </div>
                <div class="clx-card-body">
                    @if($tieneNegocio)
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            {{-- Info del negocio --}}
                            <div style="display: flex; align-items: center; gap: 12px; padding: 0.75rem; background: #f8f6ff; border-radius: 12px;">
                                @if($primeraEmpresa->neg_imagen)
                                    <img src="{{ str_starts_with($primeraEmpresa->neg_imagen, 'http') ? $primeraEmpresa->neg_imagen : asset('storage/' . $primeraEmpresa->neg_imagen) }}"
                                         alt="{{ $nombreNegocio }}"
                                         style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb;">
                                @else
                                    <div style="width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg,#5a31d7,#7b5ce0); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.1rem; flex-shrink:0;">
                                        {{ strtoupper(substr($nombreNegocio ?? 'N', 0, 1)) }}
                                    </div>
                                @endif
                                <div style="min-width:0;">
                                    <p style="font-weight:600; color:#1f2937; font-size:0.9rem; margin:0;">{{ $nombreNegocio }}</p>
                                    <p style="font-size:0.78rem; color:#6b7280; margin:2px 0 0 0;">{{ $primeraEmpresa->neg_categoria ?? 'Sin categoria' }}</p>
                                </div>
                            </div>

                            {{-- Métricas rápidas --}}
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                <div style="padding: 0.6rem 0.75rem; background: #f9fafb; border-radius: 10px; text-align: center;">
                                    <div style="font-size: 1.1rem; font-weight: 700; color: #5a31d7;">{{ $serviciosCount ?? 0 }}</div>
                                    <div style="font-size: 0.72rem; color: #6b7280;">Servicios</div>
                                </div>
                                <div style="padding: 0.6rem 0.75rem; background: #f9fafb; border-radius: 10px; text-align: center;">
                                    <div style="font-size: 1.1rem; font-weight: 700; color: #22c55e;">{{ $trabajadoresCount ?? 0 }}</div>
                                    <div style="font-size: 0.72rem; color: #6b7280;">Profesionales</div>
                                </div>
                            </div>

                            {{-- Link al perfil público --}}
                            @php
                                $slug = \Illuminate\Support\Str::slug($nombreNegocio ?? 'negocio');
                            @endphp
                            <a href="{{ url('/negocios/' . $primeraEmpresa->id . '-' . $slug) }}" target="_blank"
                               style="display:flex; align-items:center; justify-content:center; gap:6px; padding:0.6rem; background:#fff; border:1px solid #e5e7eb; border-radius:10px; font-size:0.8rem; font-weight:500; color:#5a31d7; text-decoration:none; transition:all 0.2s;"
                               onmouseover="this.style.borderColor='#5a31d7'; this.style.background='#f8f6ff'" onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='#fff'">
                                <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i> Ver perfil publico
                            </a>
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem 1rem;">
                            <i class="fas fa-store" style="font-size: 2.5rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Aun no tienes un negocio registrado</p>
                            <a href="{{ route('negocio.create') }}" style="display:inline-flex; align-items:center; gap:6px; padding:10px 20px; background:#5a31d7; color:#fff; border-radius:10px; font-size:0.85rem; font-weight:600; text-decoration:none;">
                                <i class="fas fa-plus"></i> Crear mi negocio
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        </div>{{-- /data-clx-section="dashboard" --}}

        {{-- ===== SECCION: MI PERFIL ===== --}}
        <div data-clx-section="profile" style="display:none;">

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

{{-- Bottom Nav (mobile) — 5 items --}}
<nav class="clx-bottom-nav">
    <a href="#" class="clx-bottom-nav__item clx-bnav-active" data-clx-page="dashboard">
        <i class="fas fa-home"></i>
        <span>Inicio</span>
    </a>
    @if(($misEmpresas ?? collect())->count() > 0)
        @php $empNav = $misEmpresas->first(); @endphp
        <a href="{{ route('empresa.agenda', ['id' => $empNav->id]) }}" class="clx-bottom-nav__item">
            <i class="fas fa-calendar-alt"></i>
            <span>Agenda</span>
        </a>
        <a href="{{ route('empresa.clientes.index', $empNav->id) }}" class="clx-bottom-nav__item">
            <i class="fas fa-users"></i>
            <span>Clientes</span>
        </a>
    @else
        <a href="{{ route('negocio.create') }}" class="clx-bottom-nav__item">
            <i class="fas fa-store"></i>
            <span>Crear</span>
        </a>
    @endif
    <a href="#" class="clx-bottom-nav__item" data-clx-page="profile">
        <i class="fas fa-user"></i>
        <span>Perfil</span>
    </a>
    <button type="button" class="clx-bottom-nav__item" id="btnMasCliente">
        <i class="fas fa-bars"></i>
        <span>Mas</span>
    </button>
</nav>

{{-- Drawer lateral (mobile) --}}
<div id="mobileDrawerCliente">
    <div class="drawer-overlay" onclick="cerrarDrawerCliente()"></div>
    <div class="drawer-panel">
        <button class="drawer-close btn-cerrar-drawer" onclick="cerrarDrawerCliente()">
            <i class="fas fa-times"></i>
        </button>

        {{-- User info --}}
        <div class="drawer-user">
            @if(auth()->user()->foto)
                <img src="{{ asset(auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" class="drawer-user-avatar">
            @else
                <div class="drawer-user-avatar-placeholder">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            @endif
            <div class="drawer-user-name">{{ auth()->user()->name }}</div>
            <div class="drawer-user-email">{{ auth()->user()->email }}</div>
            @if($plan ?? false)
                <span class="drawer-user-badge" style="background:linear-gradient(135deg,#5a31d7,#7b5ce0);color:#fff;">
                    <i class="fas fa-gem" style="font-size:0.55rem;"></i> {{ $plan->name }}
                </span>
            @else
                <span class="drawer-user-badge" style="background:#f0ecfb;color:#5a31d7;">
                    <i class="fas fa-tag" style="font-size:0.55rem;"></i> Free
                </span>
            @endif
        </div>

        {{-- Links --}}
        <a href="#" class="drawer-link drawer-active" data-clx-drawer-page="dashboard">
            <i class="fas fa-home"></i> Dashboard
        </a>

        @if(($misEmpresas ?? collect())->count() > 0)
            @php $empDrawer = $misEmpresas->first(); @endphp
            <a href="{{ route('empresa.agenda', ['id' => $empDrawer->id]) }}" class="drawer-link">
                <i class="fas fa-calendar-alt"></i> Agenda
            </a>
            <a href="{{ route('empresa.clientes.index', $empDrawer->id) }}" class="drawer-link">
                <i class="fas fa-users"></i> Clientes
            </a>
            <a href="{{ route('empresa.catalogo.servicios', $empDrawer->id) }}" class="drawer-link">
                <i class="fas fa-concierge-bell"></i> Servicios
            </a>
            <a href="{{ route('empresa.configuracion.negocio', $empDrawer->id) }}" class="drawer-link">
                <i class="fas fa-cog"></i> Configuracion
            </a>

            <div class="drawer-separator"></div>

            <a href="{{ route('empresa.dashboard', $empDrawer->id) }}" class="drawer-link">
                <i class="fas fa-chart-bar"></i> Panel Empresa
            </a>
        @else
            <a href="{{ route('negocio.create') }}" class="drawer-link">
                <i class="fas fa-store"></i> Crear Negocio
            </a>
        @endif

        <div class="drawer-separator"></div>

        <a href="#" class="drawer-link" data-clx-drawer-page="profile">
            <i class="fas fa-user-cog"></i> Mi Perfil
        </a>

        <div class="drawer-separator"></div>

        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="drawer-logout-btn">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>

{{-- Drawer + Bottom Nav + Topbar JS --}}
<script>
(function() {
    var drawerCliente = document.getElementById('mobileDrawerCliente');
    var btnHamCliente = document.getElementById('btnHamburguerCliente');
    var btnMasCliente = document.getElementById('btnMasCliente');
    var topbarTitle = document.getElementById('clxTopbarTitle');

    // Page title map
    var pageTitles = {
        dashboard: 'Dashboard',
        profile: 'Mi Perfil'
    };

    window.abrirDrawerCliente = function() {
        drawerCliente.style.display = 'block';
        setTimeout(function() { drawerCliente.classList.add('open'); }, 10);
    };
    window.cerrarDrawerCliente = function() {
        drawerCliente.classList.remove('open');
        setTimeout(function() { drawerCliente.style.display = 'none'; }, 300);
    };

    if (btnHamCliente) btnHamCliente.addEventListener('click', abrirDrawerCliente);
    if (btnMasCliente) btnMasCliente.addEventListener('click', abrirDrawerCliente);

    // Bottom nav: trigger sidebar page navigation + sync active states
    document.querySelectorAll('.clx-bottom-nav__item[data-clx-page]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var page = this.dataset.clxPage;

            // Trigger sidebar nav link
            var sidebarLink = document.querySelector('.clx-nav-link[data-clx-page="' + page + '"]');
            if (sidebarLink) sidebarLink.click();

            // Update bottom nav active
            document.querySelectorAll('.clx-bottom-nav__item').forEach(function(i) {
                i.classList.remove('clx-bnav-active');
            });
            this.classList.add('clx-bnav-active');

            // Update topbar title
            if (topbarTitle && pageTitles[page]) topbarTitle.textContent = pageTitles[page];
        });
    });

    // Drawer links: trigger sidebar page navigation + close drawer
    document.querySelectorAll('[data-clx-drawer-page]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var page = this.dataset.clxDrawerPage;

            var sidebarLink = document.querySelector('.clx-nav-link[data-clx-page="' + page + '"]');
            if (sidebarLink) sidebarLink.click();

            // Sync drawer active
            document.querySelectorAll('[data-clx-drawer-page]').forEach(function(l) {
                l.classList.remove('drawer-active');
            });
            this.classList.add('drawer-active');

            // Sync bottom nav
            document.querySelectorAll('.clx-bottom-nav__item').forEach(function(i) {
                i.classList.toggle('clx-bnav-active', i.dataset.clxPage === page);
            });

            // Update topbar title
            if (topbarTitle && pageTitles[page]) topbarTitle.textContent = pageTitles[page];

            cerrarDrawerCliente();
        });
    });

    // Sync bottom nav when sidebar nav is clicked (desktop)
    document.querySelectorAll('.clx-nav-link[data-clx-page]').forEach(function(link) {
        link.addEventListener('click', function() {
            var page = this.dataset.clxPage;
            document.querySelectorAll('.clx-bottom-nav__item').forEach(function(i) {
                i.classList.toggle('clx-bnav-active', i.dataset.clxPage === page);
            });
            document.querySelectorAll('[data-clx-drawer-page]').forEach(function(l) {
                l.classList.toggle('drawer-active', l.dataset.clxDrawerPage === page);
            });
            if (topbarTitle && pageTitles[page]) topbarTitle.textContent = pageTitles[page];
        });
    });
})();
</script>

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

  // --- Stats desde servidor (enfocado en negocio) ---
  $serverStats = [
      'appointmentsMonth' => (int)($citasNegocioMes ?? 0),
      'clients'           => (int)($clientesCount ?? 0),
      'pending'           => (int)($citasNegocioPendientes ?? 0),
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

    // Stats desde el servidor (negocio)
    const s = window.clxData.stats || {appointmentsMonth: 0, clients: 0, pending: 0};
    const statAppointments = document.getElementById('clx-stat-appointments');
    const statClients      = document.getElementById('clx-stat-clients');
    const statPending      = document.getElementById('clx-stat-pending');
    const pendingCount     = document.getElementById('clx-pending-count');

    if (statAppointments) clxAnimateNumber(statAppointments, s.appointmentsMonth);
    if (statClients)      clxAnimateNumber(statClients,      s.clients);
    if (statPending)      clxAnimateNumber(statPending,      s.pending);
    if (pendingCount)     pendingCount.textContent = `${s.pending} ${s.pending === 1 ? 'cita pendiente' : 'citas pendientes'}`;
  }
});
</script>
