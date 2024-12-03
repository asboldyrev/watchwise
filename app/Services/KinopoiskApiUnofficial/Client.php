<?php

namespace App\Services\KinopoiskApiUnofficial;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Cache;

class Client
{
    public function searchFilm(string $query, int $page = 1)
    {
        return $this->request('v2.1/films/search-by-keyword', [
            'keyword' => $query,
            'page' => $page,
        ]);
    }

    public function getFilm(int $id)
    {
        return $this->request('v2.2/films/' . $id);
    }

    public function getSeasons(int $id)
    {
        return $this->request('v2.2/films/' . $id . '/seasons');
    }

    public function getOnlineTheaters(int $id)
    {
        return $this->request('v2.2/films/' . $id . '/external_sources');
    }

    public function getVideos(int $id)
    {
        return $this->request('v2.2/films/' . $id . '/videos');
    }

    public function getAwards(int $id)
    {
        return $this->request('v2.2/films/' . $id . '/awards');
    }

    public function getDistributions(int $id)
    {
        return $this->request('v2.2/films/' . $id . '/distributions');
    }

    public function getImages(int $id)
    {
        return $this->request('v2.2/films/' . $id . '/images');
    }

    public function getSequelsAndPrequels(int $id)
    {
        return $this->request('v2.1/films/' . $id . '/sequels_and_prequels');
    }

    public function getStaff(int $id)
    {
        return $this->request('v1/staff', ['filmId' => $id]);
    }

    public function getPerson(int $id)
    {
        return $this->request('v1/staff/' . $id);
    }

    public function hasLimitReached()
    {
        if (Cache::get('kinopoisk_unofficial_daily_limit')) {
            return true;
        }

        sleep(1);
        $url = env('KINOPOISK_API_UNOFFICIAL_URL') . 'v1/api_keys/' . env('KINOPOISK_API_UNOFFICIAL_TOKEN');

        $headers = [
            'X-API-KEY' => env('KINOPOISK_API_UNOFFICIAL_TOKEN'),
            'Content-Type' => 'application/json',
        ];

        $config = compact('headers');
        $client = new GuzzleHttpClient($config);

        $response = $client
            ->request('get', $url, $config)
            ->getBody()
            ->getContents();

        $response = json_decode($response);

        if ($response->totalQuota->value >= 0 && $response->totalQuota->used >= $response->totalQuota->value) {
            return true;
        }

        $daily_limit = $response->dailyQuota->value >= 0 && $response->dailyQuota->used >= $response->dailyQuota->value;

        if ($daily_limit) {
            Cache::put('kinopoisk_unofficial_daily_limit', true, Carbon::now()->endOfDay()->diffInMinutes());

            return true;
        }

        return false;
    }

    protected static function request(string $url, array|null $query = null, string $method = 'get')
    {
        $url = env('KINOPOISK_API_UNOFFICIAL_URL') . $url;

        $cache_key = $url . http_build_query($query ?? []);

        if (Cache::has($cache_key)) {
            Cache::put($cache_key, Cache::get($cache_key), 7 * 24 * 60);
            return Cache::get($cache_key);
        }

        $headers = [
            'X-API-KEY' => env('KINOPOISK_API_UNOFFICIAL_TOKEN'),
        ];

        $config = compact('headers', 'query');
        $client = new GuzzleHttpClient($config);

        $response = $client
            ->request($method, $url, $config)
            ->getBody()
            ->getContents();

        Cache::put($cache_key, json_decode($response), 7 * 24 * 60);

        return json_decode($response);
    }
}
