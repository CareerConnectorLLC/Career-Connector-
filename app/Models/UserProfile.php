<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['birth_day'];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'datetime:l j M, Y',
            
        ];
    }

    protected function getBirthDayAttribute()
    {
        return (!is_null($this->date_of_birth)) ? now()->parse($this->date_of_birth)->format('m/d/Y') : '';
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
