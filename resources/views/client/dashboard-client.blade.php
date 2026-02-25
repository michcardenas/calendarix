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
            <div class="clx-user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="clx-user-name">{{ auth()->user()->name }}</div>
            <div class="clx-user-email">{{ auth()->user()->email }}</div>
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
                    <a href="#" class="clx-nav-link" data-clx-page="appointments">
                        <i class="fas fa-calendar-check clx-nav-icon"></i>
                        Mis Citas
                    </a>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="businesses">
                        <i class="fas fa-store clx-nav-icon"></i>
                        Negocios
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
                    <a href="#" class="clx-nav-link" data-clx-toggle="empresa-submenu">
                        <i class="fas fa-briefcase clx-nav-icon"></i>
                        Mi Empresa <i class="fas fa-chevron-down float-end"></i>
                    </a>

                    <ul id="empresa-submenu" class="clx-submenu" style="display: none; padding-left: 1rem;">
                        @forelse ($misEmpresas as $empresa)
                        <li>
                            <a href="{{ route('empresa.dashboard', $empresa->id) }}" class="clx-submenu-link">
                                {{ $empresa->neg_nombre_comercial ?? 'Sin nombre comercial' }}
                            </a>
                        </li>
                        @empty
                        <li><span class="text-sm text-gray-400">Sin empresas aún</span></li>
                        @endforelse
                    </ul>
                </li>
                <li class="clx-nav-item">
                    <a href="#" class="clx-nav-link" data-clx-page="notifications">
                        <i class="fas fa-bell clx-nav-icon"></i>
                        Notificaciones
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="clx-main">
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
                    <button class="clx-btn clx-btn-primary" id="clx-btn-book">
                        <i class="fas fa-plus"></i>
                        Agendar Cita
                    </button>
                    <button class="clx-btn clx-btn-secondary" id="clx-btn-search">
                        <i class="fas fa-search"></i>
                        Buscar Servicios
                    </button>
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
