<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_number',
        'name',
        'synopsis',
        'release_date',
    ];

    protected $casts = [
        'season_number' => 'integer',
        'episode_number' => 'integer',
        'name' => 'object',
        'release_date' => 'date',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
