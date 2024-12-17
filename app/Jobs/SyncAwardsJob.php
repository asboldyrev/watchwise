<?php

namespace App\Jobs;

use App\Services\Kinopoisk\Awards;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SyncAwardsJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 300;

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
        Awards::sync($this->filmId);
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
