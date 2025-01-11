<?php

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{
    public function fixturesImage(string $fixturesPath, string $storagePath): string
    {
        if (Storage::disk('public')->directoryMissing($storagePath)) {
            Storage::disk('public')->makeDirectory($storagePath);
        }

        $file = $this->generator->file(
            base_path('tests/Fixtures/images/'.$fixturesPath),
            Storage::disk('public')->path($storagePath),
            false
        );

        return '/storage/app/public/'.$storagePath.'/'.$file;
    }
}
