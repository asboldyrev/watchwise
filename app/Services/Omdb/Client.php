<?php

namespace App\Services\Omdb;

use App\Models\Film;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Client
{
    public static function get(Film $film)
    {
        if (!$film->imdb_id) {
            dd((array)$film);
        }

        return self::request($film->imdb_id);
    }

    protected static function request(string $imdbId)
    {
        $url = env('OMDB_URL') . '?' . http_build_query([
            'apikey' => env('OMDB_KEY'),
            'i' => $imdbId,
        ]);

        if (Cache::has($url)) {
            dump('cache client');
            $response = Cache::get($url);

            if ($response->Error ?? false) {
                Log::error($response->Error);
                Cache::forget($url);

                return null;
            }

            Cache::put($url, Cache::get($url), 7 * 24 * 60);
            return $response;
        }

        $client = new GuzzleHttpClient();

        try {
            $response = $client
                ->request('get', $url)
                ->getBody()
                ->getContents();
        } catch (ClientException $exception) {
            Log::critical($exception->getMessage());

            throw $exception;
        }

        $response = json_decode($response);

        if ($response->Error ?? false) {
            Log::error($response->Error);
            Cache::forget($url);

            return null;
        }

        Cache::put($url, $response, 7 * 24 * 60);

        return $response;
    }
}
