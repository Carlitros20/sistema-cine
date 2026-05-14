@extends('layouts.app')

@section('title', 'Contacto')

@section('content')

<div class="contact-card">
    <h2 style="margin-bottom:.5rem;"><i class="bi bi-envelope text-orange"></i> Contacto</h2>
    <p class="text-muted-cine" style="margin-bottom:2rem;">
        Información del proyecto y de sus desarrolladores.
    </p>

    {{-- ── Tarjetas de creadores ────────────────────────────────────── --}}
    <h3 style="margin-bottom:1rem; font-size:1rem; text-transform:uppercase; letter-spacing:1px; color:var(--c-text-muted);">
        <i class="bi bi-people-fill"></i> Creadores
    </h3>

    <div class="creators-grid">
        <div class="creator-card">
            <div class="creator-avatar"><i class="bi bi-person-circle"></i></div>
            <div class="creator-name">Carlos Manuel Pérez Molina</div>
            <div class="creator-role">Desarrollador</div>
        </div>

        <div class="creator-card">
            <div class="creator-avatar"><i class="bi bi-person-circle"></i></div>
            <div class="creator-name">Mario Muñoz Gutiérrez</div>
            <div class="creator-role">Desarrollador</div>
        </div>

        <div class="creator-card">
            <div class="creator-avatar"><i class="bi bi-person-circle"></i></div>
            <div class="creator-name">Luis Pérez Velasco</div>
            <div class="creator-role">Desarrollador</div>
        </div>
    </div>

    {{-- ── Información del proyecto ────────────────────────────────── --}}
    <h3 style="margin:2.5rem 0 1rem; font-size:1rem; text-transform:uppercase; letter-spacing:1px; color:var(--c-text-muted);">
        <i class="bi bi-info-circle-fill"></i> Información del proyecto
    </h3>

    <table class="contact-table">
        <tr>
            <th><i class="bi bi-mortarboard"></i> Asignatura</th>
            <td>Tecnologías Web</td>
        </tr>
        <tr>
            <th><i class="bi bi-building"></i> Universidad</th>
            <td>Universidad de Granada (UGR)</td>
        </tr>
        <tr>
            <th><i class="bi bi-film"></i> Proyecto</th>
            <td>Sistema de Gestión de Cine</td>
        </tr>
        <tr>
            <th><i class="bi bi-code-slash"></i> Tecnologías</th>
            <td>Laravel · MySQL · CSS propio</td>
        </tr>
        <tr>
            <th><i class="bi bi-calendar-event"></i> Curso</th>
            <td>2025-2026</td>
        </tr>
    </table>

    <div style="margin-top:2rem; display:flex; gap:.75rem; flex-wrap:wrap;">
        <a href="{{ asset('como_se_hizo.pdf') }}" class="btn-cine btn-cine-ghost" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Ver informe (como_se_hizo.pdf)
        </a>
    </div>
</div>

@endsection
