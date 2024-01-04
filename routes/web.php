<?php

use App\Http\Controllers\Web\FilmController;
use App\Http\Controllers\Web\VoteController;
use App\Http\Controllers\Web\WatchListController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(VoteController::class)->group(function (Router $router) {
    $router->get('/', 'list')->name('votes.list');
});

Route::controller(WatchListController::class)->group(function (Router $router) {
    $router->get('watch-lists/{watchList}', 'show')->name('watch-lists.show');
});

Route::controller(FilmController::class)->group(function (Router $router) {
    $router->get('films/{film}', 'show')->name('fimls.show');
});
