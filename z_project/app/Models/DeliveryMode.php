<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryMode extends Model
{
    protected $fillable = ['name'];

    public function logistics()
    {
        return $this->hasMany(OrderLogistic::class, 'mode_of_delivery_id');
    }
}
