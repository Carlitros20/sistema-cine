<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cine CLM - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cine.css') }}">

    @stack('styles')
</head>
<body>

    {{-- NAVBAR SUPERIOR --}}
    <nav class="navbar-cine">
        <a class="navbar-brand-cine" href="{{ route('cartelera') }}">
            <i class="bi bi-camera-reels"></i> Cine<span>CLM</span>
        </a>

        <button class="navbar-toggler-cine" id="navToggle" aria-label="Abrir menu">
            <i class="bi bi-list" id="navIcon"></i>
        </button>

        <div class="navbar-collapsible" id="navCollapsible">

            {{-- ZONA USUARIO: sale PRIMERO en movil --}}
            <div class="navbar-user-mobile">
                @auth
                    <span class="navbar-username">
                        <i class="bi bi-person-fill"></i>
                        {{ Auth::user()->name }}
                        <span class="badge-role">{{ Auth::user()->role }}</span>
                    </span>
                    <a class="nav-link-cine {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                       href="{{ route('profile.index') }}">
                        <i class="bi bi-person-circle"></i> Mi perfil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" class="btn-cine btn-cine-ghost btn-cine-sm">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                @else
                    <a class="nav-link-cine" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </a>
                    <a class="btn-cine btn-cine-sm" href="{{ route('register') }}">
                        <i class="bi bi-person-plus"></i> Registro
                    </a>
                @endauth
            </div>
            <span class="navbar-divider-mobile"></span>

            <div class="navbar-links">
                <a class="nav-link-cine {{ request()->routeIs('cartelera') ? 'active' : '' }}"
                   href="{{ route('cartelera') }}">
                    <i class="bi bi-film"></i> Cartelera
                </a>
                <a class="nav-link-cine {{ request()->routeIs('contacto') ? 'active' : '' }}"
                   href="{{ route('contacto') }}">
                    <i class="bi bi-envelope"></i> Contacto
                </a>

                @auth
                    @if(Auth::user()->isAdmin())
                        <a class="nav-link-cine {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}"
                           href="{{ route('admin.sessions.index') }}">
                            <i class="bi bi-calendar3"></i> Sesiones
                        </a>
                        <a class="nav-link-cine {{ request()->routeIs('admin.movies.*') ? 'active' : '' }}"
                           href="{{ route('admin.movies.index') }}">
                            <i class="bi bi-camera-video"></i> Peliculas
                        </a>
                        <a class="nav-link-cine {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}"
                           href="{{ route('admin.rooms.index') }}">
                            <i class="bi bi-door-open"></i> Salas
                        </a>
                    @endif
                @endauth
            </div>

            <div class="navbar-user navbar-user-desktop">
                @auth
                    <span class="navbar-username">
                        <i class="bi bi-person-fill"></i>
                        {{ Auth::user()->name }}
                        <span class="badge-role">{{ Auth::user()->role }}</span>
                    </span>
                    <a class="nav-link-cine {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                       href="{{ route('profile.index') }}">
                        <i class="bi bi-person-circle"></i> Mi perfil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" class="btn-cine btn-cine-ghost btn-cine-sm">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                @else
                    <a class="nav-link-cine" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </a>
                    <a class="btn-cine btn-cine-sm" href="{{ route('register') }}">
                        <i class="bi bi-person-plus"></i> Registro
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- CUERPO: sidebar (definido por la vista o el sidebar por defecto) + contenido --}}
    @php $sidebarContent = trim($__env->yieldContent('sidebar')); @endphp

    <div class="page-body has-sidebar">

        <aside class="sidebar-cine">
            @if($sidebarContent)
                @yield('sidebar')
            @else
                @include('layouts._sidebar_default')
            @endif
        </aside>

        <main class="main-content">
            @if(session('success'))
                <div class="alert-cine alert-cine-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->has('devolucion'))
                <div class="alert-cine alert-cine-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first('devolucion') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    {{-- FOOTER --}}
    <footer class="footer-cine">
        <div class="footer-inner">
            <span class="footer-logo"><i class="bi bi-camera-reels"></i> CineCLM</span>
            <span class="footer-sep">|</span>
            <a href="{{ route('contacto') }}">Contacto</a>
            <span class="footer-sep">|</span>
            <a href="{{ asset('como_se_hizo.pdf') }}" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Como se hizo
            </a>
        </div>
    </footer>

    <script>
        document.getElementById('navToggle').addEventListener('click', function () {
            var col  = document.getElementById('navCollapsible');
            var icon = document.getElementById('navIcon');
            col.classList.toggle('open');
            icon.className = col.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-list';
        });
    </script>

    @stack('scripts')
</body>
</html>
