@extends('admin.layouts.app')

@section('title', 'Detalle de Usuario')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>{{ $user->name }}</h1>
        <p>Información del usuario</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="detail-grid">
    <!-- User Info Card -->
    <div class="card user-detail-card">
        <div class="user-detail-header">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="user-avatar-lg">
            <div class="user-detail-info">
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                @foreach($user->roles as $role)
                    <span class="badge badge-lg" style="background-color: {{ $role->color }}">
                        {{ ucfirst($role->name) }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="user-detail-body">
            <div class="detail-row">
                <span class="detail-label">Estado</span>
                <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Teléfono</span>
                <span>{{ $user->phone ?: 'No registrado' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Último acceso</span>
                <span>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Registrado</span>
                <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Permissions Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-key"></i> Permisos</h3>
        </div>
        <div class="card-body">
            <div class="permissions-grid">
                @foreach($user->getAllPermissions()->groupBy('group') as $group => $permissions)
                    <div class="permission-group">
                        <h4>{{ $group ?: 'General' }}</h4>
                        <ul>
                            @foreach($permissions as $permission)
                                <li>
                                    <i class="fas fa-check text-success"></i>
                                    {{ $permission->description }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Activity Log -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history"></i> Actividad Reciente</h3>
        <a href="{{ route('admin.logs.user', $user) }}" class="btn-link">Ver toda</a>
    </div>
    <div class="card-body">
        @forelse($activities as $activity)
            <div class="activity-item">
                <div class="activity-icon {{ $activity->action_color }}">
                    @switch($activity->action)
                        @case('create')
                            <i class="fas fa-plus"></i>
                            @break
                        @case('update')
                            <i class="fas fa-edit"></i>
                            @break
                        @case('delete')
                            <i class="fas fa-trash"></i>
                            @break
                        @case('login')
                            <i class="fas fa-sign-in-alt"></i>
                            @break
                        @case('logout')
                            <i class="fas fa-sign-out-alt"></i>
                            @break
                        @default
                            <i class="fas fa-circle"></i>
                    @endswitch
                </div>
                <div class="activity-content">
                    <p>{{ $activity->description }}</p>
                    <span class="activity-time">
                        {{ $activity->created_at->format('d/m/Y H:i') }} - {{ $activity->ip_address }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">No hay actividad registrada</p>
        @endforelse
    </div>
</div>
@endsection
