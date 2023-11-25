<?php

namespace App\Services\Kinopoisk;

use Symfony\Component\DomCrawler\Crawler;

class ImportUserData
{
    public static function parse()
    {
        return [
            'votes' => self::importVotes(),
            'watch_lists' => self::importWatchLists(),
        ];
    }

    protected static function importVotes()
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

    protected static function importWatchLists()
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
