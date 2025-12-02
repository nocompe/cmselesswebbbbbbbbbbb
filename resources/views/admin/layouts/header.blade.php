<header class="admin-header">
    <!-- Left Side -->
    <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="header-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar..." id="globalSearch">
        </div>
    </div>

    <!-- Right Side -->
    <div class="header-right">
        <!-- Quick Actions -->
        <div class="header-actions">
            <a href="{{ route('home') }}" target="_blank" class="action-btn" title="Ver sitio">
                <i class="fas fa-globe"></i>
            </a>
            
            <button class="action-btn" id="refreshBtn" title="Refrescar">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <!-- User Dropdown -->
        <div class="user-dropdown" id="userDropdown">
            <button class="user-btn">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ auth()->user()->roles->first()?->name ?? 'Usuario' }}</span>
                </div>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            <div class="dropdown-menu">
                <a href="{{ route('admin.profile') }}" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    Mi Perfil
                </a>
                <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    Configuración
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
