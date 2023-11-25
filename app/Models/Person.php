<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'sex',
        'growth',
        'birthday',
        'death',
        'age',
        'birth_place',
        'death_place',
        'has_awards',
        'profession',
        'facts',
    ];

    protected $casts = [
        'id' => 'integer',
        'sex' => 'enum',
        'growth' => 'integer',
        'birthday' => 'date',
        'death' => 'date',
        'age' => 'integer',
        'has_awards' => 'integer',
        'facts' => 'array',
    ];

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class);
    }
}
