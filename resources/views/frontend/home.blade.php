@extends('layouts.app')

@section('title', 'Inicio')
@section('meta_description', $page?->meta_description ?? 'ELESS Group - Ecosistema Tecnológico Integrado')

@section('content')

    @php
        $heroVideo = $page?->getSectionContent('hero_video');
        $youtubeEmbed = null;

        if ($heroVideo && preg_match('~(youtu\\.be/|v=)([^&?/]+)~', $heroVideo, $m)) {
            $videoId = $m[2];
            $youtubeEmbed = 'https://www.youtube.com/embed/' . $videoId .
                '?autoplay=1&mute=1&loop=1&playlist=' . $videoId . '&controls=0&showinfo=0&modestbranding=1';
        }
    @endphp
    @php
        $heroVideoFrame = $page?->getSectionContent('hero_video_frame');
        $frameEmbed = null;

        if ($heroVideoFrame && preg_match('~(youtu\\.be/|v=)([^&?/]+)~', $heroVideoFrame, $m)) {
            $id = $m[2];
            $frameEmbed = 'https://www.youtube.com/embed/' . $id . '?autoplay=1&mute=1&loop=1&playlist=' . $id;
        }
    @endphp

    <!-- Hero Section -->
    <section class="hero">
        <!-- Video de fondo -->
        <div class="video-background">
            @if($youtubeEmbed)
                <iframe src="{{ $youtubeEmbed }}" frameborder="0" allow="autoplay; fullscreen" allowfullscreen
                    class="video-iframe-bg">
                </iframe>
            @else
                <video autoplay muted loop playsinline>
                    <source src="{{ asset('video/Eless_Prese.mp4') }}" type="video/mp4">
                </video>
            @endif
        </div>
        <div class="video-overlay"></div>

        <!-- Animaciones de fondo -->
        <div class="particles" id="particles"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="glow-effect"></div>

        <!-- Formas flotantes -->
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>

        <div class="hero-content">
            <div class="hero-line-top"></div>

            <h1>
                {{ $page?->getSectionContent('hero_title', 'ELESS') }}
                <span class="highlight">GROUP</span>
            </h1>

            <div class="hero_subtitle">
                {{ $page?->getSectionContent('hero_subtitle', 'Ecosistema Tecnológico Integrado') }}
            </div>

            <p>
                {{ $page?->getSectionContent('hero_description', 'Plataformas diseñadas para optimizar cada aspecto de nuestra operación empresarial') }}
            </p>

            <!-- Contadores de estadísticas -->
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number" data-target="{{ $stats['clients'] ?? 500 }}">0</div>
                    <div class="stat-label">Clientes Satisfechos</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number" data-target="{{ $stats['products'] ?? 900 }}">0</div>
                    <div class="stat-label">Productos de Alta Calidad</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number" data-target="{{ $stats['years'] ?? 5 }}">0</div>
                    <div class="stat-label">Años de Experiencia</div>
                </div>
            </div>

            <a href="#sistemas" class="cta-button-minimal">
                <span>Explorar Plataformas</span>
                <i class="fas fa-arrow-right"></i>
            </a>

            <!-- Video Frame con marco morado -->
            <div class="video-frame-container">
                <div class="video-frame">
                    @if($frameEmbed)
                        <iframe src="{{ $frameEmbed }}" frameborder="0" allow="autoplay; fullscreen" allowfullscreen
                            style="width:100%;height:100%;border-radius:inherit;"></iframe>
                    @else
                        <video autoplay muted loop playsinline id="demoVideo">
                            <source src="{{ asset('video/Eless_Prese.mp4') }}" type="video/mp4">
                        </video>
                    @endif
                </div>

                <div class="video-controls">
                    <div class="pause-button" id="pauseBtn">
                        <i class="fas fa-pause"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección E-commerce -->
    <section class="ecommerce-section" id="ecommerce">
        <div class="ecommerce-container">
            <div class="ecommerce-content">
                <h2>
                    {{ $page?->getSectionContent('ecommerce_title', 'Nuestra plataforma') }}
                    <br>
                    <span class="highlight-text">E-commerce</span>
                </h2>
                <div>
                    {!! $page?->getSectionContent('ecommerce_description', 'El canal de ventas online de ELESS GROUP conecta directamente con nuestro sistema ERP, permitiendo una sincronización automática de inventario, procesamiento de pedidos y gestión de clientes en tiempo real.') !!}
                </div>
                <p>
                    Como colaborador, podrás monitorear las ventas online, gestionar pedidos y
                    coordinar despachos directamente desde el sistema integrado.
                </p>

                <div class="ecommerce-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Catálogo Digital</h3>
                            <p>Todos los productos sincronizados con el inventario central</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Procesamiento de Pagos</h3>
                            <p>Pasarelas integradas con registro automático en el ERP</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Gestión de Despachos</h3>
                            <p>Coordinación de entregas y tracking en tiempo real</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Métricas y Reportes</h3>
                            <p>Dashboard con ventas online y rendimiento del canal digital</p>
                        </div>
                    </div>
                </div>

                <a href="https://eless.com.pe" class="ecommerce-cta" target="_blank">
                    <span>Ver Tienda Online</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @php
                $rawImage = $page?->getSectionContent('ecommerce_image');
                $ecommerceImageSrc = null;

                if ($rawImage) {
                    if (Str::startsWith($rawImage, ['http://', 'https://'])) {
                        // URL externa
                        $ecommerceImageSrc = $rawImage;
                    } else {
                        // Ruta interna en storage/app/public
                        $ecommerceImageSrc = asset('storage/' . ltrim($rawImage, '/'));
                    }
                }
            @endphp

            <div class="ecommerce-image">
                @if($ecommerceImageSrc)
                    <img src="{{ $ecommerceImageSrc }}" alt="ELESS E-commerce" style="width: 100%; height: auto;">
                @else
                    <img src="{{ asset('img/Screenshot_1.png') }}" alt="ELESS E-commerce" style="width: 100%; height: auto;">
                @endif

                <div class="ecommerce-badge">
                    <i class="fas fa-star"></i> E-commerce ELESS
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Sistemas -->
    <section class="systems-section" id="sistemas">
        <div class="systems-header">
            <div class="section-label">Ecosistema Tecnológico</div>
            <h2>Nuestras Plataformas Integradas</h2>
            <p>Conoce los tres sistemas principales que utilizamos en ELESS GROUP para gestionar nuestras operaciones</p>
        </div>

        <div class="systems-grid" id="systemsGrid">
            <!-- Card 1: E-commerce -->
            <div class="system-card" data-card="1">
                <div class="system-card-image">
                    <img src="{{ asset('img/Eless_Eco.jpg') }}" alt="E-commerce ELESS">
                    <div class="system-badge">E-commerce</div>
                </div>
                <div class="system-card-content">
                    <h3>Plataforma E-commerce</h3>
                    <p>Nuestro canal de ventas online que opera 24/7, totalmente sincronizado con el sistema central de
                        inventario y ventas.</p>
                    <div class="system-features">
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Sincronización automática de stock</span>
                        </div>
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Gestión de pedidos online</span>
                        </div>
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Integración con pasarelas de pago</span>
                        </div>
                    </div>
                    <a href="https://eless.com.pe" class="system-card-link" target="_blank">
                        <span>Ver plataforma</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2: ERP -->
            <div class="system-card" data-card="2">
                <div class="system-card-image">
                    <img src="{{ asset('img/image.png') }}" alt="ERP ELESS">
                    <div class="system-badge">Sistema ERP</div>
                </div>
                <div class="system-card-content">
                    <h3>Sistema ERP Central</h3>
                    <p>El núcleo de nuestras operaciones. Gestiona ventas, compras, inventario, clientes, proveedores y toda
                        la operación diaria.</p>
                    <div class="system-features">
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Punto de venta (POS)</span>
                        </div>
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Facturación electrónica SUNAT</span>
                        </div>
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Dashboards y reportes en vivo</span>
                        </div>
                    </div>
                    <a href="https://pruebas.eless.com.pe/sistema-erp-eless/sistema-erp-eless/public/login"
                        class="system-card-link">
                        <span>Acceder al sistema</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 3: Multi-inventario -->
            <div class="system-card" data-card="3">
                <div class="system-card-image">
                    <img src="{{ asset('img/ElessImp.jpg') }}" alt="Multi-inventario ELESS">
                    <div class="system-badge">Multi-inventario</div>
                </div>
                <div class="system-card-content">
                    <h3>Portal de Proveedores</h3>
                    <p>Plataforma exclusiva para que nuestros proveedores monitoreen en tiempo real el stock, análisis y
                        toda la información de sus productos en nuestros almacenes.</p>
                    <div class="system-features">
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Consulta de stock en tiempo real</span>
                        </div>
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Reportes y análisis de productos</span>
                        </div>
                        <div class="system-feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Seguimiento de movimientos de inventario</span>
                        </div>
                    </div>
                    <a href="https://pruebas.eless.com.pe/multiinventario_nuevo/public/login" class="system-card-link"
                        target="_blank">
                        <span>Acceder al portal</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Visión y Misión -->
    <section class="vision-mission-minimal" id="nosotros">
        <div class="vm-minimal-container">
            <div class="vm-minimal-header">
                <div class="vm-line-top"></div>
                <span class="vm-label">ACERCA DE NOSOTROS</span>
                <h2>Nuestra Esencia</h2>
                <div class="vm-line-bottom"></div>
            </div>

            <div class="vm-minimal-grid">
                <!-- Visión -->
                <div class="vm-minimal-card">
                    <div class="vm-card-header">
                        <div class="vm-icon-minimal">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                        <h3>Visión</h3>
                    </div>
                    <p>
                        Ser la empresa líder en soluciones tecnológicas integradas para el sector de
                        productos profesionales de barbería y belleza, expandiendo nuestra presencia
                        a nivel nacional e internacional, siendo referente en innovación y
                        transformación digital.
                    </p>
                    <div class="vm-card-line"></div>
                </div>

                <!-- Misión -->
                <div class="vm-minimal-card">
                    <div class="vm-card-header">
                        <div class="vm-icon-minimal">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                        </div>
                        <h3>Misión</h3>
                    </div>
                    <p>
                        Proporcionar a nuestros clientes herramientas tecnológicas de vanguardia que
                        optimicen sus operaciones comerciales, mejoren su rentabilidad y potencien
                        su crecimiento, a través de sistemas integrados, soporte especializado y
                        productos de la más alta calidad.
                    </p>
                    <div class="vm-card-line"></div>
                </div>
            </div>

            <div class="vm-values-minimal">
                <h3>Nuestros Valores</h3>
                <div class="vm-values-line"></div>

                <div class="values-minimal-grid">
                    <div class="value-minimal-item">
                        <div class="value-minimal-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4>Innovación</h4>
                        <p>Constantemente buscamos nuevas formas de mejorar y evolucionar</p>
                    </div>

                    <div class="value-minimal-item">
                        <div class="value-minimal-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Confiabilidad</h4>
                        <p>Sistemas robustos y seguros en los que puedes confiar plenamente</p>
                    </div>

                    <div class="value-minimal-item">
                        <div class="value-minimal-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Compromiso</h4>
                        <p>Dedicados al éxito de nuestros clientes y colaboradores</p>
                    </div>

                    <div class="value-minimal-item">
                        <div class="value-minimal-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Excelencia</h4>
                        <p>Calidad superior en cada producto y servicio que ofrecemos</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Preguntas Frecuentes -->
    <section class="faq-section" id="faq">
        <div class="faq-container">
            <div class="faq-header">
                <div class="faq-line-top"></div>
                <span class="faq-label">FAQ</span>
                <h2>Preguntas Frecuentes</h2>
            </div>

            <div class="faq-grid">
                <div class="faq-column">
                    <div class="faq-item">
                        <div class="faq-number">01</div>
                        <h3>¿Cómo accedo al sistema ERP?</h3>
                        <p>Puedes acceder al sistema ERP a través del enlace proporcionado en la sección de sistemas.
                            Necesitarás tus credenciales de usuario y contraseña asignadas por el administrador.</p>
                    </div>

                    <div class="faq-item">
                        <div class="faq-number">02</div>
                        <h3>¿Puedo acceder desde cualquier dispositivo?</h3>
                        <p>Sí, todos nuestros sistemas son completamente responsivos y pueden ser accedidos desde
                            computadoras, tablets y smartphones con conexión a internet.</p>
                    </div>

                    <div class="faq-item">
                        <div class="faq-number">03</div>
                        <h3>¿Cómo sincroniza el e-commerce con el ERP?</h3>
                        <p>La sincronización es automática y en tiempo real. Cada venta realizada en el e-commerce actualiza
                            instantáneamente el inventario en el sistema ERP.</p>
                    </div>
                </div>

                <div class="faq-column">
                    <div class="faq-item">
                        <div class="faq-number">04</div>
                        <h3>¿Qué hago si olvido mi contraseña?</h3>
                        <p>Utiliza la opción "Olvidé mi contraseña" en la pantalla de login, o contacta al administrador del
                            sistema para que restablezca tus credenciales.</p>
                    </div>

                    <div class="faq-item">
                        <div class="faq-number">05</div>
                        <h3>¿Cómo acceden los proveedores al multi-inventario?</h3>
                        <p>Los proveedores reciben credenciales únicas que les permiten acceder al portal de
                            multi-inventario para consultar stock y análisis de sus productos.</p>
                    </div>

                    <div class="faq-item">
                        <div class="faq-number">06</div>
                        <h3>¿Los datos están seguros?</h3>
                        <p>Sí, utilizamos encriptación de datos, respaldos automáticos y protocolos de seguridad para
                            garantizar la integridad de toda la información.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Soporte 24/7 -->
    <section class="support-section" id="soporte">
        <div class="support-container">
            <div class="support-header">
                <h2>Soporte 24/7</h2>
                <p>Nuestro equipo técnico está disponible para ayudarte en cualquier momento</p>
            </div>

            <div class="support-grid">
                <div class="support-card">
                    <div class="support-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Trabaja con Nosotros</h3>
                    <p>Únete a nuestro equipo y forma parte de una empresa innovadora con grandes oportunidades de
                        crecimiento.</p>
                    <a href="{{ route('trabaja') }}" class="support-link">
                        <span>Ver Oportunidades</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="support-card">
                    <div class="support-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Soporte Técnico</h3>
                    <p>Contacta directamente con nuestro equipo técnico para asistencia personalizada.</p>
                    <a href="mailto:soporte@eless.com.pe" class="support-link">
                        <span>Contactar Soporte</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="support-card">
                    <div class="support-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3>Videos de la Empresa</h3>
                    <p>Mira nuestros videos institucionales y conoce más sobre ELESS GROUP.</p>
                    <a href="{{ route('tutoriales') }}" class="support-link">
                        <span>Ver Videos</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Crear partículas
        const particlesContainer = document.getElementById('particles');
        if (particlesContainer) {
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Animación de contadores
        const animateCounter = (element, target) => {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 40);
        };

        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-number');
                    counters.forEach(counter => {
                        const target = parseInt(counter.dataset.target);
                        animateCounter(counter, target);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.hero-stats');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
@endpush