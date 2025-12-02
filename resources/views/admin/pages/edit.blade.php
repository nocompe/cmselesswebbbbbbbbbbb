

@extends('admin.layouts.app')
@php use Illuminate\Support\Str; @endphp
@section('title', 'Editar Página: ' . $page->title)

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>{{ $page->title }}</h1>
        <p>Edita el contenido de las secciones de esta página</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="page-editor">
    <!-- Page Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-cog"></i> Configuración de la Página</h3>
        </div>
        <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" name="title" id="title" class="form-control"
                           value="{{ old('title', $page->title) }}" required>
                </div>
                <div class="form-group">
                    <label for="slug">Slug (URL)</label>
                    <input type="text" name="slug" id="slug" class="form-control"
                           value="{{ old('slug', $page->slug) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="meta_description">Meta Descripción (SEO)</label>
                <textarea name="meta_description" id="meta_description" rows="2"
                          class="form-control">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                    <span>Página activa</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Configuración
                </button>
            </div>
        </form>
    </div>

    <!-- Sections -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-puzzle-piece"></i> Secciones de Contenido</h3>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="addSectionModal">
                <i class="fas fa-plus"></i> Agregar Sección
            </button>
        </div>
        <div class="card-body">
            @if($page->sections->count() > 0)
                <div class="sections-list" id="sectionsList">
                    @foreach($page->sections as $section)
                        <div class="section-item" data-id="{{ $section->id }}">
                            <div class="section-header">
                                <div class="section-drag">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>
                                <div class="section-info">
                                    <h4>{{ $section->name }}</h4>
                                    <span class="section-identifier">
                                        <code>{{ $section->identifier }}</code>
                                        <span class="badge badge-sm">{{ $section->type }}</span>
                                    </span>
                                </div>
                                <div class="section-actions">
                                    <button class="btn-icon" onclick="editSection({{ $section->id }})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.pages.sections.destroy', [$page, $section]) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar esta sección?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Section Edit Form (collapsible) -->
                            <div class="section-edit" id="sectionEdit{{ $section->id }}" style="display: none;">
                                <form action="{{ route('admin.pages.sections.update', [$page, $section]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label>Nombre de la sección</label>
                                        <input type="text" name="name" class="form-control" value="{{ $section->name }}">
                                    </div>

                                   @if($section->type === 'text')
        <div class="form-group">
            <label>Contenido</label>
            <input type="text" name="content" class="form-control" value="{{ $section->content }}">
        </div>

    @elseif($section->type === 'html')
        <div class="form-group">
            <label>Contenido HTML</label>
            <textarea name="content" rows="6" class="form-control code-editor">{{ $section->content }}</textarea>
        </div>

    @elseif($section->type === 'image')
        <div class="form-group">
            <label>URL de la imagen</label>
            <input
                type="text"
                name="content"
                class="form-control"
                value="{{ $section->content }}"
                placeholder="URL completa (https://...) o se llenará al subir una imagen">
        </div>

        <div class="form-group">
            <label>Subir imagen (opcional)</label>
            <input type="file" name="image_file" class="form-control" accept="image/*">
            <small class="text-muted">
                Si subes una imagen, se guardará en el servidor y se usará esa ruta local.
            </small>
        </div>

        @if($section->content)
            @php
                $isExternal = Str::startsWith($section->content, ['http://', 'https://']);
                $imgSrc = $isExternal
                    ? $section->content
                    : asset('storage/' . $section->content);
            @endphp
            <div class="current-image">
                <img src="{{ $imgSrc }}" alt="Imagen actual">
            </div>
        @endif

    @elseif($section->type === 'video')
        <div class="form-group">
            <label>URL del video</label>
            <input
                type="url"
                name="content"
                class="form-control"
                value="{{ $section->content }}"
                placeholder="URL de YouTube, Vimeo o ruta de archivo">
        </div>

        <div class="form-group">
            <label>Subir archivo de video (opcional)</label>
            <input type="file" name="video_file" class="form-control" accept="video/*">
            <small class="text-muted">
                Si subes un video, se guardará en el servidor y se usará esa ruta local.
            </small>
        </div>

    @else
        <div class="form-group">
            <label>Contenido</label>
            <textarea name="content" rows="4" class="form-control">
                {{ is_array($section->content) ? json_encode($section->content, JSON_PRETTY_PRINT) : $section->content }}
            </textarea>
        </div>
    @endif

                                    <div class="form-group">
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="is_active" value="1"
                                                   {{ $section->is_active ? 'checked' : '' }}>
                                            <span>Sección activa</span>
                                        </label>
                                    </div>

                                    <div class="section-edit-actions">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-save"></i> Guardar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="closeSection({{ $section->id }})">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-puzzle-piece"></i>
                    <p>Esta página no tiene secciones. Agrega una para comenzar a editar el contenido.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal" id="addSectionModal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar Nueva Sección</h3>
            <button class="modal-close" onclick="closeModal('addSectionModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.pages.sections.store', $page) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="newIdentifier">Identificador <span class="required">*</span></label>
                    <input type="text" name="identifier" id="newIdentifier" class="form-control"
                           placeholder="ej: hero_title" required pattern="[a-z_]+"
                           title="Solo letras minúsculas y guiones bajos">
                    <small class="text-muted">Solo letras minúsculas y guiones bajos. Este identificador se usa en el código.</small>
                </div>

                <div class="form-group">
                    <label for="newName">Nombre <span class="required">*</span></label>
                    <input type="text" name="name" id="newName" class="form-control"
                           placeholder="ej: Título del Hero" required>
                </div>

                <div class="form-group">
                    <label for="newType">Tipo de contenido <span class="required">*</span></label>
                    <select name="type" id="newType" class="form-control" required>
                        <option value="text">Texto simple</option>
                        <option value="html">HTML</option>
                        <option value="image">Imagen</option>
                        <option value="video">Video</option>
                        <option value="json">JSON (datos estructurados)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="newContent">Contenido inicial</label>
                    <textarea name="content" id="newContent" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addSectionModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar Sección
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .sections-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .section-item {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 20px;
    }

    .section-drag {
        cursor: grab;
        color: var(--text-muted);
    }

    .section-info {
        flex: 1;
    }

    .section-info h4 {
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .section-identifier {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
    }

    .section-identifier code {
        background: rgba(139, 92, 246, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
    }

    .section-actions {
        display: flex;
        gap: 8px;
    }

    .section-edit {
        padding: 20px;
        background: rgba(139, 92, 246, 0.03);
        border-top: 1px solid var(--border-color);
    }

    .section-edit-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .current-image {
        margin-top: 10px;
    }

    .current-image img {
        max-width: 200px;
        border-radius: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        color: var(--primary);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
        position: relative;
        background: var(--bg-darker);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
    }

    .modal-header h3 {
        font-size: 18px;
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 18px;
        cursor: pointer;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .mb-4 {
        margin-bottom: 25px;
    }
</style>
@endpush

@push('scripts')
<script>
    function editSection(id) {
        document.querySelectorAll('.section-edit').forEach(el => el.style.display = 'none');
        document.getElementById('sectionEdit' + id).style.display = 'block';
    }

    function closeSection(id) {
        document.getElementById('sectionEdit' + id).style.display = 'none';
    }

    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    document.querySelectorAll('[data-toggle="modal"]').forEach(btn => {
        btn.addEventListener('click', () => {
            openModal(btn.dataset.target);
        });
    });

    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', () => {
            backdrop.closest('.modal').classList.remove('active');
        });
    });
</script>
@endpush
@endsection
