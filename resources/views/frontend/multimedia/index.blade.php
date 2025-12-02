@extends('layouts.frontend')

@section('title', 'Centro Multimedia')
@section('meta_description', 'Videos y tutoriales de ELESS Group')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/videos.css') }}">
<style>
    .multimedia-hero {
        position: relative;
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: 80px;
        padding: 60px 20px;
    }

    .multimedia-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
    }

    .multimedia-content {
        position: relative;
        z-index: 10;
        text-align: center;
        max-width: 900px;
    }

    .multimedia-content h1 {
        font-size: 56px;
        font-weight: 200;
        margin-bottom: 20px;
    }

    .multimedia-content h1 .highlight {
        font-weight: 700;
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .multimedia-content p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.7);
        max-width: 600px;
        margin: 0 auto 40px;
    }

    .multimedia-stats {
        display: flex;
        justify-content: center;
        gap: 60px;
        margin-bottom: 40px;
    }

    .multimedia-stat {
        text-align: center;
    }

    .multimedia-stat-number {
        font-size: 48px;
        font-weight: 700;
        color: #8b5cf6;
    }

    .multimedia-stat-label {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Categories Filter */
    .categories-section {
        padding: 0 5%;
        margin-bottom: 40px;
    }

    .categories-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    .category-btn {
        padding: 12px 24px;
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 30px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-btn:hover,
    .category-btn.active {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        border-color: #8b5cf6;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(139, 92, 246, 0.3);
    }

    .category-btn .count {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
    }

    /* Videos Grid */
    .videos-section {
        padding: 40px 5% 80px;
    }

    .videos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .video-card {
        background: rgba(20, 20, 20, 0.8);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s;
        cursor: pointer;
    }

    .video-card:hover {
        transform: translateY(-10px);
        border-color: #8b5cf6;
        box-shadow: 0 20px 40px rgba(139, 92, 246, 0.3);
    }

    .video-card-image {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
    }

    .video-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .video-card:hover .video-card-image img {
        transform: scale(1.1);
    }

    .video-category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 6px 12px;
        background: rgba(139, 92, 246, 0.9);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .play-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(139, 92, 246, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .video-card:hover .play-overlay {
        background: #8b5cf6;
        transform: translate(-50%, -50%) scale(1.15);
    }

    .play-overlay i {
        font-size: 24px;
        margin-left: 4px;
    }

    .video-duration {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.8);
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
    }

    .video-card-content {
        padding: 25px;
    }

    .video-card-content h3 {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .video-card-content p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .video-meta {
        display: flex;
        gap: 15px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.5);
    }

    .video-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 60px 20px;
        color: rgba(255, 255, 255, 0.6);
    }

    .no-results i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #8b5cf6;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 50px;
    }

    .pagination {
        display: flex;
        gap: 10px;
    }

    .pagination a,
    .pagination span {
        padding: 10px 18px;
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 8px;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: rgba(139, 92, 246, 0.3);
    }

    .pagination .active span {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        border-color: #8b5cf6;
    }

    @media (max-width: 768px) {
        .multimedia-content h1 {
            font-size: 36px;
        }

        .multimedia-stats {
            flex-direction: column;
            gap: 30px;
        }

        .videos-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="multimedia-hero">
    <div class="particles" id="particles"></div>
    <div class="wave"></div>
    <div class="glow-effect"></div>

    <div class="multimedia-content">
        <h1>Centro <span class="highlight">Multimedia</span></h1>
        <p>Explora nuestra biblioteca de videos, tutoriales y contenido educativo sobre nuestros sistemas y productos.</p>

        <div class="multimedia-stats">
            <div class="multimedia-stat">
                <div class="multimedia-stat-number">{{ $stats['total'] }}</div>
                <div class="multimedia-stat-label">Videos Disponibles</div>
            </div>
            <div class="multimedia-stat">
                <div class="multimedia-stat-number">{{ $stats['categories'] }}</div>
                <div class="multimedia-stat-label">Categorías</div>
            </div>
        </div>

        <!-- Search -->
        <form action="{{ route('multimedia.index') }}" method="GET" class="search-form">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Buscar videos..." value="{{ request('q') }}">
                <button type="submit" class="search-btn">Buscar</button>
            </div>
        </form>
    </div>
</section>

<!-- Categories Filter -->
<section class="categories-section">
    <div class="categories-grid">
        <a href="{{ route('multimedia.index') }}" class="category-btn {{ !request('category') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            Todos
        </a>
        @foreach($categories as $category)
            <a href="{{ route('multimedia.index', ['category' => $category->id]) }}" 
               class="category-btn {{ request('category') == $category->id ? 'active' : '' }}">
                <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                {{ $category->name }}
                <span class="count">{{ $category->videos_count }}</span>
            </a>
        @endforeach
    </div>
</section>

<!-- Videos Grid -->
<section class="videos-section">
    @if($videos->count() > 0)
        <div class="videos-grid">
            @foreach($videos as $video)
                <a href="{{ route('multimedia.show', $video) }}" class="video-card">
                    <div class="video-card-image">
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                        <span class="video-category-badge">{{ $video->category?->name }}</span>
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="video-duration">{{ $video->duration_formatted }}</span>
                    </div>
                    <div class="video-card-content">
                        <h3>{{ $video->title }}</h3>
                        <p>{{ Str::limit($video->description, 100) }}</p>
                        <div class="video-meta">
                            <span><i class="fas fa-eye"></i> {{ number_format($video->views_count) }} vistas</span>
                            <span><i class="fas fa-calendar"></i> {{ $video->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($videos->hasPages())
            <div class="pagination-wrapper">
                {{ $videos->withQueryString()->links() }}
            </div>
        @endif
    @else
        <div class="no-results">
            <i class="fas fa-video-slash"></i>
            <h3>No se encontraron videos</h3>
            <p>Intenta con otros términos de búsqueda o categoría.</p>
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
    // Crear partículas
    const particlesContainer = document.getElementById('particles');
    if (particlesContainer) {
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 8 + 's';
            particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
            particlesContainer.appendChild(particle);
        }
    }
</script>
@endpush
