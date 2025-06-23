<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Mostrar listado de usuarios
    public function index()
    {
        $users = User::with('roles')->paginate(10); // paginación
        return view('admin.users.index', compact('users'));
    }

    // Mostrar formulario para crear un nuevo usuario
    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all(); // Asegúrate de importar Role
        return view('admin.users.create', compact('roles'));
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
        return view('admin.users.edit', compact('user', 'roles'));
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
