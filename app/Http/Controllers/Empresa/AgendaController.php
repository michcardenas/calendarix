<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa\Empresa;
use App\Models\DiaBloqueado;
use App\Models\HorarioLaboral;
use Illuminate\Validation\Rule;
use App\Models\Cita;
use Illuminate\Support\Facades\Log;
use App\Models\Empresa\ServicioEmpresa;
use Carbon\Carbon;
use App\Models\Trabajador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionGeneral;

class AgendaController extends Controller
{
    public function index($id)
    {
        $empresa = Empresa::findOrFail($id);

        // Verificar empresa cargada correctamente
        // dd($empresa);

        $fechasBloqueadas = DiaBloqueado::where('negocio_id', $empresa->id)
            ->pluck('fecha_bloqueada')
            ->toArray();

        // Verificar fechas bloqueadas
        // dd($fechasBloqueadas);

        $horarios = HorarioLaboral::where('negocio_id', $empresa->id)
            ->where('activo', 1)
            ->get();

        // Verificar si hay horarios cargados
        // dd($horarios);

        $eventosHorarios = [];

        $fechaInicio = Carbon::now()->startOfMonth();
        $fechaFin = Carbon::now()->endOfMonth();

        for ($fecha = $fechaInicio->copy(); $fecha <= $fechaFin; $fecha->addDay()) {
            $diaSemanaLaravel = $fecha->dayOfWeek;
            $diaSemanaBD = $diaSemanaLaravel === 0 ? 7 : $diaSemanaLaravel;

            $horario = $horarios->firstWhere('dia_semana', $diaSemanaBD);

            // Verifica qué día está evaluando y qué horario encuentra
            // dd([
            //     'fecha' => $fecha->toDateString(),
            //     'diaLaravel' => $diaSemanaLaravel,
            //     'diaBD' => $diaSemanaBD,
            //     'horarioEncontrado' => $horario
            // ]);

            if ($horario) {
                $eventosHorarios[] = [
                    'start' => $fecha->toDateString() . 'T' . $horario->hora_inicio,
                    'end' => $fecha->toDateString() . 'T' . $horario->hora_fin,
                    'display' => 'background',
                    'backgroundColor' => '#007bff',
                ];
            }
        }

        foreach ($fechasBloqueadas as $fecha) {
            $eventosHorarios[] = [
                'start' => $fecha,
                'allDay' => true,
                'display' => 'background',
                'backgroundColor' => '#ffcccc',
            ];
        }

        // Verificar resultado final
        // dd($eventosHorarios);

        return view('empresa.agenda', [
            'empresa' => $empresa,
            'eventos' => $eventosHorarios,
            'horarios' => $horarios,
        ]);
    }

    public function configurar($id)
    {
        $empresa = Empresa::findOrFail($id);

        // Días bloqueados
        $fechas = DiaBloqueado::where('negocio_id', $id)->pluck('fecha_bloqueada')->toArray();

        // Horarios laborales existentes
        $horarios = HorarioLaboral::where('negocio_id', $id)
            ->orderBy('dia_semana')
            ->get()
            ->keyBy('dia_semana'); // Para acceder fácilmente por día

        return view('agenda.configurar', [
            'empresa' => $empresa,
            'fechasBloqueadas' => $fechas,
            'horarios' => $horarios,
            'currentPage' => 'agenda',
            'currentSubPage' => 'configuracion'
        ]);
    }


    public function guardarBloqueados(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        // Guardar días bloqueados
        $fechas = explode(',', $request->fechas_bloqueadas ?? '');
        DiaBloqueado::where('negocio_id', $empresa->id)->delete();

        foreach ($fechas as $fecha) {
            if ($fecha) {
                DiaBloqueado::create([
                    'negocio_id' => $empresa->id,
                    'fecha_bloqueada' => $fecha
                ]);
            }
        }

        // Guardar horarios laborales
        HorarioLaboral::where('negocio_id', $empresa->id)->delete();

        foreach ($request->input('dias_laborales', []) as $dia => $datos) {
            HorarioLaboral::create([
                'negocio_id' => $empresa->id,
                'dia_semana' => $dia,
                'hora_inicio' => $datos['inicio'] ?? null,
                'hora_fin' => $datos['fin'] ?? null,
                'activo' => isset($datos['activo']) ? true : false,
            ]);
        }

        return redirect()->route('empresa.agenda', $empresa->id)
            ->with('success', 'Configuración guardada correctamente.');
    }

