<?php

namespace App\Services\Kinopoisk\Data;

use App\Models\Film;
use App\Services\Kinopoisk\Film as KinopoiskFilm;
use Carbon\Carbon;
use stdClass;

class VoteData
{
    public readonly Film $film;

    public readonly int|string $vote;

    public readonly Carbon $date;

    public static function make(stdClass $voteData)
    {
        $vote_data = new self();

        if ($voteData->film_id) {
            $vote_data->film = KinopoiskFilm::import($voteData->film_id);
        }

        if ($voteData->vote) {
            $vote_data->vote = $voteData->vote;
        }

        if ($voteData->date) {
            $vote_data->date = Carbon::createFromFormat('d.m.Y, H:i', $voteData->date);
        }

        return $vote_data;
    }
}
