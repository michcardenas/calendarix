<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;




class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard-admin'); 
    }
   public function dashboard()
{
    $user = Auth::user();
    $user->load('roles'); // fuerza la carga de roles

    // Debug opcional (puedes comentar o eliminar luego)
    // dd($user->roles->pluck('name'));

    if ($user->hasRole('Administrador', 'web')) {
        return view('admin.dashboard-admin', [
            'user' => $user,
        ]);
    }

    if ($user->hasRole('Cliente', 'web')) {
        return view('client.dashboard-client');
    }

    abort(403, 'No tienes permisos suficientes.');
}

}
