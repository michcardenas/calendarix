<?php

namespace App\Services;

use App\Models\User;

class RoleRedirectService
{
    public function getRedirectRoute(User $user): string
    {
        if ($user->hasRole('Administrador')) {
            return route('dashboard');
        }

        if ($user->hasRole('Cliente')) {
            return route('client.dashboard-client');
        }

        // Redirigir a login o lanzar excepción si no tiene rol válido
        abort(403, 'No tienes permisos asignados.');
    }
}
