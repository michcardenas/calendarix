{{-- CSS específico del dashboard admin --}}
      
        <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">


<div class="container mt-4">
    <!-- Título solo -->
    <h2 class="mb-4">Lista de Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">
            <strong>¡Éxito!</strong> {{ session('success') }}
        </div>
    @endif

    <!-- Barra de búsqueda con botón -->
    <div class="search-bar-container">
        <div class="search-input-wrapper">
            <div class="search-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </div>
            <input type="text" placeholder="Buscar usuarios..." class="search-input">
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-new-user">
            Nuevo Usuario
        </a>
    </div>

    <!-- Wrapper para la tabla -->
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($users as $usr_user)
                <tr data-user-id="{{ $usr_user->id }}">
                    <td>{{ $usr_user->id }}</td>
                    <td>{{ $usr_user->name }}</td>
                    <td>{{ $usr_user->email }}</td>
                    <td>{{ $usr_user->roles->first()?->name ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $usr_user) }}" 
                           class="btn btn-sm btn-warning" 
                           id="usr_edit_{{ $usr_user->id }}"
                           title="Editar usuario">
                           Editar
                        </a>
                        <form action="{{ route('admin.users.destroy', $usr_user) }}" 
                              method="POST" 
                              class="delete-form"
                              style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-sm btn-danger" 
                                    id="usr_delete_{{ $usr_user->id }}"
                                    title="Eliminar usuario"
                                    data-confirm-message="¿Estás seguro de eliminar al usuario '{{ $usr_user->name }}'?">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
</div>

{{-- JavaScript Admin Dashboard --}}
<script src="{{ asset('js/admin-users.js') }}"></script>
