<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa\Empresa;
use App\Models\DiaBloqueado;
use App\Models\HorarioLaboral;
use Carbon\Carbon;

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
}
