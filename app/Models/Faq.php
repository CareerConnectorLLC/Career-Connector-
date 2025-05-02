<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Faq extends Model
{
    use HasSlug;
    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('question')
            ->saveSlugsTo('slug');
    }

    /* Filter */
    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['global']) && $filters['global'] != '') {
            $query->when(isset($filters['global']), function ($query) use ($filters) {
                $searchTerm = trim($filters['global']);
                $query->where(function ($query) use ($searchTerm) {
                    $nameParts = explode(' ', $searchTerm);
                    foreach ($nameParts as $part) {
                        $query->orWhere('question', 'like', '%' . trim($part) . '%');
                    }
                    $query->orWhere('answer', 'like', '%' . $searchTerm . '%');
                });
            });
        }
    }

    public function scopeOrdering($query, array $filters)
    {
        if (isset($filters['fieldName']) && $filters['fieldName'] == 'full_name') {
            $filters['fieldName'] = 'first_name';
        }
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use ($filters) {
            $sql = $query->orderBy($search, $filters['shortBy']);
            //  = $query->orderBy($search, $filters['shortBy'])->toSql();
            // dd($sql->toSql());
        });
    }
}
