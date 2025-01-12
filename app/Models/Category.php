<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'slug',
        'title',
        'on_home_page',
        'sorting',
    ];

    public function scopeHomePage(Builder $builder): void
    {
        $builder->where('on_home_page', true)
            ->orderBy('sorting');
    }

    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
