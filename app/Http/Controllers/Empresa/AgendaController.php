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
use Illuminate\Support\Facades\Auth;

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

            // Evitar solapamientos (mismo negocio, misma fecha, rango cruzado)
            $overlap = Cita::where('negocio_id', $negocioId)
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
                    'errors' => ['general' => ['Ya existe una cita en ese rango de horas.']]
                ], 422);
            }

            // Servicio (validado que pertenece al negocio)
            $servicio = ServicioEmpresa::where('negocio_id', $negocioId)
                ->findOrFail($validated['servicio_id']);

            // Congelar precio en centavos (int). Si no usas centavos, ver nota abajo.
            $precioCerrado = is_null($servicio->precio)
                ? null
                : (int) round(((float) $servicio->precio) * 100);

            // Datetimes para el calendario
            $start = "{$validated['fecha']} {$validated['hora_inicio']}:00";
            $end   = "{$validated['fecha']} {$validated['hora_fin']}:00";

            // Crear la cita
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
                'precio_cerrado' => $precioCerrado,          // ← congelado
            ]);

            // Título sugerido
            $title = $cita->nombre_cliente ?: ($servicio->nombre ?: 'Cita');

            return response()->json([
                'ok' => true,
                'event' => [
                    'title' => $title,
                    'start' => $start,   // "YYYY-MM-DD HH:MM:SS"
                    'end'   => $end,
                    'color' => '#4a5eaa'
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
            $fecha = $fecha ? \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->toDateString()
                : \Carbon\Carbon::today()->toDateString();
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'errors' => ['fecha' => ['Formato de fecha inválido (use Y-m-d).']]
            ], 422);
        }

        $citas = \App\Models\Cita::where('negocio_id', $negocioId)
            ->whereDate('fecha', $fecha)
            ->orderBy('hora_inicio')
            ->get(['id', 'nombre_cliente', 'hora_inicio', 'hora_fin', 'estado', 'notas']);

        // Eventos para dibujar en FullCalendar
        $events = $citas->map(function ($c) use ($fecha) {
            return [
                'id'    => $c->id,
                'title' => $c->nombre_cliente ?: 'Cita',
                'start' => "{$fecha}T{$c->hora_inicio}",
                'end'   => "{$fecha}T{$c->hora_fin}",
                'color' => '#4a5eaa',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'source' => 'citas',  // bandera para limpiar luego
                    'estado' => $c->estado,
                ],
            ];
        })->values();

        return response()->json([
            'ok'    => true,
            'fecha' => $fecha,
            'items' => $citas,
            'events' => $events,
        ]);
    }
}
