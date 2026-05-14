@extends('layouts.app')

@section('title', 'Nueva Película')

@section('content')

<nav class="breadcrumb-cine">
    <a href="{{ route('admin.movies.index') }}"><i class="bi bi-camera-video"></i> Películas</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-active">Nueva película</span>
</nav>

<div class="form-card">
    <div class="form-card-title">
        <i class="bi bi-plus-circle text-orange"></i> Nueva Película
    </div>

    @if($errors->any())
        <div class="alert-cine alert-cine-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul style="margin:0; padding-left:1rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-cine-group">
            <label class="form-cine-label" for="title">Título</label>
            <input type="text" class="form-cine-input" id="title" name="title"
                   value="{{ old('title') }}" required maxlength="255">
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label" for="category">Categoría</label>
            <select class="form-cine-input" id="category" name="category">
                <option value="">-- Sin categoría --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label" for="description">Descripción</label>
            <textarea class="form-cine-textarea" id="description" name="description"
                      rows="4" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label" for="duration_minutes">Duración (minutos)</label>
            <input type="number" class="form-cine-input" id="duration_minutes"
                   name="duration_minutes" value="{{ old('duration_minutes') }}"
                   required min="1" max="600">
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label" for="poster">URL del póster (opcional)</label>
            <input type="url" class="form-cine-input" id="poster" name="poster"
                   value="{{ old('poster') }}" placeholder="https://...">
        </div>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap; margin-top:1.5rem;">
            <button type="submit" class="btn-cine btn-cine-success">
                <i class="bi bi-check-lg"></i> Guardar
            </button>
            <a href="{{ route('admin.movies.index') }}" class="btn-cine btn-cine-ghost">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
