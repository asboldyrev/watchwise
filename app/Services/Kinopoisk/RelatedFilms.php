<?php

namespace App\Services\Kinopoisk;

use App\Jobs\SyncFilmDataJob;
use App\Jobs\SyncRelatedFilmsJob;
use App\Models\Film;
use App\Services\KinopoiskApiUnofficial\Client;
use GuzzleHttp\Exception\ClientException;

class RelatedFilms
{
    public static function sync(int $filmId)
    {
        $film = Film::find($filmId);
        $client = new Client();

        try {
            $related_films = $client->getSequelsAndPrequels($film->id);
        } catch (ClientException $exception) {
            if ($exception->getCode() == 404) {
                return null;
            }

            throw $exception;
        }

        $related_film_ids = [];
        $need_sync = false;
        foreach ($related_films as $related_film_data) {
            $related_film = Film::find($related_film_data->filmId);

            if (!$related_film) {
                SyncFilmDataJob::dispatch($related_film_data->filmId);
                $need_sync = true;
                continue;
            }

            $related_film_ids[$related_film->id] = [
                'type' => $related_film_data->relationType,
            ];
        }

        $film->relatedFilms()->sync($related_film_ids);

        if ($need_sync) {
            SyncRelatedFilmsJob::dispatch($filmId);
        }
    }
}
