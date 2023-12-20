<?php

namespace App\Services\Kinopoisk\Data;

use App\Models\Film;
use App\Services\Kinopoisk\Film as KinopoiskFilm;
use Carbon\Carbon;
use stdClass;

class WatchListData
{
    public readonly Film $film;

    public readonly int|string $sing;

    public readonly Carbon $date;

    public readonly array $lists;

    public static function make(stdClass $watchListData)
    {
        $watch_list_data = new self();

        if ($watchListData->film_id) {
            $watch_list_data->film = KinopoiskFilm::import($watchListData->film_id);
        }

        if ($watchListData->sing) {
            $watch_list_data->sing = $watchListData->sing;
        }

        if ($watchListData->date) {
            $watch_list_data->date = Carbon::createFromFormat('d.m.Y, H:i', $watchListData->date);
        }

        if (is_array($watchListData->lists) && count($watchListData->lists)) {
            $watch_list_data->lists = $watchListData->lists;
        }

        return $watch_list_data;
    }
}
