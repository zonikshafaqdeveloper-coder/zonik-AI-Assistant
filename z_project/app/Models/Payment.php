<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $guarded = ['id'];
    
  protected $fillable = [
'order_id', 'user_id', 'outlet_id', 'total_amount', 'total_paid', 'payment_method', 'payment_status', 'payment_id', 'meta'
];


protected $casts = [
'meta' => 'array',
];


public function histories()
{
    return $this->hasMany(PaymentHistory::class, 'payment_id');
}

public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function outlet()
{
    return $this->belongsTo(User::class, 'outlet_id');
}


}
