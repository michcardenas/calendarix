<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Mostrar listado de usuarios
    public function index(Request $request)
    {
        $query = User::with(['roles', 'subscription.plan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users') + ['activeMenu' => 'users', 'search' => $request->search ?? '']);
    }

    // Mostrar formulario para crear un nuevo usuario
    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.create', compact('roles') + ['activeMenu' => 'users.create']);
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole(Role::findById($data['role']));

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente');
    }

    // Mostrar formulario para editar usuario
    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.edit', compact('user', 'roles') + ['activeMenu' => 'users']);
    }

    // Actualizar usuario existente
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|exists:roles,id',
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $user->syncRoles([Role::findById($data['role'])]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente');
    }

    // Eliminar usuario
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente');
    }
}
