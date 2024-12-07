<?php

namespace App\Models;

use App\Enums\Profession;
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
        'spouses',
        'awards_count',
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
        'spouses' => 'array',
        'awards_count' => 'integer',
        'facts' => 'array',
    ];

    public $incrementing = false;

    public function scopeOrderByProfession($query)
    {
        // Получаем все возможные значения `enum`
        $statuses = Profession::cases();

        // Генерируем условия для `CASE` с помощью цикла
        $caseStatements = [];
        foreach ($statuses as $index => $status) {
            $caseStatements[] = "WHEN '{$status->value}' THEN {$index}";
        }

        // Объединяем условия в одну строку
        $caseSql = implode(' ', $caseStatements);

        // Добавляем `ORDER BY` с динамически построенным `CASE`
        return $query->orderByRaw("CASE pivot_profession_key {$caseSql} END");
    }

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
}
