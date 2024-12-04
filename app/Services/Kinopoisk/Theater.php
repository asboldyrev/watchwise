<?php

namespace App\Services\Kinopoisk;

use App\Models\Film;
use App\Models\OnlineTheater;
use App\Services\KinopoiskApiUnofficial\Client;

class Theater
{
    public static function syncOnlineTheaters(int $filmId)
    {
        $film = Film::find($filmId);
        $film->onlineTheaters()->detach();

        $client = new Client();

        $kp_film = $client->getFilm($filmId);

        self::syncKinopoiskTheater($film, $kp_film->kinopoiskHDId);

        $online_theaters = $client->getOnlineTheaters($film->id);

        foreach ($online_theaters->items as $online_theater_data) {
            $theater = OnlineTheater::firstOrCreate(['name' => $online_theater_data->platform]);

            if ($theater->wasRecentlyCreated && $online_theater_data->logoUrl) {
                $theater
                    ->AddMediaFromUrl($online_theater_data->logoUrl)
                    ->toMediaCollection('logo');
            }

            $film
                ->onlineTheaters()
                ->attach(
                    $theater->id,
                    ['url' => $online_theater_data->url]
                );
        }
    }


    protected static function syncKinopoiskTheater(Film $film, string $kinopoiskHdId)
    {
        /** @var OnlineTheater $kinopoisk_theater */
        $kinopoisk_theater = OnlineTheater::firstOrCreate(['name' => 'Кинопоиск']);

        if ($kinopoisk_theater->wasRecentlyCreated) {
            $kinopoisk_theater
                ->copyMedia(storage_path('logos/kp.png'))
                ->toMediaCollection('logo');
        }

        $film
            ->onlineTheaters()
            ->attach(
                $kinopoisk_theater->id,
                ['url' => 'https://hd.kinopoisk.ru/film/' . $kinopoiskHdId]
            );
    }
}