    public function store(Request $request, $negocioId)
{
    try {
        // Reglas (si no hay usuario autenticado, nombre_cliente requerido)
        $rules = [
            'fecha'          => ['required', 'date_format:Y-m-d'],
            'hora_inicio'    => ['required', 'date_format:H:i'],
            'hora_fin'       => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'notas'          => ['nullable', 'string'],
            'nombre_cliente' => Auth::check() ? ['nullable', 'string', 'max:255'] : ['required', 'string', 'max:255'],
            'servicio_id'    => [
                'required',
                'integer',
                Rule::exists('servicios_empresa', 'id')->where(fn($q) => $q->where('negocio_id', $negocioId)),
            ],
            'trabajador_id'  => [
                'required',
                'integer',
                Rule::exists('trabajadores', 'id')->where(fn($q) => $q->where('negocio_id', $negocioId)),
            ],
        ];
        $validated = $request->validate($rules);

        // Día bloqueado
        $bloqueada = DiaBloqueado::where('negocio_id', $negocioId)
            ->whereDate('fecha_bloqueada', $validated['fecha'])
            ->exists();

        if ($bloqueada) {
            return response()->json([
                'ok' => false,
                'errors' => ['general' => ['Ese día está bloqueado.']]
            ], 422);
        }

        // Evitar solapamientos POR TRABAJADOR (mismo negocio, misma fecha, mismo trabajador)
        $overlap = Cita::where('negocio_id', $negocioId)
            ->where('trabajador_id', $validated['trabajador_id'])
            ->whereDate('fecha', $validated['fecha'])
            ->where(function ($q) use ($validated) {
                // (inicio existente < fin nueva) AND (fin existente > inicio nueva)
                $q->whereRaw('TIME(hora_inicio) < TIME(?)', [$validated['hora_fin']])
                  ->whereRaw('TIME(hora_fin) > TIME(?)', [$validated['hora_inicio']]);
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'ok' => false,
                'errors' => ['general' => ['El trabajador ya tiene una cita en ese rango de horas.']]
            ], 422);
        }

        // Servicio (validado que pertenece al negocio)
        $servicio = ServicioEmpresa::where('negocio_id', $negocioId)
            ->findOrFail($validated['servicio_id']);

        // Congelar precio en centavos (int). Si no usas centavos, ignora
        $precioCerrado = is_null($servicio->precio)
            ? null
            : (int) round(((float) $servicio->precio) * 100);

        // Datetimes para el calendario
        $start = "{$validated['fecha']} {$validated['hora_inicio']}:00";
        $end   = "{$validated['fecha']} {$validated['hora_fin']}:00";

        // Crear la cita (incluye trabajador_id)
        $cita = Cita::create([
            'negocio_id'     => $negocioId,
            'user_id'        => Auth::id(),               // puede ser null
            'nombre_cliente' => $request->input('nombre_cliente'),
            'fecha'          => $validated['fecha'],
            'hora_inicio'    => $validated['hora_inicio'],
            'hora_fin'       => $validated['hora_fin'],
            'notas'          => $request->input('notas'),
            'estado'         => 'pendiente',
            'servicio_id'    => $validated['servicio_id'],
            'precio_cerrado' => $precioCerrado,
            'trabajador_id'  => $validated['trabajador_id'], // ← nuevo
        ]);

        // Título sugerido (cliente + nombre de trabajador opcional)
        $trabajador = Trabajador::find($validated['trabajador_id']);
        $workerTag  = $trabajador?->nombre ? " · {$trabajador->nombre}" : '';
        $title      = ($cita->nombre_cliente ?: ($servicio->nombre ?: 'Cita')) . $workerTag;

        // 📧 ENVIAR EMAILS
        $this->enviarEmailsCita($cita, $servicio, $trabajador, $negocioId);

        return response()->json([
            'ok' => true,
            'event' => [
                'title' => $title,
                'start' => $start,   // "YYYY-MM-DD HH:MM:SS"
                'end'   => $end,
                'color' => '#4a5eaa',
                'extendedProps' => [
                    'trabajador_id' => $cita->trabajador_id,
                    'servicio_id'   => $cita->servicio_id,
                    'estado'        => $cita->estado,
                ],
            ]
        ]);
    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json(['ok' => false, 'errors' => $ve->errors()], 422);
    } catch (\Throwable $e) {
        Log::error('Error al guardar cita: ' . $e->getMessage(), ['negocio_id' => $negocioId]);
        return response()->json(['ok' => false, 'errors' => ['general' => ['Error al agendar.']]], 500);
    }
}


