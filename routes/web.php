<?php

use App\Http\Controllers\FilmController;
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

Route::view('watch-lists', 'watch-lists');

Route::controller(FilmController::class)->group(function (Router $router) {
    $router->get('/', 'list');
    
    $router->get('films/{film}', 'show')->name('fimls.show');
});
