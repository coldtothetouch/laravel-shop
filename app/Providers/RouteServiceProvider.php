<?php

namespace App\Providers;

use App\Contracts\RouteRegistrarContract;
use App\Routing\AppRegistrar;
use Domains\Auth\Routing\AuthRegistrar;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class RouteServiceProvider extends ServiceProvider
{
    protected static array $registrars = [
        AppRegistrar::class,
        AuthRegistrar::class
    ];

    public static function mapRoutes(): void
    {
        foreach (self::$registrars as $registrar) {
            if (! class_exists($registrar) || ! is_subclass_of($registrar, RouteRegistrarContract::class)) {
                throw new RuntimeException(sprintf(
                    'Cannot map routes \'%s\', it is not a valid routes class',
                    $registrar
                ));
            }

            (new $registrar)->map();
        }
    }
}
