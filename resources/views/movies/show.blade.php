@extends('layouts.app')

@section('title', $movie->title)

@section('content')

<nav class="breadcrumb-cine">
    <a href="{{ route('cartelera') }}"><i class="bi bi-film"></i> Cartelera</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-active">{{ $movie->title }}</span>
</nav>

<div style="display:flex; gap:2rem; flex-wrap:wrap; margin-bottom:2rem;">

    {{-- Póster --}}
    <div style="flex-shrink:0;">
        @if($movie->poster)
            <img src="{{ $movie->poster }}" alt="Póster {{ $movie->title }}" class="movie-detail-poster">
        @else
            <div class="movie-detail-poster" style="background:var(--c-bg3); display:flex; align-items:center; justify-content:center; color:var(--c-text-muted); font-size:3rem; width:220px; height:330px; border-radius:var(--radius-lg);">
                <i class="bi bi-film"></i>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div style="flex:1; min-width:220px;">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:.5rem;">
            <h1 style="margin:0 0 .5rem;">{{ $movie->title }}</h1>

            {{-- Botón favorito --}}
            @auth
            <form action="{{ route('favorites.toggle', $movie->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-fav {{ $isFavorite ? 'active' : '' }}" title="{{ $isFavorite ? 'Quitar de favoritos' : 'Añadir a favoritos' }}">
                    <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    {{ $isFavorite ? 'En favoritos' : 'Favorito' }}
                </button>
            </form>
            @endauth
        </div>

        <p class="text-muted-cine" style="margin-bottom:1rem; display:flex; gap:1rem; flex-wrap:wrap;">
            <span><i class="bi bi-hourglass-split"></i> {{ $movie->duration_minutes }} min</span>
            @php $avg = $movie->averageRating(); $total = $movie->ratings()->count(); @endphp
            <span>
                <span class="stars-display">
                    @for($s = 1; $s <= 5; $s++)
                        <i class="bi {{ $s <= round($avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </span>
                {{ $avg > 0 ? $avg . ' / 5' : 'Sin valoraciones' }}
                @if($total > 0) <span class="text-muted-cine">({{ $total }})</span> @endif
            </span>
        </p>

        <p style="line-height:1.7; color:var(--c-text);">{{ $movie->description }}</p>
    </div>
</div>

<hr class="section-divider">

{{-- Sesiones disponibles --}}
<h3 style="margin-bottom:1rem;"><i class="bi bi-calendar3"></i> Sesiones disponibles</h3>

@forelse($sesiones as $sesion)
    <div class="session-row">
        <div style="display:flex; gap:1.5rem; flex-wrap:wrap; align-items:center;">
            <span style="font-weight:600;"><i class="bi bi-door-open"></i> {{ $sesion->room->name }}</span>
            <span class="text-muted-cine"><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($sesion->start_time)->format('d/m/Y') }}</span>
            <span class="text-muted-cine"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($sesion->start_time)->format('H:i') }}</span>
        </div>
        <a href="{{ route('sessions.show', $sesion->id) }}" class="btn-cine btn-sm-cine">
            <i class="bi bi-ticket-perforated"></i> Comprar
        </a>
    </div>
@empty
    <div class="alert-cine alert-cine-warning"><i class="bi bi-info-circle"></i> No hay sesiones disponibles para esta película.</div>
@endforelse

<a href="{{ route('cartelera') }}" class="btn-outline-cine" style="margin-top:1.5rem; display:inline-flex;">
    <i class="bi bi-arrow-left"></i> Volver a la cartelera
</a>

<hr class="section-divider">

{{-- Valoraciones --}}
<h3 style="margin-bottom:1.25rem;"><i class="bi bi-star-half"></i> Valoraciones</h3>

@auth
    @php
        $myRating = $movie->ratings()->where('user_id', Auth::id())->first();
    @endphp

    <div class="form-card" style="margin-bottom:1.5rem;">
        <div class="form-card-title" style="font-size:1rem;">
            {{ $myRating ? 'Tu valoración' : 'Valora esta película' }}
        </div>

        @if(session('success'))
            <div class="alert-cine alert-cine-success" style="margin-bottom:1rem;">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('ratings.store', $movie->id) }}" method="POST">
            @csrf
            <div class="form-cine-group">
                <label class="form-cine-label">Puntuación</label>
                <div class="rating-form-stars" id="starPicker">
                    @for($s = 5; $s >= 1; $s--)
                        <input type="radio" name="score" id="star{{ $s }}" value="{{ $s }}"
                               {{ $myRating && $myRating->score == $s ? 'checked' : '' }} required>
                        <label for="star{{ $s }}" title="{{ $s }} estrella{{ $s > 1 ? 's' : '' }}">&#9733;</label>
                    @endfor
                </div>
                @error('score') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-cine-group">
                <label class="form-cine-label" for="comment">Comentario (opcional)</label>
                <textarea class="form-cine-control" id="comment" name="comment"
                          rows="3" maxlength="500"
                          placeholder="Escribe tu opinión...">{{ old('comment', $myRating?->comment) }}</textarea>
            </div>

            <div style="display:flex; gap:.75rem; align-items:center; flex-wrap:wrap;">
                <button type="submit" class="btn-cine btn-sm-cine">
                    <i class="bi bi-check-lg"></i> {{ $myRating ? 'Actualizar' : 'Enviar valoración' }}
                </button>
                @if($myRating)
                    <form action="{{ route('ratings.destroy', $movie->id) }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger-cine btn-sm-cine"
                                onclick="return confirm('¿Eliminar tu valoración?')">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </form>
    </div>
@else
    <div class="alert-cine alert-cine-info" style="margin-bottom:1.5rem;">
        <i class="bi bi-info-circle"></i>
        <a href="{{ route('login') }}">Inicia sesión</a> para valorar esta película.
    </div>
@endauth

{{-- Listado de valoraciones --}}
@php $ratings = $movie->ratings()->with('user')->latest()->get(); @endphp

@forelse($ratings as $rating)
    <div class="rating-card">
        <div class="rating-card-header">
            <span class="rating-card-user">{{ $rating->user->name }}</span>
            <div style="display:flex; align-items:center; gap:.75rem;">
                <span class="stars-display" style="font-size:.85rem;">
                    @for($s = 1; $s <= 5; $s++)
                        <i class="bi {{ $s <= $rating->score ? 'bi-star-fill' : 'bi-star star-empty' }}"></i>
                    @endfor
                </span>
                <span class="rating-card-date">{{ $rating->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        @if($rating->comment)
            <p class="rating-card-text">{{ $rating->comment }}</p>
        @endif
    </div>
@empty
    <p class="text-muted-cine">Aún no hay valoraciones para esta película. ¡Sé el primero!</p>
@endforelse

@endsection
