<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ELESS - Group')</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='80' fill='%238b5cf6'>E</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('extra-css')

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-logo">
            <img src="{{ asset('img/logo_blanco.png') }}" alt="ELESS Logo" class="logo">
        </div>
        <div class="nav-links">
            <a href="{{ route('home') }}#sistemas">Nuestros Sistemas</a>
            <a href="{{ route('home') }}#ecommerce">E-commerce</a>
            <a href="{{ route('home') }}#nosotros">Acerca de</a>
            <a href="{{ route('home') }}#faq">FAQ</a>
            <a href="{{ route('home') }}#soporte">Soporte</a>

        </div>
        <div class="nav-buttons">
            {{-- Nuevo botón Administrador --}}
            <a href="{{ route('admin.applications') }}" class="join-btn" style="margin-right: 12px;">
                Administrador
            </a>

            {{-- Botón existente --}}
            <a href="https://pruebas.eless.com.pe/sistema-erp-eless/sistema-erp-eless/public/login" class="join-btn">
                Acceder al ERP
            </a>
        </div>

    </nav>

    <!-- Content -->
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
                            <li><a href="https://eless.com.pe" target="_blank">E-commerce</a></li>
                            <li><a href="https://pruebas.eless.com.pe/sistema-erp-eless/sistema-erp-eless/public/login">Sistema
                                    ERP</a></li>
                            <li><a href="https://pruebas.eless.com.pe/multiinventario_nuevo/public/login"
                                    target="_blank">Multi-inventario</a></li>
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
                            <li><a href="mailto:soporte@eless.com.pe">Contacto Técnico</a></li>
                            <li><a href="{{ route('capacitaciones') }}">Capacitaciones</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Contacto</h4>
                        <ul>
                            <li><a href="mailto:info@eless.com.pe">info@eless.com.pe</a></li>
                            <li><a href="tel:+51999999999">+51 999 999 999</a></li>
                            <li>San Isidro, Lima - Perú</li>
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
                    <a href="#" class="social-link" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
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

    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>