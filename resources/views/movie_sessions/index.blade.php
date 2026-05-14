@extends('layouts.app')

@section('title', 'Cartelera')

@section('content')

{{-- ═══ HERO con imagen de cine ═══ --}}
<div class="cinema-hero">
    <div class="cinema-hero-content">
        <h1><i class="bi bi-film"></i> Cartelera</h1>
        <p class="cinema-hero-tagline">
            Descubre las mejores películas en cartel · <strong>{{ $movies->count() }}</strong>
            {{ $movies->count() === 1 ? 'película disponible' : 'películas disponibles' }}
        </p>
    </div>
</div>

{{-- ═══ FILTROS ═══ --}}
<form class="filters-bar" method="GET" action="{{ route('cartelera') }}">
    <div class="filter-group">
        <label class="filter-label" for="sala">
            <i class="bi bi-door-open"></i> Sala
        </label>
        <select class="filter-select" id="sala" name="sala" onchange="this.form.submit()">
            <option value="">Todas las salas</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}" {{ request('sala') == $room->id ? 'selected' : '' }}>
                    {{ $room->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="categoria">
            <i class="bi bi-tags"></i> Categoría
        </label>
        <select class="filter-select" id="categoria" name="categoria" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('categoria') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="fecha">
            <i class="bi bi-calendar3"></i> Fecha
        </label>
        <input type="date"
               class="filter-select"
               id="fecha"
               name="fecha"
               value="{{ request('fecha') }}"
               onchange="this.form.submit()">
    </div>

    @if(request('sala') || request('fecha') || request('categoria'))
        <a href="{{ route('cartelera') }}" class="btn-cine btn-cine-ghost btn-cine-sm" style="align-self:flex-end;">
            <i class="bi bi-x-circle"></i> Limpiar filtros
        </a>
    @endif
</form>

{{-- ═══ GRID DE PELÍCULAS ═══ --}}
@if($movies->isEmpty())
    <div class="alert-cine alert-cine-info" style="flex-direction:column; text-align:center; padding:3rem 1rem;">
        <i class="bi bi-calendar-x" style="font-size:3rem; opacity:.4; margin-bottom:.75rem;"></i>
        <strong>No hay películas que coincidan con los filtros seleccionados.</strong>
        <span style="margin-top:.4rem;">Prueba a cambiar la sala, la categoría o la fecha.</span>
    </div>
@else
    <div class="movies-grid">
        @foreach($movies as $movie)
            <div class="movie-card">

                <a href="{{ route('movies.show', $movie->id) }}" class="movie-card-poster-link">
                    @if($movie->poster)
                        <img src="{{ str_starts_with($movie->poster, 'http') ? $movie->poster : asset('storage/' . $movie->poster) }}"
                             alt="Póster de {{ $movie->title }}"
                             class="movie-poster">
                    @else
                        <div class="movie-poster-placeholder">
                            <i class="bi bi-camera-reels"></i>
                            <span>{{ $movie->title }}</span>
                        </div>
                    @endif
                    <div class="movie-overlay">
                        <i class="bi bi-caret-right-circle-fill"></i>&nbsp;Ver sesiones
                    </div>

                    @if($movie->category)
                        <span class="category-tag">{{ $movie->category }}</span>
                    @endif
                </a>

                <div class="movie-card-body">

                    <div class="movie-card-header">
                        <a href="{{ route('movies.show', $movie->id) }}" class="movie-title">
                            {{ $movie->title }}
                        </a>

                        @auth
                            <form action="{{ route('favorites.toggle', $movie->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="btn-fav {{ in_array($movie->id, $favoriteIds) ? 'active' : '' }}"
                                        title="{{ in_array($movie->id, $favoriteIds) ? 'Quitar de favoritos' : 'Añadir a favoritos' }}">
                                    <i class="bi {{ in_array($movie->id, $favoriteIds) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                </button>
                            </form>
                        @endauth
                    </div>

                    <div class="movie-meta">
                        <span class="meta-chip">
                            <i class="bi bi-clock"></i> {{ $movie->duration_minutes }} min
                        </span>
                        <span class="meta-chip meta-chip-accent">
                            <i class="bi bi-calendar3"></i>
                            {{ $movie->sesiones_count }} {{ $movie->sesiones_count === 1 ? 'sesión' : 'sesiones' }}
                        </span>
                    </div>

                    <p class="movie-description">{{ Str::limit($movie->description, 100) }}</p>

                    <a href="{{ route('movies.show', $movie->id) }}"
                       class="btn-cine btn-cine-sm"
                       style="width:100%; justify-content:center;">
                        <i class="bi bi-ticket-perforated"></i> Ver sesiones
                    </a>
                </div>

            </div>
        @endforeach
    </div>
@endif

@endsection
