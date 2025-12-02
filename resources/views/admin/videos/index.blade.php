@extends('admin.layouts.app')

@section('title', 'Videos')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Videos</h1>
        <p>Gestiona los videos del centro multimedia</p>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Video
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-filters">
        <form action="{{ route('admin.videos.index') }}" method="GET" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Buscar video..." 
                       value="{{ request('search') }}" class="form-control">
            </div>
            <div class="filter-group">
                <select name="category" class="form-control">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
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
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Video</th>
                    <th>Categoría</th>
                    <th>Tipo</th>
                    <th>Vistas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($videos as $video)
                    <tr>
                        <td>
                            <div class="video-item">
                                <div class="video-thumb">
                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                                    <span class="video-duration">{{ $video->duration_formatted }}</span>
                                </div>
                                <div class="video-info">
                                    <h4>{{ Str::limit($video->title, 40) }}</h4>
                                    <small class="text-muted">{{ $video->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $video->category?->color ?? '#8b5cf6' }}">
                                {{ $video->category?->name ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">
                                <i class="fab fa-{{ $video->video_type }}"></i>
                                {{ ucfirst($video->video_type) }}
                            </span>
                        </td>
                        <td>{{ number_format($video->views_count) }}</td>
                        <td>
                            <span class="status-badge {{ $video->is_active ? 'active' : 'inactive' }}">
                                {{ $video->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                            @if($video->is_featured)
                                <span class="badge badge-warning"><i class="fas fa-star"></i></span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('multimedia.show', $video) }}" class="btn-icon" title="Ver" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="{{ route('admin.videos.edit', $video) }}" class="btn-icon" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.videos.toggle-status', $video) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-icon" title="{{ $video->is_active ? 'Desactivar' : 'Activar' }}">
                                        <i class="fas fa-{{ $video->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este video?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No hay videos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($videos->hasPages())
        <div class="card-footer">
            {{ $videos->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
