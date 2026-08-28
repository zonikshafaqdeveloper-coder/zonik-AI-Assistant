<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnNoteItem extends Model
{
    use HasFactory;

     protected $fillable = [
        'return_note_id',
        'stock_receiving_item_id',
        'product_id',
        'qty',
        'reason',
        'price',
        'tax',
        'total'
    ];

    public function returnNote()
    {
        return $this->belongsTo(ReturnNote::class);
    }

    public function receivingItem()
    {
        return $this->belongsTo(StockReceivingItem::class, 'stock_receiving_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
