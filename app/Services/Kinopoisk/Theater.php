<?php

namespace App\Services\Kinopoisk;

use App\Models\Film;
use App\Models\OnlineTheater;
use App\Services\KinopoiskApiUnofficial\Client;

class Theater
{
    public static function sync(int $filmId)
    {
        $film = Film::find($filmId);

        $client = new Client();
        $theater_ids = [];

        $kp_film = $client->getFilm($filmId);

        $kp_theater = self::syncKinopoiskTheater($film, $kp_film->kinopoiskHDId);

        if ($kp_theater) {
            array_merge($theater_ids, $kp_theater);
        }

        $online_theaters = $client->getOnlineTheaters($film->id);

        foreach ($online_theaters->items as $online_theater_data) {
            $theater = OnlineTheater::firstOrCreate(['name' => $online_theater_data->platform]);

            if ($theater->wasRecentlyCreated && $online_theater_data->logoUrl) {
                $theater
                    ->AddMediaFromUrl($online_theater_data->logoUrl)
                    ->toMediaCollection('logo');
            }

            $theater_ids[$theater->id] = ['url' => $online_theater_data->url];
        }

        $film
            ->onlineTheaters()
            ->sync($theater_ids);
    }


    protected static function syncKinopoiskTheater(Film $film, string $kinopoiskHdId = null)
    {
        if (!$kinopoiskHdId) {
            return;
        }

        /** @var OnlineTheater $kinopoisk_theater */
        $kinopoisk_theater = OnlineTheater::firstOrCreate(['name' => 'Кинопоиск']);

        if ($kinopoisk_theater->wasRecentlyCreated) {
            $kinopoisk_theater
                ->copyMedia(storage_path('logos/kp.png'))
                ->toMediaCollection('logo');
        }

        return [
            $kinopoisk_theater->id => [
                'url' => 'https://hd.kinopoisk.ru/film/' . $kinopoiskHdId
            ],
        ];
    }
}
