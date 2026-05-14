<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\MovieSession;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class AdminStatsController extends Controller
{
    public function index()
    {
        // ── Ocupación por sala ────────────────────────────────────────────
        // Para cada sala: capacidad total acumulada (suma de capacidades de todas
        // sus sesiones) vs entradas vendidas
        $rooms = Room::withCount('movieSessions')->get()->map(function ($room) {
            $totalCapacity = $room->capacity * $room->movie_sessions_count;
            $soldTickets   = Ticket::whereHas('movieSession', fn($q) =>
                $q->where('room_id', $room->id)
            )->count();

            $room->total_capacity = $totalCapacity;
            $room->sold_tickets   = $soldTickets;
            $room->occupancy_pct  = $totalCapacity > 0
                ? round($soldTickets / $totalCapacity * 100, 1)
                : 0;
            return $room;
        });

        // ── Sesiones más vendidas (top 10) ───────────────────────────────
        $topSessions = MovieSession::with(['movie', 'room'])
            ->withCount('tickets')
            ->orderByDesc('tickets_count')
            ->limit(10)
            ->get();

        // ── Totales globales ─────────────────────────────────────────────
        $totalTickets = Ticket::count();
        $totalSessions = MovieSession::count();

        return view('admin.stats.index', compact(
            'rooms', 'topSessions', 'totalTickets', 'totalSessions'
        ));
    }
}
