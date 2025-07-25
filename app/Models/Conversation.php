<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest('id');
    }

    public function getParticipantsAttribute()
    {
        $participants = new \Illuminate\Database\Eloquent\Collection();

        if ($this->relationLoaded('customer')) {
            $participants->push($this->customer);
        }

        if ($this->relationLoaded('provider')) {
            $participants->push($this->provider);
        }

        return $participants;
    }

    public function getParticipantNameAttribute()
    {
        if (auth()->check()) {
            if ($this->customer_id === auth()->id()) {
                return $this->provider->name;
            }
            if ($this->provider_id === auth()->id()) {
                return $this->customer->name;
            }
        }
        return null;
    }

    public function getParticipantAvatarAttribute()
    {
        if (auth()->check()) {
            if ($this->customer_id === auth()->id()) {
                return $this->provider->profile_photo_url;
            }
            if ($this->provider_id === auth()->id()) {
                return $this->customer->profile_photo_url;
            }
        }
        return null;
    }
}