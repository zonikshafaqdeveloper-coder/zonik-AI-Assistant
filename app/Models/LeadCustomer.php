<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'outlet_name',
        'location_cluster',
        'area',
        'address',
        'mobile_number',
        'payment_term',
        'outbound_sale_name',
        'inbound_account_lead',
    ];
}
