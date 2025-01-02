<?php

namespace App\Providers;

use App\Support\Testing\FakerImageProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        app()->singleton(
            Generator::class.':'.config('app.faker_locale'),
            static function () {
                $faker = Factory::create(config('app.faker_locale'));
                $faker->addProvider(FakerImageProvider::class);

                return $faker;
            }
        );
    }

}
