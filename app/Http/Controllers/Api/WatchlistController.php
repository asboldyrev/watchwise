<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KinopoiskUnofficial\FilmResource;
use App\Http\Resources\WatchlistResource;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function list(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $watchlists = $user
            ->watchLists()
            ->with([
                'films' => fn($query) => $query->where('films.id', $request->input('film_id')),
            ])
            ->orderBy('name')
            ->get();

        return WatchlistResource::collection($watchlists);
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $user
            ->watchLists()
            ->create([
                'name' => $request->input('name'),
            ]);

        return $this->list($request);
    }

    public function show(Watchlist $watchlist)
    {
        return WatchlistResource::make($watchlist);
    }

    public function update(Request $request, Watchlist $watchlist)
    {
        $watchlist->update([
            'name' => $request->input('name'),
        ]);

        return $this->show($watchlist);
    }

    public function delete(Watchlist $watchlist)
    {
        //
    }
}
