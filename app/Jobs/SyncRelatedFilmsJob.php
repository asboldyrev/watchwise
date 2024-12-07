<?php

namespace App\Jobs;

use App\Services\Kinopoisk\RelatedFilms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SyncRelatedFilmsJob implements ShouldQueue
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
        RelatedFilms::sync($this->filmId);
    }

    public function uniqueId(): string
    {
        return $this->filmId;
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping($this->filmId),
        ];
    }
}