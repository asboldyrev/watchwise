<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilmResource as ResourcesFilmResource;
use App\Http\Resources\KinopoiskUnofficial\FilmResource;
use App\Jobs\SyncFilmDataJob;
use App\Models\Award;
use App\Models\Film;
use App\Services\KinopoiskApiUnofficial\Client;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function search(Request $request, Client $client)
    {
        $query = $request->input('query');

        $films = $client->searchFilm($query);

        return FilmResource::make($films);
    }

    public function list()
    {
        $films = Film
            ::with([
                'countries',
                'genres',
                'media',
            ])
            ->orderBy('year', 'desc')
            ->paginate(16);

        return ResourcesFilmResource::collection($films);
    }

    public function show(int $filmId)
    {
        /** @var Film $film */
        $film = Film
            ::where('id', $filmId)
            ->with([
                'countries',
                'genres',
                'media',
                'onlineTheaters' => fn($query) => $query->with('media'),
                'seasons' => fn($query) => $query->with('episodes'),
                'relatedFilms' => fn($query) => $query->orderByRaw('year IS NULL')->orderBy('year')->with([
                    'countries',
                    'genres',
                    'media',
                ]),
                'persons' => fn($query) => $query->orderByProfession()->with('media'),
                // 'nominations' => fn($query) => $query->with([
                //     'award' => fn($query) => $query->with('media'),
                //     'persons',
                // ])->orderBy('year')
            ])
            ->first();

        if (!$film) {
            SyncFilmDataJob::dispatch($filmId);

            return response()->json(['importing' => true]);
        }

        $this->loadProfessions($film);
        $this->loadAwards($film);

        return ResourcesFilmResource::make($film);
    }

    private function loadProfessions(Film &$film)
    {
        $professions = [];
        foreach ($film->persons as $person) {
            $name = $person?->pivot?->profession_key?->name;

            if ($name) {
                $professions[$name][] = $person->id;
            }
        }

        $film->setRelation('professions', $professions);
    }

    private function loadAwards(Film &$film)
    {
        $awards = Award
            ::whereHas('nominations', fn($query) => $query->where('film_id', $film->id))
            ->with([
                'media',
                'nominations' => fn($query) => $query
                    ->where('film_id', $film->id)
                    ->orderBy('year')
                    ->with('persons')
            ])
            ->orderBy('name')
            ->get();

        $film->setRelation('awards', $awards);
    }
}
