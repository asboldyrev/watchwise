<?php

namespace App\Services\Kinopoisk;

use App\Models\Country;
use App\Models\Film as ModelsFilm;
use App\Models\Genre;
use App\Services\KinopoiskApiUnofficial\Client;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;
use stdClass;

class Film
{
    public static function sync(stdClass $filmData): ModelsFilm
    {
        $id = get_film_id($filmData);

        $client = new Client();
        $filmData = $client->getFilm($id);

        $data = [
            'id' => $id,
            'imdb_id' => $filmData->imdbId,
            'name' => [
                'ru' => $filmData->nameRu,
                'en' => $filmData->nameEn,
                'original' => $filmData->nameOriginal,
            ],
            'rating' => [
                'goodReview' => [
                    'review' => $filmData->ratingGoodReview,
                    'count' => $filmData->ratingGoodReviewVoteCount,
                ],
                'kinopoisk' => [
                    'review' => $filmData->ratingKinopoisk,
                    'count' => $filmData->ratingKinopoiskVoteCount,
                ],
                'imdb' => [
                    'review' => $filmData->ratingImdb,
                    'count' => $filmData->ratingImdbVoteCount,
                ],
                'filmCritics' => [
                    'review' => $filmData->ratingFilmCritics,
                    'count' => $filmData->ratingFilmCriticsVoteCount,
                ],
                'await' => [
                    'review' => $filmData->ratingAwait,
                    'count' => $filmData->ratingAwaitCount,
                ],
                'rfCritics' => [
                    'review' => $filmData->ratingRfCritics,
                    'count' => $filmData->ratingRfCriticsVoteCount,
                ],
            ],
            'year' => $filmData->year,
            'length' => $filmData->filmLength,
            'slogan' => $filmData->slogan,
            'description' => $filmData->description,
            'short_description' => $filmData->shortDescription,
            'type' => $filmData->type,
            'mpaa' => $filmData->ratingMpaa,
            'age_limits' => $filmData->ratingAgeLimits,
            'start_year' => $filmData->startYear,
            'end_year' => $filmData->endYear,
            'serial' => $filmData->serial,
            'short' => $filmData->shortFilm,
            'completed' => $filmData->completed,
        ];

        $film = ModelsFilm::updateOrCreate(['id' => $data['id']], $data);

        self::syncCountries($film, $filmData->countries);
        self::syncGenres($film, $filmData->genres);
        self::syncMedia($film, $filmData);

        return $film;
    }

    protected static function syncCountries(ModelsFilm $film, array $countries)
    {
        $country_ids = [];

        foreach ($countries as $country_data) {
            $country = Country::firstOrCreate(['name' => $country_data->country]);

            $country_ids[] = $country->id;
        }

        $film->countries()->sync($country_ids);
    }

    protected static function syncGenres(ModelsFilm $film, array $genres)
    {
        $genre_ids = [];

        foreach ($genres as $genre_data) {
            $genre = Genre::firstOrCreate(['name' => $genre_data->genre]);

            $genre_ids[] = $genre->id;
        }

        $film->genres()->sync($genre_ids);
    }

    protected static function syncMedia(ModelsFilm $film, stdClass $filmData)
    {
        $images = [
            'poster' => $filmData->posterUrl,
            'cover' => $filmData->coverUrl,
            'logo' => $filmData->logoUrl,
        ];

        try {
            foreach ($images as $collection => $url) {
                if (!$url) {
                    continue;
                }

                if ($film->media()->where('custom_properties', 'like', '%' . $url . '%')->count() == 0) {
                    $film
                        ->addMediaFromUrl($url)
                        ->withCustomProperties(['orig_url' => $url])
                        ->toMediaCollection($collection);
                }
            }
        } catch (UnreachableUrl $exception) {
            //
        }
    }
}
