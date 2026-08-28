<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReceivingItem extends Model
{
    use HasFactory;

     protected $fillable = [
        'stock_receiving_id',
        'purchase_order_item_id',
        'product_id',
        'po_qty',
        'free_quantity',
        'row_tax',
        'actual_qty',
        'returned_qty',
        'return_reason',
        'to_be_return_qty',
        'to_be_return_reason',
        'short_qty',
        'purchase_rate',
        'batch_no',
        'expiry_date',
        'mrp',
    ];

    public function stockReceiving()
    {
        return $this->belongsTo(StockReceiving::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
