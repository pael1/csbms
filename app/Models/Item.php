<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'customer_id',
        'horse_power_id',
        'mounting_type_id',
        'type_id',
        'item_number',
        'indoor_model',
        'indoor_sn',
        'outdoor_model',
        'outdoor_sn',
    ];

    protected $casts = [
        'indoor_sn' => 'array',
        'outdoor_sn' => 'array',
    ];

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
    public function horsePower()
    {
        return $this->belongsTo(\App\Models\HoresPower::class);
    }
    public function mountingType()
    {
        return $this->belongsTo(\App\Models\MountingType::class);
    }
    public function type()
    {
        return $this->belongsTo(\App\Models\Type::class);
    }
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }
}
