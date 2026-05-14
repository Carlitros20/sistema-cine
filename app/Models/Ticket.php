<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = ['user_id', 'movie_session_id', 'seat'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function movieSession(): BelongsTo {
        return $this->belongsTo(MovieSession::class);
    }
}