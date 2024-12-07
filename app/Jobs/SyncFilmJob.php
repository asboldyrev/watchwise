<?php

namespace App\Jobs;

use App\Models\Film as ModelsFilm;
use App\Services\Kinopoisk\Film;
use App\Services\KinopoiskApiUnofficial\Client;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use stdClass;

class SyncFilmJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public stdClass|int $filmData,
    ) {
        if (is_int($this->filmData)) {
            $client = new Client();
            $this->filmData = $client->getFilm($this->filmData);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Film::sync($this->filmData);
    }

    public function uniqueId(): string
    {
        return get_film_id($this->filmData);
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping(get_film_id($this->filmData)),
        ];
    }
}
