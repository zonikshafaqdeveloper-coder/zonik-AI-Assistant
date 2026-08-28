<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number',
        'lead_customer_id',
        'quotation_date',
        'subtotal',
        'total_gst',
        'grand_total',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function leadCustomer()
    {
        return $this->belongsTo(LeadCustomer::class);
    }
}