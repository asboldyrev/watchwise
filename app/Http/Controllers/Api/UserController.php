<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\WatchlistResource;
use App\Models\User;
use App\Models\Watchlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function current()
    {
        return UserResource::make(Auth::user());
    }

    public function watchlists(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Watchlist $watchlist */
        $watchlist = $user->watchLists()->where('id', $request->input('watchlist_id'))->first();

        if ($request->input('status')) {
            $watchlist
                ->films()
                ->attach($request->input('film_id'), [
                    'date' => Carbon::now()
                ]);
        } else {
            $watchlist->films()->detach($request->input('film_id'));
        }

        $watchlists = $user
            ->watchLists()
            ->with([
                'films' => fn($query) => $query->where('films.id', $request->input('film_id')),
            ])
            ->orderBy('name')
            ->get();

        return WatchlistResource::collection($watchlists);
    }
}
