<?php

namespace App\Console\Commands;

use App\Models\Film;
use App\Services\Omdb\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ImportOtherRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-other-rating';

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
        // $this->removeKinoOgon();
        $exclude_films = Cache::get('exclude_films', []);
        $films = Film
            ::whereNot(
                fn ($query) =>
                $query
                    ->where('rating', 'like', '%Rotten Tomatoes%')
                    ->where('rating', 'like', '%Metacritic%')
            )
            ->whereNotIn('id', $exclude_films)
            ->get();

        // dd($films->count(), count($exclude_films));

        /** @var Film $film */
        foreach ($films as $film) {
            $find_rating = 0;
            Cache::put('exclude_films', $exclude_films, 43_200);
            $this->line($film->getName());

            $rating = $film->rating;
            if (
                (
                    !key_exists('Rotten Tomatoes', $film->rating) ||
                    !key_exists('Metacritic', $film->rating)
                ) &&
                $film->imdb_id
            ) {

                try {
                    $data = Client::get($film);
                } catch (ClientException $exception) {
                    $this->error('Ошибка в данных');
                    return;
                }

                if (!$data) {
                    $this->error('Ошибка в данных');
                    $exclude_films[] = $film->id;
                    continue;
                }

                $ratings = $data->Ratings;

                $rt = array_filter($ratings, fn ($item) => $item->Source == 'Rotten Tomatoes');
                $metacritic = array_filter($ratings, fn ($item) => $item->Source == 'Metacritic');

                if (count($rt)) {
                    $find_rating++;
                    $rt = array_shift($rt);
                    $rating['Rotten Tomatoes'] = [
                        'count' => null,
                        'review' => intval($rt->Value),
                    ];
                }

                if (count($metacritic)) {
                    $find_rating++;
                    $metacritic = array_shift($metacritic);
                    $rating['Metacritic'] = [
                        'count' => null,
                        'review' => intval($metacritic->Value),
                    ];
                }

                if ($find_rating < 2) {
                    $exclude_films[] = $film->id;
                }
            } elseif (!$film->imdb_id) {
                $this->error($film->getName());
                $exclude_films[] = $film->id;
                continue;
            }

            if ($find_rating == 2) {
                $rating['Кино Огонь'] = [
                    'count' => null,
                    'review' => $film->getKinoOgonRating($rating),
                ];
            }

            if ($film->rating != $rating) {
                $film->update(compact('rating'));

                $this->info($film->getName());
            } else {
                $this->comment($film->getName());
            }
        }
    }

    protected function removeKinoOgon()
    {
        $films = Film
            ::whereNot(
                fn ($query) =>
                $query
                    ->where('rating', 'like', '%Rotten Tomatoes%')
                    ->orWhere('rating', 'like', '%Metacritic%')
            )
            ->where('rating', 'like', '%Кино Огонь%')
            ->get();

        // dd($films->count());
        /** @var Film $film */
        foreach ($films as $film) {
            if(key_exists('Кино Огонь', $film->rating) && $film->rating['Кино Огонь']['review'] == '-') {
                $rating = $film->rating;
                unset($rating['Кино Огонь']);
                $film->update(compact('rating'));
                $this->info($film->getName());
            }
        }
    }
}
