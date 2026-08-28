<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnNote extends Model
{
    use HasFactory;

     protected $fillable = [
        'stock_receiving_id',
        'vendor_id',
        'return_note_no',
        'type',
        'total_amount'
    ];

    public function items()
    {
        return $this->hasMany(ReturnNoteItem::class);
    }

    public function receiving()
    {
        return $this->belongsTo(StockReceiving::class, 'stock_receiving_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
