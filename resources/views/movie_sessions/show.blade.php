@extends('layouts.app')

@section('title', 'Seleccionar butaca')

@section('content')

<nav class="breadcrumb-cine">
    <a href="{{ route('cartelera') }}"><i class="bi bi-film"></i> Cartelera</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('movies.show', $session->movie->id) }}">{{ $session->movie->title }}</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-active">Seleccionar butaca</span>
</nav>

<h2 style="margin-bottom:.4rem;">{{ $session->movie->title }}</h2>
<p class="text-muted-cine" style="margin-bottom:1.5rem; display:flex; flex-wrap:wrap; gap:.75rem;">
    <span><i class="bi bi-door-open"></i> {{ $session->room->name }}</span>
    <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('d/m/Y') }}</span>
    <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</span>
    <span><i class="bi bi-hourglass-split"></i> {{ $session->movie->duration_minutes }} min</span>
</p>

@if(isset($saleClosed) && $saleClosed)
    <div class="alert-cine alert-cine-danger" style="max-width:520px; margin:0 auto 1rem;">
        <i class="bi bi-lock-fill"></i>
        La venta de entradas para esta sesión está cerrada (5 min antes del inicio).
    </div>
@endif

@auth
    {{-- Contador de entradas estilo Kinepolis --}}
    <div class="ticket-counter" id="ticketCounter">
        @if($maxReached)
            <div class="ticket-counter-bar ticket-counter-full">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Has alcanzado el límite de {{ $myTicketsCount }} / 10 entradas para esta sesión.
            </div>
        @else
            <div class="ticket-counter-bar">
                <i class="bi bi-ticket-perforated"></i>
                Entradas compradas para esta sesión:
                <strong id="counterValue">{{ $myTicketsCount }}</strong> / 10
                <div class="counter-dots">
                    @for($d = 1; $d <= 10; $d++)
                        <span class="counter-dot {{ $d <= $myTicketsCount ? 'filled' : '' }}" data-dot="{{ $d }}"></span>
                    @endfor
                </div>
            </div>
        @endif
    </div>
    <div class="seat-timer" id="seatTimer"></div>
@endauth

@guest
    <div class="alert-cine alert-cine-warning" style="margin-bottom:1rem;">
        <i class="bi bi-lock"></i>
        Debes <a href="{{ route('login') }}">iniciar sesión</a> para comprar entradas.
    </div>
@endguest

{{--
    DISTRIBUCIÓN FIJA: cinema-room-outer permite scroll horizontal.
    cinema-room-inner tiene ancho fijo 520px → el grid NUNCA cambia forma.
--}}
<div class="cinema-room-outer">
    <div class="cinema-room-inner">

        <div class="cinema-screen"></div>

        @auth
        <form action="{{ route('tickets.buy') }}" method="POST" id="buyForm">
            @csrf
            <input type="hidden" name="movie_session_id" value="{{ $session->id }}">
            {{-- Los seats se añaden dinámicamente como inputs hidden por JS --}}
        <div id="seatsInputContainer"></div>

        <div class="seats-grid" id="seatsGrid">
            @php
                $capacity     = $session->room->capacity;
                $cols         = 10;
                $lastRowSeats = $capacity % $cols;
                $startCol     = $lastRowSeats > 0 ? intdiv($cols - $lastRowSeats, 2) + 1 : 1;
                $firstOfLastRow = $lastRowSeats > 0 ? $capacity - $lastRowSeats + 1 : -1;
            @endphp
            @for($i = 1; $i <= $capacity; $i++)
                @php
                    $isBooked = in_array($i, $bookedSeats);
                    $isLocked = in_array($i, $lockedSeats);
                    $isMine   = $myLock && (int)$myLock->seat === $i;

                    if ($isBooked)      $cls = 'seat-booked';
                    elseif ($isLocked)  $cls = 'seat-locked';
                    elseif ($isMine)    $cls = 'seat-mine';
                    else                $cls = 'seat-free';

                    $colStyle = ($i === $firstOfLastRow) ? 'grid-column-start:' . $startCol . ';' : '';
                @endphp
                <button
                    type="button"
                    class="seat-btn {{ $cls }}"
                    data-seat="{{ $i }}"
                    style="{{ $colStyle }}"
                    {{ ($isBooked || $isLocked || (isset($saleClosed) && $saleClosed)) ? 'disabled' : '' }}
                    title="Butaca {{ $i }}{{ $isBooked ? ' (comprada)' : ($isLocked ? ' (reservada)' : '') }}">
                    {{ $i }}
                </button>
            @endfor
        </div>
        </form>
        @endauth

        @guest
        <div class="seats-grid">
            @php
                $capacity       = $session->room->capacity;
                $cols           = 10;
                $lastRowSeats   = $capacity % $cols;
                $startCol       = $lastRowSeats > 0 ? intdiv($cols - $lastRowSeats, 2) + 1 : 1;
                $firstOfLastRow = $lastRowSeats > 0 ? $capacity - $lastRowSeats + 1 : -1;
            @endphp
            @for($i = 1; $i <= $capacity; $i++)
                @php
                    $isBooked  = in_array($i, $bookedSeats);
                    $isLocked  = in_array($i, $lockedSeats);
                    $colStyle  = ($i === $firstOfLastRow) ? 'grid-column-start:' . $startCol . ';' : '';
                @endphp
                <button type="button"
                        class="seat-btn {{ $isBooked ? 'seat-booked' : ($isLocked ? 'seat-locked' : 'seat-free') }}"
                        style="{{ $colStyle }}"
                        disabled>
                    {{ $i }}
                </button>
            @endfor
        </div>
        @endguest

    </div>{{-- /cinema-room-inner --}}
