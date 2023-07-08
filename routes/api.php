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
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');
        Route::post('refresh', 'refresh')->name('refresh');

        Route::prefix('password')
            ->as('password.')
            ->group(function () {
                Route::post('reset', 'resetPassword')->name('reset');
                Route::post('verify', 'resetPasswordVerify')->name('verify');
            });
    });

Route::controller(UserController::class)
    ->middleware('auth')
    ->prefix('users')
    ->as('user.')
    ->group(function () {
        Route::get('', 'current')->name('current');
        Route::patch('', 'update')->name('update');
        Route::patch('password', 'updatePassword')->name('updatePassword');
        Route::post('verify', 'verify')->name('verify');
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

                Route::middleware('auth')->group(function () {
                    Route::post('', 'store')->name('store');
                });
            });

        Route::controller(ChapterController::class)
            ->middleware('auth')
            ->prefix('{book}/chapters')
            ->as('chapters.')
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('{chapter}', 'show')->name('show');
            });
    });
