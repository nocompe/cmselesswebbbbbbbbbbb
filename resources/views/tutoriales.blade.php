@extends('layouts.app')

@section('title', 'ELESS - Tutoriales')

@push('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        .hero {
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 80px;
        }

        .filter-section {
            padding: 3rem 5%;
            position: relative;
            z-index: 10;
        }

        .filter-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 2rem;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 1rem;
            color: #a78bfa;
            font-weight: 600;
        }

        .filter-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.3);
            color: #ffffff;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            border-color: #8b5cf6;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(139, 92, 246, 0.3);
        }

        .tutorials-section {
            padding: 4rem 5%;
            position: relative;
        }

        .tutorials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .tutorial-card {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s;
            cursor: pointer;
            position: relative;
        }

        .tutorial-card:hover {
            transform: translateY(-10px);
            border-color: #8b5cf6;
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.3);
        }

        .tutorial-card-image {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: #1a1a1a;
        }

        .tutorial-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .tutorial-card:hover .tutorial-card-image img {
            transform: scale(1.1);
        }

        .tutorial-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(139, 92, 246, 0.9);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 5;
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

        .tutorial-card:hover .play-overlay {
            background: #8b5cf6;
            transform: translate(-50%, -50%) scale(1.15);
        }

        .play-overlay i {
            font-size: 1.5rem;
            margin-left: 3px;
        }

        .tutorial-card-content {
            padding: 30px;
        }

        .tutorial-card-content h3 {
            font-size: 20px;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 15px;
        }

        .tutorial-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .tutorial-meta span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .tutorial-card-content p {
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }

        .tutorial-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .tutorial-tag {
            background: rgba(139, 92, 246, 0.1);
            color: #a78bfa;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .video-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .video-modal.active {
            display: flex;
        }

        .video-modal-content {
            position: relative;
            width: 90%;
            max-width: 1200px;
            background: #1a1a1a;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 40px;
            height: 40px;
            background: rgba(139, 92, 246, 0.2);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 10;
        }

        .close-modal:hover {
            background: #8b5cf6;
            transform: rotate(90deg);
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 12px;
            margin-top: 1rem;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .modal-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }

        .modal-description {
            color: #d1d5db;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')

    @php
        // HERO
        $heroBadge = $page?->getSectionContent('tutoriales_hero_badge', 'Contenido Multimedia');
        $heroTitleLeft = $page?->getSectionContent('tutoriales_hero_title_left', 'VIDEOS');
        $heroTitleRight = $page?->getSectionContent('tutoriales_hero_title_right', 'ELESS');
        $heroSubtitle = $page?->getSectionContent('tutoriales_hero_subtitle', 'Conoce Nuestra Empresa');
        $heroText = $page?->getSectionContent(
            'tutoriales_hero_text',
            'Videos institucionales, presentaciones y contenido corporativo de ELESS GROUP'
        );

        // FILTROS
        $filterLabel = $page?->getSectionContent('tutoriales_filter_label', 'Filtrar por:');
        $filterAllLabel = $page?->getSectionContent('tutoriales_filter_all_label', 'Todos');
        $filterErpLabel = $page?->getSectionContent('tutoriales_filter_erp_label', 'Sistema ERP');
        $filterEcommerceLabel = $page?->getSectionContent('tutoriales_filter_ecommerce_label', 'E-commerce');
        $filterInventarioLabel = $page?->getSectionContent('tutoriales_filter_inventario_label', 'Multi-inventario');
        $filterBasicoLabel = $page?->getSectionContent('tutoriales_filter_basico_label', 'Nivel Básico');
        $filterAvanzadoLabel = $page?->getSectionContent('tutoriales_filter_avanzado_label', 'Nivel Avanzado');
    @endphp
    <section class="hero">
        <div class="particles" id="particles"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="glow-effect"></div>

        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-video"></i> {{ $heroBadge }}
            </div>
            <div class="hero-line-top"></div>
            <h1>
                {{ $heroTitleLeft }} <span class="highlight">{{ $heroTitleRight }}</span>
            </h1>
            <div class="hero-subtitle">{{ $heroSubtitle }}</div>
            <p>{{ $heroText }}</p>
    </section>

    <section class="filter-section">
        <div class="filter-container">
            <span class="filter-label">{{ $filterLabel }}</span>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">{{ $filterAllLabel }}</button>
                <button class="filter-btn" data-filter="erp">{{ $filterErpLabel }}</button>
                <button class="filter-btn" data-filter="ecommerce">{{ $filterEcommerceLabel }}</button>
                <button class="filter-btn" data-filter="inventario">{{ $filterInventarioLabel }}</button>
                <button class="filter-btn" data-filter="basico">{{ $filterBasicoLabel }}</button>
                <button class="filter-btn" data-filter="avanzado">{{ $filterAvanzadoLabel }}</button>
            </div>
        </div>
    </section>

    <section class="tutorials-section">
        <div class="tutorials-grid" id="tutorialsGrid">
            @foreach($tutorials as $tutorial)
                <div class="tutorial-card" data-category="{{ $tutorial['category'] }}" data-tutorial-id="{{ $tutorial['id'] }}"
                    data-video-url="{{ $tutorial['video_url'] ?? '' }}">
                    <div class="tutorial-card-image">
                        <img src="{{ $tutorial['image'] }}" alt="{{ $tutorial['title'] }}">
                        <div class="tutorial-badge">{{ strtoupper(explode(' ', $tutorial['category'])[0]) }}</div>
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="tutorial-card-content">
                        <h3>{{ $tutorial['title'] }}</h3>
                        <div class="tutorial-meta">
                            <span><i class="fas fa-clock"></i> {{ $tutorial['duration'] }}</span>
                            <span><i class="fas fa-signal"></i> {{ $tutorial['level'] }}</span>
                        </div>
                        <p>{{ $tutorial['description'] }}</p>
                        <div class="tutorial-tags">
                            @foreach($tutorial['tags'] as $tag)
                                <span class="tutorial-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="video-modal" id="videoModal">
        <div class="video-modal-content">
            <button class="close-modal" id="closeModal">
                <i class="fas fa-times"></i>
            </button>
            <h2 class="modal-title" id="modalTitle">Título del Tutorial</h2>
            <p class="modal-description" id="modalDescription">Descripción del tutorial</p>
            <div class="video-wrapper" id="videoWrapper"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Crear partículas
            function createParticles() {
                const container = document.getElementById('particles');
                if (container) {
                    for (let i = 0; i < 50; i++) {
                        const particle = document.createElement('div');
                        particle.className = 'particle';
                        particle.style.left = Math.random() * 100 + '%';
                        particle.style.top = Math.random() * 100 + '%';
                        particle.style.animationDelay = Math.random() * 8 + 's';
                        container.appendChild(particle);
                    }
                }
            }

            createParticles();

            // Sistema de filtros
            const filterButtons = document.querySelectorAll('.filter-btn');
            const tutorialCards = document.querySelectorAll('.tutorial-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    const filter = button.dataset.filter;

                    tutorialCards.forEach(card => {
                        const categories = card.dataset.category.split(' ');
                        if (filter === 'all' || categories.includes(filter)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Modal de video
            const modal = document.getElementById('videoModal');
            const closeModal = document.getElementById('closeModal');

            tutorialCards.forEach(card => {
                card.addEventListener('click', () => {
                    const title = card.querySelector('h3').textContent;
                    const description = card.querySelector('p').textContent;

                    const videoUrl = card.dataset.videoUrl || 'https://www.youtube.com/embed/dQw4w9WgXcQ';

                    document.getElementById('modalTitle').textContent = title;
                    document.getElementById('modalDescription').textContent = description;
                    document.getElementById('videoWrapper').innerHTML =
                        '<iframe src="' + videoUrl + '" allowfullscreen></iframe>';

                    modal.classList.add('active');
                });
            });

            closeModal.addEventListener('click', () => {
                modal.classList.remove('active');
                document.getElementById('videoWrapper').innerHTML = '';
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal.click();
                }
            });
        });
    </script>
@endpush