public function citasDia(Request $request, $negocioId)
{
    $fecha = $request->query('fecha');
    try {
        $fecha = $fecha
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->toDateString()
            : \Carbon\Carbon::today()->toDateString();
    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'errors' => ['fecha' => ['Formato de fecha inválido (use Y-m-d).']]
        ], 422);
    }

    // Trae trabajador_id (y nombre) para poder bloquear por trabajador en el front
    $citas = \App\Models\Cita::with(['trabajador:id,nombre'])
        ->where('negocio_id', $negocioId)
        ->whereDate('fecha', $fecha)
        ->orderBy('hora_inicio')
        ->get([
            'id',
            'nombre_cliente',
            'hora_inicio',
            'hora_fin',
            'estado',
            'notas',
            'trabajador_id',   // ← IMPORTANTE
            'servicio_id',     // (opcional) útil en el front
        ]);

    // Opcional: normalizar items a arreglo plano (evita payload con objetos Eloquent)
    $items = $citas->map(function ($c) {
        return [
            'id'              => $c->id,
            'nombre_cliente'  => $c->nombre_cliente,
            'hora_inicio'     => substr((string)$c->hora_inicio, 0, 5),
            'hora_fin'        => substr((string)$c->hora_fin, 0, 5),
            'estado'          => $c->estado,
            'notas'           => $c->notas,
            'trabajador_id'   => $c->trabajador_id,
            'trabajador_nombre' => optional($c->trabajador)->nombre, // cómodo para UI
            'servicio_id'     => $c->servicio_id,
        ];
    })->values();

    // Eventos para dibujar en FullCalendar (con trabajador_id en extendedProps)
    $events = $citas->map(function ($c) use ($fecha) {
        return [
            'id'    => $c->id,
            'title' => $c->nombre_cliente ?: 'Cita',
            'start' => "{$fecha}T{$c->hora_inicio}",
            'end'   => "{$fecha}T{$c->hora_fin}",
            'color' => '#4a5eaa',
            'textColor' => '#ffffff',
            'extendedProps' => [
                'source'        => 'citas',
                'estado'        => $c->estado,
                'trabajador_id' => $c->trabajador_id,
                'servicio_id'   => $c->servicio_id,
            ],
        ];
    })->values();

    return response()->json([
        'ok'     => true,
        'fecha'  => $fecha,
        'items'  => $items,   // ← contiene trabajador_id
        'events' => $events,
    ]);
}

public function citasMes(Request $request, $negocioId)
{
    try {
        // Obtener mes/año desde la query o usar el actual
        $year  = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        $inicio = Carbon::create($year, $month, 1)->startOfMonth();
        $fin    = Carbon::create($year, $month, 1)->endOfMonth();

        // Cargar todas las citas del mes con relación de trabajador
        $citas = \App\Models\Cita::with(['trabajador:id,nombre', 'servicio:id,nombre'])
            ->where('negocio_id', $negocioId)
            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get([
                'id',
                'nombre_cliente',
                'fecha',
                'hora_inicio',
                'hora_fin',
                'estado',
                'trabajador_id',
                'servicio_id',
            ]);

        // Convertir a formato de eventos para FullCalendar
        $events = $citas->map(function ($c) {
            $trabajadorNombre = optional($c->trabajador)->nombre;
            $servicioNombre = optional($c->servicio)->nombre;

            // Título con información relevante
            $title = $c->nombre_cliente ?: 'Cita';
            if ($trabajadorNombre) {
                $title .= " · {$trabajadorNombre}";
            }

            return [
                'id'    => "cita-{$c->id}",
                'title' => $title,
                'start' => "{$c->fecha}T{$c->hora_inicio}",
                'end'   => "{$c->fecha}T{$c->hora_fin}",
                'color' => $c->estado === 'confirmada' ? '#10b981' : ($c->estado === 'cancelada' ? '#ef4444' : '#6366f1'),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => 'cita',
                    'cita_id' => $c->id,
                    'estado' => $c->estado,
                    'trabajador_id' => $c->trabajador_id,
                    'trabajador_nombre' => $trabajadorNombre,
                    'servicio_id' => $c->servicio_id,
                    'servicio_nombre' => $servicioNombre,
                ],
            ];
        })->values();

        return response()->json([
            'ok' => true,
            'events' => $events,
        ]);
    } catch (\Throwable $e) {
        Log::error('Error al cargar citas del mes: ' . $e->getMessage(), ['negocio_id' => $negocioId]);
        return response()->json([
            'ok' => false,
            'errors' => ['general' => ['Error al cargar citas.']]
        ], 500);
    }
}

