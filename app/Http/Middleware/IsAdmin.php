<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos que esté logueado y que su rol sea 'admin'
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        abort(403, 'Acceso denegado. Área exclusiva para administradores.');
    }
}