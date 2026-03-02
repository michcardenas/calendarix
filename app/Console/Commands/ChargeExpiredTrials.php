<?php

namespace App\Console\Commands;

use App\Mail\NotificacionGeneral;
use App\Models\Subscription;
use App\Services\BambooPaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ChargeExpiredTrials extends Command
{
    protected $signature = 'bamboo:charge-expired-trials';
    protected $description = 'Cobra automáticamente las suscripciones trial vencidas usando Bamboo Payment';

    public function handle(BambooPaymentService $bamboo): int
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->where('status', Subscription::STATUS_TRIAL)
            ->whereNotNull('bamboo_token')
            ->whereDate('ends_at', '<=', now())
            ->get();

        $this->info("Encontradas {$subscriptions->count()} suscripciones trial vencidas.");

        if ($subscriptions->isEmpty()) {
            return Command::SUCCESS;
        }

        $charged = 0;
        $failed  = 0;

        foreach ($subscriptions as $sub) {
            $user = $sub->user;
            $plan = $sub->plan;

            if (!$user || !$plan) {
                $this->warn("  Sub #{$sub->id}: usuario o plan no encontrado, omitida.");
                continue;
            }

            $this->info("  Procesando sub #{$sub->id} — usuario: {$user->email} — plan: {$plan->name}");

            try {
                $result = $bamboo->createPurchase(
                    trxToken: $sub->bamboo_token,
                    amount: (float) $plan->price,
                    currency: $plan->currency ?? 'UYU',
                    uniqueId: $user->bamboo_unique_id ?? '',
                    user: $user,
                    order: "TRIAL-{$sub->id}-" . now()->format('Ymd'),
                    description: "Suscripción {$plan->name} - Calendarix (post-trial)",
                    subscriptionId: $sub->id,
                );

                if ($result['success']) {
                    $newEndsAt = $plan->interval === 'yearly'
                        ? now()->addYear()
                        : now()->addMonth();

                    $sub->update([
                        'status'    => Subscription::STATUS_ACTIVE,
                        'is_trial'  => false,
                        'starts_at' => now()->toDateString(),
                        'ends_at'   => $newEndsAt->toDateString(),
                    ]);

                    // Enviar email de confirmación
                    try {
                        Mail::to($user->email)->queue(new NotificacionGeneral(
                            asunto: 'Suscripción activada - Calendarix',
                            titulo: 'Tu suscripción fue activada',
                            mensaje: "Tu periodo de prueba ha terminado y tu plan {$plan->name} ha sido activado exitosamente.",
                            detalles: [
                                'Plan'           => $plan->name,
                                'Monto'          => '$' . number_format($plan->price) . ' ' . $plan->currency,
                                'Próximo cobro'  => $newEndsAt->format('d/m/Y'),
                            ],
                            accionTexto: 'Ir al Dashboard',
                            accionUrl: route('client.dashboard-client'),
                            tipoIcono: 'success'
                        ));
                    } catch (\Exception $e) {
                        Log::error("ChargeExpiredTrials: error enviando email de confirmación", [
                            'sub_id' => $sub->id,
                            'error'  => $e->getMessage(),
                        ]);
                    }

                    $charged++;
                    $this->info("    COBRADO OK — nueva vigencia hasta {$newEndsAt->format('d/m/Y')}");
                } else {
                    $sub->update(['status' => Subscription::STATUS_PAYMENT_FAILED]);

                    // Enviar email de fallo
                    try {
                        Mail::to($user->email)->queue(new NotificacionGeneral(
                            asunto: 'Error en el cobro de tu suscripción - Calendarix',
                            titulo: 'No pudimos procesar tu pago',
                            mensaje: 'El cobro automático de tu suscripción no pudo ser procesado. Por favor elige un nuevo plan o actualiza tu método de pago.',
                            detalles: [
                                'Plan'  => $plan->name,
                                'Error' => $result['error'] ?? 'Pago rechazado',
                            ],
                            accionTexto: 'Elegir un plan',
                            accionUrl: route('client.elegir-plan'),
                            tipoIcono: 'error'
                        ));
                    } catch (\Exception $e) {
                        Log::error("ChargeExpiredTrials: error enviando email de fallo", [
                            'sub_id' => $sub->id,
                            'error'  => $e->getMessage(),
                        ]);
                    }

                    $failed++;
                    $this->warn("    PAGO RECHAZADO — {$result['error']}");
                }
            } catch (\Exception $e) {
                Log::error("ChargeExpiredTrials: excepción en sub #{$sub->id}", [
                    'error' => $e->getMessage(),
                ]);
                $this->error("    EXCEPCIÓN — {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("Resumen: {$charged} cobrados, {$failed} fallidos.");

        return Command::SUCCESS;
    }
}
