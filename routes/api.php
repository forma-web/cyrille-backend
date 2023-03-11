<?php

use App\Http\Controllers\V1\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)
    ->prefix('auth')
    ->as('auth.')
    ->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
    });

Route::middleware('auth')
    ->group(function () {
        Route::prefix('user')->as('user.')->group(function () {
            Route::get('', [AuthenticationController::class, 'current'])->name('current');
        });
    });
