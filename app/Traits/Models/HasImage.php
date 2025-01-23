<?php

namespace App\Traits\Models;

use Illuminate\Support\Facades\File;

trait HasImage
{
    abstract protected function imageDirectory(): string;

    public function makeImage(string $size, string $method = 'resize') {
        return route('storage.image', [
            'size' => $size,
            'method' => $method,
            'dir' => $this->imageDirectory(),
            'file' => File::basename($this->{$this->imageColumn()})
        ]);
    }

    protected function imageColumn(): string {
        return 'thumbnail';
    }
}
