<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_bill_id',
        'vendor_id',
        'payment_date',
        'amount',
        'payment_mode',
        'reference_no',
        'payment_document',
        'remarks',
    ];

    public function bill()
    {
        return $this->belongsTo(VendorBill::class, 'vendor_bill_id');
    }
}
