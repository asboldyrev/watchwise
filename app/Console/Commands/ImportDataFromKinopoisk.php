<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class ImportDataFromKinopoisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-data-from-kinopoisk';

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
        $films = [
            'votes' => $this->importVotes(),
            'watch_lists' => $this->importWatchLists(),
        ];

        file_put_contents(storage_path('import/data.json'), json_encode($films, JSON_UNESCAPED_UNICODE));
    }

    protected function importVotes()
    {
        $votes = [];

        foreach (glob(storage_path('import/votes/*')) as $path) {
            if (is_dir($path)) {
                continue;
            }

            $html = file_get_contents($path);
            $crawler = new Crawler($html);

            $crawler->filter('.profileFilmsList .item')->each(function (Crawler $crawler) use (&$votes) {
                $id = $crawler->filter('.rateNow')->attr('mid');
                $vote = $crawler->filter('.rateNow')->attr('vote');

                if (! array_key_exists($id, $votes)) {
                    $votes[$id] = [
                        'vote' => $vote,
                        'date' => $crawler->filter('.date')->text(),
                    ];
                }
            });
        }

        return $votes;
    }

    protected function importWatchLists()
    {
        $watch_lists = [];

        foreach (glob(storage_path('import/watchlists/*')) as $path) {
            if (is_dir($path)) {
                continue;
            }

            $html = file_get_contents($path);
            $crawler = new Crawler($html);

            $listName = '';

            $crawler->filter('#folderList')->each(function (Crawler $crawler) use (&$listName) {
                $item = $crawler->filter('.act');
                if ($item->count()) {
                    preg_match('/^([\w|\W]*)\s*\([\d]+\)/mU', $crawler->filter('.act .nameAndNum')->html(), $matches);
                    $listName = $matches[1];
                }
            });

            $crawler->filter('#itemList li')->each(function (Crawler $crawler) use (&$watch_lists, $listName) {
                $id = $crawler->attr('data-id');

                if (array_key_exists($id, $watch_lists)) {
                    $watch_lists[$id]['lists'][] = $listName;
                } else {
                    $watch_lists[$id] = [
                        'date' => $crawler->filter('.remove')->nextAll()->first()->text(),
                        'sing' => $crawler->filter('.sign textarea')->text(),
                        'lists' => [$listName],
                    ];
                }
            });
        }

        return $watch_lists;
    }
}
