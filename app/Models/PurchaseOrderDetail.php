<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_details';

    protected $fillable = [
        'purchase_order_number',
        'reference',
        'vendor_id',
        'location',
        'pincode',
        'po_date',
        'delivery_date',
        'subtotal_basic',
        'product_discount',
        'tax_total',
        'delivery_charges',
        'grand_total',
        'payment_method',
        'payment_status',
        'save_type',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'created_by',
        'updated_by',
    ];


    public function items()
    {
        return $this->hasMany(
            PurchaseOrderItem::class,
            'purchase_order_id'
        );
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
