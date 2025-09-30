<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // 🔍 LOG: Usuario accediendo al dashboard
        Log::info('Dashboard Cliente - Inicio', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $user->name,
        ]);

        // Empresas del usuario
        $misEmpresas = Negocio::where('user_id', $user->id)->get();
        $misEmpresasIds = $misEmpresas->pluck('id')->toArray();

        // 🔍 LOG: Empresas encontradas
        Log::info('Dashboard Cliente - Empresas', [
            'user_id' => $user->id,
            'empresas_count' => $misEmpresas->count(),
            'empresas_ids' => $misEmpresasIds,
        ]);

        // Rangos de tiempo
        $inicioMes    = Carbon::now()->startOfMonth();
        $finMes       = Carbon::now()->endOfMonth();
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana    = Carbon::now()->endOfWeek();

        // Estados
        $estadosActivos     = ['pendiente', 'confirmada'];
        $estadosFinalizados = ['cancelada', 'completada'];

        // 🎯 Citas del mes: AMBAS (como cliente Y de mis negocios)
        $citasMes = Cita::where(function($query) use ($user, $misEmpresasIds) {
                // Citas donde soy el cliente
                $query->where('user_id', $user->id)
                      // O citas en mis negocios
                      ->orWhereIn('negocio_id', $misEmpresasIds);
            })
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();

        // 🔍 LOG: Estadísticas del mes
        Log::info('Dashboard Cliente - Citas del Mes', [
            'user_id' => $user->id,
            'citas_mes' => $citasMes,
            'rango' => [$inicioMes->toDateString(), $finMes->toDateString()],
        ]);

        // Conteos por estado en la semana: AMBAS
        $conteosSemana = Cita::where(function($query) use ($user, $misEmpresasIds) {
                $query->where('user_id', $user->id)
                      ->orWhereIn('negocio_id', $misEmpresasIds);
            })
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

        // Próximas citas (hoy en adelante) activas: AMBAS
        // Eager loading de relaciones para evitar N+1 queries
        $proximasCitas = Cita::with(['negocio', 'servicio', 'trabajador', 'user'])
            ->where(function($query) use ($user, $misEmpresasIds) {
                $query->where('user_id', $user->id)
                      ->orWhereIn('negocio_id', $misEmpresasIds);
            })
            ->whereDate('fecha', '>=', Carbon::today())
            ->whereIn('estado', $estadosActivos)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(8)
            ->get();

        // 🔍 LOG: Próximas citas encontradas
        Log::info('Dashboard Cliente - Próximas Citas', [
            'user_id' => $user->id,
            'count' => $proximasCitas->count(),
            'citas' => $proximasCitas->map(function($c) use ($user) {
                // Identificar si es cita como cliente o de mi negocio
                $esMiCita = $c->user_id == $user->id;
                return [
                    'id' => $c->id,
                    'tipo' => $esMiCita ? 'CLIENTE' : 'NEGOCIO',
                    'fecha' => $c->fecha,
                    'hora_inicio' => $c->hora_inicio,
                    'estado' => $c->estado,
                    'negocio_id' => $c->negocio_id,
                    'negocio_nombre' => $c->negocio?->neg_nombre_comercial ?? 'NO_CARGADO',
                    'servicio_nombre' => $c->servicio?->nombre ?? 'NO_CARGADO',
                    'trabajador_nombre' => $c->trabajador?->nombre ?? 'NO_CARGADO',
                    'cliente_nombre' => $c->user?->name ?? $c->nombre_cliente ?? 'NO_CARGADO',
                ];
            })->toArray(),
        ]);

        // Recomendados simples
        $recomendados = Negocio::latest()->take(6)->get();

        // (Opcional) favoritos
        $favoritosCount = 0;
        if (class_exists(\App\Models\Favorito::class)) {
            $favoritosCount = \App\Models\Favorito::where('user_id', $user->id)->count();
        } elseif (method_exists($user, 'favoritos')) {
            $favoritosCount = $user->favoritos()->count();
        }

        // 🔍 LOG: Resumen final antes de renderizar vista
        Log::info('Dashboard Cliente - Resumen Final', [
            'user_id' => $user->id,
            'stats' => [
                'empresas' => $misEmpresas->count(),
                'citas_mes' => $citasMes,
                'favoritos' => $favoritosCount,
                'pendientes' => $citasPendientes,
                'proximas_citas' => $proximasCitas->count(),
                'recomendados' => $recomendados->count(),
            ],
        ]);

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

    /**
     * Endpoint de debug para verificar citas en producción
     * Acceso: /dashboard-cliente/debug (solo usuarios autenticados)
     */
    public function debugCitas()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Consulta básica de citas
        $todasLasCitas = Cita::where('user_id', $user->id)
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // Consulta con relaciones
        $citasConRelaciones = Cita::with(['negocio', 'servicio', 'trabajador'])
            ->where('user_id', $user->id)
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // Verificar si el modelo Negocio tiene los datos
        $negociosTest = [];
        foreach ($citasConRelaciones as $cita) {
            if ($cita->negocio) {
                $negociosTest[] = [
                    'cita_id' => $cita->id,
                    'negocio_id' => $cita->negocio->id,
                    'negocio_class' => get_class($cita->negocio),
                    'has_neg_nombre_comercial' => isset($cita->negocio->neg_nombre_comercial),
                    'neg_nombre_comercial' => $cita->negocio->neg_nombre_comercial ?? 'NULL',
                    'attributes' => array_keys($cita->negocio->getAttributes()),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'citas_count' => $todasLasCitas->count(),
            'citas_sin_relaciones' => $todasLasCitas->map(function($c) {
                return [
                    'id' => $c->id,
                    'fecha' => $c->fecha,
                    'estado' => $c->estado,
                    'negocio_id' => $c->negocio_id,
                    'servicio_id' => $c->servicio_id,
                    'trabajador_id' => $c->trabajador_id,
                ];
            }),
            'citas_con_relaciones' => $citasConRelaciones->map(function($c) {
                return [
                    'id' => $c->id,
                    'fecha' => $c->fecha,
                    'estado' => $c->estado,
                    'negocio' => $c->negocio ? [
                        'id' => $c->negocio->id,
                        'class' => get_class($c->negocio),
                        'nombre' => $c->negocio->neg_nombre_comercial ?? 'NULL',
                    ] : null,
                    'servicio' => $c->servicio ? [
                        'id' => $c->servicio->id,
                        'nombre' => $c->servicio->nombre ?? 'NULL',
                    ] : null,
                    'trabajador' => $c->trabajador ? [
                        'id' => $c->trabajador->id,
                        'nombre' => $c->trabajador->nombre ?? 'NULL',
                    ] : null,
                ];
            }),
            'negocios_test' => $negociosTest,
            'models_info' => [
                'Cita' => Cita::class,
                'Negocio' => \App\Models\Negocio::class,
                'Empresa' => \App\Models\Empresa\Empresa::class,
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
