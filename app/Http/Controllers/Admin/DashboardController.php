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
        $user->load('roles');

        if ($user->hasRole('Administrador', 'web')) {
            return view('admin.dashboard-admin', [
                'user' => $user,
            ]);
        }

        if ($user->hasRole('Cliente', 'web')) {
            $misEmpresas = \App\Models\Negocio::where('user_id', $user->id)->get();

            return view('client.dashboard-client', compact('misEmpresas'));
        }

        abort(403, 'No tienes permisos suficientes.');
    }


}
