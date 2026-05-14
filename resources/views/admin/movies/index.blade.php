@extends('layouts.app')

@section('title', 'Gestionar Películas')

@section('content')

<div class="admin-hero admin-hero-movies">
    <div class="admin-hero-text">
        <h2><i class="bi bi-camera-video"></i> Gestión de Películas</h2>
        <p>Añade, edita o elimina películas del catálogo del cine</p>
    </div>
    <div class="admin-hero-actions">
        <a href="{{ route('admin.movies.create') }}" class="btn-cine">
            <i class="bi bi-plus-lg"></i> Nueva película
        </a>
    </div>
</div>

<div class="table-wrap">
    <table class="table-cine">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Duración</th>
                <th>Sesiones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movies as $movie)
                <tr>
                    <td>
                        <a href="{{ route('movies.show', $movie->id) }}" class="text-orange">
                            {{ $movie->title }}
                        </a>
                    </td>
                    <td>
                        @if($movie->category)
                            <span class="meta-chip meta-chip-accent">{{ $movie->category }}</span>
                        @else
                            <span class="text-muted-cine" style="font-size:.78rem;">—</span>
                        @endif
                    </td>
                    <td>{{ $movie->duration_minutes }} min</td>
                    <td>
                        <span class="meta-chip">{{ $movie->movie_sessions_count }}</span>
                    </td>
                    <td style="display:flex; gap:.5rem; flex-wrap:wrap;">
                        <a href="{{ route('admin.movies.edit', $movie->id) }}"
                           class="btn-cine btn-cine-sm" style="background:var(--c-warning); color:#000;">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar «{{ $movie->title }}»? Se borrarán también sus sesiones y entradas.')">
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
                    <td colspan="5" style="text-align:center; color:var(--c-text-muted); padding:2rem;">
                        No hay películas registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
