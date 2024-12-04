<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Image\Enums\Fit;
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

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function onlineTheaters(): BelongsToMany
    {
        return $this->belongsToMany(OnlineTheater::class)->withPivot('url');
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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('card')
            ->fit(Fit::Max, 151, 227)
            ->nonQueued();
    }
}
