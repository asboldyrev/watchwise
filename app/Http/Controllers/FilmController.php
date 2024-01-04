<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\FilmUser;

class FilmController extends Controller
{
    public function list()
    {
        $film_users = FilmUser::with(['film', 'user'])->whereNotNull('vote')->orderBy('date', 'desc')->paginate(60);

        return view('votes')->with('filmUsers', $film_users);
    }

    public function show(Film $film)
    {
        return view('film', compact('film'));
    }
}
