<?php

namespace App\Services\Kinopoisk;

use App\Jobs\ImportFilm;
use App\Models\Country;
use App\Models\Distribution;
use App\Models\DistributionCompany;
use App\Models\Film as ModelsFilm;
use App\Models\Genre;
use App\Models\OnlineTheater;
use App\Models\Person;
use App\Services\Kinopoisk\Person as KinopoiskPerson;
use App\Services\KinopoiskApiUnofficial\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;
use stdClass;

class Film
{
    public static function import(int|string $filmId, bool $withSequelsAndPrequels = true)
    {
        $client = new Client();

        /** @var Film $film */
        $film = ModelsFilm::where('id', $filmId)->first();

        if (!$film) {
            $film_data = $client->getFilm($filmId);

            /** @var ModelsFilm $film */
            $film = ModelsFilm::create([
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

            self::syncCountries($film, $film_data->countries);
            self::syncGenres($film, $film_data->genres);

            if ($film_data->kinopoiskHDId) {
                self::syncKinopoiskTheater($film, $film_data->kinopoiskHDId);
            }

            self::syncMedia($film, $film_data);

            if ($film_data->serial) {
                self::syncSeasons($film);
            }
        }

        self::syncAwards($film);
        self::syncDistributions($film);

        if ($withSequelsAndPrequels) {
            self::syncSequelsAndPrequels($film);
        }

        self::syncPersons($film);
        self::syncOnlineTheaters($film);

        return $film;
    }


    protected static function syncCountries(ModelsFilm $film, array $countries)
    {
        $country_ids = [];

        foreach ($countries as $country_data) {
            $country = Country::where('name', $country_data->country)->first();

            if (!$country) {
                $country = Country::create(['name' => $country_data->country]);
            }

            $country_ids[] = $country->id;
        }

        $film->countries()->sync($country_ids);
    }

    protected static function syncGenres(ModelsFilm $film, array $genres)
    {
        $genre_ids = [];

        foreach ($genres as $genre_data) {
            $genre = Genre::where('name', $genre_data->genre)->first();

            if (!$genre) {
                $genre = Genre::create([
                    'name' => $genre_data->genre,
                ]);
            }

            $genre_ids[] = $genre->id;
        }

        $film->genres()->sync($genre_ids);
    }

    protected static function syncKinopoiskTheater(ModelsFilm $film, string $kinopoiskHdId)
    {
        $kinopoisk_theater = OnlineTheater::where('name', 'Кинопоиск')->first();

        if (!$kinopoisk_theater) {
            $kinopoisk_theater = OnlineTheater::create(['name' => 'Кинопоиск']);
        }

        if ($film->onlineTheaters()->where('name', $kinopoisk_theater->name)->count() == 0) {
            $film
                ->onlineTheaters()
                ->attach($kinopoisk_theater->id, ['url' => 'https://hd.kinopoisk.ru/film/' . $kinopoiskHdId]);
        }
    }

    protected static function syncMedia(ModelsFilm $film, stdClass $filmData)
    {
        try {
            if ($filmData->posterUrl) {
                $film
                    ->addMediaFromUrl($filmData->posterUrl)
                    ->withCustomProperties(['orig_url' => $filmData->posterUrl])
                    ->toMediaCollection('poster');
            }

            if ($filmData->coverUrl) {
                $film
                    ->addMediaFromUrl($filmData->coverUrl)
                    ->withCustomProperties(['orig_url' => $filmData->coverUrl])
                    ->toMediaCollection('cover');
            }

            if ($filmData->logoUrl) {
                $film
                    ->addMediaFromUrl($filmData->logoUrl)
                    ->withCustomProperties(['orig_url' => $filmData->logoUrl])
                    ->toMediaCollection('logo');
            }
        } catch (UnreachableUrl $exception) {
            //
        }
    }

    protected static function syncSeasons(ModelsFilm $film)
    {
        $client = new Client();

        $seasons = $client->getSeasons($film->id);
        $film->seasons()->delete();

        foreach ($seasons->items as $season_data) {
            $season = $film->seasons()->create([
                'number' => $season_data->number,
            ]);

            foreach ($season_data->episodes as $episode_data) {
                $season->episodes()->create([
                    'episode_number' => $episode_data->episodeNumber,
                    'name' => [
                        'ru' => $episode_data->nameRu,
                        'en' => $episode_data->nameEn,
                    ],
                    'synopsis' => $episode_data->synopsis,
                    'release_date' => $episode_data->releaseDate ? Carbon::createFromFormat('Y-m-d', $episode_data->releaseDate) : null,
                ]);
            }
        }
    }

    protected static function syncAwards(ModelsFilm $film)
    {
        $client = new Client();

        $awards = $client->getAwards($film->id);

        foreach ($awards->items as $award_data) {
            $award = $film->awards()->where('name', $award_data->name)->first();

            if (!$award) {
                $award = $film
                    ->awards()
                    ->create([
                        'name' => $award_data->name,
                        'win' => $award_data->win,
                        'nomination_name' => $award_data->nominationName,
                        'year' => $award_data->year,
                    ]);
            }

            if ($award->media()->count() == 0 && $award_data->imageUrl) {
                $award
                    ->addMediaFromUrl($award_data->imageUrl)
                    ->withCustomProperties(['orig_url' => $award_data->imageUrl])
                    ->toMediaCollection('image');
            }

            if (count($award_data->persons)) {
                $person_ids = [];
                foreach ($award_data->persons as $person_data) {
                    $person = Person::where('id', $person_data->kinopoiskId)->first();

                    if (!$person) {
                        $person = KinopoiskPerson::import($person_data->kinopoiskId);
                    }

                    if ($award->persons()->where('id', $person->id)->count() == 0) {
                        $person_ids[] = $person->id;
                    }
                }
                $award->persons()->sync($person_ids);
            }
        }
    }

    protected static function syncDistributions(ModelsFilm $film)
    {
        $client = new Client();

        $distributions = $client->getDistributions($film->id);

        $film->distributions()->delete();
        foreach ($distributions->items as $distribution_data) {
            /**
             * @var Distribution $distribution
             */
            $distribution = Distribution::make([
                'type' => $distribution_data->type,
                'sub_type' => $distribution_data->subType,
                'date' => $distribution_data->date,
                're_release' => $distribution_data->reRelease,
            ]);
            $distribution->film()->associate($film->id);

            if ($distribution_data->country) {
                $country = Country::where('name', $distribution_data->country->country)->first();

                if (!$country) {
                    $country = Country::create(['name' => $distribution_data->country->country]);
                }

                $distribution->country()->associate($country->id);
            }

            $distribution->save();

            $company_ids = [];
            foreach ($distribution_data->companies as $company_data) {
                $company = DistributionCompany::where('name', $company_data->name)->first();

                if (!$company) {
                    $company = DistributionCompany::create(['name' => $company_data->name]);
                }

                $company_ids[] = $company->id;
            }
            $distribution->distributionCompanies()->sync($company_ids);
        }
    }

    protected static function syncSequelsAndPrequels(ModelsFilm $film)
    {
        $client = new Client();

        try {
            $sequels_and_prequels = $client->getSequelsAndPrequels($film->id);
        } catch (ClientException $exception) {
            if ($exception->getCode() == 404) {
                return null;
            }
            dd(
                $exception->getTrace(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );

            throw $exception;
        }

        $sap_ids = [];
        foreach ($sequels_and_prequels as $sequel_and_prequel_data) {
            $sap = self::import($sequel_and_prequel_data->filmId, false);
            ImportFilm::dispatch($sap->id, true)->delay(now()->addMinutes(10));
            $sap_ids[$sap->id] = [
                'type' => $sequel_and_prequel_data->relationType,
            ];
        }
        $film->sequelsAndPrequels()->sync($sap_ids);
    }

    protected static function syncPersons(ModelsFilm $film)
    {
        $client = new Client();

        try {
            $persons = $client->getStaff($film->id);
        } catch (ClientException $exception) {
            if ($exception->getCode() == 404) {
                return null;
            }
            dd(
                $exception->getTrace(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );
            throw $exception;
        }

        if (!isset($persons)) {
            return 1;
        }

        $person_ids = [];
        foreach ($persons as $person_data) {
            $person = KinopoiskPerson::import($person_data->staffId);
            $person_ids[$person->id] = [
                'description' => $person_data->description,
                'profession_text' => $person_data->professionText,
                'profession_key' => $person_data->professionKey,
            ];
        }
        $film->persons()->sync($person_ids);
    }

    protected static function syncOnlineTheaters(ModelsFilm $film)
    {
        $client = new Client();

        $online_theaters = $client->getOnlineTheaters($film->id);

        foreach ($online_theaters->items as $online_theater_data) {

            $theater = OnlineTheater::where('name', $online_theater_data->platform)->first();

            if (!$theater) {
                $theater = OnlineTheater::create(['name' => $online_theater_data->platform]);

                if ($online_theater_data->logoUrl) {
                    $theater
                        ->AddMediaFromUrl($online_theater_data->logoUrl)
                        ->toMediaCollection('logo');
                }
            }

            if ($film->onlineTheaters()->where('name', $theater->name)->count() == 0) {
                $film
                    ->onlineTheaters()
                    ->attach($theater->id, ['url' => $online_theater_data->url]);
            }
        }
    }
}
