<?php

namespace App\Console\Commands;

use App\Jobs\SyncFilmDataJob;
use App\Models\Film;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncFilms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-films {--ids=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ids = $this->getIds();
        if (count($ids) > 0) {
            $films = Film::whereIn('id', $ids)->get();
        } else {
            $films = Film::all();
        }

        foreach ($films as $film) {
            SyncFilmDataJob::dispatch($film->id);
        }
    }

    protected function getIds(): array
    {
        $mediaIds = $this->option('ids');

        if (! is_array($mediaIds)) {
            $mediaIds = explode(',', (string) $mediaIds);
        }

        if (count($mediaIds) === 1 && Str::contains((string) $mediaIds[0], ',')) {
            $mediaIds = explode(',', (string) $mediaIds[0]);
        }

        return $mediaIds;
    }
}
