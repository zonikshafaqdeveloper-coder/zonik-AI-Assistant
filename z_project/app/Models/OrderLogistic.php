<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLogistic extends Model
{
    protected $fillable = [
        'order_id',
        'rack_no',
        'no_of_box',
        'delivery_priority',
        'mode_of_delivery_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function mode()
    {
        return $this->belongsTo(DeliveryMode::class, 'mode_of_delivery_id');
    }
}
