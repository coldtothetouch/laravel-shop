<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response as Response;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(app()->isLocal());
        Model::preventSilentlyDiscardingAttributes(app()->isLocal());

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(500)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response(
                        'Too many requests',
                        Response::HTTP_TOO_MANY_REQUESTS,
                        $headers,
                    );
                });
        });

        app(Kernel::class)->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function (Request $request) {
                logger()
                    ->channel('telegram')
                    ->debug('whenRequestLifecycleIsLongerThan: '.$request->url);
            },
        );

        DB::whenQueryingForLongerThan(1000, function (Connection $connection) {
            logger()
                ->channel('telegram')
                ->debug(
                    'whenQueryingForLongerThan: '.$connection->query()->toRawSql()
                );
        });
    }

}
