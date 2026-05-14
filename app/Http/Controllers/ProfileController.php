<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Historial de entradas con relaciones necesarias
        $tickets = $user->tickets()
            ->with(['movieSession.movie', 'movieSession.room'])
            ->latest()
            ->get();

        // Películas marcadas como favoritas
        $favorites = $user->favorites()
            ->orderBy('title')
            ->get();

        // Stats personales para mostrar en el banner
        $stats = [
            'total_tickets'  => $tickets->count(),
            'upcoming'       => $tickets->filter(fn($t) =>
                \Carbon\Carbon::parse($t->movieSession->start_time)->isFuture()
            )->count(),
            'unique_movies'  => $tickets->pluck('movieSession.movie.id')->unique()->count(),
            'favorites'      => $favorites->count(),
        ];

        return view('profile.index', compact('tickets', 'favorites', 'stats'));
    }
}
