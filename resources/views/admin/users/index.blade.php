@extends('layouts.admin')
@section('title', 'Usuarios')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-suscripciones.css') }}">
@endpush
@section('admin-content')

<div class="container mt-4">
    <h2 class="mb-4">Lista de Usuarios</h2>

    @if(session('success'))
        <div class="admin-alert admin-alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Barra de busqueda -->
    <div class="search-bar-container">
        <form method="GET" action="{{ route('admin.users.index') }}" class="sub-search" style="flex:1;">
            <div class="sub-search-input">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre o email...">
            </div>
            <button type="submit" class="sub-search-btn">Buscar</button>
            @if(!empty($search))
                <a href="{{ route('admin.users.index') }}" class="sub-btn" style="width:auto;padding:0 1rem;font-size:0.85rem;height:38px;" title="Limpiar">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
        <a href="{{ route('admin.users.create') }}" class="btn btn-new-user">
            Nuevo Usuario
        </a>
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Plan</th>
                    <th>Estado Sub.</th>
                    <th>Vence</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($users as $usr_user)
                @php
                    $sub = $usr_user->subscription;
                    $plan = $sub?->plan;
                    $statusClass = match($sub?->status) {
                        'active' => 'confirmed',
                        'trial' => 'pending',
                        'cancelled', 'payment_failed', 'expired' => 'cancelled',
                        default => '',
                    };
                    $statusLabel = match($sub?->status) {
                        'active' => 'Activa',
                        'trial' => 'Trial',
                        'cancelled' => 'Cancelada',
                        'payment_failed' => 'Fallida',
                        'expired' => 'Expirada',
                        default => '-',
                    };
                @endphp
                <tr data-user-id="{{ $usr_user->id }}">
                    <td>{{ $usr_user->id }}</td>
                    <td>{{ $usr_user->name }}</td>
                    <td>{{ $usr_user->email }}</td>
                    <td>{{ $usr_user->roles->first()?->name ?? '—' }}</td>
                    <td>{{ $plan?->name ?? '-' }}</td>
                    <td>
                        @if($sub)
                            <span class="admin-status {{ $statusClass }}">{{ $statusLabel }}</span>
                        @else
                            <span style="color:var(--admin-text-light);font-size:0.8rem;">Sin plan</span>
                        @endif
                    </td>
                    <td>
                        @if($sub && $sub->ends_at)
                            {{ \Carbon\Carbon::parse($sub->ends_at)->format('d/m/Y') }}
                            @if(\Carbon\Carbon::parse($sub->ends_at)->isPast())
                                <br><small style="color:var(--admin-danger);font-weight:600;">Vencida</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="sub-actions">
                            <a href="{{ route('admin.users.edit', $usr_user) }}"
                               class="sub-btn sub-btn-view"
                               title="Editar usuario">
                               <i class="fas fa-edit"></i>
                            </a>
                            @if($sub)
                                <a href="{{ route('admin.suscripciones.show', $sub) }}"
                                   class="sub-btn"
                                   title="Ver suscripcion"
                                   style="color:var(--admin-primary);">
                                   <i class="fas fa-credit-card"></i>
                                </a>
                            @endif
                            <form action="{{ route('admin.users.destroy', $usr_user) }}"
                                  method="POST"
                                  class="delete-form"
                                  style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="sub-btn"
                                        style="color:var(--admin-danger);"
                                        title="Eliminar usuario"
                                        data-confirm-message="Estas seguro de eliminar al usuario '{{ $usr_user->name }}'?">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--admin-text-light);">
                        No se encontraron usuarios.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginacion -->
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-users.js') }}"></script>
@endpush
