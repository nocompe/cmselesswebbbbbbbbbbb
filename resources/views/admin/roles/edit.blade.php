@extends('admin.layouts.app')

@section('title', isset($role) ? 'Editar Rol' : 'Nuevo Rol')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>{{ isset($role) ? 'Editar Rol' : 'Nuevo Rol' }}</h1>
        <p>{{ isset($role) ? $role->name : 'Crear un nuevo rol de usuario' }}</p>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" 
          method="POST" class="form">
        @csrf
        @if(isset($role))
            @method('PUT')
        @endif
        
        <div class="form-grid cols-3">
            <div class="form-group">
                <label for="name">Nombre del rol <span class="required">*</span></label>
                <input type="text" name="name" id="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $role->name ?? '') }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <input type="text" name="description" id="description" 
                       class="form-control @error('description') is-invalid @enderror" 
                       value="{{ old('description', $role->description ?? '') }}">
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="color">Color</label>
                <input type="color" name="color" id="color" 
                       class="form-control form-control-color" 
                       value="{{ old('color', $role->color ?? '#8b5cf6') }}">
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-key"></i> Permisos</h3>
            <p class="text-muted">Selecciona los permisos que tendrá este rol</p>
            
            <div class="permissions-grid">
                @foreach($permissions as $group => $groupPermissions)
                    <div class="permission-group-card">
                        <div class="permission-group-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="select-all-group" data-group="{{ $group }}">
                                <span>{{ $group ?: 'General' }}</span>
                            </label>
                        </div>
                        <div class="permission-group-body">
                            @foreach($groupPermissions as $permission)
                                <label class="checkbox-label">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                           class="permission-checkbox" data-group="{{ $group }}"
                                           {{ (isset($rolePermissions) && in_array($permission->name, $rolePermissions)) ? 'checked' : '' }}>
                                    <span>{{ $permission->description }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isset($role) ? 'Guardar Cambios' : 'Crear Rol' }}
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.querySelectorAll('.select-all-group').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const group = this.dataset.group;
        document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(cb => {
            cb.checked = this.checked;
        });
    });
});
</script>
@endpush
@endsection
