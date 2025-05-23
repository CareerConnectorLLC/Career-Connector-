<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use App\Observers\UserObserver;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    protected $appends = [
        'role_names',
        'profile_photo_url',
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

    public function getProfilePhotoUrlAttribute()
    {
        return ($this->profile_photo_path) ? asset('/storage/' . $this->profile_photo_path) : null;
    }

    public function clientBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function providerPersonalInfos(): HasMany
    {
        return $this->hasMany(ProviderPersonalInfo::class, 'provider_id');
    }

    public function providerServiceDetails(): HasMany
    {
        return $this->hasMany(ProviderServiceDetail::class, 'provider_id');
    }

    public function providerDocuments(): HasMany
    {
        return $this->hasMany(ProviderDocument::class, 'provider_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function providerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
    
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
            }
        }
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
