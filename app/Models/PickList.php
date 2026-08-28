<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickList extends Model
{
    use HasFactory;

     protected $fillable = [
        'order_id','product_id','rack_no','level_no','slot_no',
        'batch_no','expiry_date','quantity','status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


}
