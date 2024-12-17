<?php

namespace App\Jobs;

use App\Jobs\Middlewares\KinopoiskApiLimit;
use App\Services\Kinopoisk\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SyncPersonJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $personId
    ) {
        $this->onQueue('import_film');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Person::sync($this->personId);
    }

    public function uniqueId(): string
    {
        return $this->personId;
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping($this->personId),
            new KinopoiskApiLimit(),
        ];
    }
}
