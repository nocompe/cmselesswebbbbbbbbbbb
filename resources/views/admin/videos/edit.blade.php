@extends('admin.layouts.app')

@section('title', isset($video) ? 'Editar Video' : 'Nuevo Video')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>{{ isset($video) ? 'Editar Video' : 'Nuevo Video' }}</h1>
        <p>{{ isset($video) ? $video->title : 'Agregar un nuevo video al centro multimedia' }}</p>
    </div>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <form action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}" 
          method="POST" enctype="multipart/form-data" class="form">
        @csrf
        @if(isset($video))
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="title">Título <span class="required">*</span></label>
                <input type="text" name="title" id="title" 
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $video->title ?? '') }}" required>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Categoría <span class="required">*</span></label>
                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">Seleccionar categoría...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('category_id', $video->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea name="description" id="description" rows="4" 
                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $video->description ?? '') }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-section">
            <h3><i class="fas fa-video"></i> Fuente del Video</h3>
            
            <div class="form-group">
                <label>Tipo de Video</label>
                <div class="video-type-options">
                    <label class="radio-card">
                        <input type="radio" name="video_type" value="youtube" 
                               {{ old('video_type', $video->video_type ?? 'youtube') == 'youtube' ? 'checked' : '' }}>
                        <div class="radio-card-content">
                            <i class="fab fa-youtube"></i>
                            <span>YouTube</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="video_type" value="vimeo"
                               {{ old('video_type', $video->video_type ?? '') == 'vimeo' ? 'checked' : '' }}>
                        <div class="radio-card-content">
                            <i class="fab fa-vimeo-v"></i>
                            <span>Vimeo</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="video_type" value="local"
                               {{ old('video_type', $video->video_type ?? '') == 'local' ? 'checked' : '' }}>
                        <div class="radio-card-content">
                            <i class="fas fa-upload"></i>
                            <span>Subir Video</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group" id="videoUrlGroup">
                <label for="video_url">URL del Video</label>
                <input type="url" name="video_url" id="video_url" 
                       class="form-control @error('video_url') is-invalid @enderror"
                       value="{{ old('video_url', $video->video_url ?? '') }}"
                       placeholder="https://www.youtube.com/watch?v=...">
                <small class="text-muted">Pega la URL completa de YouTube o Vimeo</small>
                @error('video_url')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="videoFileGroup" style="display: none;">
                <label for="video_file">Archivo de Video</label>
                <input type="file" name="video_file" id="video_file" 
                       class="form-control @error('video_file') is-invalid @enderror"
                       accept="video/mp4,video/webm,video/ogg">
                <small class="text-muted">Formatos: MP4, WebM, OGG. Máximo: 100MB</small>
                @error('video_file')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="thumbnail">Miniatura</label>
                <input type="file" name="thumbnail" id="thumbnail" 
                       class="form-control @error('thumbnail') is-invalid @enderror"
                       accept="image/*">
                <small class="text-muted">Dejar vacío para usar la miniatura de YouTube</small>
                @if(isset($video) && $video->thumbnail)
                    <div class="current-thumb">
                        <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Miniatura actual">
                    </div>
                @endif
                @error('thumbnail')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="duration">Duración (segundos)</label>
                <input type="number" name="duration" id="duration" 
                       class="form-control @error('duration') is-invalid @enderror"
                       value="{{ old('duration', $video->duration ?? '') }}"
                       min="0" placeholder="Ej: 300 para 5 minutos">
                @error('duration')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="order">Orden</label>
            <input type="number" name="order" id="order" 
                   class="form-control" style="max-width: 150px;"
                   value="{{ old('order', $video->order ?? 0) }}" min="0">
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" 
                       {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}>
                <span>Video activo (visible en el sitio)</span>
            </label>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_featured" value="1" 
                       {{ old('is_featured', $video->is_featured ?? false) ? 'checked' : '' }}>
                <span>Video destacado</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isset($video) ? 'Guardar Cambios' : 'Crear Video' }}
            </button>
            <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@push('styles')
<style>
    .video-type-options {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .radio-card {
        flex: 1;
        cursor: pointer;
    }

    .radio-card input {
        display: none;
    }

    .radio-card-content {
        padding: 20px;
        background: var(--bg-card);
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        text-align: center;
        transition: all 0.3s;
    }

    .radio-card input:checked + .radio-card-content {
        border-color: var(--primary);
        background: rgba(139, 92, 246, 0.1);
    }

    .radio-card-content i {
        font-size: 28px;
        margin-bottom: 10px;
        display: block;
        color: var(--primary);
    }

    .radio-card-content span {
        font-weight: 500;
    }

    .current-thumb {
        margin-top: 10px;
    }

    .current-thumb img {
        max-width: 200px;
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('input[name="video_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const urlGroup = document.getElementById('videoUrlGroup');
            const fileGroup = document.getElementById('videoFileGroup');
            
            if (this.value === 'local') {
                urlGroup.style.display = 'none';
                fileGroup.style.display = 'block';
            } else {
                urlGroup.style.display = 'block';
                fileGroup.style.display = 'none';
            }
        });
    });

    // Trigger on page load
    document.querySelector('input[name="video_type"]:checked')?.dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
