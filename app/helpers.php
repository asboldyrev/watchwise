<?php

use App\Models\Film;

if (!function_exists('get_vote_class')) {
    function get_vote_class(Film $film)
    {
        return match ($film->usersWithVotes->first()->pivot->vote) {
            0 => 'vote-0',
            1 => 'vote-1',
            2 => 'vote-2',
            3 => 'vote-3',
            4 => 'vote-4',
            5 => 'vote-5',
            6 => 'vote-6',
            7 => 'vote-7',
            8 => 'vote-8',
            9 => 'vote-9',
            10 => 'vote-10',
        };
    }
}
