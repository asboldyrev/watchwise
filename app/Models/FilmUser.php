<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FilmUser extends Pivot
{
    use HasFactory;

    protected $casts = [
        'vote' => 'integer',
        'date' => 'datetime',
    ];


    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
