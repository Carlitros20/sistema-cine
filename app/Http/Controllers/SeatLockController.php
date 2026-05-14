<?php

namespace App\Http\Controllers;

use App\Models\SeatLock;
use App\Models\MovieSession;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class SeatLockController extends Controller
{
    const MAX_TICKETS_PER_SESSION = 10;

    public function lock(Request $request)
    {
        $request->validate([
            'movie_session_id' => 'required|exists:movie_sessions,id',
            'seat'             => 'required|integer',
        ]);

        $sessionId = $request->movie_session_id;
        $seat      = $request->seat;

        // Bloquear si la venta está cerrada (5 min antes del inicio)
        $session = MovieSession::find($sessionId);
        if ($session && now()->gte(\Carbon\Carbon::parse($session->start_time)->subMinutes(5))) {
            return response()->json([
                'ok'      => false,
                'closed'  => true,
                'message' => 'La venta de entradas ya está cerrada para esta sesión.',
            ], 409);
        }

        $myBought = Ticket::where('movie_session_id', $sessionId)
            ->where('user_id', Auth::id())->count();

        $myLocked = SeatLock::where('movie_session_id', $sessionId)
            ->where('user_id', Auth::id())
            ->where('expires_at', '>', now())->count();

        if (($myBought + $myLocked) >= self::MAX_TICKETS_PER_SESSION) {
            return response()->json([
                'ok'      => false,
                'limit'   => true,
                'message' => 'Has alcanzado el límite de ' . self::MAX_TICKETS_PER_SESSION . ' entradas por sesión.',
            ], 409);
        }

        $booked = Ticket::where('movie_session_id', $sessionId)
            ->where('seat', $seat)->exists();
        if ($booked) {
            return response()->json(['ok' => false, 'message' => 'Butaca ya comprada.'], 409);
        }

        // Borrar bloqueo propio para esa butaca (expirado O activo)
        // Cubre re-selección tras unlock fallido
        SeatLock::where('movie_session_id', $sessionId)
            ->where('seat', $seat)
            ->where('user_id', Auth::id())
            ->delete();

        try {
            $lock = SeatLock::create([
                'movie_session_id' => $sessionId,
                'seat'             => $seat,
                'user_id'          => Auth::id(),
                'expires_at'       => now()->addMinutes(10),
            ]);
            return response()->json([
                'ok'         => true,
                'seat'       => $seat,
                'expires_at' => $lock->expires_at->toISOString(),
            ]);
        } catch (QueryException) {
            return response()->json(['ok' => false, 'message' => 'Butaca bloqueada por otro usuario.'], 409);
        }
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'movie_session_id' => 'required|exists:movie_sessions,id',
            'seat'             => 'nullable|integer',
        ]);

        $query = SeatLock::where('movie_session_id', $request->movie_session_id)
            ->where('user_id', Auth::id());

        if ($request->filled('seat')) {
            $query->where('seat', $request->seat);
        }

        $query->delete();
        return response()->json(['ok' => true]);
    }

    public function status(MovieSession $session)
    {
        $booked = Ticket::where('movie_session_id', $session->id)
            ->pluck('seat')->map(fn($s) => (int)$s)->toArray();

        $locked = SeatLock::where('movie_session_id', $session->id)
            ->where('expires_at', '>', now())
            ->where('user_id', '!=', Auth::id())
            ->pluck('seat')->map(fn($s) => (int)$s)->toArray();

        $myLocks = SeatLock::where('movie_session_id', $session->id)
            ->where('user_id', Auth::id())
            ->where('expires_at', '>', now())
            ->pluck('seat')->map(fn($s) => (int)$s)->toArray();

        $myBought = Ticket::where('movie_session_id', $session->id)
            ->where('user_id', Auth::id())->count();

        return response()->json([
            'booked'      => $booked,
            'locked'      => $locked,
            'my_locks'    => $myLocks,
            'my_count'    => $myBought,
            'max_reached' => ($myBought + count($myLocks)) >= self::MAX_TICKETS_PER_SESSION,
        ]);
    }
}
