@extends('admin.layouts.app')

@section('title', 'Editar Usuario')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Editar Usuario</h1>
        <p>{{ $user->name }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nombre completo <span class="required">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Rol <span class="required">*</span></label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" 
                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }} - {{ $role->description }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" name="password" id="password" 
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Dejar en blanco para mantener la actual">
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <span>Usuario activo</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
