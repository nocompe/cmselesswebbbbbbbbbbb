@extends('admin.layouts.app')

@section('title', 'Mi Perfil')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Mi Perfil</h1>
        <p>Administra tu información personal</p>
    </div>
</div>
@endsection

@section('content')
<div class="profile-grid">
    <!-- Profile Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Información Personal</h3>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="form">
            @csrf
            @method('PUT')

            <div class="profile-avatar-section">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar" id="avatarPreview">
                <div class="avatar-upload">
                    <input type="file" name="avatar" id="avatar" accept="image/*" 
                           onchange="handleImageUpload(this, 'avatarPreview')">
                    <label for="avatar" class="btn btn-sm btn-secondary">
                        <i class="fas fa-camera"></i> Cambiar foto
                    </label>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                           value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <input type="text" class="form-control" 
                           value="{{ $user->roles->first()?->name ?? 'Usuario' }}" disabled>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-lock"></i> Cambiar Contraseña</h3>
        </div>
        <form action="{{ route('admin.profile.password') }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Contraseña actual</label>
                <input type="password" name="current_password" id="current_password" 
                       class="form-control @error('current_password') is-invalid @enderror" required>
                @error('current_password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" name="password" id="password" 
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="form-control" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history"></i> Mi Actividad Reciente</h3>
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
            <p class="text-muted text-center">No hay actividad reciente</p>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .profile-avatar-section {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary);
    }

    .avatar-upload input {
        display: none;
    }

    @media (max-width: 992px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@endsection
