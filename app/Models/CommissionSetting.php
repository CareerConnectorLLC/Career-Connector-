<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    protected $fillable = [
        'booking_charge',
        'service_charge',
    ];

    protected $table = 'commission_settings';
}
