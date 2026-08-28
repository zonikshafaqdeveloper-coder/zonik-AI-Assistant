<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreMaterialShortLog extends Model
{
    use HasFactory;

     protected $fillable = [
        'product_id',
        'order_id',
        'required_qty',
        'available_stock',
        'comment',
        'lost_value'
    ];


public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}

public function stock()
{
    return $this->hasOne(ProductStock::class, 'product_id', 'product_id');
}

}
