@extends('layouts.app')

@section('title', 'Editar Sesión')

@section('content')

<nav class="breadcrumb-cine">
    <a href="{{ route('admin.sessions.index') }}"><i class="bi bi-calendar3"></i> Sesiones</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-active">Editar sesión</span>
</nav>

<div class="form-card">
    <div class="form-card-title">
        <i class="bi bi-pencil text-orange"></i> Editar sesión: {{ $session->movie->title }}
    </div>

    @if($errors->any())
        <div class="alert-cine alert-cine-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul style="margin:0; padding-left:1rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sessions.update', $session->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-cine-group">
            <label class="form-cine-label">Película</label>
            <select name="movie_id" class="form-cine-select" required>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}"
                        {{ $session->movie_id == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }} ({{ $movie->duration_minutes }} min)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label">Sala</label>
            <select name="room_id" class="form-cine-select" required>
                <option value="">Selecciona una sala…</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}"
                        {{ $session->room_id == $room->id ? 'selected' : '' }}>
                        {{ $room->name }} (Aforo: {{ $room->capacity }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label">Fecha y hora</label>
            <input type="datetime-local" name="start_time" class="form-cine-input" required
                   value="{{ \Carbon\Carbon::parse($session->start_time)->format('Y-m-d\TH:i') }}">
        </div>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap; margin-top:1.5rem;">
            <button type="submit" class="btn-cine btn-cine-success">
                <i class="bi bi-check-lg"></i> Actualizar
            </button>
            <a href="{{ route('admin.sessions.index') }}" class="btn-cine btn-cine-ghost">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
