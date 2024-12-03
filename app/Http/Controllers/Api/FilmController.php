<?php

namespace App\Http\Controllers\Api;

use App\Events\FilmImported;
use App\Http\Controllers\Controller;
use App\Http\Resources\FilmResource as ResourcesFilmResource;
use App\Http\Resources\KinopoiskUnofficial\FilmResource;
use App\Jobs\SyncAwardsJob;
use App\Jobs\SyncFilmJob;
use App\Jobs\SyncPersonJob;
use App\Jobs\SyncRelatedFilmsJob;
use App\Jobs\SyncSeasonsJob;
use App\Jobs\SyncTheatresJob;
use App\Models\Film;
use App\Services\KinopoiskApiUnofficial\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class FilmController extends Controller
{
    public function search(Request $request, Client $client)
    {
        $query = $request->input('query');

        $films = $client->searchFilm($query);

        return FilmResource::make($films);
    }

    public function show(int $filmId)
    {
        $film = Film
            ::where('id', $filmId)
            ->with([
                'countries',
                'genres',
                'media',
            ])
            ->first();

        if (!$film) {
            SyncFilmJob::dispatch($filmId);
            // SyncTheatresJob::dispatch($filmId);
            // SyncSeasonsJob::dispatch($filmId);
            // SyncAwardsJob::dispatch($filmId);
            // new SyncRelatedFilmsJob($film),
            // new SyncPersonJob($film),
            FilmImported::dispatch($filmId);


            return response()->json(['importing' => true]);
        }

        return ResourcesFilmResource::make($film);
    }
}
