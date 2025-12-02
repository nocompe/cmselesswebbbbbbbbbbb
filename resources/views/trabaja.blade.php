@extends('layouts.app')

@section('title', 'ELESS - Trabaja con Nosotros')

@push('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        .careers-hero {
            position: relative;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 80px;
        }

        .benefits-section {
            padding: 80px 5%;
            background: rgba(255, 255, 255, 0.02);
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 60px auto 0;
        }

        .benefit-card {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            border-color: #8b5cf6;
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.3);
        }

        .benefit-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(236, 72, 153, 0.2));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #8b5cf6;
        }

        .benefit-card h3 {
            font-size: 22px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 15px;
        }

        .benefit-card p {
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
        }

        .positions-section {
            padding: 80px 5%;
        }

        .positions-grid {
            display: grid;
            gap: 25px;
            max-width: 1000px;
            margin: 60px auto 0;
        }

        .position-card {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 35px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .position-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #8b5cf6, #ec4899);
        }

        .position-card:hover {
            transform: translateX(10px);
            border-color: #8b5cf6;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.2);
        }

        .position-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .position-title {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .position-department {
            font-size: 14px;
            color: #8b5cf6;
            font-weight: 500;
        }

        .position-badge {
            padding: 6px 16px;
            background: rgba(139, 92, 246, 0.2);
            border: 1px solid rgba(139, 92, 246, 0.4);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #8b5cf6;
            text-transform: uppercase;
        }

        .position-description {
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }

        .position-requirements {
            margin-bottom: 25px;
        }

        .position-requirements h4 {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .position-requirements ul {
            list-style: none;
            padding: 0;
        }

        .position-requirements li {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            padding: 8px 0 8px 25px;
            position: relative;
        }

        .position-requirements li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #8b5cf6;
            font-weight: bold;
        }

        .position-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .position-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .position-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        .position-meta-item i {
            color: #8b5cf6;
        }

        .apply-btn {
            padding: 12px 28px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(139, 92, 246, 0.4);
        }

        .application-section {
            padding: 80px 5%;
            background: rgba(139, 92, 246, 0.05);
        }

        .application-form-container {
            max-width: 700px;
            margin: 60px auto 0;
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 50px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 8px;
            color: #ffffff;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #8b5cf6;
            background: rgba(255, 255, 255, 0.08);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 18px;
            background: rgba(139, 92, 246, 0.1);
            border: 2px dashed rgba(139, 92, 246, 0.4);
            border-radius: 8px;
            color: #8b5cf6;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-input-label:hover {
            background: rgba(139, 92, 246, 0.2);
            border-color: #8b5cf6;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        }

        @media (max-width: 768px) {
            .application-form-container {
                padding: 30px 20px;
            }

            .position-header {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')


@php
        // HERO
        $heroBadge = $page?->getSectionContent('trabaja_hero_badge', 'ÚNETE AL EQUIPO');
        $heroTitleLeft = $page?->getSectionContent('trabaja_hero_title_left', 'TRABAJA');
        $heroTitleRight = $page?->getSectionContent('trabaja_hero_title_right', 'CON NOSOTROS');
        $heroSubtitle = $page?->getSectionContent('trabaja_hero_subtitle', 'Construye tu Futuro con ELESS');
        $heroText = $page?->getSectionContent('trabaja_hero_text', 'Forma parte de un equipo innovador que está transformando la industria con tecnología de vanguardia');

        // BENEFICIOS – cabecera
        $benefitsLabel = $page?->getSectionContent('trabaja_benefits_label', '¿POR QUÉ ELESS?');
        $benefitsTitle = $page?->getSectionContent('trabaja_benefits_title', 'Beneficios de Trabajar con Nosotros');
        $benefitsDescription = $page?->getSectionContent('trabaja_benefits_description', 'Ofrecemos un ambiente de trabajo excepcional con beneficios competitivos');

        // BENEFICIOS – tarjetas
        $benefit1Title = $page?->getSectionContent('trabaja_benefit_one_title', 'Crecimiento Profesional');
        $benefit1Text = $page?->getSectionContent('trabaja_benefit_one_text', 'Oportunidades de desarrollo continuo y capacitación en las últimas tecnologías');

        $benefit2Title = $page?->getSectionContent('trabaja_benefit_two_title', 'Tecnología Innovadora');
        $benefit2Text = $page?->getSectionContent('trabaja_benefit_two_text', 'Trabaja con herramientas y plataformas de última generación');

        $benefit3Title = $page?->getSectionContent('trabaja_benefit_three_title', 'Equipo Colaborativo');
        $benefit3Text = $page?->getSectionContent('trabaja_benefit_three_text', 'Ambiente de trabajo inclusivo y colaborativo con profesionales talentosos');

        $benefit4Title = $page?->getSectionContent('trabaja_benefit_four_title', 'Flexibilidad');
        $benefit4Text = $page?->getSectionContent('trabaja_benefit_four_text', 'Opciones de trabajo remoto e híbrido según el rol');

        $benefit5Title = $page?->getSectionContent('trabaja_benefit_five_title', 'Pasajes para Movilidad');
        $benefit5Text = $page?->getSectionContent('trabaja_benefit_five_text', 'Cobertura de la movilidad');

        $benefit6Title = $page?->getSectionContent('trabaja_benefit_six_title', 'Bonos y Recompensas');
        $benefit6Text = $page?->getSectionContent('trabaja_benefit_six_text', 'Sistema de incentivos por desempeño y cumplimiento de objetivos');

        // POSICIONES – cabecera
        $positionsLabel = $page?->getSectionContent('trabaja_positions_label', 'OPORTUNIDADES');
        $positionsTitle = $page?->getSectionContent('trabaja_positions_title', 'Posiciones Abiertas');
        $positionsDescription = $page?->getSectionContent('trabaja_positions_description', 'Encuentra la posición perfecta para ti');

        // FORMULARIO – cabecera
        $applicationLabel = $page?->getSectionContent('trabaja_application_label', 'POSTULA AHORA');
        $applicationTitle = $page?->getSectionContent('trabaja_application_title', 'Envíanos tu Postulación');
        $applicationDescription = $page?->getSectionContent('trabaja_application_description', 'Completa el formulario y te contactaremos pronto');
    @endphp
    <section class="careers-hero hero">
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
                <i class="fas fa-briefcase"></i> {{ $heroBadge }}
            </div>
            <div class="hero-line-top"></div>
           <h1> {{ $heroTitleLeft }}
           <span class="highlight">{{ $heroTitleRight }}</span>
            </h1>
            <div class="hero-subtitle">{{ $heroSubtitle }}</div>
            <p>{{ $heroText }}</p>
            </div>
    </section>

    <!-- Sección de Beneficios -->
    <section class="benefits-section">
        <div class="section-header">
            <div class="section-label">{{ $benefitsLabel }}</div>
<h2 class="section-title">{{ $benefitsTitle }}</h2>
<p class="section-description">{{ $benefitsDescription }}</p>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>{{ $benefit1Title }}</h3>
<p>{{ $benefit1Text }}</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3>Tecnología Innovadora</h3>
                <p>Trabaja con herramientas y plataformas de última generación</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Equipo Colaborativo</h3>
                <p>Ambiente de trabajo inclusivo y colaborativo con profesionales talentosos</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Flexibilidad</h3>
                <p>Opciones de trabajo remoto e híbrido según el rol</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Pasajes para Movilidad</h3>
                <p>Cobertura De la Movilidad</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <h3>Bonos y Recompensas</h3>
                <p>Sistema de incentivos por desempeño y cumplimiento de objetivos</p>
            </div>
        </div>
    </section>

    <!-- Posiciones Disponibles -->
    <section class="positions-section">
        <div class="section-header">
            <div class="section-label">OPORTUNIDADES</div>
            <h2 class="section-title">Posiciones Abiertas</h2>
            <p class="section-description">Encuentra la posición perfecta para ti</p>
        </div>

        <div class="positions-grid">
            @foreach($positions as $position)
                <div class="position-card">
                    <div class="position-header">
                        <div>
                            <h3 class="position-title">{{ $position['title'] }}</h3>
                            <div class="position-department">{{ $position['department'] }}</div>
                        </div>
                        <span class="position-badge">{{ $position['type'] }}</span>
                    </div>

                    <p class="position-description">{{ $position['description'] }}</p>

                    <div class="position-requirements">
                        <h4>Requisitos</h4>
                        <ul>
                            @foreach($position['requirements'] as $requirement)
                                <li>{{ $requirement }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="position-footer">
                        <div class="position-meta">
                            <div class="position-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $position['location'] }}</span>
                            </div>
                            <div class="position-meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $position['schedule'] }}</span>
                            </div>
                        </div>
                        <a href="#aplicar" class="apply-btn">
                            <span>Postular</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Formulario de Aplicación -->
    <section class="application-section" id="aplicar">
        <div class="section-header">
            <div class="section-label">{{ $applicationLabel }}</div>
<h2 class="section-title">{{ $applicationTitle }}</h2>
<p class="section-description">{{ $applicationDescription }}</p>
        </div>

        <div class="application-form-container">
            @if(session('success'))
                <div
                    style="padding: 15px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; margin-bottom: 25px; color: #22c55e;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div
                    style="padding: 15px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; margin-bottom: 25px; color: #ef4444;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('trabaja.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre Completo *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono *</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="position">Posición de Interés *</label>
                    <select id="position" name="position" required>
                        <option value="">Selecciona una posición</option>
                        @foreach($positions as $position)
                            <option value="{{ $position['title'] }}" {{ old('position') == $position['title'] ? 'selected' : '' }}>
                                {{ $position['title'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('position')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">Mensaje / Carta de Presentación</label>
                    <textarea id="message" name="message"
                        placeholder="Cuéntanos por qué eres el candidato ideal...">{{ old('message') }}</textarea>
                    @error('message')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cv">Curriculum Vitae (PDF) *</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="cv" name="cv" accept=".pdf" required>
                        <label for="cv" class="file-input-label">
                            <i class="fas fa-upload"></i>
                            <span>Adjuntar CV (Máx. 5MB)</span>
                        </label>
                    </div>
                    @error('cv')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Enviar Postulación
                </button>
            </form>
        </div>
    </section>
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

            // Actualizar nombre del archivo cuando se selecciona
            const fileInput = document.getElementById('cv');
            const fileLabel = document.querySelector('.file-input-label span');

            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', function () {
                    const fileName = this.files[0]?.name || 'Adjuntar CV';
                    fileLabel.textContent = fileName;
                });
            }
        });
    </script>
@endpush
