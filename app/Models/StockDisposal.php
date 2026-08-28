<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDisposal extends Model
{
    use HasFactory;
 
    protected $table = 'stock_disposals';

    protected $fillable = [
        'product_id',
        'stock_receiving_id',
        'batch_no',
        'expiry_date',
        'quantity',
        'unit_cost',
        'total_value',
        'stock_type',
        'reason',
        'disposed_by'
    ];


    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'disposed_by');
    }
}
