<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'in_invoice',
        'price',
        'coupon_discount',
        'offer_price',
        'mrp',
    ];

    public function order()
{
    return $this->belongsTo(Order::class);
}

public function product()
{
    return $this->belongsTo(Product::class);
}

public function originalItem()
{
    return $this->hasOne(OriginalItem::class)
        ->whereColumn('original_items.order_id', 'order_items.order_id')
        ->whereColumn('original_items.product_id', 'order_items.product_id');
}


        // public function outstanding()
        // {
        //     return $this->belongsTo(User::class, 'user_id');
        // }

}
