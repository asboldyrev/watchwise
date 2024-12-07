<?php

namespace App\Jobs;

use App\Jobs\Middlewares\KinopoiskApiLimit;
use App\Services\Kinopoisk\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SyncPersonsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $filmId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Person::syncMany($this->filmId);
    }

    public function uniqueId(): string
    {
        return $this->filmId;
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping($this->filmId),
            new KinopoiskApiLimit(),
        ];
    }
}