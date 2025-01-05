<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::as('auth.')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login.index');
        Route::post('/login', 'store')->name('login');
        Route::delete('/logout', 'destroy')->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register.index');
        Route::post('/register', 'store')->name('register');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/reset-password', 'resetPassword')->name('reset-password.index');
        Route::post('/reset-password', 'store')->name('reset-password');
        Route::get('/forgot-password', 'forgotPassword')->name('forgot-password.index');
        Route::post('/forgot-password', 'sendEmail')->name('send-email');
    });
});
