<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_items';

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'cost_per_item',
        'vendor_price',
        'mrp',
        'profit_margin',
        'quantity',
        'free_quantity',
        'row_tax',
        'received_qty',
        'amount',
    ];


    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrderDetail::class,
            'purchase_order_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
