<?php

namespace App\Services\Kinopoisk;

use App\Models\User;
use App\Services\Kinopoisk\Data\VoteData;

class Vote
{
    public static function import(User $user, VoteData $voteData)
    {
        if ($user->filmsWithVotes()->where('id', $voteData->film->id)->count() == 0) {
            $user->filmsWithVotes()->attach($voteData->film->id, [
                'vote' => $voteData->vote,
                'date' => $voteData->date,
            ]);
        }
    }
}
