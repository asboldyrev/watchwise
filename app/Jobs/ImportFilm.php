<?php

namespace App\Jobs;

use App\Services\Kinopoisk\Film;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportFilm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string|int $filmId,
        protected bool $withSequelsAndPrequels = true
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Film::import($this->filmId, $this->withSequelsAndPrequels);
    }
}
