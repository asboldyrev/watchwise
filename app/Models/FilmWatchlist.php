<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FilmWatchlist extends Pivot
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }


    public function watchlist(): BelongsTo
    {
        return $this->belongsTo(Watchlist::class);
    }
}
