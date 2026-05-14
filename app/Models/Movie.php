<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    protected $fillable = ['title', 'description', 'duration_minutes', 'poster', 'category'];

    // Categorías disponibles (fijas, controladas por el admin)
    public const CATEGORIES = [
        'Acción',
        'Aventura',
        'Animación',
        'Ciencia Ficción',
        'Comedia',
        'Documental',
        'Drama',
        'Familiar',
        'Fantasía',
        'Romance',
        'Suspense',
        'Terror',
        'Thriller',
    ];

    public function movieSessions(): HasMany
    {
        return $this->hasMany(MovieSession::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating(): float
    {
        return round($this->ratings()->avg('score') ?? 0, 1);
    }
}
