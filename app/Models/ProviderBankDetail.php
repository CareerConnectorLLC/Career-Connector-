<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderBankDetail extends Model
{
    protected $guarded = [];

    public static function booted(): void
    {
        static::saving(function ($model) {
            if ($model->is_primary) {
                auth()->user()
                    ->bankDetails()
                    ->where('id', '!=', $model->id)
                    ->update(['is_primary' => false]);
            }
        });
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }
}
