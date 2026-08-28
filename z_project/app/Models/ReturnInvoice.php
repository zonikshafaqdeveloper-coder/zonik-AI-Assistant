<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInvoice extends Model
{
    protected $fillable = [
        'order_id',
        'credit_note_no',
        'total_amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnInvoiceItem::class);
    }
}
