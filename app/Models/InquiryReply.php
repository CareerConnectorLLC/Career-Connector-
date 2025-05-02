<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryReply extends Model
{
    /** @use HasFactory<\Database\Factories\InquiryReplyFactory> */
    use HasFactory;

    protected $guarded = [];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
