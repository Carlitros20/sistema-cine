@extends('layouts.app')

@section('title', 'Crear cuenta')

@section('content')

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-card-title">
            <i class="bi bi-person-plus text-orange"></i> Crear cuenta
        </div>
        <p class="auth-card-subtitle">Únete a Cine CLM en unos segundos</p>

        @if($errors->any())
            <div class="alert-cine alert-cine-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul style="margin:0; padding-left:1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-cine-group">
                <label class="form-cine-label" for="name">Nombre completo</label>
                <input type="text" class="form-cine-input" id="name" name="name"
                       value="{{ old('name') }}" required maxlength="255" autocomplete="name">
            </div>

            <div class="form-cine-group">
                <label class="form-cine-label" for="email">Correo electrónico</label>
                <input type="email" class="form-cine-input" id="email" name="email"
                       value="{{ old('email') }}" required autocomplete="email"
                       placeholder="ejemplo@dominio.com">
                <span class="form-hint">Formato requerido: algo@algo.algo</span>
            </div>

            <div class="form-cine-group">
                <label class="form-cine-label" for="password">Contraseña</label>
                <input type="password" class="form-cine-input" id="password" name="password"
                       required minlength="8" autocomplete="new-password">
                <span class="form-hint">Mínimo 8 caracteres.</span>
            </div>

            <div class="form-cine-group">
                <label class="form-cine-label" for="password_confirmation">Confirmar contraseña</label>
                <input type="password" class="form-cine-input" id="password_confirmation"
                       name="password_confirmation" required minlength="8" autocomplete="new-password">
            </div>

            <button type="submit" class="btn-cine" style="width:100%; justify-content:center;">
                <i class="bi bi-person-check-fill"></i> Registrarse
            </button>
        </form>

        <p style="text-align:center; margin-top:1.25rem; color:var(--c-text-muted); font-size:.88rem;">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </div>
</div>

@endsection
