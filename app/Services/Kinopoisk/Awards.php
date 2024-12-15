<?php

namespace App\Services\Kinopoisk;

use App\Models\Award;
use App\Models\Film;
use App\Models\Nomination;
use App\Models\Person;
use App\Services\Kinopoisk\Person as KinopoiskPerson;
use App\Services\KinopoiskApiUnofficial\Client;

class Awards
{
    public static function sync(int $filmId)
    {
        $film = Film::find($filmId);
        $client = new Client();

        $awards = $client->getAwards($film->id);

        foreach ($awards->items as $award_data) {
            /** @var Award $award */
            $award = Award::firstOrCreate(['name' => $award_data->name]);

            if ($award->media()->count() == 0 && $award_data->imageUrl && $award_data->win) {
                $award
                    ->addMediaFromUrl($award_data->imageUrl)
                    ->withCustomProperties(['orig_url' => $award_data->imageUrl])
                    ->toMediaCollection('image');
            }

            $nomination = Nomination
                ::where('name', $award_data->nominationName)
                ->where('year', $award_data->year)
                ->where('is_win', $award_data->win)
                ->first();

            if (!$nomination) {
                $nomination = new Nomination([
                    'name' => $award_data->nominationName,
                    'year' => $award_data->year,
                    'is_win' => $award_data->win,
                ]);

                $nomination->award()->associate($award);
                $nomination->film()->associate($film);
                $nomination->save();
            }

            $person_ids = [];
            foreach ($award_data->persons ?? [] as $person_data) {
                $person = Person::find($person_data->kinopoiskId);

                if (!$person) {
                    KinopoiskPerson::sync($person_data->kinopoiskId);
                    $person = Person::find($person_data->kinopoiskId);
                }

                if ($nomination->persons()->where('id', $person->id)->count() == 0) {
                    $person_ids[] = $person->id;
                }
            }

            $nomination->persons()->sync($person_ids);
        }
    }
}
