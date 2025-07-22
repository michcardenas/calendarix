@extends('layouts.app')

@section('title', $negocio->neg_nombre_comercial ?? 'Perfil del Negocio')

@section('content')
<style>
body::before,
body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-repeat: no-repeat;
  background-size: cover;
  z-index: 0;
  pointer-events: none;
}

body::before {
  background:
    radial-gradient(circle 160px at 10% 20%, rgba(126, 121, 201, 0.28) 0%, transparent 60%),
    radial-gradient(circle 120px at 30% 40%, rgba(90, 78, 187, 0.25) 0%, transparent 60%),
    radial-gradient(circle 150px at 50% 20%, rgba(126, 121, 201, 0.3) 0%, transparent 60%),
    radial-gradient(circle 130px at 70% 35%, rgba(90, 78, 187, 0.3) 0%, transparent 60%),
    radial-gradient(circle 180px at 85% 10%, rgba(126, 121, 201, 0.25) 0%, transparent 60%),
    radial-gradient(circle 100px at 20% 75%, rgba(90, 78, 187, 0.22) 0%, transparent 60%),
    radial-gradient(circle 120px at 45% 90%, rgba(126, 121, 201, 0.26) 0%, transparent 60%),
    radial-gradient(circle 140px at 80% 80%, rgba(90, 78, 187, 0.3) 0%, transparent 60%);
  animation: bubblesBefore 18s ease-in-out infinite;
}

body::after {
  background:
    radial-gradient(circle 130px at 15% 50%, rgba(126, 121, 201, 0.2) 0%, transparent 60%),
    radial-gradient(circle 160px at 35% 60%, rgba(90, 78, 187, 0.28) 0%, transparent 60%),
    radial-gradient(circle 120px at 55% 45%, rgba(126, 121, 201, 0.24) 0%, transparent 60%),
    radial-gradient(circle 140px at 75% 55%, rgba(90, 78, 187, 0.22) 0%, transparent 60%),
    radial-gradient(circle 160px at 90% 35%, rgba(126, 121, 201, 0.23) 0%, transparent 60%),
    radial-gradient(circle 100px at 10% 85%, rgba(90, 78, 187, 0.2) 0%, transparent 60%),
    radial-gradient(circle 150px at 40% 10%, rgba(126, 121, 201, 0.28) 0%, transparent 60%),
    radial-gradient(circle 130px at 60% 85%, rgba(90, 78, 187, 0.25) 0%, transparent 60%);
  animation: bubblesAfter 22s ease-in-out infinite reverse;
}

@keyframes bubblesBefore {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-30px); }
}

@keyframes bubblesAfter {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(30px); }
}

</style>


<div class="py-10 relative z-10">
    <div class="max-w-6xl mx-auto">
        {{-- Portada --}}
        <div class="relative w-full h-80 rounded-2xl overflow-hidden shadow-2xl mb-14 bg-[#4a5eaa]">
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
                <h1 class="text-4xl font-extrabold text-[#4a5eaa]">{{ $negocio->neg_nombre_comercial }}</h1>
                <p class="text-lg text-[#3B4269B3]">{{ $negocio->neg_categoria }}</p>
            </div>
        </div>

        {{-- Secciones --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-10">

            {{-- Info y Servicios --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">📄 Información del negocio</h3>
                    <ul class="text-base text-gray-700 space-y-2">
                        <li><strong>Email:</strong> {{ $negocio->neg_email }}</li>
                        <li><strong>Teléfono:</strong> {{ $negocio->neg_telefono }}</li>
                        <li><strong>Dirección:</strong> {{ $negocio->neg_direccion }}</li>
                    </ul>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">💼 Servicios</h3>
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
                <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 border-[#4a5eaa]">
                    <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">⏰ Horarios de Atención</h3>
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
                    <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 border-[#4a5eaa]">
                        <h3 class="text-xl font-semibold mb-4 text-[#4a5eaa]">📅 Días Bloqueados</h3>
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
