<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\NotificacionGeneral;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // 📧 Enviar email de confirmación de cambio de contraseña
                try {
                    Mail::to($user->email)->send(new NotificacionGeneral(
                        asunto: '✅ Contraseña Restablecida',
                        titulo: 'Tu contraseña ha sido cambiada',
                        mensaje: 'Tu contraseña ha sido actualizada exitosamente. Si no fuiste tú quien realizó este cambio, contacta con soporte inmediatamente.',
                        detalles: [
                            'Nombre' => $user->name,
                            'Email' => $user->email,
                            'Fecha del cambio' => now()->format('d/m/Y H:i'),
                            'Dirección IP' => $request->ip(),
                        ],
                        accionTexto: 'Iniciar Sesión',
                        accionUrl: route('login'),
                        tipoIcono: 'success'
                    ));
                } catch (\Exception $e) {
                    \Log::error('Error al enviar email de confirmación de cambio de contraseña', ['error' => $e->getMessage()]);
                }
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
