<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertiseProvider extends Model
{
    protected $gaurded = [];

    public function serviceProviders()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function expertise()
    {
        return $this->belongsTo(Expertise::class, 'expertise_id');
    }
}
