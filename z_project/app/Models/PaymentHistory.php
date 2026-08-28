<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class PaymentHistory extends Model
{
protected $fillable = [
'payment_id', 'paid_amount', 'payment_mode', 'source', 'paid_to','reference', 'documents', 'meta'
];


protected $casts = [
'documents' => 'array',
'meta' => 'array',
];


public function payment()
{
return $this->belongsTo(Payment::class);
}
}