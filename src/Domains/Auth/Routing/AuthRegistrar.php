<?php

namespace Domains\Auth\Routing;

use App\Contracts\RouteRegistrarContract;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

class AuthRegistrar implements RouteRegistrarContract
{
    public function map(): void
    {
        Route::middleware('web')->group(function () {
            Route::controller(LoginController::class)->as('auth.')->group(function () {
                Route::get('/login', 'index')
                    ->middleware('guest')
                    ->name('login.index');

                Route::post('/login', 'store')
                    ->middleware(['guest', 'throttle:auth'])
                    ->name('login.store');

                Route::delete('/logout', 'destroy')
                    ->middleware('auth')
                    ->name('logout');

                Route::get('/auth/github/redirect', 'github')
                    ->middleware('guest')
                    ->name('github.redirect');

                Route::get('/auth/github/callback', 'githubCallback')
                    ->middleware('guest')
                    ->name('github.callback');
            });

            Route::controller(RegisterController::class)->as('auth.')->group(function () {
                Route::get('/register', 'index')
                    ->middleware('guest')
                    ->name('register.index');

                Route::post('/register', 'store')
                    ->middleware(['guest', 'throttle:auth'])
                    ->name('register');
            });

            Route::controller(ResetPasswordController::class)->group(function () {
                Route::get('/reset-password/{token}', 'index')
                    ->middleware('guest')
                    ->name('password.reset');

                Route::post('/update-password','store')
                    ->middleware('guest')
                    ->name('auth.password.update');
            });

            Route::controller(ForgotPasswordController::class)->as('auth.')->group(function () {
                Route::get('/forgot-password', 'index')
                    ->middleware('guest')
                    ->name('forgot-password.index');

                Route::post('/forgot-password', 'store')
                    ->middleware('guest')
                    ->name('send-email');
            });
        });
    }
}
