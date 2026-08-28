<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackStock extends Model
{
    use HasFactory;

     protected $fillable = [
        'stock_receiving_id',
        'product_id',
        'batch_no',
        'expiry_date',
        'quantity',
        'rack_no',
        'level_no',
        'slot_no',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockReceiving()
    {
        return $this->belongsTo(StockReceiving::class);
    }
    
     protected $casts = [
        'expiry_date' => 'date',
    ];
}
