<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'offer_price',
        'offer_check',
        'mrp',
        'discount',
        'status',
        'cost_per_item',
        'expected_price_value',
        'product_types',
        'user_id',
        'monthlyconsumption',
        'enquiry_no',
        'rejected',
        'reoffer',
        'reoffer_count',
        'rejected_customer_comment',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    


}
