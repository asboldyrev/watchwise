<?php

namespace App\Jobs;

use App\Events\FilmImported;
use App\Jobs\Middlewares\KinopoiskApiLimit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;

class SyncFilmDataJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $filmId
    ) {
        $this->onQueue('import_film');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Bus::chain([
            new SyncFilmJob($this->filmId),
            new SyncTheatersJob($this->filmId),
            new SyncSeasonsJob($this->filmId),
            new SyncAwardsJob($this->filmId),
            new SyncRelatedFilmsJob($this->filmId),
            new SyncPersonsJob($this->filmId),
            new FilmImportedEventJob($this->filmId),
        ])->onQueue('import_film')->dispatch();
    }

    public function middleware()
    {
        return [new KinopoiskApiLimit()];
    }
}
