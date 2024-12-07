<?php

namespace App\Services\Kinopoisk;

use App\Models\Film;
use App\Services\KinopoiskApiUnofficial\Client;
use Carbon\Carbon;

class Seasons
{
    public static function sync(int $filmId)
    {
        $film = Film::find($filmId);
        $client = new Client();

        $seasons = $client->getSeasons($film->id);
        $film->seasons()->delete();

        foreach ($seasons->items as $season_data) {
            $season = $film->seasons()->create([
                'number' => $season_data->number,
            ]);

            foreach ($season_data->episodes as $episode_data) {
                $season->episodes()->create([
                    'episode_number' => $episode_data->episodeNumber,
                    'name' => [
                        'ru' => $episode_data->nameRu,
                        'en' => $episode_data->nameEn,
                    ],
                    'synopsis' => $episode_data->synopsis,
                    'release_date' => $episode_data->releaseDate ? Carbon::createFromFormat('Y-m-d', $episode_data->releaseDate) : null,
                ]);
            }
        }
    }
}
