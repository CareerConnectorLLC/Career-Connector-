<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Cms extends Model
{
    use HasSlug;
    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }



    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $search) {
            $query->where('title','like', "%" . trim($search) . "%");
        });
    }

    public function scopeOrdering($query, array $filters)
    {
        $query->when($filters['fieldName'] ?? null, function ($query) use ($filters) {
            $query->orderBy($filters['fieldName'],$filters['sortBy']);
        });
    }

}
