<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Film;
use App\Models\Genre;
use App\Models\OnlineTheater;
use App\Models\User;
use App\Models\WatchList;
use App\Services\Kinopoisk\Data\VoteData;
use App\Services\Kinopoisk\Data\WatchListData;
use App\Services\Kinopoisk\ImportUserData;
use App\Services\Kinopoisk\Parser;
use App\Services\Kinopoisk\Vote;
use App\Services\Kinopoisk\WatchList as KinopoiskWatchList;
use App\Services\KinopoiskApiUnofficial\Client;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;
use stdClass;

class ImportDataFromKinopoisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-data-from-kinopoisk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импортируем данные с кинопоиска и связываем с пользователем';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::find(1);
        $client = new Client();

        $this->importVotes($user, $client);
        $this->importWatchLists($user, $client);
    }

    protected function importVotes(User $user, Client $client)
    {
        $vote_filename = storage_path('import/' . $user->id . '/votes.json');

        if (!file_exists($vote_filename)) {
            Parser::parseVotes(storage_path('import/votes.zip'), $user);
        }

        $votes = file($vote_filename);

        while ($vote = array_shift($votes)) {
            $vote = json_decode($vote);
            $this->line('Film id: ' . $vote->film_id);

            if ($client->hasLimitReached()) {
                $this->warn('Достигнут лимит запросов к API');

                return 1;
            }

            try {
                $vote_data = VoteData::make($vote);
                Vote::import($user, $vote_data);
            } catch (Exception $exception) {
                if ($client->hasLimitReached()) {
                    $this->warn('Достигнут лимит запросов к API');
                }
                dd($exception);
                return 1;
            }

            $this->info($vote_data->film->name->ru ?: $vote_data->film->name->original);

            file_put_contents($vote_filename, implode('', $votes));
        }

        $this->info('Импорт оценок завершён');
    }

    protected function importWatchLists(User $user, Client $client)
    {
        $watch_list_filename = storage_path('import/' . $user->id . '/watchlists.json');

        if (!file_exists($watch_list_filename)) {
            Parser::parseWatchLists(storage_path('import/watchlists.zip'), $user);
        }

        $watch_lists = file($watch_list_filename);

        while ($watch_list = array_shift($watch_lists)) {
            $watch_list = json_decode($watch_list);
            $this->line('Film id: ' . $watch_list->film_id);

            if ($client->hasLimitReached()) {
                $this->warn('Достигнут лимит запросов к API');

                return 1;
            }

            try {
                $watch_list_data = WatchListData::make($watch_list);
                KinopoiskWatchList::import($user, $watch_list_data);
            } catch (Exception $exception) {
                if ($client->hasLimitReached()) {
                    $this->warn('Достигнут лимит запросов к API');
                }
                dd($exception);
                return 1;
            }

            $this->info($watch_list_data->film->name->ru ?: $watch_list_data->film->name->original);

            file_put_contents($watch_list_filename, implode('', $watch_lists));
        }

        $this->info('Импорт списков завершён');
    }
}
