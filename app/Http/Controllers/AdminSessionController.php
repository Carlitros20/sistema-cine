<?php

namespace App\Http\Controllers;

use App\Models\MovieSession;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminSessionController extends Controller
{
    // Listado de todas las sesiones para el administrador
    public function index()
    {
        $sesiones = MovieSession::with(['movie', 'room'])->get();
        return view('admin.sessions.index', compact('sesiones'));
    }

    public function create()
    {
        $movies = Movie::all();
        $rooms = Room::all();
        return view('admin.sessions.create', compact('movies', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now',
        ]);

        MovieSession::create($request->all());
        return redirect()->route('admin.sessions.index')->with('success', 'Sesión creada.');
    }

    // Formulario de edición
    public function edit(MovieSession $session)
    {
        $movies = Movie::all();
        $rooms = Room::all();
        return view('admin.sessions.edit', compact('session', 'movies', 'rooms'));
    }

    // Actualizar datos en la BD
    public function update(Request $request, MovieSession $session)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
        ]);

        $session->update($request->all());
        return redirect()->route('admin.sessions.index')->with('success', 'Sesión actualizada.');
    }

    // Borrar sesión de la BD
    public function destroy(MovieSession $session)
    {
        $session->delete();
        return redirect()->route('admin.sessions.index')->with('success', 'Sesión eliminada.');
    }
}