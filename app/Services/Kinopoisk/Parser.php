<?php

namespace App\Services\Kinopoisk;

use App\Models\User;
use Exception;
use Symfony\Component\DomCrawler\Crawler;
use ZipArchive;

class Parser
{
    public static function parseVotes(string $zipFile, User $user)
    {
        if (file_exists($zipFile) && mime_content_type($zipFile) == 'application/zip') {
            $zip = new ZipArchive;
            $resource = $zip->open($zipFile);
            if ($resource === true) {
                $zip->extractTo(storage_path('import/' . $user->id . '/votes'));
                $zip->close();
            } else {
                dd('ошибка с кодом:' . $resource);
            }

            $votes = [];

            foreach (glob(storage_path('import/' . $user->id . '/votes/*')) as $path) {
                if (is_dir($path)) {
                    continue;
                }

                $html = file_get_contents($path);
                $crawler = new Crawler($html);

                $crawler->filter('.profileFilmsList .item')->each(function (Crawler $crawler) use (&$votes) {
                    $id = $crawler->filter('.rateNow')->attr('mid');
                    $vote = $crawler->filter('.rateNow')->attr('vote');

                    if (!array_key_exists($id, $votes)) {
                        $votes[] = json_encode([
                            'film_id' => $id,
                            'vote' => $vote,
                            'date' => $crawler->filter('.date')->text(),
                        ]);
                    }
                });
            }

            file_put_contents(storage_path('import/' . $user->id . '/votes.json'), implode(PHP_EOL, $votes));
            exec('rm -rf ' . storage_path('import/' . $user->id . '/votes'));
            exec('rm -rf ' . $zipFile);

            return storage_path('import/' . $user->id . '/votes.json');
        }

        throw new Exception("Файл отсутствует или не является zip-архивом", 1);
    }

    public static function parseWatchLists(string $zipFile, User $user)
    {
        if (file_exists($zipFile) && mime_content_type($zipFile) == 'application/zip') {
            $zip = new ZipArchive;
            $resource = $zip->open($zipFile);
            if ($resource === true) {
                $zip->extractTo(storage_path('import/' . $user->id . '/watchlists'));
                $zip->close();
            } else {
                dd('ошибка с кодом:' . $resource);
            }

            $watch_lists = [];

            foreach (glob(storage_path('import/' . $user->id . '/watchlists/*')) as $path) {
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
                            'film_id' => $id,
                            'date' => $crawler->filter('.remove')->nextAll()->first()->text(),
                            'sing' => $crawler->filter('.sign textarea')->text(),
                            'lists' => [$listName],
                        ];
                    }
                });
            }

            $watch_lists = array_map(fn ($item) => json_encode($item), $watch_lists);

            file_put_contents(storage_path('import/' . $user->id . '/watchlists.json'), implode(PHP_EOL, $watch_lists));
            exec('rm -rf ' . storage_path('import/' . $user->id . '/watchlists'));
            exec('rm -rf ' . $zipFile);

            return storage_path('import/' . $user->id . '/watchlists.json');
        }

        throw new Exception("Файл отсутствует или не является zip-архивом", 1);
    }
}
