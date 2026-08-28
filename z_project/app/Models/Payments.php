<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

protected $fillable = ['order_id', 'user_id', 'amount', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
      public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function outlet()
    {
        return $this->belongsTo(User::class, 'outlet_id');
    }
}
