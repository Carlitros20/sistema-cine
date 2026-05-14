@extends('layouts.app')

@section('title', 'Gestionar Sesiones')

@section('content')

<div class="admin-hero admin-hero-sessions">
    <div class="admin-hero-text">
        <h2><i class="bi bi-calendar3"></i> Gestión de Sesiones</h2>
        <p>Programa, edita y elimina las sesiones del cine</p>
    </div>
    <div class="admin-hero-actions">
        <a href="{{ route('admin.sessions.create') }}" class="btn-cine">
            <i class="bi bi-plus-lg"></i> Nueva sesión
        </a>
    </div>
</div>

<div class="table-wrap">
    <table class="table-cine">
        <thead>
            <tr>
                <th>Película</th>
                <th>Sala</th>
                <th>Fecha y hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sesiones as $sesion)
                <tr>
                    <td>
                        <a href="{{ route('movies.show', $sesion->movie->id) }}" class="text-orange">
                            {{ $sesion->movie->title }}
                        </a>
                    </td>
                    <td>{{ $sesion->room->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($sesion->start_time)->format('d/m/Y H:i') }}</td>
                    <td style="display:flex; gap:.5rem; flex-wrap:wrap;">
                        <a href="{{ route('admin.sessions.edit', $sesion->id) }}"
                           class="btn-cine btn-cine-sm" style="background:var(--c-warning); color:#000;">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('admin.sessions.destroy', $sesion->id) }}" method="POST"
                              onsubmit="return confirm('¿Borrar esta sesión?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cine btn-cine-danger btn-cine-sm">
                                <i class="bi bi-trash"></i> Borrar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--c-text-muted); padding:2rem;">
                        No hay sesiones registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
