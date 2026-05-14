@extends('layouts.app')

@section('title', 'Estadísticas')

@section('content')

{{-- ═══ HERO con fondo de cine ═══ --}}
<div class="stats-hero">
    <h2><i class="bi bi-bar-chart-fill"></i> Estadísticas</h2>
    <p class="text-muted-cine" style="margin:.4rem 0 0; text-shadow:0 1px 4px rgba(0,0,0,.7);">
        Resumen de actividad del cine en tiempo real
    </p>
</div>

{{-- ═══ CARDS DE RESUMEN ═══ --}}
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-card-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
        <div class="stat-card-value">{{ $totalTickets }}</div>
        <div class="stat-card-label">Entradas vendidas</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><i class="bi bi-calendar3"></i></div>
        <div class="stat-card-value">{{ $totalSessions }}</div>
        <div class="stat-card-label">Sesiones programadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><i class="bi bi-door-open-fill"></i></div>
        <div class="stat-card-value">{{ $rooms->count() }}</div>
        <div class="stat-card-label">Salas activas</div>
    </div>
</div>

{{-- ═══ OCUPACIÓN POR SALA ═══ --}}
<h3 class="stats-section-title">
    <i class="bi bi-door-open"></i> Ocupación por sala
</h3>

@forelse($rooms as $room)
    <div class="occupancy-card">
        <div class="occupancy-card-header">
            <span class="occupancy-room-name">
                <i class="bi bi-door-open-fill"></i>
                {{ $room->name }}
            </span>
            <span class="occupancy-stats-text">
                {{ $room->sold_tickets }} / {{ $room->total_capacity }} butacas
                <strong>{{ $room->occupancy_pct }}%</strong>
            </span>
        </div>
        <div class="occ-bar-wrap">
            <div class="occ-bar" style="width:{{ $room->occupancy_pct }}%;"></div>
        </div>
    </div>
@empty
    <div class="alert-cine alert-cine-info">No hay salas registradas.</div>
@endforelse

{{-- ═══ TOP SESIONES ═══ --}}
<h3 class="stats-section-title" style="margin-top:2.5rem;">
    <i class="bi bi-trophy-fill"></i> Sesiones más vendidas
</h3>

@forelse($topSessions as $i => $session)
    @php
        $rankClass = match($i) {
            0 => 'gold',
            1 => 'silver',
            2 => 'bronze',
            default => 'other',
        };
        $rankIcon = match($i) {
            0 => '🥇',
            1 => '🥈',
            2 => '🥉',
            default => '#' . ($i + 1),
        };
    @endphp
    <div class="top-session">
        <div class="top-session-left">
            <span class="top-session-rank {{ $rankClass }}">{{ $rankIcon }}</span>
            <div>
                <div class="top-session-title">{{ $session->movie->title }}</div>
                <div class="top-session-meta">
                    <span><i class="bi bi-door-open"></i> {{ $session->room->name }}</span>
                    <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
        <div class="top-session-count">
            <div class="top-session-count-value">{{ $session->tickets_count }}</div>
            <div class="top-session-count-label">entradas</div>
        </div>
    </div>
@empty
    <div class="alert-cine alert-cine-info">No hay datos de ventas todavía.</div>
@endforelse

@endsection
