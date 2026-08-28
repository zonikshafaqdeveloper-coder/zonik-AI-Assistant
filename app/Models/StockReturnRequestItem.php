<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReturnRequestItem extends Model
{
  protected $fillable = [
        'stock_return_request_id',
        'item_type',
        'order_item_id',
        'product_id',
        'purchase_rate',
        'customer_price',
        'return_qty',
        'return_stock_type',
        'rack_no',
        'level_no',
        'slot_no',
        'batch_no',
        'expiry_date',
        'new_rack_no',
        'new_level_no',
        'new_slot_no',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function orderItem() { return $this->belongsTo(OrderItem::class); }
}