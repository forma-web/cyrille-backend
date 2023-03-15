<?php

use App\Http\Controllers\V1\AuthenticationController;
use App\Http\Controllers\V1\BookController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)
    ->prefix('auth')
    ->as('auth.')
    ->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');
        Route::post('refresh', 'refresh')->name('refresh');
    });

Route::middleware('auth')->group(function () {
    Route::prefix('user')->as('user.')->group(function () {
        Route::get('', [AuthenticationController::class, 'current'])->name('current');
    });
});

Route::controller(BookController::class)
    ->prefix('books')
    ->as('books.')
    ->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
    });
