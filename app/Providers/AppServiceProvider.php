<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(app()->isLocal());
        Model::preventSilentlyDiscardingAttributes(app()->isLocal());

//        DB::whenQueryingForLongerThan(1000, function (Connection $connection) {
//
//        });
    }
}
