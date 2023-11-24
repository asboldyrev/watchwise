<?php

namespace App\Services\KinopoiskApiUnofficial;

use GuzzleHttp\Client as GuzzleHttpClient;

class Client
{
    public function getFilm(int $id)
    {
        return $this->request('films/606646');
    }

    public function getSeasons(int $id)
    {
        return $this->request('films/606646/seasons');
    }

    public function getOnlineTheaters(int $id)
    {
        return $this->request('films/606646/external_sources');
    }

    public function getVideos(int $id)
    {
        return $this->request('films/606646/videos');
    }

    public function getAwards(int $id)
    {
        return $this->request('films/606646/awards');
    }

    public function getDistributions(int $id)
    {
        return $this->request('films/606646/distributions');
    }

    public function getImages(int $id)
    {
        return $this->request('films/606646/images');
    }

    public function getSequelsAndPrequels(int $id)
    {
        return $this->request('films/606646/sequels_and_prequels');
    }

    public function getStaff(int $id)
    {
        return $this->request('staff', query: ['filmId' => $id]);
    }

    protected static function request(string $url, string $method = 'get', $query = null)
    {
        $url = env('KINOPOISK_API_UNOFFICIAL_URL').$url;

        $headers = [
            'X-API-KEY' => env('KINOPOISK_API_UNOFFICIAL_TOKEN'),
        ];

        $config = compact('headers', 'query');
        $client = new GuzzleHttpClient($config);

        $response = $client
            ->request($method, $url, $config)
            ->getBody()
            ->getContents();

        return json_decode($response);
    }
}
