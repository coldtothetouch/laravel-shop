<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', HomeController::class)->name('home');

Route::as('auth.')->middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login.index');
        Route::post('/login', 'store')->name('login');

        Route::delete('/logout', 'destroy')
            ->withoutMiddleware('guest')
            ->middleware('auth')
            ->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register.index');
        Route::post('/register', 'store')->name('register');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/forgot-password', 'forgotPassword')->name('forgot-password.index');
        Route::post('/forgot-password', 'sendEmail')->name('send-email');
    });

    Route::get('/auth/github/redirect', [LoginController::class, 'github'])->name('github.redirect');
    Route::get('/auth/github/callback', [LoginController::class, 'githubCallback'])->name('github.callback');
});
Route::post('/update-password', [ResetPasswordController::class, 'store'])->name('password.update');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');


