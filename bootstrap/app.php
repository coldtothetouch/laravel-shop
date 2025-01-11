<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            RouteServiceProvider::mapRoutes();
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ThrottleRequests::class.':global');
    })
    ->withExceptions(function (Exceptions $exceptions) {
//        $exceptions->reportable(function (Throwable $e) {
//
//        });
//        $exceptions->renderable(function (Throwable $e) {
//
//        });
    })->create();
