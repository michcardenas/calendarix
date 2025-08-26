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
            return view('admin.dashboard-admin', [
                'user' => $user,
            ]);
        }

        if ($user->hasRole('Cliente', 'web')) {
            // Empresas del usuario
            $misEmpresas = Negocio::where('user_id', $user->id)->get();

            // Citas este mes (del cliente)
            $inicioMes = Carbon::now()->startOfMonth();
            $finMes    = Carbon::now()->endOfMonth();

            $citasMes = Cita::where('user_id', $user->id)
                ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
                ->count();

            // Citas pendientes (semana en curso)
            $inicioSemana = Carbon::now()->startOfWeek();
            $finSemana    = Carbon::now()->endOfWeek();

            $citasPendientes = Cita::where('user_id', $user->id)
                ->whereBetween('fecha', [$inicioSemana->toDateString(), $finSemana->toDateString()])
                ->whereIn('estado', ['pendiente', 'confirmada']) // ajusta a tus estados
                ->count();

            // Próximas citas (hoy en adelante)
            $proximasCitas = Cita::with('negocio')
                ->where('user_id', $user->id)
                ->whereDate('fecha', '>=', Carbon::today())
                ->orderBy('fecha')
                ->orderBy('hora_inicio')
                ->take(8)
                ->get();

            // Favoritos (si existe el modelo/relación); de lo contrario 0
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

            // Recomendados simples (negocios recientes)
            $recomendados = Negocio::latest()->take(6)->get();

            return view('client.dashboard-client', [
                'misEmpresas'     => $misEmpresas,
                'citasMes'        => $citasMes,
                'favoritosCount'  => $favoritosCount,
                'citasPendientes' => $citasPendientes,
                'proximasCitas'   => $proximasCitas,
                'recomendados'    => $recomendados,
            ]);
        }

        abort(403, 'No tienes permisos suficientes.');
    }
}
