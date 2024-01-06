<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function getName()
    {
        if ($this->name->ru) {
            return $this->name->ru;
        }

        return $this->name->en ?: $this->name->original;
    }

    public function getSing()
    {
        $user = $this->usersWithSings()->first();

        if ($user) {
            return $user?->pivot?->text;
        }
    }

    public function getKinoOgonRating(array|null $rating = null)
    {
        if (is_null($rating)) {
            $rating = $this->rating;
        }

        if (
            key_exists('kinopoisk', $rating) &&
            key_exists('imdb', $rating) &&
            key_exists('Rotten Tomatoes', $rating) &&
            key_exists('Metacritic', $rating)
        ) {
            return round(
                (
                    $rating['kinopoisk']['review'] * 10 +
                    $rating['imdb']['review'] * 10 +
                    (
                        $rating['Metacritic']['review'] +
                        $rating['Rotten Tomatoes']['review']
                    ) / 2
                ) / 3,
                1
            );
        }

        return '-';
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('poster')
            ->singleFile();

        $this
            ->addMediaCollection('cover')
            ->singleFile();

        $this
            ->addMediaCollection('logo')
            ->singleFile();
    }

    public function usersWithVotes(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(FilmUser::class)->withPivot(['vote', 'date']);
    }

    public function usersWithSings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sings')->using(Sing::class)->withPivot(['text']);
    }

    public function watchLists(): BelongsToMany
    {
        return $this->belongsToMany(WatchList::class)->using(FilmWatchList::class)->withPivot(['date']);
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
        return $this->belongsToMany(Film::class, 'prequel_sequel', 'film_id', 'related_film_id')->using(FilmPrequelSequel::class)->withPivot('type');
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(Distribution::class);
    }

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)->using(FilmPerson::class)->withPivot(['description', 'profession_text', 'profession_key']);
    }
}
