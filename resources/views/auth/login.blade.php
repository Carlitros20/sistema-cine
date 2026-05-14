@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-card-title">
            <i class="bi bi-box-arrow-in-right text-orange"></i> Iniciar sesión
        </div>
        <p class="auth-card-subtitle">Accede a tu cuenta para comprar entradas</p>

        @if($errors->any())
            <div class="alert-cine alert-cine-danger">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-cine-group">
                <label class="form-cine-label" for="email">Correo electrónico</label>
                <input type="email" class="form-cine-input" id="email" name="email"
                       value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="form-cine-group">
                <label class="form-cine-label" for="password">Contraseña</label>
                <input type="password" class="form-cine-input" id="password" name="password"
                       required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-cine" style="width:100%; justify-content:center;">
                <i class="bi bi-box-arrow-in-right"></i> Entrar
            </button>
        </form>

        <p style="text-align:center; margin-top:1.25rem; color:var(--c-text-muted); font-size:.88rem;">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}">Regístrate</a>
        </p>
    </div>
</div>

@endsection
