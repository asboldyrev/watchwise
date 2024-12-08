<?php

namespace App\Jobs\Middlewares;

use App\Services\KinopoiskApiUnofficial\Client;

class KinopoiskApiLimit
{
    public function handle($job, $next)
    {
        $client = new Client();

        if ($client->hasLimitReached()) {
            $job->release(12 * 60 * 60); // Попробовать через 12 часов
            return;
        }

        $next($job);
    }
}
