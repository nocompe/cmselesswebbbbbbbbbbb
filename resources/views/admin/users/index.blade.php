@extends('admin.layouts.app')

@section('title', 'Usuarios')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Usuarios</h1>
        <p>Gestiona los usuarios del sistema</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <!-- Filters -->
    <div class="card-filters">
        <form action="{{ route('admin.users.index') }}" method="GET" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Buscar usuario..." 
                       value="{{ request('search') }}" class="form-control">
            </div>
            <div class="filter-group">
                <select name="role" class="form-control">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="status" class="form-control">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="user-avatar-sm">
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge" style="background-color: {{ $role->color }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn-icon" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn-icon" title="{{ $user->is_active ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No se encontraron usuarios
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="card-footer">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
