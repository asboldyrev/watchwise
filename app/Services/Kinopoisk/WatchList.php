<?php

namespace App\Services\Kinopoisk;

use App\Models\User;
use App\Services\Kinopoisk\Data\WatchListData;

class WatchList
{
    public static function import(User $user, WatchListData $watchListsData)
    {
        dd('watchListsData');
        if ($user->filmsWithSings()->where('id', $watchListsData->film->id)->count() == 0) {
            $user->filmsWithSings()->attach($watchListsData->film->id, [
                'text' => $watchListsData->sing,
            ]);
        }

        foreach ($watchListsData->lists as $list_name) {
            /** @var WatchList $watch_list */
            $watch_list = $user->watchLists()->where('name', $list_name)->first();

            if (!$watch_list) {
                $watch_list = $user->watchLists()->create(['name' => $list_name]);
            }

            if ($watch_list->films()->where('id', $watchListsData->film->id)->count() == 0) {
                $watch_list->films()->attach($watchListsData->film->id, ['date' => $watchListsData->date]);
            }
        }
    }
}
