<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Film extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'id',
        'imdb_id',
        'name',
        'rating',
        'year',
        'length',
        'slogan',
        'description',
        'short_description',
        'type',
        'mpaa',
        'age_limits',
        'start_year',
        'end_year',
        'serial',
        'short',
        'completed',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'object',
        'rating' => 'json',
        'year' => 'integer',
        'length' => 'integer',
        'start_year' => 'integer',
        'end_year' => 'integer',
        'serial' => 'boolean',
        'short' => 'boolean',
        'completed' => 'boolean',
    ];

    public $incrementing = false;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('vote');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function onlineTheaters(): BelongsToMany
    {
        return $this->belongsToMany(OnlineTheater::class)->withPivot('url');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function sequelsAndPrequels(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'prequel_sequel', 'film_id', 'related_film_id')->withPivot('type');
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function award(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)->withPivot(['description', 'profession_text', 'profession_key']);
    }
}
