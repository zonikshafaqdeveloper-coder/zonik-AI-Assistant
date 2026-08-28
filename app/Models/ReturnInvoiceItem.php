<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInvoiceItem extends Model
{
    protected $fillable = [
        'return_invoice_id',
        'order_item_id',
        'return_qty',
        'reason',
        'price',
        'tax',
        'total'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

