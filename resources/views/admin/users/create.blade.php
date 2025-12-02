@extends('admin.layouts.app')

@section('title', 'Nuevo Usuario')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Nuevo Usuario</h1>
        <p>Crear un nuevo usuario del sistema</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('admin.users.store') }}" method="POST" class="form">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nombre completo <span class="required">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone') }}">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Rol <span class="required">*</span></label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="">Seleccionar rol...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }} - {{ $role->description }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña <span class="required">*</span></label>
                <input type="password" name="password" id="password" 
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña <span class="required">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span>Usuario activo</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Crear Usuario
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
