@extends('layouts.base')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/empresa/dashboard-empresa.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 15; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="layout" style="display: flex;">
    {{-- Sidebar a la izquierda --}}
    @include('empresa.partials.sidebar', [
        'empresa' => $empresa,
        'currentPage' => $currentPage ?? 'dashboard',
        'currentSubPage' => $currentSubPage ?? null
    ])


    <main class="content" style="padding: 2rem;">
        <div class="header">Dashboard de {{ $empresa->neg_nombre_comercial }}</div>

        <!-- Alerta informativa para el editor (opcional) -->
        <!-- <div class="alert alert-info" style="background-color: #e6f2ff; border-left: 5px solid #00304b; padding: 1rem; margin-bottom: 2rem; border-radius: 8px;">
            <strong>🎨 Personaliza tu página:</strong> Diseña cómo se verá tu perfil público arrastrando y organizando tus secciones.
            <a href="{{ route('empresa.editor', ['id' => $empresa->id]) }}" class="btn btn-sm btn-primary" style="margin-left: 1rem;">Diseñar mi vista</a>
        </div> -->

        <div class="stats">
            <div class="card">
                <h4>Total de Citas</h4>
                <p>{{ number_format($totalCitas ?? 0) }}</p>
            </div>
            <div class="card">
                <h4>Servicios Activos</h4>
                <p>{{ number_format($serviciosActivos ?? 0) }}</p>
            </div>
            <div class="card">
                <h4>Miembros del equipo</h4>
                <p>{{ number_format($miembrosEquipo ?? 0) }}</p>
            </div>
        </div>

        <div class="card">
            <h4>Próximas citas</h4>

            @if(($proximasCitas ?? collect())->isEmpty())
                <p>No hay citas próximas.</p>
            @else
                <ul style="margin:0; padding-left:1rem; list-style:disc;">
                    @foreach($proximasCitas as $cita)
                        <li style="margin: .35rem 0;">
                            <strong>{{ $cita->nombre_cliente ?? '—' }}</strong>
                            — {{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                            • {{ \Illuminate\Support\Str::of($cita->hora_inicio)->limit(5,'') }}
                            @if(!empty($cita->hora_fin))
                                – {{ \Illuminate\Support\Str::of($cita->hora_fin)->limit(5,'') }}
                            @endif
                            • <em>{{ ucfirst($cita->estado ?? 'pendiente') }}</em>
                            @if(!empty($cita->notas))
                                — {{ \Illuminate\Support\Str::limit($cita->notas, 80) }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
</div>


    </main>
</div>

<script src="{{ asset('js/empresa/dashboard-empresa.js') }}"></script>

{{-- Estilos adicionales para los botones rápidos --}}
<style>
.quick-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.quick-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.quick-btn i {
    font-size: 1.2rem;
}
</style>
