<?php

namespace App\Models;

use App\Enums\Sex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Person extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $table = 'persons';

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
        'name' => 'object',
        'sex' => Sex::class,
        'growth' => 'integer',
        'birthday' => 'date',
        'death' => 'date',
        'age' => 'integer',
        'has_awards' => 'integer',
        'facts' => 'array',
    ];

    public $incrementing = false;

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('poster')
            ->singleFile();
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class)->using(FilmPerson::class)->withPivot(['description', 'profession_text', 'profession_key']);
    }

    public function awards(): BelongsToMany
    {
        return $this->belongsToMany(Award::class, 'award_person', 'person_id');
    }
}
