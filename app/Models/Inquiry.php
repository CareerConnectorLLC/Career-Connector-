<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $gaurded = [];

    public function reply()
    {
        return $this->hasOne(InquiryReply::class);
    }

    /* Filtering */
    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $filter) {
            if (isset($filter['column'], $filter['value'])) {
                $column = $filter['column'];
                $value = $filter['value'];

                switch ($column) {
                    case 'name':
                        $names = explode(' ', trim($value[0]));

                        if (count($names) >= 2) {
                            $query->where(function ($q) use ($names) {
                                $q->where("name", "like", "%" . $names[0] . "%");

                                if (isset($names[1])) {
                                    $q->where("name", "like", "%" . $names[1] . "%")
                                      ->orWhere("name", "like", "%" . $names[1] . "%");
                                }
                            
                                if (isset($names[2])) {
                                    $q->orWhere("name", "like", "%" . $names[2] . "%");
                                }
                            });
                        } else {
                            $query->where("name", "like", "%" . $value[0] . "%")
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
        // dd($query->toSql());
        return $query;
    }

    public function scopeOrdering($query, array $filters)
    {
        $query->when($filters['fieldName'] ?? null, function ($query) use ($filters) {
            $sql = $query->orderBy($filters['fieldName'],$filters['shortBy']);
        });
    }
}
