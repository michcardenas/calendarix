<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Cita;
use App\Models\Negocio;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard-admin');
    }

    // Entrypoint: decide a dónde ir según el rol
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('roles');

        if ($user->hasRole('Administrador', 'web')) {
            return view('admin.dashboard-admin', ['user' => $user]);
        }

        if ($user->hasRole('Cliente', 'web')) {
            // 👉 puedes redirigir a la ruta o llamar al método directamente
            // return redirect()->route('client.dashboard-client');
            return $this->cliente(); // evita redirección y duplica menos
        }

        abort(403, 'No tienes permisos suficientes.');
    }

    // 👉 mismo código que ya tenías para el dashboard de cliente
    public function cliente()
    {
        $user = Auth::user();

        // Empresas del usuario
        $misEmpresas = Negocio::where('user_id', $user->id)->get();

        // Rangos de tiempo
        $inicioMes    = Carbon::now()->startOfMonth();
        $finMes       = Carbon::now()->endOfMonth();
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana    = Carbon::now()->endOfWeek();

        // Estados
        $estadosActivos     = ['pendiente', 'confirmada'];
        $estadosFinalizados = ['cancelada', 'completada'];

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

        $citasPendientesSemana  = (int) ($conteosSemana['pendiente']  ?? 0);
        $citasConfirmadasSemana = (int) ($conteosSemana['confirmada'] ?? 0);
        $citasCanceladasSemana  = (int) ($conteosSemana['cancelada']  ?? 0);
        $citasCompletadasSemana = (int) ($conteosSemana['completada'] ?? 0);

        // Compatibilidad
        $citasPendientes = $citasPendientesSemana + $citasConfirmadasSemana;

        // Próximas citas (hoy en adelante) activas
        // Eager loading de relaciones para evitar N+1 queries
        $proximasCitas = Cita::with(['negocio', 'servicio', 'trabajador'])
            ->where('user_id', $user->id)
            ->whereDate('fecha', '>=', Carbon::today())
            ->whereIn('estado', $estadosActivos)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(8)
            ->get();

        // Recomendados simples
        $recomendados = Negocio::latest()->take(6)->get();

        // (Opcional) favoritos
        $favoritosCount = 0;
        if (class_exists(\App\Models\Favorito::class)) {
            $favoritosCount = \App\Models\Favorito::where('user_id', $user->id)->count();
        } elseif (method_exists($user, 'favoritos')) {
            $favoritosCount = $user->favoritos()->count();
        }

        return view('client.dashboard-client', [
            'misEmpresas'             => $misEmpresas,
            'citasMes'                => $citasMes,
            'favoritosCount'          => $favoritosCount,
            'citasPendientes'         => $citasPendientes,
            'proximasCitas'           => $proximasCitas,
            'recomendados'            => $recomendados,
            'citasPendientesSemana'   => $citasPendientesSemana,
            'citasConfirmadasSemana'  => $citasConfirmadasSemana,
            'citasCanceladasSemana'   => $citasCanceladasSemana,
            'citasCompletadasSemana'  => $citasCompletadasSemana,
        ]);
    }
}
