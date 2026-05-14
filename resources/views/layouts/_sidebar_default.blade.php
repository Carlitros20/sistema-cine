<div class="sidebar-title">
    <i class="bi bi-camera-reels"></i> Cine CLM
</div>
<nav class="sidebar-nav">
    <a href="{{ route('cartelera') }}"
       class="sidebar-link {{ request()->routeIs('cartelera') ? 'active' : '' }}">
        <i class="bi bi-film"></i> Cartelera
    </a>
    <a href="{{ route('contacto') }}"
       class="sidebar-link {{ request()->routeIs('contacto') ? 'active' : '' }}">
        <i class="bi bi-envelope"></i> Contacto
    </a>

    @auth
        <a href="{{ route('profile.index') }}"
           class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Mi perfil
        </a>

        @if(Auth::user()->isAdmin())
            <div class="sidebar-divider"></div>
            <div class="sidebar-title" style="margin-top:.25rem;">
                <i class="bi bi-shield-lock"></i> Administración
            </div>
            <a href="{{ route('admin.sessions.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Sesiones
            </a>
            <a href="{{ route('admin.movies.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.movies.*') ? 'active' : '' }}">
                <i class="bi bi-camera-video"></i> Películas
            </a>
            <a href="{{ route('admin.rooms.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i> Salas
            </a>
            <a href="{{ route('admin.stats.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.stats.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Estadísticas
            </a>
        @endif
    @endauth
</nav>
