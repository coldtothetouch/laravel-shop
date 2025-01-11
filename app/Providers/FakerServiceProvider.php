<?php

namespace App\Providers;

use Support\Testing\FakerImageProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        app()->singleton(
            Generator::class,
            static function () {
                $faker = Factory::create(config('app.faker_locale'));
                $faker->addProvider(new FakerImageProvider($faker));

                return $faker;
            }
        );
    }

}
