<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'reference_type',
        'reference_id',
        'movement_type',
        'quantity',
        'unit_cost',
        'batch_no',
        'expiry_date',
        'remarks',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
        public function receiving()
    {
        return $this->belongsTo(StockReceiving::class, 'reference_id');
    }
}
