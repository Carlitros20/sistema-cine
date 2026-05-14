<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMovieController extends Controller
{
    public function index()
    {
        $movies = Movie::withCount('movieSessions')->orderBy('title')->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        $categories = Movie::CATEGORIES;
        return view('admin.movies.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'poster'           => 'nullable|url',
            'category'         => ['nullable', Rule::in(Movie::CATEGORIES)],
        ]);

        Movie::create($data);

        return redirect()->route('admin.movies.index')->with('success', 'Película creada correctamente.');
    }

    public function edit(Movie $movie)
    {
        $categories = Movie::CATEGORIES;
        return view('admin.movies.edit', compact('movie', 'categories'));
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'poster'           => 'nullable|url',
            'category'         => ['nullable', Rule::in(Movie::CATEGORIES)],
        ]);

        $movie->update($data);

        return redirect()->route('admin.movies.index')->with('success', 'Película actualizada correctamente.');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Película eliminada.');
    }
}
