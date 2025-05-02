<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $gaurded = [];
    protected $fillable = [
        'day',
        'user_id',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(User::class);
    }
}
