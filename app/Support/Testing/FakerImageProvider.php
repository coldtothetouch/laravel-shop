<?php

namespace App\Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class FakerImageProvider extends Base
{
    public static function image(): string
    {
        $images = Collection::make(glob(base_path('tests/Fixtures/images/products/*')));
        $randomImage = $images->random();

        $path = '/images/products/'.Str::random().'.jpg';
        $file = File::get($randomImage);
        dd($file);

        Storage::disk('public')->put($path, $file);
        dd(storage_path($path));

        return $path;
    }
}
