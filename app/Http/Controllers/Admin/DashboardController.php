<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\BambooPaymentLog;
use App\Models\Cita;
use App\Models\Negocio;
use App\Models\Resena;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats reales
        $citasHoy        = Cita::whereDate('fecha', today())->count();
        $empresasActivas = Negocio::count();
        $totalUsuarios   = User::count();
        $suscripcionesActivas = Subscription::whereIn('status', ['active', 'trial'])->count();

        // Citas recientes
        $citasRecientes = Cita::with(['negocio', 'servicio', 'user'])
            ->latest('fecha')
            ->take(10)
            ->get();

        // Chart: citas por dia (ultima semana)
        $citasSemana = Cita::selectRaw('DATE(fecha) as dia, COUNT(*) as total')
            ->whereBetween('fecha', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupByRaw('DATE(fecha)')
            ->orderBy('dia')
            ->pluck('total', 'dia');

        // Chart: categorias de negocios
        $categoriasRaw = Negocio::whereNotNull('neg_categorias')
            ->pluck('neg_categorias');

        $categorias = collect();
        foreach ($categoriasRaw as $cats) {
            $arr = is_array($cats) ? $cats : json_decode($cats, true);
            if (is_array($arr)) {
                foreach ($arr as $cat) {
                    $categorias->push($cat);
                }
            }
        }
        $categorias = $categorias->countBy()->sortDesc()->take(6);

        return view('admin.dashboard-admin', compact(
            'citasHoy',
            'empresasActivas',
            'totalUsuarios',
            'suscripcionesActivas',
            'citasRecientes',
            'citasSemana',
            'categorias',
        ));
    }

    // Entrypoint: decide a dónde ir según el rol
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('roles');

        if ($user->hasRole('Administrador', 'web')) {
            return $this->index();
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

        // Verificar si tiene suscripción activa (no expirada)
        $user->load('subscription');
        $activeSub = $user->subscription;
        if (!$activeSub || $activeSub->isExpired()) {
            return redirect()->route('client.elegir-plan');
        }

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

        $citasCompletadas = Cita::with(['negocio', 'servicio', 'trabajador'])
            ->where('user_id', $user->id)
            ->where('estado', 'completada')
            ->orderByDesc('fecha')
            ->take(10)
            ->get();

        $resenasExistentes = Resena::where('user_id', $user->id)->pluck('cita_id')->toArray();

        // Subscription & plan
        $user->load('subscription.plan');
        $subscription = $user->subscription;
        $plan = $subscription?->plan;

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
            'citasCompletadas'        => $citasCompletadas,
            'resenasExistentes'       => $resenasExistentes,
            'subscription'            => $subscription,
            'plan'                    => $plan,
        ]);
    }

    /**
     * Actualizar perfil del cliente desde el dashboard.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'dni'     => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:30',
            'pais'    => 'nullable|string|max:100',
            'ciudad'  => 'nullable|string|max:100',
            'foto'    => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'user_' . $user->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/usuarios'), $filename);
            $validated['foto'] = 'images/usuarios/' . $filename;
        }

        $user->update($validated);

        return redirect()->route('client.dashboard-client')
            ->with('profile_success', 'Perfil actualizado correctamente.');
    }

    /**
     * Mostrar página de selección de plan.
     */
    public function elegirPlan()
    {
        $user = Auth::user();

        // Si ya tiene suscripción activa, ir al dashboard
        $user->load('subscription');
        $activeSub = $user->subscription;
        if ($activeSub && !$activeSub->isExpired()) {
            return redirect()->route('client.dashboard-client');
        }

        // Verificar si ya usó un plan gratuito antes
        $usedFreePlan = Subscription::where('user_id', $user->id)
            ->whereHas('plan', fn($q) => $q->where('price', 0))
            ->exists();

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('client.elegir-plan', compact('plans', 'usedFreePlan'));
    }

    /**
     * Procesar la selección de plan.
     */
    public function seleccionarPlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Si es plan gratuito, verificar que no lo haya usado antes
        if ((float) $plan->price == 0) {
            $yaUsoGratis = Subscription::where('user_id', $user->id)
                ->whereHas('plan', fn($q) => $q->where('price', 0))
                ->exists();

            if ($yaUsoGratis) {
                return back()->with('error', 'Ya utilizaste tu periodo gratuito de 15 días. Por favor elige un plan pago.');
            }
        }

        // Desactivar suscripciones anteriores
        Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // Duración según tipo de plan
        if ((float) $plan->price == 0) {
            // Plan gratuito: 15 días
            $endsAt = now()->addDays(15)->toDateString();
        } else {
            // Plan pago: según intervalo
            $endsAt = $plan->interval === 'yearly'
                ? now()->addYear()->toDateString()
                : now()->addMonth()->toDateString();
        }

        Subscription::create([
            'user_id'   => $user->id,
            'plan_id'   => $plan->id,
            'status'    => 'active',
            'is_trial'  => (float) $plan->price == 0,
            'starts_at' => now()->toDateString(),
            'ends_at'   => $endsAt,
        ]);

        return redirect()->route('client.dashboard-client')
            ->with('plan_success', '¡Plan activado correctamente!');
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
