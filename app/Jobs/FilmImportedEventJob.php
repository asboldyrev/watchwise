<?php

namespace App\Jobs;

use App\Events\FilmImported;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class FilmImportedEventJob implements ShouldQueue
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

    public function handle()
    {
        event(new FilmImported($this->filmId));
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
