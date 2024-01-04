<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Film;

class FilmController extends Controller
{
    public function show(Film $film)
    {
        return view('film', compact('film'));
    }
}
