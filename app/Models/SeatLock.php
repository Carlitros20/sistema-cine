<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatLock extends Model
{
    protected $fillable = ['movie_session_id', 'seat', 'user_id', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function movieSession(): BelongsTo
    {
        return $this->belongsTo(MovieSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Comprueba si el bloqueo sigue vigente
    public function isActive(): bool
    {
        return $this->expires_at->isFuture();
    }
}
