<?php

use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\UploadFileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WatchlistController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route
    ::prefix('films')
    ->controller(FilmController::class)
    ->group(function (Router $router) {
        $router->post('search', 'search');
        $router->post('list', 'list');
        $router->post('{film}/show', 'show');
    });

Route
    ::prefix('persons')
    ->controller(PersonController::class)
    ->group(function (Router $router) {
        $router->post('list', 'list');
        $router->post('{person}/show', 'show');
    });

Route
    ::prefix('users')
    ->controller(UserController::class)
    ->group(function (Router $router) {
        $router->post('current', 'current');
        $router->post('watchlists', 'watchlists');
    });

Route
    ::prefix('watchlists')
    ->controller(WatchlistController::class)
    ->group(function (Router $router) {
        $router->post('list', 'list');
        $router->post('store', 'store');
        $router->post('{watchlist}/show', 'show');
        $router->post('{watchlist}/update', 'update');
        $router->post('{watchlist}/delete', 'delete');
    });

Route::prefix('upload-files')
    ->controller(UploadFileController::class)
    ->group(function (Router $router) {

        $router->post('store', 'store');
        $router->post('attach', 'attach');
    });
