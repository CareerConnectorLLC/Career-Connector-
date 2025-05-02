<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $gaurded = [];

    public function services()
    {
        return $this->hasMany(Service::class);
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
                        $query->orderBy('active', $filters['shortBy']);
                        break;
            
                    case 'name':
                        $sql = $query->orderBy('name', $filters['shortBy']);
                        break;
                }
            }
            
            
        }
}
