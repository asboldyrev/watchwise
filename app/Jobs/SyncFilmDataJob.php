<?php

namespace App\Jobs;

use App\Events\FilmImported;
use App\Jobs\Middlewares\KinopoiskApiLimit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncFilmDataJob implements ShouldQueue
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
        SyncFilmJob::dispatch($this->filmId);
        SyncTheatersJob::dispatch($this->filmId);
        SyncSeasonsJob::dispatch($this->filmId);
        // SyncAwardsJob::dispatch($this->filmId);
        SyncRelatedFilmsJob::dispatch($this->filmId);
        SyncPersonsJob::dispatch($this->filmId);
        FilmImported::dispatch($this->filmId);
    }

    public function middleware()
    {
        return [new KinopoiskApiLimit()];
    }
}
