<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    public function __invoke(
        string $dir,
        string $method,
        string $size,
        string $filename,
    )
    {
        abort_if(
            !in_array($size, config('images.allowed_sizes', [])),
            403,
            'Size not allowed'
        );

        $storage = Storage::disk('images');

        if (!$storage->exists("$dir/$method/$size/$filename")) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($storage->path("$dir/$filename"));

            [$w, $h] = explode('x', $size);

            $image->{$method}($w, $h);

            if (!$storage->exists("$dir/$method/$size")) {
                $storage->makeDirectory("$dir/$method/$size");
            }

            $image->save($storage->path("$dir/$method/$size/$filename"));
        }

        return response()->file($storage->path("$dir/$method/$size/$filename"));
    }
}
