<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionGeneral;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // 📧 Enviar email de confirmación de verificación
            try {
                Mail::to($request->user()->email)->send(new NotificacionGeneral(
                    asunto: '✅ Email Verificado',
                    titulo: '¡Tu email ha sido verificado!',
                    mensaje: 'Tu dirección de email ha sido verificada exitosamente. Ahora tienes acceso completo a todas las funcionalidades de Calendarix.',
                    detalles: [
                        'Nombre' => $request->user()->name,
                        'Email' => $request->user()->email,
                        'Fecha de verificación' => now()->format('d/m/Y H:i'),
                    ],
                    accionTexto: 'Explorar Negocios',
                    accionUrl: url('/dashboard'),
                    tipoIcono: 'success'
                ));
            } catch (\Exception $e) {
                \Log::error('Error al enviar email de verificación confirmada', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
