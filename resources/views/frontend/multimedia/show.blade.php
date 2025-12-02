@extends('layouts.frontend')

@section('title', $video->title)
@section('meta_description', $video->description)

@push('styles')
<style>
    .video-detail-section {
        margin-top: 80px;
        padding: 60px 5%;
    }

    .video-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }

    .video-main {
        width: 100%;
    }

    .video-player {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        background: #000;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(139, 92, 246, 0.3);
    }

    .video-player iframe,
    .video-player video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .video-info {
        padding: 30px 0;
    }

    .video-category-link {
        display: inline-block;
        padding: 6px 14px;
        background: rgba(139, 92, 246, 0.2);
        border-radius: 20px;
        color: #a78bfa;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .video-category-link:hover {
        background: rgba(139, 92, 246, 0.4);
    }

    .video-info h1 {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 15px;
        line-height: 1.3;
    }

    .video-stats {
        display: flex;
        gap: 25px;
        padding: 15px 0;
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        margin-bottom: 20px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
    }

    .video-stats span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .video-description {
        font-size: 16px;
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.8);
    }

    .video-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 25px;
    }

    .video-tag {
        padding: 6px 14px;
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 6px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 13px;
    }

    /* Sidebar - Related Videos */
    .video-sidebar {
        position: sticky;
        top: 100px;
    }

    .sidebar-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
    }

    .related-videos {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .related-video {
        display: flex;
        gap: 15px;
        text-decoration: none;
        padding: 10px;
        border-radius: 12px;
        transition: all 0.3s;
    }

    .related-video:hover {
        background: rgba(139, 92, 246, 0.1);
    }

    .related-video-thumb {
        position: relative;
        width: 140px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .related-video-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-video-thumb .duration {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.8);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 11px;
        color: #fff;
    }

    .related-video-info h4 {
        font-size: 14px;
        font-weight: 500;
        color: #fff;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .related-video-info p {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.5);
    }

    /* Back Button */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 30px;
        transition: color 0.3s;
    }

    .back-btn:hover {
        color: #8b5cf6;
    }

    @media (max-width: 992px) {
        .video-container {
            grid-template-columns: 1fr;
        }

        .video-sidebar {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .video-info h1 {
            font-size: 24px;
        }

        .video-stats {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<section class="video-detail-section">
    <div class="video-container">
        <!-- Main Content -->
        <div class="video-main">
            <a href="{{ route('multimedia.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Volver al Centro Multimedia
            </a>

            <!-- Video Player -->
            <div class="video-player">
                @if($video->video_type === 'youtube')
                    <iframe src="{{ $video->embed_url }}?autoplay=0&rel=0" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                @elseif($video->video_type === 'vimeo')
                    <iframe src="{{ $video->embed_url }}" 
                            frameborder="0" 
                            allow="autoplay; fullscreen; picture-in-picture" 
                            allowfullscreen></iframe>
                @else
                    <video controls>
                        <source src="{{ $video->embed_url }}" type="video/mp4">
                        Tu navegador no soporta el tag de video.
                    </video>
                @endif
            </div>

            <!-- Video Info -->
            <div class="video-info">
                @if($video->category)
                    <a href="{{ route('multimedia.category', $video->category) }}" class="video-category-link">
                        <i class="{{ $video->category->icon ?? 'fas fa-folder' }}"></i>
                        {{ $video->category->name }}
                    </a>
                @endif

                <h1>{{ $video->title }}</h1>

                <div class="video-stats">
                    <span><i class="fas fa-eye"></i> {{ number_format($video->views_count) }} vistas</span>
                    <span><i class="fas fa-clock"></i> {{ $video->duration_formatted }}</span>
                    <span><i class="fas fa-calendar"></i> {{ $video->created_at->format('d M, Y') }}</span>
                </div>

                <div class="video-description">
                    {!! nl2br(e($video->description)) !!}
                </div>

                @if($video->tags && count($video->tags) > 0)
                    <div class="video-tags">
                        @foreach($video->tags as $tag)
                            <span class="video-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar - Related Videos -->
        <aside class="video-sidebar">
            <h3 class="sidebar-title">Videos Relacionados</h3>
            <div class="related-videos">
                @forelse($relatedVideos as $related)
                    <a href="{{ route('multimedia.show', $related) }}" class="related-video">
                        <div class="related-video-thumb">
                            <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}">
                            <span class="duration">{{ $related->duration_formatted }}</span>
                        </div>
                        <div class="related-video-info">
                            <h4>{{ Str::limit($related->title, 50) }}</h4>
                            <p><i class="fas fa-eye"></i> {{ number_format($related->views_count) }} vistas</p>
                        </div>
                    </a>
                @empty
                    <p class="text-muted">No hay videos relacionados</p>
                @endforelse
            </div>
        </aside>
    </div>
</section>
@endsection
