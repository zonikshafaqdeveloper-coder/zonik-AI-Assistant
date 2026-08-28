<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

  protected $fillable = [
        'delivery_date','delivery_time_slot','delivery_slot_type', 'outlet_id', 'user_id', 'billing_address', 'shipping_address',
        'subtotal', 'product_discount', 'cgst_sgst', 'shipping_pincode', 'packing_charges',
        'coupon_discount', 'others_charges', 'delivery_charges', 'total_discount_value',
        'payment_method','status','payment_status', 'order_id', 'invoice_id'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function originalItems()
    {
        return $this->hasMany(OriginalItem::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'outlet_id', 'id');
    }
    
        public function mainuser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
        
    public function outlet()
    {
        return $this->belongsTo(User::class, 'outlet_id');
    }

    public function outstanding()
    {
        return $this->hasMany(OutstandingStatement::class, 'order_id');
    }

    public function pickList()
    {
        return $this->hasOne(PickList::class, 'order_id', 'id');
    }

    public function latestDelivery()
    {
        return $this->hasOne(DeliveryManagement::class)->latestOfMany();
    }


    public function deliveries()
    {
        return $this->hasMany(DeliveryManagement::class);
    }


    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'order_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
     public function logistic()
    {
        return $this->hasOne(OrderLogistic::class);
    }
    
        public function logistics()
    {
        return $this->hasOne(LogisticDispatchedRackBox::class, 'order_id');
    }
    
        public function delivery()
{
    return $this->hasOne(DeliveryManagement::class);
}

    public function returnInvoice()
{
    return $this->hasOne(ReturnInvoice::class);
}

 public function backendSalesOrder()
    {
        return $this->hasOne(BackendSalesOrder::class, 'order_id');
    }


}
