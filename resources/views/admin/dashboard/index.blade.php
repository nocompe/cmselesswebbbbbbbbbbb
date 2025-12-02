@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-content">
            <h1>¡Bienvenido, {{ auth()->user()->name }}!</h1>
            <p>Panel de administración de ELESS Group. Gestiona el contenido de tu sitio web.</p>
        </div>
        <div class="welcome-actions">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar Contenido
            </a>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Nuevo Video
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['users'] }}</h3>
                <p>Usuarios</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #be185d);">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['pages'] }}</h3>
                <p>Páginas</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-video"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['videos'] }}</h3>
                <p>Videos</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-folder"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['categories'] }}</h3>
                <p>Categorías</p>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="dashboard-grid">
        <!-- Recent Activity -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-history"></i> Actividad Reciente</h2>
                <a href="{{ route('admin.logs.index') }}" class="btn-link">Ver todo</a>
            </div>
            <div class="card-body">
                @forelse($recentActivities as $activity)
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
                                @default
                                    <i class="fas fa-circle"></i>
                            @endswitch
                        </div>
                        <div class="activity-content">
                            <p>
                                <strong>{{ $activity->user?->name ?? 'Sistema' }}</strong>
                                {{ $activity->description }}
                            </p>
                            <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No hay actividad reciente</p>
                @endforelse
            </div>
        </div>

        <!-- Popular Videos -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-chart-line"></i> Videos Más Vistos</h2>
                <a href="{{ route('admin.videos.index') }}" class="btn-link">Ver todo</a>
            </div>
            <div class="card-body">
                @forelse($popularVideos as $video)
                    <div class="video-item">
                        <div class="video-thumb">
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                            <span class="video-duration">{{ $video->duration_formatted }}</span>
                        </div>
                        <div class="video-info">
                            <h4>{{ Str::limit($video->title, 40) }}</h4>
                            <p>
                                <span class="badge">{{ $video->category?->name }}</span>
                                <span class="views"><i class="fas fa-eye"></i> {{ number_format($video->views_count) }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No hay videos aún</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Acciones Rápidas</h3>
        <div class="actions-grid">
            <a href="{{ route('admin.pages.index') }}" class="quick-action-card">
                <i class="fas fa-edit"></i>
                <span>Editar Páginas</span>
            </a>
            <a href="{{ route('admin.videos.create') }}" class="quick-action-card">
                <i class="fas fa-video"></i>
                <span>Subir Video</span>
            </a>
            <a href="{{ route('admin.media.index') }}" class="quick-action-card">
                <i class="fas fa-images"></i>
                <span>Galería</span>
            </a>
            <a href="{{ route('admin.users.create') }}" class="quick-action-card">
                <i class="fas fa-user-plus"></i>
                <span>Nuevo Usuario</span>
            </a>
            <a href="{{ route('admin.faqs.create') }}" class="quick-action-card">
                <i class="fas fa-question"></i>
                <span>Nueva FAQ</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="quick-action-card">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a>
        </div>
    </div>
</div>
@endsection
