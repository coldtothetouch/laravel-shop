<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        self::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    public function makeSlug(): void
    {
        if ($this->slug) {
            return;
        }

        $slug = str($this->{self::slugFrom()})->slug();
        $baseSlug = $slug;

        $counter = 1;
        while (self::slugExist($slug)) {
            $slug = "$baseSlug-$counter";
            $counter++;
        }

        $this->slug = $slug;
    }

    public static function slugExist(string $slug): bool
    {
        return self::query()
            ->where('slug', $slug)
            ->withoutGlobalScopes()
            ->exists();
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
