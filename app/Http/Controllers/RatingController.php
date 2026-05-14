<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        $request->validate([
            'score'   => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // updateOrCreate: si ya valoró esta película, actualiza; si no, crea
        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'movie_id' => $movie->id],
            ['score' => $request->score, 'comment' => $request->comment]
        );

        return back()->with('success', 'Tu valoración ha sido guardada.');
    }

    public function destroy(Movie $movie)
    {
        Rating::where('user_id', Auth::id())
              ->where('movie_id', $movie->id)
              ->delete();

        return back()->with('success', 'Valoración eliminada.');
    }
}
