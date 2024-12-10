<?php

use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\PersonController;
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
