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
    return [
        'status' => 'ok',
        'version' => config('app.version'),
    ];
});

Route::get('loaderio-fae53a2a2584211a78ac1318994b17fa', function () {
    return 'loaderio-fae53a2a2584211a78ac1318994b17fa';
});
