@extends('layouts.app')

@section('title', $negocio->neg_nombre_comercial ?? 'Perfil del Negocio')

@section('content')
{{-- Fondo animado con bolitas --}}
<style>
/* 🫧 BOLITAS DE FONDO */
body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background:
    radial-gradient(circle 150px at 10% 20%, rgba(246, 245, 247, 0.4) 0%, rgba(246, 245, 247, 0.1) 30%, transparent 60%),
    radial-gradient(circle 120px at 90% 15%, rgba(246, 245, 247, 0.35) 0%, rgba(246, 245, 247, 0.08) 35%, transparent 65%),
    radial-gradient(circle 180px at 15% 85%, rgba(246, 245, 247, 0.38) 0%, rgba(246, 245, 247, 0.06) 40%, transparent 70%),
    radial-gradient(circle 140px at 85% 80%, rgba(246, 245, 247, 0.42) 0%, rgba(246, 245, 247, 0.09) 35%, transparent 65%);
  animation: floatingBubbles 15s ease-in-out infinite;
  pointer-events: none;
  z-index: 0;
}

body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background:
    radial-gradient(circle 110px at 70% 25%, rgba(246, 245, 247, 0.3) 0%, rgba(246, 245, 247, 0.08) 40%, transparent 70%),
    radial-gradient(circle 160px at 25% 50%, rgba(246, 245, 247, 0.25) 0%, rgba(246, 245, 247, 0.05) 45%, transparent 75%),
    radial-gradient(circle 100px at 80% 60%, rgba(246, 245, 247, 0.32) 0%, rgba(246, 245, 247, 0.07) 40%, transparent 70%),
    radial-gradient(circle 130px at 30% 15%, rgba(246, 245, 247, 0.28) 0%, rgba(246, 245, 247, 0.06) 45%, transparent 75%);
  animation: floatingBubbles 20s ease-in-out infinite reverse;
  pointer-events: none;
  z-index: 0;
}

@keyframes floatingBubbles {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-15px); }
}
</style>

<div class="py-10 relative z-10">
    <div class="max-w-6xl mx-auto">

        {{-- Portada --}}
        <div class="relative w-full h-80 rounded-2xl overflow-hidden shadow-2xl mb-14" style="background-color: #7E79C9;">
            @if($negocio->neg_portada)
                <img src="{{ $negocio->neg_portada }}" class="absolute inset-0 w-full h-full object-cover" alt="Portada">
            @endif
        </div>

        {{-- Avatar + Encabezado --}}
        <div class="relative -mt-32 flex items-center gap-8 px-10 pb-10">
            <div class="shrink-0">
                <img src="{{ $negocio->neg_imagen ?? '/images/default-user.png' }}"
                     class="w-40 h-40 rounded-full border-4 border-white object-cover shadow-xl bg-white"
                     alt="Avatar">
            </div>
            <div>
                <h1 class="text-4xl font-extrabold" style="color: #5A4EBB;">{{ $negocio->neg_nombre_comercial }}</h1>
                <p class="text-lg text-gray-600">{{ $negocio->neg_categoria }}</p>
            </div>
        </div>

        {{-- Secciones --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-10">

            {{-- Info y Servicios --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4" style="border-color: #7E79C9;">
                    <h3 class="text-xl font-semibold mb-4" style="color: #5A4EBB;">📄 Información del negocio</h3>
                    <ul class="text-base text-gray-700 space-y-2">
                        <li><strong>Email:</strong> {{ $negocio->neg_email }}</li>
                        <li><strong>Teléfono:</strong> {{ $negocio->neg_telefono }}</li>
                        <li><strong>Dirección:</strong> {{ $negocio->neg_direccion }}</li>
                    </ul>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4" style="border-color: #7E79C9;">
                    <h3 class="text-xl font-semibold mb-4" style="color: #5A4EBB;">💼 Servicios</h3>
                    @if($negocio->servicios->count())
                        <ul class="list-disc pl-5 text-base text-gray-700 space-y-2">
                            @foreach($negocio->servicios as $servicio)
                                <li>{{ $servicio->nombre }} - ${{ number_format($servicio->precio, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-base text-gray-500">Este negocio aún no tiene servicios registrados.</p>
                    @endif
                </div>
            </div>

            {{-- Horarios y Calendario --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl p-6 shadow-md border-t-4" style="border-color: #7E79C9;">
                    <h3 class="text-xl font-semibold mb-4" style="color: #5A4EBB;">⏰ Horarios de Atención</h3>
                    <table class="w-full text-base">
                        <thead>
                            <tr class="text-gray-600 border-b">
                                <th class="py-2 text-left">Día</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($negocio->horarios as $h)
                                <tr class="border-b text-gray-700">
                                    <td class="py-2">
                                        {{ \Carbon\Carbon::create()->startOfWeek()->addDays($h->dia_semana - 1)->locale('es')->isoFormat('dddd') }}
                                    </td>
                                    <td>{{ $h->hora_inicio ?? '—' }}</td>
                                    <td>{{ $h->hora_fin ?? '—' }}</td>
                                    <td>{!! $h->activo ? '<span class="text-green-600 font-semibold">Sí</span>' : '<span class="text-red-600">No</span>' !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($negocio->bloqueos->count())
                    <div class="bg-white rounded-2xl p-6 shadow-md border-t-4" style="border-color: #7E79C9;">
                        <h3 class="text-xl font-semibold mb-4" style="color: #5A4EBB;">📅 Días Bloqueados</h3>
                        <div id="calendarioBloqueos" class="rounded overflow-hidden"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendarioBloqueos');
        if (calendarEl) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 500,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: [
                    @foreach($negocio->bloqueos as $bloqueo)
                        {
                            title: 'Bloqueado',
                            start: '{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',
                            allDay: true,
                            color: '#dc2626'
                        },
                    @endforeach
                ]
            });
            calendar.render();
        }
    });
</script>
@endpush
