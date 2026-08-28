<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'outlet_id',
        'quantity',
        'offer_price',
        'total_amt_basic',
        'mrp',
        'total_qty',
        'discount',
        'expected_price_value',
        'product_types',
        'count_value',
        'monthlyconsumption'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    function enquery()
    {
        return $this->belongsTo(Enquiry::class ,'enquiry_id');
    }
}
