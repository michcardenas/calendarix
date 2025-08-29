<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Cita;
use App\Models\Negocio;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard-admin');
    }
    
   public function dashboard()
{
    $user = Auth::user();
    $user->load('roles');

    if ($user->hasRole('Administrador', 'web')) {
        return view('admin.dashboard-admin', ['user' => $user]);
    }

    if ($user->hasRole('Cliente', 'web')) {
        // Empresas del usuario
        $misEmpresas = Negocio::where('user_id', $user->id)->get();

        // Rangos de tiempo
        $inicioMes    = Carbon::now()->startOfMonth();
        $finMes       = Carbon::now()->endOfMonth();
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana    = Carbon::now()->endOfWeek();

        // Estados
        $estadosActivos      = ['pendiente', 'confirmada'];
        $estadosFinalizados  = ['cancelada', 'completada'];
        $todosLosEstados     = array_merge($estadosActivos, $estadosFinalizados);

        // Citas del mes (todas)
        $citasMes = Cita::where('user_id', $user->id)
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();

        // Conteos por estado en la semana
        $conteosSemana = Cita::where('user_id', $user->id)
            ->whereBetween('fecha', [$inicioSemana->toDateString(), $finSemana->toDateString()])
            ->selectRaw('estado, COUNT(*) AS total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Normaliza: asegura llaves con cero si no existen
        $citasPendientesSemana  = (int) ($conteosSemana['pendiente']  ?? 0);
        $citasConfirmadasSemana = (int) ($conteosSemana['confirmada'] ?? 0);
        $citasCanceladasSemana  = (int) ($conteosSemana['cancelada']  ?? 0);
        $citasCompletadasSemana = (int) ($conteosSemana['completada'] ?? 0);

        // Compatibilidad: "citasPendientes" = activas (pendiente + confirmada) en la semana
        $citasPendientes = $citasPendientesSemana + $citasConfirmadasSemana;

        // Próximas citas (hoy en adelante) solo activas (no canceladas ni completadas)
        $proximasCitas = Cita::with('negocio')
            ->where('user_id', $user->id)
            ->whereDate('fecha', '>=', Carbon::today())
            ->whereIn('estado', $estadosActivos)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(8)
            ->get();

        // Favoritos (si existe)
        $favoritosCount = 0;
        try {
            if (class_exists(\App\Models\Favorito::class)) {
                $favoritosCount = \App\Models\Favorito::where('user_id', $user->id)->count();
            } elseif (method_exists($user, 'favoritos')) {
                $favoritosCount = $user->favoritos()->count();
            }
        } catch (\Throwable $e) {
            $favoritosCount = 0;
        }

        // Recomendados simples
        $recomendados = Negocio::latest()->take(6)->get();

        return view('client.dashboard-client', [
            'misEmpresas'          => $misEmpresas,
            'citasMes'             => $citasMes,
            'favoritosCount'       => $favoritosCount,
            'citasPendientes'      => $citasPendientes,          // activas (pendiente + confirmada) - compat
            'proximasCitas'        => $proximasCitas,
            'recomendados'         => $recomendados,

            // 👇 nuevos datos por estado en la semana (útiles para cards/gráficas)
            'citasPendientesSemana'  => $citasPendientesSemana,
            'citasConfirmadasSemana' => $citasConfirmadasSemana,
            'citasCanceladasSemana'  => $citasCanceladasSemana,
            'citasCompletadasSemana' => $citasCompletadasSemana,
        ]);
    }

    abort(403, 'No tienes permisos suficientes.');
}

}
