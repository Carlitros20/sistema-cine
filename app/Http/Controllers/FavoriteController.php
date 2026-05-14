<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Movie $movie)
    {
        $user = Auth::user();
        $user->favorites()->toggle($movie->id);

        return back()->with('success',
            $user->favorites()->where('movies.id', $movie->id)->exists()
                ? "'{$movie->title}' añadida a favoritos."
                : "'{$movie->title}' eliminada de favoritos."
        );
    }
}
