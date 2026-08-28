<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReturnRequest extends Model
{
    protected $fillable = ['order_id', 'requested_by', 'status', 'reject_reason', 'approved_by', 'approved_at'];

    public function order() { return $this->belongsTo(Order::class); }
    public function requestedBy() { return $this->belongsTo(Admin::class, 'requested_by'); }
    public function approvedBy() { return $this->belongsTo(Admin::class, 'approved_by'); }
    public function items() { return $this->hasMany(StockReturnRequestItem::class); }
}