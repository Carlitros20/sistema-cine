@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')

{{-- ═══ HERO con avatar y datos ═══ --}}
<div class="profile-hero">
    <div class="profile-hero-inner">
        <div class="profile-avatar-lg">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="profile-info-block">
            <h1>{{ Auth::user()->name }}</h1>
            <div class="profile-email">
                <i class="bi bi-envelope"></i> {{ Auth::user()->email }}
            </div>
            <span class="badge-role">{{ Auth::user()->role }}</span>
        </div>
    </div>
</div>

{{-- ═══ STATS PERSONALES ═══ --}}
<div class="profile-stats">
    <div class="profile-stat">
        <div class="profile-stat-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
        <div>
            <div class="profile-stat-value">{{ $stats['total_tickets'] }}</div>
            <div class="profile-stat-label">Entradas compradas</div>
        </div>
    </div>
    <div class="profile-stat">
        <div class="profile-stat-icon"><i class="bi bi-calendar-event-fill"></i></div>
        <div>
            <div class="profile-stat-value">{{ $stats['upcoming'] }}</div>
            <div class="profile-stat-label">Próximas sesiones</div>
        </div>
    </div>
    <div class="profile-stat">
        <div class="profile-stat-icon"><i class="bi bi-film"></i></div>
        <div>
            <div class="profile-stat-value">{{ $stats['unique_movies'] }}</div>
            <div class="profile-stat-label">Películas vistas</div>
        </div>
    </div>
    <div class="profile-stat">
        <div class="profile-stat-icon"><i class="bi bi-heart-fill"></i></div>
        <div>
            <div class="profile-stat-value">{{ $stats['favorites'] }}</div>
            <div class="profile-stat-label">Favoritos</div>
        </div>
    </div>
</div>

{{-- ═══ TABS ═══ --}}
<div class="tabs-cine">
    <button class="tab-btn active" data-tab="tab-tickets">
        <i class="bi bi-ticket-perforated"></i> Mis entradas
        <span class="badge-role" style="background:var(--c-bg3); color:var(--c-text-muted);">
            {{ $tickets->count() }}
        </span>
    </button>
    <button class="tab-btn" data-tab="tab-favorites">
        <i class="bi bi-heart-fill"></i> Favoritos
        <span class="badge-role" style="background:var(--c-bg3); color:var(--c-text-muted);">
            {{ $favorites->count() }}
        </span>
    </button>
</div>

{{-- ── TAB: ENTRADAS ─────────────────────────────────────────── --}}
<div class="tab-panel active" id="tab-tickets">
    @forelse($tickets as $ticket)
        @php
            $startTime = \Carbon\Carbon::parse($ticket->movieSession->start_time);
            $now       = now();
            // Devolución permitida hasta 5 min DESPUÉS del inicio
            $minutesAfterStart = $startTime->diffInMinutes($now, false); // negativo si aún no empieza
            $canReturn = $minutesAfterStart <= 5;
            $past = $startTime->lt($now->copy()->subMinutes(5));
        @endphp
        <div class="ticket-card {{ $past ? 'ticket-card-past' : '' }}">
            <div>
                <div class="ticket-movie">{{ $ticket->movieSession->movie->title }}</div>
                <div class="ticket-meta">
                    <span><i class="bi bi-door-open"></i> {{ $ticket->movieSession->room->name }}</span>
                    <span><i class="bi bi-calendar-event"></i> {{ $startTime->format('d/m/Y') }}</span>
                    <span><i class="bi bi-clock"></i> {{ $startTime->format('H:i') }}</span>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
                <span class="seat-badge">Butaca {{ $ticket->seat }}</span>
                <span class="localizador">Loc. #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</span>

                @if($canReturn)
                    <form action="{{ route('tickets.devolver', $ticket->id) }}" method="POST"
                          onsubmit="return confirm('¿Seguro que quieres devolver esta entrada?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-cine btn-cine-danger btn-cine-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Devolver
                        </button>
                    </form>
                @else
                    <span class="text-muted-cine" style="font-size:.78rem;">
                        <i class="bi bi-lock"></i> No se puede devolver
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div class="alert-cine alert-cine-info">
            <i class="bi bi-info-circle"></i>
            Aún no has comprado ninguna entrada.
            <a href="{{ route('cartelera') }}" style="margin-left:.4rem;">Ver cartelera</a>
        </div>
    @endforelse
</div>

{{-- ── TAB: FAVORITOS ────────────────────────────────────────── --}}
<div class="tab-panel" id="tab-favorites">
    @if($favorites->isEmpty())
        <div class="alert-cine alert-cine-info">
            <i class="bi bi-heart"></i>
            Todavía no tienes películas en favoritos.
            <a href="{{ route('cartelera') }}" style="margin-left:.4rem;">Ver cartelera</a>
        </div>
    @else
        <div class="favorites-grid">
            @foreach($favorites as $movie)
                <a href="{{ route('movies.show', $movie->id) }}" class="fav-card">
                    @if($movie->poster)
                        <img src="{{ str_starts_with($movie->poster, 'http') ? $movie->poster : asset('storage/' . $movie->poster) }}"
                             alt="{{ $movie->title }}"
                             class="fav-card-img">
                    @else
                        <div class="fav-card-placeholder">
                            <i class="bi bi-camera-reels"></i>
                        </div>
                    @endif
                    <div class="fav-card-body">
                        <div class="fav-card-title">{{ $movie->title }}</div>
                        <div style="font-size:.75rem; color:var(--c-text-muted); margin-top:.25rem;">
                            <i class="bi bi-clock"></i> {{ $movie->duration_minutes }} min
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });
</script>
@endpush
