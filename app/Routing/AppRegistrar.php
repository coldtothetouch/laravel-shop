<?php

namespace App\Routing;

use App\Contracts\RouteRegistrarContract;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

class AppRegistrar implements RouteRegistrarContract
{
    public function map(): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/', HomeController::class)->name('home');
        });
    }
}
