@extends('layouts.app')

@section('title', 'Editar Sala')

@section('content')

<nav class="breadcrumb-cine">
    <a href="{{ route('admin.rooms.index') }}"><i class="bi bi-door-open"></i> Salas</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-active">Editar: {{ $room->name }}</span>
</nav>

<div class="form-card">
    <div class="form-card-title">
        <i class="bi bi-pencil text-orange"></i> Editar Sala
    </div>

    @if($errors->any())
        <div class="alert-cine alert-cine-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul style="margin:0; padding-left:1rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-cine-group">
            <label class="form-cine-label" for="name">Nombre de la sala</label>
            <input type="text" class="form-cine-input" id="name" name="name"
                   value="{{ old('name', $room->name) }}" required maxlength="100">
        </div>

        <div class="form-cine-group">
            <label class="form-cine-label" for="capacity">Aforo (número de butacas)</label>
            <input type="number" class="form-cine-input" id="capacity" name="capacity"
                   value="{{ old('capacity', $room->capacity) }}" required min="1" max="500">
            <span class="form-hint">Las butacas se distribuyen en filas de 10.</span>
        </div>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap; margin-top:1.5rem;">
            <button type="submit" class="btn-cine btn-cine-success">
                <i class="bi bi-check-lg"></i> Guardar cambios
            </button>
            <a href="{{ route('admin.rooms.index') }}" class="btn-cine btn-cine-ghost">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
