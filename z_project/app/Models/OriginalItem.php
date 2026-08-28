<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OriginalItem extends Model
{
    use HasFactory;

      protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'offer_price',
        'mrp',
        'product_name',
        'sku',
    ];

        public function order()
{
    return $this->belongsTo(Order::class);
}

public function product()
{
    return $this->belongsTo(Product::class);
}

}
