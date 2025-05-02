<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $filter) {
            if (isset($filter['column'], $filter['value'])) {
                $column = $filter['column'];
                $value = $filter['value'];

                switch ($column) {
                    case 'service':
                        $this->applyRelationshipFilter($query, $column, $value);
                        break;
                    case 'client_name':
                        $this->applyRelationshipFilter($query, $column, $value);
                        break;
                    
                    case 'created_at':
                        if (count($value) > 1 && isset($value[1])) {
                            $first_date = Carbon::parse($value[0])->timezone('Asia/Kolkata')->toDateString();
                            $second_date = Carbon::parse($value[1])->timezone('Asia/Kolkata')->toDateString();
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
        // dd($query->toSql());
        return $query;
    }

    /* Ordering */
    public function scopeOrdering($query, array $filters)
    {
        $query->when($filters['fieldName'] ?? null, function ($query, $search) use ($filters) {
            $query->orderBy($search, $filters['shortBy']);
        });
    }

    protected function applyRelationshipFilter($query, string $relation, $searchText)
    {
        $relationshipMap = [
            'service'       =>      'service.name',
            'client_name'   =>      'client'
        ];

        if (!isset($relationshipMap[$relation])) {
            return;
        }
        $column = $relationshipMap[$relation];
        // dd($column);
        switch ($relation) {
            case 'client_name':
                $names = explode(' ', trim($searchText[0]));
                $query->whereHas($column, function ($query) use ($names) {
                    if (count($names) >= 2) {
                        $query->where(function ($q) use ($names) {
                            $q->where("first_name", "like", "%" . $names[0] . "%")
                              ->where("last_name", "like", "%" . $names[1] . "%");
                        });
                    } else {
                        $query->where("first_name", "like", "%" . $names[0] . "%")
                        ->orWhere("last_name", "like", "%" . $names[0] . "%")
                        ->orWhere("email", "like", "%" . $names[0] . "%");
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
