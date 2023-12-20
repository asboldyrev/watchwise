<?php

namespace App\Models;

use App\Enums\DistributionSubType;
use App\Enums\DistributionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'sub_type',
        'date',
        're_release',
    ];

    protected $casts = [
        'type' => DistributionType::class,
        'sub_type' => DistributionSubType::class,
        'date' => 'date',
        're_release' => 'boolean',
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function distributionCompanies(): BelongsToMany
    {
        return $this->belongsToMany(DistributionCompany::class);
    }
}
