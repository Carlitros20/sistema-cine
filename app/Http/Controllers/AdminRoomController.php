<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class AdminRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount('movieSessions')->orderBy('name')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100', 'unique:rooms,name'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        Room::create($request->only('name', 'capacity'));

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Sala "' . $request->name . '" creada correctamente.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100', 'unique:rooms,name,' . $room->id],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $room->update($request->only('name', 'capacity'));

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Sala actualizada correctamente.');
    }

    public function destroy(Room $room)
    {
        if ($room->movieSessions()->exists()) {
            return back()->withErrors([
                'room' => 'No se puede eliminar la sala porque tiene sesiones asociadas.'
            ]);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Sala eliminada correctamente.');
    }
}
