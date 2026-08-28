<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReceiving extends Model
{
    use HasFactory;

      protected $fillable = [
        'purchase_order_id',
        'vendor_id',
        'receipt_date',
        'bill_no',
        'bill_date',
        'original_bill',
        'subtotal',
        'discount_percent',
        'tax_amount',
        'delivery_charges',
        'grand_total',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(StockReceivingItem::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrderDetail::class);
    }

    public function vendorBill()
    {
        return $this->hasOne(VendorBill::class);
    }
     public function rackStocks()
    {
        return $this->hasMany(RackStock::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
        public function debitNote()
    {
        return $this->hasOne(DebitNote::class);
    }
    public function debitNotes()
    {
        return $this->hasMany(DebitNote::class);
    }
        public function returnNotes()
    {
        return $this->hasMany(ReturnNote::class);
    }
    
}
