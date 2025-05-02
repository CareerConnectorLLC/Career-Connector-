<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Service extends Model
{
    use HasSlug, HasFactory;

    protected $gaurded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function expertises()
    {
        return $this->hasMany(Expertise::class, 'service_id');
    }


    public function serviceProviders()
    {
        return $this->belongsToMany(User::class, 'provider_services', 'service_id', 'user_id');
    }
    public function location()
    {
        return $this->belongsToMany(Location::class, 'provider_services', 'service_id', 'location_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function scopeFilter($query, array $filters)
    // {
    //     if (isset($filters['global']) && $filters['global'] != '') {
    //         $query->when(isset($filters['global']), function ($query) use ($filters) {
    //             $searchTerm = trim($filters['global']);
    //             $nameParts = explode(' ', $searchTerm);
    //             $query->whereHas('expertises', function ($query) use ($searchTerm) {
    //                 $nameParts = explode(' ', $searchTerm);
    //                 foreach ($nameParts as $part) {
    //                     $query->where('name', 'like', '%' . trim($part) . '%');
    //                 }
    //             })->orWhereHas('category', function ($query) use ($nameParts) {
    //                 foreach ($nameParts as $part) {
    //                     $query->where('name', 'like', '%' . trim($part) . '%');
    //                 }
    //             })->orWhere(function ($query) use ($searchTerm) {
    //                 $nameParts = explode(' ', $searchTerm);
    //                 foreach ($nameParts as $part) {
    //                     $query->where('name', 'like', '%' . trim($part) . '%');
    //                 }
    //             });
    //         });
    //     }
    // }

    /* Ordering */
    public function scopeOrdering($query, array $filters)
    {
        if (isset($filters['fieldName'])) {
            switch ($filters['fieldName']) {
                case 'category_name':
                    $sql = $query->when($filters['shortBy'] ?? null, function ($query) use ($filters) {
                        $query->orderBy(Category::select('name')
                            ->whereColumn('categories.id', 'services.category_id'), $filters['shortBy']);  // Order by user's first name
                    });
                    // dd($sql->toSql());
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

                // Handle multiple values with whereIn()
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
