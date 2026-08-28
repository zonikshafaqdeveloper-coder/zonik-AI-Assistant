<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyccustomer extends Model
{
    protected $fillable = [
        // other fields,
        'outlet-name',
        'email',
        'password',
        'phone',
        'delivery_address',
        'city',
        'state',
        'pincode',
        'delivery_time',
        'document_type',
        'document_number',
        'document_image',
        'gst_verification_status',
    ];
}
