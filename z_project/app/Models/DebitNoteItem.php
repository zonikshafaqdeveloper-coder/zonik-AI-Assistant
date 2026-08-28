<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebitNoteItem extends Model
{
    protected $fillable = [
        'debit_note_id',
        'stock_receiving_item_id',
        'product_id',
        'batch_no',
        'expiry_date',
        'return_qty',
        'reason',
        'price',
        'tax',
        'total'
    ];

    public function receivingItem()
    {
        return $this->belongsTo(
            \App\Models\StockReceivingItem::class,
            'stock_receiving_item_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}