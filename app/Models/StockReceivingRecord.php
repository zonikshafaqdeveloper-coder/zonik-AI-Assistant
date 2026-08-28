<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReceivingRecord extends Model
{
    use HasFactory;

    protected $table = 'stock_receiving_records';

    protected $fillable = [
        'entry_done_by',
        'material_receipt_date',
        'po_no',
        'po_date',
        'bill_no',
        'bill_date',
        'product_id',
        'uom',
        'qty',
        'brand',
        'batch_no',
        'manufacture_date',
        'expiry_date',
        'supplier_name',
        'purchase_rate_basic',
        'gst',
        'mrp',
        'bill_document',
        'remarks',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
