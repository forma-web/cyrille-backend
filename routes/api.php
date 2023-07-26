<?php

use App\Http\Controllers\V1\AuthenticationController;
use App\Http\Controllers\V1\BookController;
use App\Http\Controllers\V1\ChapterController;
use App\Http\Controllers\V1\ReviewController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)
    ->prefix('auth')
    ->as('auth.')
    ->group(function () {
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');
        Route::post('refresh', 'refresh')->name('refresh');
        Route::post('register', 'register')
            ->name('register')
            ->middleware('throttle:1,0.2');
        Route::post('check', 'check')->name('check');

        Route::prefix('password')
            ->middleware('guest')
            ->as('password.')
            ->group(function () {
                Route::post('verify', 'passwordVerify')
                    ->name('verify')
                    ->middleware('throttle:1,0.2');
                Route::post('check', 'passwordCheck')->name('check');
                Route::post('reset', 'passwordReset')->name('reset');
            });
    });

Route::controller(UserController::class)
    ->middleware(['auth', 'verified'])
    ->prefix('users')
    ->as('user.')
    ->group(function () {
        Route::get('', 'current')->name('current');
        Route::patch('', 'update')->name('update');

        Route::prefix('email')
            ->as('email.')
            ->group(function () {
                Route::post('verify', 'emailVerify')
                    ->name('verify')
                    ->middleware('throttle:1,0.2');
                Route::post('check', 'emailCheck')->name('check');
            });
    });

Route::controller(BookController::class)
    ->prefix('books')
    ->as('books.')
    ->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('{book}', 'show')->name('show');

        Route::controller(ReviewController::class)
            ->prefix('{book}/reviews')
            ->as('reviews.')
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::middleware(['auth', 'verified'])->group(function () {
                    Route::post('', 'store')->name('store');
                });
            });

        Route::controller(ChapterController::class)
            ->middleware(['auth', 'verified'])
            ->prefix('{book}/chapters')
            ->as('chapters.')
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('{chapter}', 'show')->name('show');
            });
    });
