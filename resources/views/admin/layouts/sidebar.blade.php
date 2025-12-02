<aside class="admin-sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('img/logo_blanco.png') }}" alt="ELESS">
        </a>
        <span class="sidebar-badge">CMS</span>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <!-- Contenido -->
        <div class="nav-group">
            <div class="nav-group-title">Contenido</div>
            
            <a href="{{ route('admin.pages.index') }}" class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Páginas</span>
            </a>
            
            <a href="{{ route('admin.sections.index') }}" class="nav-item {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                <i class="fas fa-puzzle-piece"></i>
                <span>Secciones</span>
            </a>
            
            <a href="{{ route('admin.sliders.index') }}" class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <span>Sliders</span>
            </a>
            
            <a href="{{ route('admin.faqs.index') }}" class="nav-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i>
                <span>FAQs</span>
            </a>
        </div>

        <!-- Multimedia -->
        <div class="nav-group">
            <div class="nav-group-title">Multimedia</div>
            
            <a href="{{ route('admin.videos.index') }}" class="nav-item {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                <i class="fas fa-video"></i>
                <span>Videos</span>
            </a>
            
            <a href="{{ route('admin.video-categories.index') }}" class="nav-item {{ request()->routeIs('admin.video-categories.*') ? 'active' : '' }}">
                <i class="fas fa-folder"></i>
                <span>Categorías</span>
            </a>
            
            <a href="{{ route('admin.media.index') }}" class="nav-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <i class="fas fa-photo-video"></i>
                <span>Galería</span>
            </a>
        </div>

        <!-- Administración -->
        <div class="nav-group">
            <div class="nav-group-title">Administración</div>
            
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
            
            <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Roles</span>
            </a>
            
            <a href="{{ route('admin.logs.index') }}" class="nav-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Actividad</span>
            </a>
        </div>

        <!-- Sistema -->
        <div class="nav-group">
            <div class="nav-group-title">Sistema</div>
            
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <a href="{{ route('home') }}" target="_blank" class="nav-item">
            <i class="fas fa-external-link-alt"></i>
            <span>Ver Sitio</span>
        </a>
    </div>
</aside>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
