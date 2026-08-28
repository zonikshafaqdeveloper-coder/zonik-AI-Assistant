<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneProcessing extends Model
{
    use HasFactory;

    protected $table = 'zoneprocessing';

    protected $fillable = [
        'zone_name',
        'processing_time',
        'shipping_time',
        'delivery_time',
        'bulk_delivery_charges',
        'single_delivery_charges',
        'packing_charge',
        'order_above',
        'same_day_timing',
        'next_day_timing',
        'pay_on_delivery',
        'same_day_slot',
        'next_day_slot',
        'week_day_slot',
        'min_order',
        'others_charges',
        'status',
        'regular_days',
        'delivery_days',
        
    ];
    
     protected $casts = [
    'delivery_days' => 'array',
];
}
