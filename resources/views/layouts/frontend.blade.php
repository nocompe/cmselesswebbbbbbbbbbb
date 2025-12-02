<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ELESS Group') - Ecosistema Tecnológico</title>
    <meta name="description" content="@yield('meta_description', 'ELESS Group - Ecosistema Tecnológico Integrado')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='80' fill='%238b5cf6'>E</text></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo_blanco.png') }}" alt="ELESS Logo" class="logo">
            </a>
        </div>
        <div class="nav-links">
            <a href="{{ route('home') }}#sistemas">Nuestros Sistemas</a>
            <a href="{{ route('home') }}#ecommerce">E-commerce</a>
            <a href="{{ route('multimedia.index') }}">Centro Multimedia</a>
            <a href="{{ route('home') }}#nosotros">Acerca de</a>
            <a href="{{ route('home') }}#faq">FAQ</a>
            <a href="{{ route('home') }}#soporte">Soporte</a>
        </div>
        <div class="nav-buttons">
            <a href="{{ \App\Models\Setting::get('link_erp', '#') }}" class="join-btn" target="_blank">Acceder al ERP</a>
        </div>
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('home') }}#sistemas">Nuestros Sistemas</a>
        <a href="{{ route('home') }}#ecommerce">E-commerce</a>
        <a href="{{ route('multimedia.index') }}">Centro Multimedia</a>
        <a href="{{ route('home') }}#nosotros">Acerca de</a>
        <a href="{{ route('home') }}#faq">FAQ</a>
        <a href="{{ route('home') }}#soporte">Soporte</a>
        <a href="{{ \App\Models\Setting::get('link_erp', '#') }}" class="join-btn">Acceder al ERP</a>
    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-brand">
                    <img src="{{ asset('img/logo_blanco.png') }}" alt="ELESS Logo" class="footer-logo">
                    <p>Transformando negocios con tecnología de vanguardia</p>
                </div>

                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Plataformas</h4>
                        <ul>
                            <li><a href="{{ \App\Models\Setting::get('link_ecommerce', '#') }}" target="_blank">E-commerce</a></li>
                            <li><a href="{{ \App\Models\Setting::get('link_erp', '#') }}" target="_blank">Sistema ERP</a></li>
                            <li><a href="{{ \App\Models\Setting::get('link_multiinventario', '#') }}" target="_blank">Multi-inventario</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Empresa</h4>
                        <ul>
                            <li><a href="{{ route('home') }}#nosotros">Acerca de</a></li>
                            <li><a href="{{ route('home') }}#nosotros">Visión y Misión</a></li>
                            <li><a href="{{ route('home') }}#faq">Preguntas Frecuentes</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Soporte</h4>
                        <ul>
                            <li><a href="{{ route('home') }}#soporte">Centro de Ayuda</a></li>
                            <li><a href="mailto:{{ \App\Models\Setting::get('contact_email', 'soporte@eless.com.pe') }}">Contacto Técnico</a></li>
                            <li><a href="{{ route('multimedia.index') }}">Tutoriales</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Contacto</h4>
                        <ul>
                            <li><a href="mailto:{{ \App\Models\Setting::get('contact_email', 'info@eless.com.pe') }}">{{ \App\Models\Setting::get('contact_email', 'info@eless.com.pe') }}</a></li>
                            <li><a href="tel:{{ \App\Models\Setting::get('contact_phone', '+51999999999') }}">{{ \App\Models\Setting::get('contact_phone', '+51 999 999 999') }}</a></li>
                            <li>{{ \App\Models\Setting::get('contact_address', 'San Isidro, Lima - Perú') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>&copy; {{ date('Y') }} ELESS GROUP. Todos los derechos reservados.</p>
                </div>

                <div class="footer-social">
                    @if(\App\Models\Setting::get('social_facebook'))
                        <a href="{{ \App\Models\Setting::get('social_facebook') }}" class="social-link" target="_blank" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if(\App\Models\Setting::get('social_instagram'))
                        <a href="{{ \App\Models\Setting::get('social_instagram') }}" class="social-link" target="_blank" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if(\App\Models\Setting::get('social_linkedin'))
                        <a href="{{ \App\Models\Setting::get('social_linkedin') }}" class="social-link" target="_blank" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if(\App\Models\Setting::get('contact_whatsapp'))
                        <a href="https://wa.me/{{ \App\Models\Setting::get('contact_whatsapp') }}" class="social-link" target="_blank" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                </div>

                <div class="footer-links-bottom">
                    <a href="#">Política de Privacidad</a>
                    <span class="separator">•</span>
                    <a href="#">Términos de Uso</a>
                </div>
            </div>
        </div>
        <div class="footer-line"></div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
