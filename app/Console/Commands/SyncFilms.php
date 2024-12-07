<?php

namespace App\Console\Commands;

use App\Jobs\SyncFilmDataJob;
use App\Models\Film;
use Illuminate\Console\Command;

class SyncFilms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-films';

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
        $films = Film::all();

        foreach ($films as $film) {
            SyncFilmDataJob::dispatch($film->id);
        }
    }
}
