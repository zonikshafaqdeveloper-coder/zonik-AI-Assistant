<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_receiving_id',
        'purchase_order_id',
        'vendor_id',
        'bill_no',
        'vendor_invoice_no',
        'bill_date',
        'subtotal',
        'discount_percent',
        'tax_amount',
        'delivery_charges',
        'grand_total',
        'status',
        'original_bill',

    ];
    
         protected $casts = [
        'bill_date' => 'date',
    ];



    public function stockReceiving()
    {
        return $this->belongsTo(StockReceiving::class);
    }
    
       public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function payments()
    {
        return $this->hasMany(VendorPayment::class);
    }
}
