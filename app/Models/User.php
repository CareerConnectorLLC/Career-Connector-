<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasProfilePhoto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasProfilePhoto;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    protected $appends = [
        'role_names',
        'profile_photo_url',
        'full_name',
        'role_permission'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'string',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRoleNamesAttribute()
    {
        if ($this->roles()->exists()) {
            return $this->getRoleNames();
        } else {
            return collect([]);
        }
    }
    public function getRolePermissionAttribute()
    {
        if ($this->roles()->exists())
            return $this->getAllPermissions();
        else
            return 0;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function clientBookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function providerBookings()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'provider_services', 'user_id', 'service_id');
    }

    public function expertises()
    {
        return $this->hasManyThrough(Expertise::class, ExpertiseProvider::class, 'user_id', 'id', 'id', 'expertise_id');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function categories()
    {
        return $this->services->map(function ($service) {
            return $service->category; // Access the category for each service
        })->unique('id'); // Remove duplicates by category id
    }
    

    /* Filter */
    // public function scopeFilter($query, array $filters)
    // {
    //     if (isset($filters['global']) && $filters['global'] != '') {
    //         $query->when(isset($filters['global']), function ($query) use ($filters) {
    //             $searchTerm = trim($filters['global']);
    //             $query->where(function ($query) use ($searchTerm) {
    //                 $nameParts = explode(' ', $searchTerm);
    //                 foreach ($nameParts as $part) {
    //                     $query->orWhere('first_name', 'like', '%' . trim($part) . '%')
    //                         ->orWhere('middle_name', 'like', '%' . trim($part) . '%')
    //                         ->orWhere('last_name', 'like', '%' . trim($part) . '%');
    //                 }
    //                 $query->orWhere('email', 'like', '%' . $searchTerm . '%');
    //             });
    //         });
    //     }

    //     $query->when(isset($filters['active']) ?? null, function ($query, $search) use ($filters) {
    //         $query->where('active', $filters['active']);
    //     });
    // }

    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $filter) {
            if (isset($filter['column'], $filter['value'])) {
                $column = $filter['column'];
                $value = $filter['value'];

                switch ($column) {
                    case 'services':
                        $this->applyRelationshipFilter($query, $column, $value);
                        break;

                    case 'category':
                        $this->applyRelationshipFilter($query, $column, $value);
                        break;

                    case 'location':
                        $this->applyRelationshipFilter($query, $column, $value);
                        break;

                    case 'name':
                        $names = explode(' ', trim($value[0]));

                        if (count($names) >= 2) {
                            $query->where(function ($q) use ($names) {
                                $q->where("first_name", "like", "%" . $names[0] . "%");

                                if (isset($names[1])) {
                                    $q->where("middle_name", "like", "%" . $names[1] . "%")
                                      ->orWhere("last_name", "like", "%" . $names[1] . "%");
                                }
                            
                                if (isset($names[2])) {
                                    $q->orWhere("last_name", "like", "%" . $names[2] . "%");
                                }
                            });
                        } else {
                            $query->where("first_name", "like", "%" . $value[0] . "%")
                                  ->orWhere("last_name", "like", "%" . $value[0] . "%")
                                  ->orWhere("email", "like", "%" . $value[0] . "%");
                        }
                        break;
                    
                    case 'created_at':
                        if (count($value) > 1 && isset($value[1])) {
                            $first_date = Carbon::parse($value[0])->timezone('Asia/Kolkata')->startOfDay();
                            $second_date = Carbon::parse($value[1])->timezone('Asia/Kolkata')->endOfDay();
                            $query->whereBetween($column, [$first_date, $second_date]);
                        } else {
                            $first_date = Carbon::parse($value[0])->timezone('Asia/Kolkata')->toDateString();
                            $query->whereDate($column, [$first_date]);
                        }
                        break;
                    
                    default:
                        if (count($value) > 1) {
                            $query->whereIn($column, $value);
                        } else {
                            $query->where($column, $value[0]);
                        }
                        break;
                }


                // Handle multiple values with whereIn()
                // if (count($value) > 1) {
                //     $query->whereIn($column, $value);
                // } else {
                //     $query->where($column, $value[0]);
                // }
                
            }
        }
        // dd($query->toSql());
        return $query;
    }


    public function scopeOrdering($query, array $filters)
    {
        if (isset($filters['fieldName']) && $filters['fieldName'] == 'full_name') {
            $filters['fieldName'] = 'first_name';
        }
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use ($filters) {
            $query->orderBy($search, $filters['shortBy']);
        });
    }

        /**
     * Apply filters for relationships like technologies and departments.
     */
    protected function applyRelationshipFilter($query, string $relation, $searchText)
    {
        $relationshipMap = [
            'services'      =>      'services.name',
            'category'      =>      'services.category',
            'location'      =>      'services.location',
        ];

        if (!isset($relationshipMap[$relation])) {
            return;
        }
        $column = $relationshipMap[$relation];
        switch ($relation) {
            case 'category':
                $column =  'name';
                $query->whereHas($relationshipMap[$relation], function ($query) use ($column, $searchText) {
                    if (count($searchText) > 1) {
                        $query->whereIn($column, $searchText);
                    } else {
                        $query->where($column, $searchText[0]);
                    }
                });
                break;

            case 'location':
                $column =  'name';
                $query->whereHas($relationshipMap[$relation], function ($query) use ($column, $searchText) {
                    if (count($searchText) > 1) {
                        $query->whereIn($column, $searchText);
                    } else {
                        $query->where($column, $searchText[0]);
                    }
                });
                break;
            
            default:
                $query->whereHas($relation, function ($query) use ($column, $searchText) {
                    if (count($searchText) > 1) {
                        $query->whereIn($column, $searchText);
                    } else {
                        $query->where($column, $searchText[0]);
                    }
                });
                break;
        }

    }
}
