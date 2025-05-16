<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{   
    use HasSlug;

    protected $guarded = [];

    protected $appends = [
        'teaser',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function getTeaserAttribute()
    {
        return \Str::substr($this->text_content, 0,100);
    }

    protected function ImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (!is_null($value)) ? asset('storage/'.$value) : null
        );
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }
    
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['global']) && $filters['global'] != '') {
            $query->when(isset($filters['global']), function ($query) use ($filters) {
                $searchTerm = trim($filters['global']);
                $nameParts = explode(' ', $searchTerm);
                $query->whereHas('user', function ($query) use ($searchTerm) {
                    $nameParts = explode(' ', $searchTerm);
                    foreach ($nameParts as $part) {
                        $query->where('first_name', 'like', '%' . trim($part) . '%')
                        ->orWhere('last_name', 'like', '%' . trim($part) . '%');
                    }
                })->orWhereHas('category', function ($query) use ($nameParts) {
                    foreach ($nameParts as $part) {
                        $query->where('name', 'like', '%' . trim($part) . '%');
                    }
                })->orWhere(function ($query) use ($searchTerm) {
                    $nameParts = explode(' ', $searchTerm);
                    foreach ($nameParts as $part) {
                        $query->where('title', 'like', '%' . trim($part) . '%');
                    }
                });
            });
        }
    }

    public function scopeOrdering($query, array $filters)
    {
        if (isset($filters['fieldName'])) {
            switch ($filters['fieldName']) {
                case 'full_name':
                    $sql = $query->when($filters['shortBy'] ?? null, function ($query) use ($filters) {
                        $query->orderBy(User::select('first_name')
                        ->whereColumn('users.id', 'blogs.user_id'), $filters['shortBy']);  // Order by user's first name
                    });
                    break;                
                case 'active':
                    $query->orderBy('active', $filters['shortBy']);
                    break;
        
                case 'title':
                    $query->orderBy('title', $filters['shortBy']);
                    break;
            }
        }        
    }
}