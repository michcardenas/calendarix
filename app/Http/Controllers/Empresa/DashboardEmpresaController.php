<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;
use App\Models\Cita;
use App\Models\Trabajador;
use App\Models\Cliente;
use App\Models\Empresa\ServicioEmpresa;
use Illuminate\Support\Carbon;

class DashboardEmpresaController extends Controller
{
    public function index($id)
    {
        $empresa = Negocio::findOrFail($id);

        $hoy = Carbon::today();

        // Estadísticas principales
        $totalCitas = Cita::where('negocio_id', $id)->count();
        $citasHoy = Cita::where('negocio_id', $id)->whereDate('fecha', $hoy)->count();
        $citasPendientes = Cita::where('negocio_id', $id)->where('estado', 'pendiente')->count();
        $citasConfirmadas = Cita::where('negocio_id', $id)->where('estado', 'confirmada')->count();
        $citasCanceladas = Cita::where('negocio_id', $id)->where('estado', 'cancelada')->count();
        $serviciosActivos = ServicioEmpresa::where('negocio_id', $id)->count();
        $miembrosEquipo = Trabajador::where('negocio_id', $id)->count();
        $totalClientes = Cliente::where('negocio_id', $id)->count();

        // Citas de esta semana
        $citasSemana = Cita::where('negocio_id', $id)
            ->whereBetween('fecha', [$hoy, $hoy->copy()->endOfWeek()])
            ->count();

        // Citas del mes
        $citasMes = Cita::where('negocio_id', $id)
            ->whereMonth('fecha', $hoy->month)
            ->whereYear('fecha', $hoy->year)
            ->count();

        // Próximas citas (hoy en adelante, máximo 8)
        $proximasCitas = Cita::where('negocio_id', $id)
            ->where('fecha', '>=', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->with(['servicio', 'trabajador'])
            ->limit(8)
            ->get();

        // Citas recientes pasadas (últimas 5)
        $citasRecientes = Cita::where('negocio_id', $id)
            ->where('fecha', '<', $hoy)
            ->orderByDesc('fecha')
            ->orderByDesc('hora_inicio')
            ->with(['servicio', 'trabajador'])
            ->limit(5)
            ->get();

        return view('empresa.dashboard', compact(
            'empresa',
            'totalCitas',
            'citasHoy',
            'citasPendientes',
            'citasConfirmadas',
            'citasCanceladas',
            'serviciosActivos',
            'miembrosEquipo',
            'totalClientes',
            'citasSemana',
            'citasMes',
            'proximasCitas',
            'citasRecientes'
        ));
    }
}
