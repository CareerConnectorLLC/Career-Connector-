<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Location extends Model
{
    use HasSlug;
    protected $gaurded  = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'provider_services', 'location_id', 'service_id');
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['global']) && $filters['global'] != '') {
            $query->when(isset($filters['global']), function ($query) use ($filters) {
                $searchTerm = trim($filters['global']);
                $nameParts = explode(' ', $searchTerm);
                $query->where(function ($query) use ($searchTerm) {
                    $nameParts = explode(' ', $searchTerm);
                    foreach ($nameParts as $part) {
                        $query->where('name', 'like', '%' . trim($part) . '%');
                    }
                });
            });

        }
    }

        /* Ordering */
        public function scopeOrdering($query, array $filters)
        {   
            if (isset($filters['fieldName'])) {
                switch ($filters['fieldName']) {          
                    case 'active':
                        $sql = $query->orderBy('active', $filters['shortBy']);
                        break;
            
                    case 'name':
                        $sql = $query->orderBy('name', $filters['shortBy']);
                        break;
                }
            }
            
            
        }
}
