<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WatchList;

class WatchListController extends Controller
{
    public function show(WatchList $watchList)
    {
        $watch_lists = WatchList::withCount('films')->orderBy('name')->get();

        $films = $watchList->films()->orderByPivot('date')->paginate(30);

        return view('watch-lists')
            ->with('watchLists', $watch_lists)
            ->with('currentWatchList', $watchList)
            ->with('films', $films);
    }
}
