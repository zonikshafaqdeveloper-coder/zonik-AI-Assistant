<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'coupon_name',
        'max_price',
        'coupon_code',
        'start_date',
        'end_date',
        'discount_amount',
        'description',
        'is_active',
    ];


}
