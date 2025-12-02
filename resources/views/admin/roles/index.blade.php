@extends('admin.layouts.app')

@section('title', 'Roles')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Roles y Permisos</h1>
        <p>Gestiona los roles del sistema</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Rol
    </a>
</div>
@endsection

@section('content')
<div class="roles-grid">
    @foreach($roles as $role)
        <div class="role-card">
            <div class="role-header" style="border-color: {{ $role->color }}">
                <div class="role-icon" style="background-color: {{ $role->color }}">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="role-info">
                    <h3>{{ ucfirst($role->name) }}</h3>
                    <p>{{ $role->description ?? 'Sin descripción' }}</p>
                </div>
            </div>
            
            <div class="role-stats">
                <div class="stat">
                    <span class="stat-value">{{ $role->users_count }}</span>
                    <span class="stat-label">Usuarios</span>
                </div>
                <div class="stat">
                    <span class="stat-value">{{ $role->permissions_count }}</span>
                    <span class="stat-label">Permisos</span>
                </div>
            </div>

            <div class="role-actions">
                @if(!in_array($role->name, ['super-admin']))
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endif
                @if(!in_array($role->name, ['super-admin', 'admin', 'editor', 'viewer']))
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline"
                          onsubmit="return confirm('¿Estás seguro de eliminar este rol?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
