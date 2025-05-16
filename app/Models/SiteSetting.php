<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteSetting extends Model
{
    use HasFactory;

    protected $gaurded = [];

    protected function socialHandles(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $items = collect(json_decode($value, true))->map(function ($item) {
                    $item['icon_url'] = asset('/frontend_assets/images/'.$item['icon'].'.svg');
                    return $item;
                })->toJson();
                
                return $items;
            }
        );
    }
}
