@extends('layouts.app')

@section('title', 'Compra confirmada')

@section('content')

<div class="confirm-card">
    <div class="confirm-header">
        <i class="bi bi-check-circle-fill"></i>
        ¡Compra realizada con éxito!
        — {{ $tickets->count() }} {{ $tickets->count() === 1 ? 'entrada' : 'entradas' }}
    </div>
    <div class="confirm-body">

        @php $first = $tickets->first(); @endphp
        <p style="text-align:center; color:var(--c-text-muted); margin-bottom:1.5rem;">
            Tu compra ha sido confirmada. Aquí tienes el resumen:
        </p>

        <table class="confirm-table">
            <tr>
                <th>Película</th>
                <td>{{ $first->movieSession->movie->title }}</td>
            </tr>
            <tr>
                <th>Sala</th>
                <td>{{ $first->movieSession->room->name }}</td>
            </tr>
            <tr>
                <th>Fecha y hora</th>
                <td>{{ \Carbon\Carbon::parse($first->movieSession->start_time)->format('d/m/Y \a \l\a\s H:i') }}</td>
            </tr>
            <tr>
                <th>Butacas</th>
                <td>
                    <div style="display:flex; flex-wrap:wrap; gap:.4rem;">
                        @foreach($tickets as $ticket)
                            <span class="seat-badge">Butaca {{ $ticket->seat }}</span>
                        @endforeach
                    </div>
                </td>
            </tr>
            <tr>
                <th>Localizadores</th>
                <td>
                    @foreach($tickets as $ticket)
                        <span class="localizador">#{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</span>
                        @if(!$loop->last) &nbsp; @endif
                    @endforeach
                </td>
            </tr>
        </table>

        <div class="confirm-actions">
            <a href="{{ route('cartelera') }}" class="btn-cine btn-cine-ghost">
                <i class="bi bi-film"></i> Volver a la cartelera
            </a>
            <a href="{{ route('profile.index') }}" class="btn-cine">
                <i class="bi bi-ticket-perforated"></i> Mis entradas
            </a>
        </div>
    </div>
</div>

@endsection
