<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Award extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'win',
        'nomination_name',
        'year',
    ];

    protected $casts = [
        'win' => 'boolean',
        'year' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image');
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'award_person', relatedPivotKey: 'person_id');
    }
}
