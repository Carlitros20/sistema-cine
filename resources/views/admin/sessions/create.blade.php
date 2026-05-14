@extends('layouts.app')

@section('title', 'Nueva Sesión')

@section('content')

<nav class="breadcrumb-cine">
    <a href="{{ route('admin.sessions.index') }}"><i class="bi bi-calendar3"></i> Sesiones</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-active">Nueva sesión</span>
</nav>

<div class="form-card">
    <div class="form-card-title">
        <i class="bi bi-calendar-plus text-orange"></i> Nueva Sesión
    </div>

    @if($errors->any())
        <div class="alert-cine alert-cine-danger">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.sessions.store') }}" method="POST">
        @csrf

        <div class="form-cine-group">
            <label class="form-cine-label" for="movie_id">Película</label>
            <select class="form-cine-select" id="movie_id" name="movie_id" required>
                <option value="">Selecciona una película…</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }} ({{ $movie->duration_minutes }} min)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label" for="room_id">Sala</label>
            <select class="form-cine-select" id="room_id" name="room_id" required>
                <option value="">Selecciona una sala…</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }} (Aforo: {{ $room->capacity }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label" for="start_time">Fecha y hora de inicio</label>
            <input type="datetime-local" class="form-cine-input" id="start_time"
                   name="start_time" value="{{ old('start_time') }}" required>
        </div>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap; margin-top:1.5rem;">
            <button type="submit" class="btn-cine btn-cine-success">
                <i class="bi bi-check-lg"></i> Guardar sesión
            </button>
            <a href="{{ route('admin.sessions.index') }}" class="btn-cine btn-cine-ghost">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
