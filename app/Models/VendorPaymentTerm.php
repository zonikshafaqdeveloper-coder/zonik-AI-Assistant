<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPaymentTerm extends Model
{
    use HasFactory;

     protected $fillable = [
        'vendor_id',
        'credit_status',
        'credit_limit',
        'due_limit_days',
        'verified_status',
        'from_range',
        'to_range',
        'days',
        'custom_payment_term',
    ];
}
