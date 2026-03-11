<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Services\BambooPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuscripcionController extends Controller
{
    public function __construct(
        private BambooPaymentService $bamboo
    ) {}

    /**
     * Iniciar el flujo de suscripción: crear customer en Bamboo y redirigir a tokenización.
     * POST /suscripcion/iniciar
     */
    public function iniciarSuscripcion(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Crear customer en Bamboo si no existe
        if (!$user->bamboo_customer_id) {
            $result = $this->bamboo->createCustomer($user);

            if (!$result['success']) {
                return back()->with('error', $result['error']);
            }

            $user->update([
                'bamboo_customer_id' => $result['customer_id'],
                'bamboo_unique_id'   => $result['unique_id'],
            ]);
        }

        // Guardar plan_id en sesión y redirigir a la vista de tokenización
        session(['bamboo_plan_id' => $plan->id]);

        return redirect()->route('suscripcion.tokenizar', ['plan_id' => $plan->id]);
    }

    /**
     * Mostrar el formulario de tokenización embebido de Bamboo.
     * GET /suscripcion/tokenizar?plan_id=X
     */
    public function mostrarFormularioTokenizacion(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Verificar que el usuario tiene bamboo_unique_id
        if (!$user->bamboo_unique_id) {
            return redirect()->route('client.elegir-plan')
                ->with('error', 'Error en la configuración de pago. Intenta nuevamente.');
        }

        return view('suscripcion.tokenizar', [
            'plan'              => $plan,
            'publicKey'         => $this->bamboo->getPublicKey(),
            'captureScriptUrl'  => $this->bamboo->getCaptureScriptUrl(),
            'captureIntegrity'  => $this->bamboo->getCaptureIntegrity(),
            'targetCountry'     => $this->bamboo->getTargetCountry(),
            'uniqueId'          => $user->bamboo_unique_id,
        ]);
    }

    /**
     * Procesar el token recibido de Bamboo.
     * POST /suscripcion/procesar-token
     */
    public function procesarToken(Request $request)
    {
        $request->validate([
            'plan_id'  => 'required|exists:plans,id',
            'token_id' => 'required|string',
            'dni'      => 'nullable|string|max:20',
        ]);

        $user  = Auth::user();
        $plan  = Plan::findOrFail($request->plan_id);
        $token = $request->token_id;

        // Guardar DNI si se proporcionó
        if ($request->filled('dni') && !$user->dni) {
            $user->update(['dni' => $request->dni]);
        }

        // Idempotencia: verificar que no tenga ya una suscripción activa
        $user->load('subscription');
        if ($user->subscription && !$user->subscription->isExpired()) {
            return redirect()->route('client.dashboard-client');
        }

        // Cancelar suscripciones anteriores
        Subscription::where('user_id', $user->id)
            ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIAL])
            ->update(['status' => Subscription::STATUS_CANCELLED, 'cancelled_at' => now()]);

        // Si el usuario no ha usado su trial → crear trial de 15 días
        if (!$user->hasUsedTrial()) {
            Subscription::create([
                'user_id'      => $user->id,
                'plan_id'      => $plan->id,
                'status'       => Subscription::STATUS_TRIAL,
                'is_trial'     => true,
                'bamboo_token' => $token,
                'starts_at'    => now()->toDateString(),
                'ends_at'      => now()->addDays(15)->toDateString(),
            ]);

            $user->markTrialUsed();

            return redirect()->route('client.dashboard-client')
                ->with('plan_success', '¡15 días de prueba gratis activados para ' . $plan->name . '!');
        }

        // Si ya usó trial → cobrar inmediatamente
        $result = $this->bamboo->createPurchase(
            trxToken: $token,
            amount: (float) $plan->price,
            currency: $plan->currency ?? 'UYU',
            uniqueId: $user->bamboo_unique_id,
            user: $user,
            order: 'SUB-' . $user->id . '-' . now()->format('YmdHis'),
            description: "Suscripción {$plan->name} - Calendarix",
        );

        if ($result['success']) {
            $endsAt = $plan->interval === 'yearly'
                ? now()->addYear()->toDateString()
                : now()->addMonth()->toDateString();

            Subscription::create([
                'user_id'      => $user->id,
                'plan_id'      => $plan->id,
                'status'       => Subscription::STATUS_ACTIVE,
                'is_trial'     => false,
                'bamboo_token' => $token,
                'starts_at'    => now()->toDateString(),
                'ends_at'      => $endsAt,
            ]);

            return redirect()->route('client.dashboard-client')
                ->with('plan_success', '¡Plan ' . $plan->name . ' activado correctamente!');
        }

        // Pago rechazado
        Log::warning('BambooPayment purchase rejected', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'error'   => $result['error'],
        ]);

        return redirect()->route('client.elegir-plan')
            ->with('error', 'No se pudo procesar el pago: ' . $result['error']);
    }

    /**
     * Cancelar la suscripción activa.
     * POST /suscripcion/cancelar
     */
    public function cancelar()
    {
        $user = Auth::user();
        $user->load('subscription');

        if ($user->subscription) {
            $user->subscription->update([
                'status'       => Subscription::STATUS_CANCELLED,
                'cancelled_at' => now(),
            ]);
        }

        return redirect()->route('client.elegir-plan')
            ->with('plan_success', 'Tu suscripción ha sido cancelada.');
    }
}
