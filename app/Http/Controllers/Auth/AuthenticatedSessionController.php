<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar la vista de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar la autenticación.
     */
    public function store(Request $request)

    {
         \Log::info('== INTENTANDO LOGIN ==', $request->only('email'));
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        \Log::info('== CREDENCIALES VALIDAS ==', $credentials);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->hasRole('Administrador')) {
                return redirect()->route('dashboard');
            }

            if ($user->hasRole('Cliente')) {
                return redirect()->route('dashboard');
            }

            Auth::logout();
            return abort(403, 'No tienes permisos asignados.');
        }
        \Log::warning('== CREDENCIALES INVALIDAS ==', $credentials);

        throw ValidationException::withMessages([
            'email' => __('Estas credenciales no coinciden con nuestros registros.'),
        ]);
        
    }


    /**
     * Cerrar sesión.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
