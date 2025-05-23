<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Service extends Model
{
    use HasSlug, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'image_url'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /* Ordering */
    public function scopeOrdering($query, array $filters)
    {
        if (isset($filters['fieldName'])) {
            switch ($filters['fieldName']) {
                case 'category_name':
                    $sql = $query->when($filters['shortBy'] ?? null, function ($query) use ($filters) {
                        $query->orderBy(Category::select('name')
                            ->whereColumn('categories.id', 'services.category_id'), $filters['shortBy']);
                    });
                    break;
                case 'active':
                    $query->orderBy('active', $filters['shortBy']);
                    break;

                case 'name':
                    $query->orderBy('name', $filters['shortBy']);
                    break;
            }
        }
    }

    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $filter) {
            if (isset($filter['column'], $filter['value'])) {
                $column = $filter['column'];
                $value = $filter['value'];

                if (count($value) > 1) {
                    $query->whereIn($column, $value);
                } else {
                    $query->where($column, $value[0]);
                }
            }
        }
        return $query;
    }
}
