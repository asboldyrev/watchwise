<?php

use App\Events\FilmImported;
use App\Models\Film;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

Schedule
    ::command(
        'queue:work',
        [
            // '--stop-when-empty',
            '--tries' => 3,
            '--max-time' => 300,
        ]
    )
    ->skip(fn() => DB::table('jobs')->count() == 0)
    ->everyMinute()
    ->withoutOverlapping();

// Schedule::command('reverb:start')->everyMinute()->withoutOverlapping();
