<?php

namespace App\Services\Kinopoisk;

use App\Jobs\SyncFilmDataJob;
use App\Jobs\SyncPersonJob;
use App\Jobs\SyncPersonsJob;
use App\Models\Film;
use App\Models\Person as ModelsPerson;
use App\Services\KinopoiskApiUnofficial\Client;
use Carbon\Carbon;
use Exception;

class Person
{
    public static function syncMany(int $filmId)
    {
        $film = Film::find($filmId);
        $client = new Client();

        $persons = $client->getStaff($film->id);

        if (!$persons) {
            return null;
        }

        $person_ids = [];
        $need_sync = false;
        foreach ($persons as $person_data) {
            $person = ModelsPerson::find($person_data->staffId);

            if (!$person) {
                SyncPersonJob::dispatch($person_data->staffId);
                $need_sync = true;
                continue;
            }

            $person_ids[$person->id] = [
                'description' => $person_data->description,
                'profession_text' => $person_data->professionText,
                'profession_key' => $person_data->professionKey,
            ];
        }

        $film->persons()->sync($person_ids);

        if ($need_sync) {
            SyncPersonsJob::dispatch($filmId);
        }
    }

    public static function sync(int $personId)
    {
        $personId = match ($personId) {
            5492279 => 3683141,
            6326676 => 4453931,
            7169658 => 10131931,
            6241605 => 4722812,
            5531636 => 6101548,
            4965940 => 2345082,
            6808437 => 6793776,
            6837814 => 1141993,
            default => $personId
        };

        $client = new Client();
        $person_data = $client->getPerson($personId);

        $person = ModelsPerson::updateOrCreate(
            ['id' => $person_data->personId],
            [
                'name' => [
                    'ru' => $person_data->nameRu,
                    'en' => $person_data->nameEn,
                ],
                'sex' => $person_data->sex,
                'growth' => $person_data->growth,
                'birthday' => $person_data->birthday ? Carbon::createFromFormat('Y-m-d', $person_data->birthday) : null,
                'death' => $person_data->death ? Carbon::createFromFormat('Y-m-d', $person_data->death) : null,
                'age' => when($person_data->age < 255 && $person_data->age > 0, $person_data->age),
                'birth_place' => $person_data->birthplace,
                'death_place' => $person_data->deathplace,
                'awards_count' => $person_data->hasAwards,
                'profession' => $person_data->profession,
                'facts' => $person_data->facts,
            ]
        );

        if ($person->media()->count() == 0) {
            try {
                $person
                    ->addMediaFromUrl($person_data->posterUrl)
                    ->withCustomProperties(['orig_url' => $person_data->posterUrl])
                    ->toMediaCollection('poster');
            } catch (Exception $exception) {
                //
            }
        }

        if (config('import.person_extend')) {
            foreach ($person_data->films as $film_data) {
                $film = Film::find($film_data->filmId);

                if (!$film) {
                    SyncFilmDataJob::dispatch($film_data->filmId);
                }
            }
        }
    }
}
