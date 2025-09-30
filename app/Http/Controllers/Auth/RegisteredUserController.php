<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use App\Mail\NotificacionGeneral;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ✅ Asignar rol por defecto (por nombre)
        $user->assignRole('Cliente');

        // O si prefieres por ID (ej. ID 2)
        // $user->assignRole(Role::findById(2));

        event(new Registered($user));

        // 📧 Enviar email de bienvenida
        try {
            Mail::to($user->email)->send(new NotificacionGeneral(
                asunto: '¡Bienvenido a Calendarix!',
                titulo: '¡Gracias por registrarte!',
                mensaje: 'Tu cuenta ha sido creada exitosamente. Ahora puedes explorar negocios, agendar citas y realizar pedidos.',
                detalles: [
                    'Nombre' => $user->name,
                    'Email' => $user->email,
                    'Fecha de registro' => now()->format('d/m/Y H:i'),
                ],
                accionTexto: 'Ir al Dashboard',
                accionUrl: url('/dashboard'),
                tipoIcono: 'success'
            ));
        } catch (\Exception $e) {
            \Log::error('Error al enviar email de bienvenida', ['error' => $e->getMessage()]);
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
