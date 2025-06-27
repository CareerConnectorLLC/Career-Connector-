<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderServiceDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'service_id',
        'location',
        'description',
        'price',
        'provider_id',
        'image_path'
    ];

    protected $appends = ['file_path'];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function getFilePathAttribute()
    {
        return (!is_null($this->image_path)) ? asset('storage/' . $this->image_path) : null;
    }
}
