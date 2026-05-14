@extends('layouts.app')

@section('title', 'Gestionar Salas')

@section('content')

<div class="admin-hero admin-hero-rooms">
    <div class="admin-hero-text">
        <h2><i class="bi bi-door-open"></i> Gestión de Salas</h2>
        <p>Configura las salas del cine y su aforo</p>
    </div>
    <div class="admin-hero-actions">
        <a href="{{ route('admin.rooms.create') }}" class="btn-cine">
            <i class="bi bi-plus-lg"></i> Nueva sala
        </a>
    </div>
</div>

@if($errors->has('room'))
    <div class="alert-cine alert-cine-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first('room') }}
    </div>
@endif

<div class="table-wrap">
    <table class="table-cine">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Aforo</th>
                <th>Sesiones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
                <tr>
                    <td><strong>{{ $room->name }}</strong></td>
                    <td>{{ $room->capacity }} butacas</td>
                    <td>
                        <span class="meta-chip meta-chip-accent">
                            {{ $room->movie_sessions_count }} sesiones
                        </span>
                    </td>
                    <td style="display:flex; gap:.5rem; flex-wrap:wrap;">
                        <a href="{{ route('admin.rooms.edit', $room->id) }}"
                           class="btn-cine btn-cine-sm" style="background:var(--c-warning); color:#000;">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar la sala {{ $room->name }}? Solo es posible si no tiene sesiones.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cine btn-cine-danger btn-cine-sm">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--c-text-muted); padding:2rem;">
                        No hay salas registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
