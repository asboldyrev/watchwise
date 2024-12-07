<?php

namespace App\Jobs;

use App\Jobs\Middlewares\KinopoiskApiLimit;
use App\Services\Kinopoisk\Theater;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SyncTheatersJob implements ShouldQueue
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
        Theater::sync($this->filmId);
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
