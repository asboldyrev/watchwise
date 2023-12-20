<?php

namespace App\Models;

use App\Enums\Profession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FilmPerson extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'description',
        'profession_text',
        'profession_key',
    ];

    protected $casts = [
        'profession_key' => Profession::class,
    ];
}
