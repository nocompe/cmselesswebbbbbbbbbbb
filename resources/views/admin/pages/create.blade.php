@extends('admin.layouts.app')

@section('title', 'Nueva Página')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Nueva Página</h1>
        <p>Crear una nueva página para el sitio</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('admin.pages.store') }}" method="POST" class="form">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label for="title">Título <span class="required">*</span></label>
                <input type="text" name="title" id="title" 
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" required>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug (URL)</label>
                <input type="text" name="slug" id="slug" 
                       class="form-control @error('slug') is-invalid @enderror"
                       value="{{ old('slug') }}" placeholder="Dejar vacío para generar automáticamente">
                @error('slug')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Descripción (SEO)</label>
            <textarea name="meta_description" id="meta_description" rows="2" 
                      class="form-control @error('meta_description') is-invalid @enderror"
                      placeholder="Descripción para motores de búsqueda (máx. 160 caracteres)">{{ old('meta_description') }}</textarea>
            @error('meta_description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_keywords">Palabras clave (SEO)</label>
            <input type="text" name="meta_keywords" id="meta_keywords" 
                   class="form-control @error('meta_keywords') is-invalid @enderror"
                   value="{{ old('meta_keywords') }}" placeholder="palabra1, palabra2, palabra3">
            @error('meta_keywords')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span>Página activa</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Crear Página
            </button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
