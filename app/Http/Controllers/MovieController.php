<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieSession;
use App\Models\Room;
use App\Models\Ticket;
use App\Models\SeatLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class MovieController extends Controller
{
    const MAX_TICKETS_PER_SESSION = 10;

    // ── Cartelera ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $cutoff = now()->addMinutes(5);

        $query = Movie::whereHas('movieSessions', function ($q) use ($request, $cutoff) {
            $q->where('start_time', '>', $cutoff);
            if ($request->filled('sala'))  $q->where('room_id', $request->sala);
            if ($request->filled('fecha')) $q->whereDate('start_time', Carbon::parse($request->fecha));
        })->withCount(['movieSessions as sesiones_count' => function ($q) use ($request, $cutoff) {
            $q->where('start_time', '>', $cutoff);
            if ($request->filled('sala'))  $q->where('room_id', $request->sala);
            if ($request->filled('fecha')) $q->whereDate('start_time', Carbon::parse($request->fecha));
        }]);

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('category', $request->categoria);
        }

        $movies      = $query->orderBy('title')->get();
        $rooms       = Room::orderBy('name')->get();
        $categories  = Movie::CATEGORIES;
        $favoriteIds = Auth::check()
            ? Auth::user()->favorites()->pluck('movies.id')->toArray()
            : [];

        return view('movie_sessions.index', compact('movies', 'rooms', 'categories', 'favoriteIds'));
    }

    // ── Detalle de película ───────────────────────────────────────────────────
    public function showMovie(Movie $movie)
    {
        // Solo sesiones con más de 5 min hasta su inicio
        $sesiones = $movie->movieSessions()
            ->with('room')
            ->where('start_time', '>', now()->addMinutes(5))
            ->orderBy('start_time')
            ->get();

        $isFavorite = Auth::check()
            ? Auth::user()->favorites()->where('movies.id', $movie->id)->exists()
            : false;

        return view('movies.show', compact('movie', 'sesiones', 'isFavorite'));
    }

    // ── Selección de butacas ──────────────────────────────────────────────────
    public function show(MovieSession $session)
    {
        $session->load(['movie', 'room', 'tickets']);

        $bookedSeats = $session->tickets->pluck('seat')->map(fn($s) => (int)$s)->toArray();

        $lockedSeats = SeatLock::where('movie_session_id', $session->id)
            ->where('expires_at', '>', now())
            ->when(Auth::check(), fn($q) => $q->where('user_id', '!=', Auth::id()))
            ->pluck('seat')
            ->map(fn($s) => (int)$s)
            ->toArray();

        $myLock = Auth::check()
            ? SeatLock::where('movie_session_id', $session->id)
                ->where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->first()
            : null;

        // Cuántas entradas lleva compradas el usuario en esta sesión
        $myTicketsCount = Auth::check()
            ? Ticket::where('movie_session_id', $session->id)
                ->where('user_id', Auth::id())
                ->count()
            : 0;

        $maxReached = $myTicketsCount >= self::MAX_TICKETS_PER_SESSION;

        // Cierre de venta: 5 min antes del inicio de la sesión
        $saleClosed = now()->gte(
            Carbon::parse($session->start_time)->subMinutes(5)
        );

        return view('movie_sessions.show', compact(
            'session', 'bookedSeats', 'lockedSeats', 'myLock',
            'myTicketsCount', 'maxReached', 'saleClosed'
        ));
    }

    // ── Compra múltiple ───────────────────────────────────────────────────────
    public function buyTicket(Request $request)
    {
        $request->validate([
            'movie_session_id' => 'required|exists:movie_sessions,id',
            'seats'            => 'required|array|min:1|max:' . self::MAX_TICKETS_PER_SESSION,
            'seats.*'          => 'required|integer',
        ]);

        $sessionId = $request->movie_session_id;
        $seats     = $request->seats;

        // Comprobar que la venta no esté cerrada (5 min antes del inicio)
        $session = MovieSession::findOrFail($sessionId);
        if (now()->gte(Carbon::parse($session->start_time)->subMinutes(5))) {
            return back()->withErrors([
                'seat' => 'La venta de entradas ya está cerrada para esta sesión (5 min antes del inicio).'
            ]);
        }

        // Límite: compradas + las que intenta comprar ahora
        $myCount = Ticket::where('movie_session_id', $sessionId)
            ->where('user_id', Auth::id())->count();

        if (($myCount + count($seats)) > self::MAX_TICKETS_PER_SESSION) {
            return back()->withErrors([
                'seat' => 'Superarías el límite de ' . self::MAX_TICKETS_PER_SESSION . ' entradas por sesión.'
            ]);
        }

        // Verificar que todas tienen bloqueo válido del usuario
        foreach ($seats as $seat) {
            $lock = SeatLock::where('movie_session_id', $sessionId)
                ->where('seat', $seat)
                ->where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->exists();

            if (!$lock) {
                return back()->withErrors([
                    'seat' => "La reserva de la butaca {$seat} ha expirado. Selecciónala de nuevo."
                ]);
            }
        }

        $ticketIds = [];
        try {
            foreach ($seats as $seat) {
                $ticket = Ticket::create([
                    'user_id'          => Auth::id(),
                    'movie_session_id' => $sessionId,
                    'seat'             => $seat,
                ]);
                $ticketIds[] = $ticket->id;

                // Liberar bloqueo
                SeatLock::where('movie_session_id', $sessionId)
                    ->where('seat', $seat)
                    ->where('user_id', Auth::id())
                    ->delete();
            }

            session(['last_ticket_ids' => $ticketIds]);
            return redirect()->route('tickets.confirmacion');

        } catch (QueryException) {
            return back()->withErrors([
                'seat' => 'Una o más butacas acaban de ser reservadas por otro usuario. Vuelve a seleccionar.'
            ]);
        }
    }

    // ── Confirmación múltiple ─────────────────────────────────────────────────
    public function confirmacion()
    {
        $ticketIds = session('last_ticket_ids');

        if (!$ticketIds || empty($ticketIds)) {
            return redirect()->route('cartelera');
        }

        $tickets = Ticket::with(['movieSession.movie', 'movieSession.room'])
            ->whereIn('id', $ticketIds)
            ->get();

        session()->forget('last_ticket_ids');

        return view('tickets.confirmacion', compact('tickets'));
    }

    // ── Devolución ────────────────────────────────────────────────────────────
    public function devolverTicket(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        // Se permite devolver hasta 5 min DESPUÉS del inicio de la sesión.
        // diffInMinutes con (otra_fecha, false) devuelve negativo si la otra fecha ya pasó.
        $minutosHastaInicio = now()->diffInMinutes(
            Carbon::parse($ticket->movieSession->start_time), false
        );

        // minutosHastaInicio < -5 significa que han pasado más de 5 min desde el inicio
        if ($minutosHastaInicio < -5) {
            return back()->withErrors([
                'devolucion' => 'No se puede devolver una entrada pasados 5 minutos del inicio de la sesión.'
            ]);
        }

        $ticket->delete();

        return back()->with('success', 'Entrada devuelta correctamente.');
    }

    // ── Contacto ──────────────────────────────────────────────────────────────
    public function contacto()
    {
        return view('contacto');
    }
}
