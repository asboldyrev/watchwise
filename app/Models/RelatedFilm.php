<?php

namespace App\Models;

use App\Enums\FilmRelationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RelatedFilm extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'related_film_id',
        'type',
    ];

    protected $casts = [
        'type' => FilmRelationType::class,
    ];
}
