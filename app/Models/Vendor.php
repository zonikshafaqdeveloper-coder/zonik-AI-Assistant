<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'name',
        'lead_time',
        'moq_type',
        'mobile',
        'email',
        'location',
        'pincode',
        'pan_number',
        'pan_document',
        'gst_number',
        'gst_document',
        'fssai_number',
        'fssai_document',
    ];
    
        public function paymentTerm()
{
    return $this->hasOne(VendorPaymentTerm::class, 'vendor_id');
}
}
