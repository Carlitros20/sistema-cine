<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['name', 'capacity'];

    public function movieSessions(): HasMany {
        return $this->hasMany(MovieSession::class);
    }
}