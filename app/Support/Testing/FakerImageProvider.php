<?php

namespace App\Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FakerImageProvider extends Base
{
    public static function image(string $path): string
    {
        $images = Collection::make(glob(base_path('tests/Fixtures/images/products/*')));
        $randomImage = $images->random();

        $name =  Str::random().'.jpg';
        $imagePath = $path . $name;

        if (Storage::disk('public')->directoryMissing($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        $image = File::get($randomImage);

        Storage::disk('public')->put($imagePath, $image);

        return Str::substr(Storage::disk('public')->path($imagePath), 14);
    }
}
