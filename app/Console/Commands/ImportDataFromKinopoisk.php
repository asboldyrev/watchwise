<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use App\Services\Kinopoisk\ImportUserData;
use App\Services\KinopoiskApiUnofficial\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportDataFromKinopoisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-data-from-kinopoisk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var User $user */
        $user = User::find(1);

        $filepath_votes = storage_path('import/votes_data.json');
        $filepath_watch_lists = storage_path('import/watch_lists_data.json');

        if (file_exists($filepath_votes) || file_exists($filepath_watch_lists)) {
            $votes = json_decode(file_get_contents($filepath_votes));
            $watch_lists = json_decode(file_get_contents($filepath_watch_lists));
        } else {
            $films = ImportUserData::parse();
            $votes = $films['votes'];
            $watch_lists = $films['watch_lists'];

            file_put_contents($filepath_votes, json_encode($votes), JSON_UNESCAPED_UNICODE);
            file_put_contents($filepath_watch_lists, json_encode($watch_lists), JSON_UNESCAPED_UNICODE);
        }

        $client = new Client();

        foreach ($votes as $film_id => $vote) {
            /** @var Film $film */
            $film = Film::find($film_id);
            $film_data = $client->getFilm($film_id);

            if (! $film) {
                /** @var Film $film */
                $film = Film::create([
                    'id' => $film_data->kinopoiskId,
                    'imdb_id' => $film_data->imdbId,
                    'name' => [
                        'ru' => $film_data->nameRu,
                        'en' => $film_data->nameEn,
                        'original' => $film_data->nameOriginal,
                    ],
                    'rating' => [
                        'goodReview' => [
                            'review' => $film_data->ratingGoodReview,
                            'count' => $film_data->ratingGoodReviewVoteCount,
                        ],
                        'kinopoisk' => [
                            'review' => $film_data->ratingKinopoisk,
                            'count' => $film_data->ratingKinopoiskVoteCount,
                        ],
                        'imdb' => [
                            'review' => $film_data->ratingImdb,
                            'count' => $film_data->ratingImdbVoteCount,
                        ],
                        'filmCritics' => [
                            'review' => $film_data->ratingFilmCritics,
                            'count' => $film_data->ratingFilmCriticsVoteCount,
                        ],
                        'await' => [
                            'review' => $film_data->ratingAwait,
                            'count' => $film_data->ratingAwaitCount,
                        ],
                        'rfCritics' => [
                            'review' => $film_data->ratingRfCritics,
                            'count' => $film_data->ratingRfCriticsVoteCount,
                        ],
                    ],
                    'year' => $film_data->year,
                    'length' => $film_data->filmLength,
                    'slogan' => $film_data->slogan,
                    'description' => $film_data->description,
                    'short_description' => $film_data->shortDescription,
                    'type' => $film_data->type,
                    'mpaa' => $film_data->ratingMpaa,
                    'age_limits' => $film_data->ratingAgeLimits,
                    'start_year' => $film_data->startYear,
                    'end_year' => $film_data->endYear,
                    'serial' => $film_data->serial,
                    'short' => $film_data->shortFilm,
                    'completed' => $film_data->completed,
                ]);
            }

            $user->films()->attach($film, [
                'vote' => $vote->vote,
                'date' => Carbon::createFromFormat('d.m.Y, H:i', $vote->date),
            ]);

            foreach ($film_data->countries as $country_data) {
                $country = Country::where('name', $country_data->name)->first();

                if ($country && $country->films()->where('id', $film->id)->count() == 0) {
                    $film->countries()->attach($country);
                } elseif (! $country) {
                    $film->countries()->create([
                        'name' => $country_data->name,
                    ]);
                }
            }

            foreach ($film_data->genres as $genre_data) {
                $genre = Genre::where('name', $genre_data->name)->first();

                if ($genre && $genre->films()->where('id', $film->id)->count() == 0) {
                    $film->genres()->attach($genre);
                } elseif (! $genre) {
                    $film->genres()->create([
                        'name' => $genre_data->name,
                    ]);
                }
            }

            // TODO доделать создание сериалов
            // TODO доделать создание людей
            // TODO доделать создание наград
            // TODO доделать создание дистрибуций
            // TODO доделать создание сиквелов и приквелов
        }

        dd(112);
    }
}
