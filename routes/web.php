<?php

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

Route::get('/', function () {
    if (app()->isProduction())
        return ['status' => 'ok'];

    $routes = [];

    foreach (Route::getRoutes()->getRoutes() as $route) {
        if (Str::contains($route->uri, 'api')) {
            $routes[Str::before($route->getName(), '.')][] = [
                'uri' => $route->uri,
                'methods' => $route->methods[0],
            ];
        }
    }

    return view('welcome', [
        'routes' => $routes,
    ]);
});
