<?php

namespace App\Routing;

use App\Contracts\RouteRegistrarContract;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

class AppRegistrar implements RouteRegistrarContract
{
    public function map(): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/', HomeController::class)->name('home');
            Route::get('/storage/images/{dir}/{method}/{size}/{file}', ImageController::class)
                ->where('method', 'crop|resize|fit')
                ->where('size', '\d+x\d+')
                ->where('file', '.+\.(jpg|jpeg|png|gif)$')
                ->name('storage.image');
        });
    }
}
