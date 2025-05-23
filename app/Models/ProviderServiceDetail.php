<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderServiceDetail extends Model
{
    protected $fillable = [
        'service_id',
        'location',
        'description',
        'price'
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
