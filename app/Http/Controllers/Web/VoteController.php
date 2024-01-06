<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FilmUser;

class VoteController extends Controller
{
    public function list()
    {
        $film_users = FilmUser
            ::with(['film', 'user'])
            ->whereNotNull('vote')
            ->orderBy('date', 'desc')
            ->paginate(30);

        return view('votes')->with('filmUsers', $film_users);
    }
}