/**
 * Enviar emails de notificación cuando se crea una cita
 */
private function enviarEmailsCita($cita, $servicio, $trabajador, $negocioId)
{
    try {
        $negocio = \App\Models\Negocio::find($negocioId);
        $fechaFormateada = Carbon::parse($cita->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        // 📧 Email al CLIENTE
        if ($cita->user_id) {
            $cliente = \App\Models\User::find($cita->user_id);
            if ($cliente && $cliente->email) {
                Mail::to($cliente->email)->send(new NotificacionGeneral(
                    asunto: '✅ Cita Confirmada - ' . ($negocio->neg_nombre_comercial ?? 'Negocio'),
                    titulo: '¡Tu cita ha sido agendada!',
                    mensaje: 'Hemos confirmado tu cita exitosamente. Te esperamos en la fecha y hora indicada.',
                    detalles: [
                        'Negocio' => $negocio->neg_nombre_comercial ?? 'Sin nombre',
                        'Servicio' => $servicio->nombre ?? 'Servicio',
                        'Trabajador' => $trabajador?->nombre ?? 'Por asignar',
                        'Fecha' => $fechaFormateada,
                        'Hora' => substr($cita->hora_inicio, 0, 5) . ' - ' . substr($cita->hora_fin, 0, 5),
                        'Estado' => 'Pendiente de confirmación',
                    ],
                    accionTexto: 'Ver mis citas',
                    accionUrl: url('/dashboard-cliente'),
                    tipoIcono: 'success'
                ));
            }
        }

        // 📧 Email al DUEÑO DEL NEGOCIO
        if ($negocio && $negocio->user_id) {
            $dueno = \App\Models\User::find($negocio->user_id);
            if ($dueno && $dueno->email) {
                Mail::to($dueno->email)->send(new NotificacionGeneral(
                    asunto: '🔔 Nueva Cita Agendada',
                    titulo: '¡Tienes una nueva cita!',
                    mensaje: 'Un cliente ha agendado una cita en tu negocio. Revisa los detalles y confirma la disponibilidad.',
                    detalles: [
                        'Cliente' => $cita->nombre_cliente ?? 'Cliente',
                        'Servicio' => $servicio->nombre ?? 'Servicio',
                        'Trabajador' => $trabajador?->nombre ?? 'Por asignar',
                        'Fecha' => $fechaFormateada,
                        'Hora' => substr($cita->hora_inicio, 0, 5) . ' - ' . substr($cita->hora_fin, 0, 5),
                        'Notas' => $cita->notas ?? 'Sin notas',
                    ],
                    accionTexto: 'Ver cita y confirmar',
                    accionUrl: route('empresa.configuracion.citas', $negocioId),
                    tipoIcono: 'info'
                ));
            }
        }

        Log::info('Emails de cita enviados', ['cita_id' => $cita->id]);
    } catch (\Throwable $e) {
        Log::error('Error al enviar emails de cita: ' . $e->getMessage(), [
            'cita_id' => $cita->id ?? null,
            'negocio_id' => $negocioId
        ]);
        // No lanzamos excepción para no interrumpir el flujo de la cita
    }
}

}