</div>{{-- /cinema-room-outer --}}

{{-- Leyenda fuera del scroll, centrada en la página --}}
<div class="seats-legend" style="margin-top:1.25rem;">
    <span class="legend-item"><span class="legend-dot" style="background:#2D6A4F;"></span> Libre</span>
    <span class="legend-item"><span class="legend-dot" style="background:#5C4033;"></span> Reservada</span>
    <span class="legend-item"><span class="legend-dot" style="background:#4A1942;"></span> Comprada</span>
    <span class="legend-item"><span class="legend-dot" style="background:var(--c-orange);"></span> Mi selección</span>
</div>

@auth
    @if($errors->has('seat'))
        <div class="alert-cine alert-cine-danger" style="max-width:520px; margin:0 auto .75rem;">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first('seat') }}
        </div>
    @endif

    <div style="max-width:520px; margin:0 auto;">
        <button type="submit" form="buyForm" class="btn-cine" id="buyBtn"
                style="width:100%; justify-content:center;"
                disabled>
            <i class="bi bi-cart-check"></i>
            Confirmar compra (<span id="buyBtnCount">0</span> butaca<span id="buyBtnPlural">s</span>)
        </button>
    </div>
@endauth

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const SESSION_ID  = {{ $session->id }};
    const LOCK_URL    = "{{ route('seats.lock') }}";
    const UNLOCK_URL  = "{{ route('seats.unlock') }}";
    const RELEASE_URL = "{{ route('seats.release') }}";
    const STATUS_URL  = "{{ route('seats.status', $session->id) }}";
    const CSRF        = document.querySelector('meta[name="csrf-token"]')?.content
                     || "{{ csrf_token() }}";
    const MAX         = 10;

    const grid        = document.getElementById('seatsGrid');
    const buyBtn      = document.getElementById('buyBtn');
    const inputsCont  = document.getElementById('seatsInputContainer');
    const timerEl     = document.getElementById('seatTimer');
    const counterVal  = document.getElementById('counterValue');
    const btnCount    = document.getElementById('buyBtnCount');
    const btnPlural   = document.getElementById('buyBtnPlural');

    if (!grid) return;

    // Map: seat (int) -> { btn, expiry (ms), intervalId }
    const selected = new Map();

    let maxReached = {{ $maxReached ? 'true' : 'false' }};
    let myBought   = {{ $myTicketsCount }};

    // ── Reconstruir inputs hidden ───────────────────────────────
    function rebuildInputs() {
        inputsCont.innerHTML = '';
        selected.forEach((_, seat) => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'seats[]';
            inp.value = seat;
            inputsCont.appendChild(inp);
        });
    }

    // ── Actualizar contador y botón ─────────────────────────────
    function updateCounter() {
        const total = myBought + selected.size;
        if (counterVal) counterVal.textContent = total;
        if (btnCount)   btnCount.textContent   = selected.size;
        if (btnPlural)  btnPlural.textContent  = selected.size === 1 ? '' : 's';

        document.querySelectorAll('.counter-dot').forEach(dot => {
            const n = parseInt(dot.dataset.dot);
            dot.classList.remove('filled');
            dot.style.opacity = '1';
            if (n <= myBought) {
                dot.classList.add('filled');
            } else if (n <= total) {
                dot.classList.add('filled');
                dot.style.opacity = '.55';
            }
        });

        maxReached      = total >= MAX;
        buyBtn.disabled = selected.size === 0;
    }

    // ── Timer del que expira antes ──────────────────────────────
    function updateTimerDisplay() {
        if (selected.size === 0) { timerEl.textContent = ''; return; }
        let minExpiry = Infinity;
        selected.forEach(({ expiry }) => { if (expiry < minExpiry) minExpiry = expiry; });
        const secs = Math.round((minExpiry - Date.now()) / 1000);
        if (secs <= 0) return;
        const m = String(Math.floor(secs / 60)).padStart(2, '0');
        const s = String(secs % 60).padStart(2, '0');
        timerEl.textContent = `⏱ Reserva temporal — expira en ${m}:${s}`;
    }
    setInterval(updateTimerDisplay, 1000);

    // ── Timer individual: quitar butaca al expirar ──────────────
    function startSeatTimer(seat, expiry) {
        return setInterval(() => {
            if (Date.now() >= expiry) {
                clearInterval(selected.get(seat)?.intervalId);
                const entry = selected.get(seat);
                if (entry) {
                    entry.btn.classList.remove('seat-mine');
                    entry.btn.classList.add('seat-free');
                    entry.btn.disabled = false;
                }
                selected.delete(seat);
                rebuildInputs();
                updateCounter();
                updateTimerDisplay();
            }
        }, 1000);
    }

    // ── Desbloquear una butaca concreta en el servidor ──────────
    // Usamos POST con _method=DELETE para que Laravel lea el body correctamente
    async function unlockSeat(seat) {
        try {
            const body = new URLSearchParams({
                _token:           CSRF,
                movie_session_id: SESSION_ID,
                seat:             seat,
            });
            await fetch(RELEASE_URL, { method: 'POST', body });
        } catch (_) {}
    }

    // ── Clic en butaca ──────────────────────────────────────────
    grid.addEventListener('click', async function (e) {
        const btn = e.target.closest('.seat-btn');
        if (!btn || btn.disabled) return;

        const seat = parseInt(btn.dataset.seat);

        // Ya seleccionada → deseleccionar
        if (selected.has(seat)) {
            const entry = selected.get(seat);
            clearInterval(entry.intervalId);
            selected.delete(seat);
            btn.classList.remove('seat-mine');
            btn.classList.add('seat-free');
            rebuildInputs();
            updateCounter();
            unlockSeat(seat);
            return;
        }

        // Límite alcanzado
        if (maxReached) {
            timerEl.textContent = '⚠ Has alcanzado el límite de ' + MAX + ' entradas para esta sesión.';
            return;
        }

        // Bloquear en servidor
        let res, data;
        try {
            res  = await fetch(LOCK_URL, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body:    JSON.stringify({ movie_session_id: SESSION_ID, seat }),
            });
            data = await res.json();
        } catch (_) {
            timerEl.textContent = 'Error de red. Inténtalo de nuevo.';
            return;
        }

        if (data.ok) {
            btn.classList.remove('seat-free');
            btn.classList.add('seat-mine');
            const expiry     = new Date(data.expires_at).getTime();
            const intervalId = startSeatTimer(seat, expiry);
            selected.set(seat, { btn, expiry, intervalId });
            rebuildInputs();
            updateCounter();
        } else if (data.limit) {
            timerEl.textContent = data.message;
        } else {
            // Butaca ocupada por otro
            btn.classList.remove('seat-free');
            btn.classList.add('seat-locked');
            btn.disabled = true;
            timerEl.textContent = data.message || 'Esa butaca ya no está disponible.';
        }
    });

    // ── Al salir: liberar TODAS las butacas del usuario ─────────
    // Sin seat específico → el servidor borra todos los bloqueos del usuario
    window.addEventListener('beforeunload', () => {
        if (selected.size > 0) {
            const body = new URLSearchParams({
                _token:           CSRF,
                movie_session_id: SESSION_ID,
            });
            navigator.sendBeacon(RELEASE_URL, body);
        }
    });

    // ── Polling cada 15s ────────────────────────────────────────
    setInterval(async () => {
        try {
            const res  = await fetch(STATUS_URL);
            const data = await res.json();
            grid.querySelectorAll('.seat-btn').forEach(btn => {
                const s = parseInt(btn.dataset.seat);
                if (selected.has(s)) return;
                if (data.booked.includes(s)) {
                    btn.className = 'seat-btn seat-booked'; btn.disabled = true;
                } else if (data.locked.includes(s)) {
                    btn.className = 'seat-btn seat-locked'; btn.disabled = true;
                } else if (!btn.classList.contains('seat-mine')) {
                    btn.className = 'seat-btn seat-free'; btn.disabled = false;
                }
            });
            maxReached = data.max_reached;
        } catch (_) {}
    }, 15000);

    updateCounter();

})();
</script>
@endpush
