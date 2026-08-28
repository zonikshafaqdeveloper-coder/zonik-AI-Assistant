<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'brand',
        'category',
        'cost_per_item',
        'sale_price_basic',
        'profit_margin',
        'customer_price',
        'total_saving_percent',
        'last_grn_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}