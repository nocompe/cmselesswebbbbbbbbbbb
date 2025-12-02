@extends('admin.layouts.app')

@section('title', 'Páginas')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Páginas</h1>
        <p>Gestiona las páginas y su contenido</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Página
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Slug</th>
                    <th>Secciones</th>
                    <th>Estado</th>
                    <th>Última actualización</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>
                            <strong>{{ $page->title }}</strong>
                        </td>
                        <td>
                            <code>/{{ $page->slug }}</code>
                        </td>
                        <td>
                            <span class="badge">{{ $page->sections->count() }} secciones</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $page->is_active ? 'active' : 'inactive' }}">
                                {{ $page->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $page->updated_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn-icon" title="Editar contenido">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!in_array($page->slug, ['home', 'about', 'contact', 'multimedia']))
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta página?')">
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
                            No hay páginas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pages->hasPages())
        <div class="card-footer">
            {{ $pages->links() }}
        </div>
    @endif
</div>
@endsection
