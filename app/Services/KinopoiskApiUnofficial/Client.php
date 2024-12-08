<?php

namespace App\Services\KinopoiskApiUnofficial;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Client
{
    protected static $maxRetryCount = 3;

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

        if (Cache::get('kinopoisk_unofficial_daily_limit_check')) {
            return true;
        }

        $url = 'v1/api_keys/' . env('KINOPOISK_API_UNOFFICIAL_TOKEN');
        $response = self::request($url);

        $this->setCacheLimitCheck($response);

        return $this->checkDailyLimit($response);
    }

    protected static function request(string $url, array|null $params = null, string $method = 'get')
    {
        $url = env('KINOPOISK_API_UNOFFICIAL_URL') . $url;
        $cache_key = self::generateCacheKey($url, $params);

        // Проверка кеша
        if ($cachedResponse = self::getFromCache($cache_key)) {
            return $cachedResponse;
        }

        $lock = self::getApiLock();
        $response = self::makeApiRequest($method, $url, $params, $lock);

        // Сохранение в кеш
        self::saveToCache($cache_key, $response);

        return $response;
    }

    protected static function generateCacheKey(string $url, array|null $params): string
    {
        return $url . http_build_query($params ?? []);
    }

    protected static function getFromCache(string $cache_key)
    {
        if (Cache::has($cache_key)) {
            Cache::put($cache_key, Cache::get($cache_key), 7 * 24 * 60);
            return Cache::get($cache_key);
        }

        return null;
    }

    protected static function getApiLock()
    {
        return Cache::lock('api_request_lock', 10);
    }

    protected static function makeApiRequest(string $method, string $url, array|null $params, $lock)
    {
        $retry_count = 1;
        $client = self::getHttpClient($params);

        while ($retry_count <= self::$maxRetryCount) {
            if (!$lock->get()) {
                sleep(1);
                continue;
            }

            try {
                usleep(60_000);
                $response = $client
                    ->request($method, $url, ['query' => $params])
                    ->getBody()
                    ->getContents();

                return json_decode($response);
            } catch (ClientException $exception) {
                if ($exception->getCode() == 429) {
                    $retry_count++;
                    Log::warning('Rate limit reached. Retrying...');
                    sleep(1);
                } elseif ($exception->getCode() == 404) {
                    return json_decode('[]');
                } else {
                    Log::error('API request failed', ['exception' => $exception]);
                    throw $exception;
                }
            } finally {
                $lock->release();
            }
        }

        // Если попытки закончились, вернем пустой ответ
        return json_decode('[]');
    }

    protected static function getHttpClient(array|null $params): GuzzleHttpClient
    {
        $headers = [
            'X-API-KEY' => env('KINOPOISK_API_UNOFFICIAL_TOKEN'),
        ];

        return new GuzzleHttpClient(compact('headers', 'params'));
    }

    protected static function saveToCache(string $cache_key, $response)
    {
        Cache::put($cache_key, $response, 7 * 24 * 60);
    }

    protected function setCacheLimitCheck($response)
    {
        // Рассчитываем проценты оставшихся запросов
        $totalQuota = $response?->dailyQuota?->value ?: 1;
        $usedQuota = $response?->dailyQuota?->used ?: 1;
        $remainingQuota = $totalQuota - $usedQuota;

        // Определяем процент оставшихся запросов
        $remainingPercentage = $remainingQuota / $totalQuota;

        // Рассчитываем время жизни кеша в зависимости от оставшихся запросов
        $cacheDuration = match (true) {
            $remainingPercentage >= 0.9 => 10, // 90% и более - 10 минут
            $remainingPercentage >= 0.5 => 5,  // 50% и более - 5 минут
            $remainingPercentage >= 0.1 => 2,  // 10% и более - 2 минуты
            $remainingPercentage >= 0.01 => 0, // 1% и более - кеш отключен
            default => 0, // Меньше 1% - кеш отключен
        };

        Cache::put('kinopoisk_unofficial_daily_limit_check', false, $cacheDuration);
    }

    protected function checkDailyLimit($response)
    {
        $has_daily_limit = $response?->dailyQuota?->value >= 0 && $response?->dailyQuota?->used >= $response?->dailyQuota?->value;

        if ($response && $has_daily_limit) {
            Cache::put('kinopoisk_unofficial_daily_limit', true, Carbon::now()->endOfDay()->diffInMinutes());
            return true;
        }

        return false;
    }
}
