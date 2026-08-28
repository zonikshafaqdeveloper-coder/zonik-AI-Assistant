<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPriceList extends Model
{
    use HasFactory;

    protected $table = 'vendor_price_list';

    protected $fillable = [
        'vendor_id',
        'product_id',
        'vendor_price',
    ];

        public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

        public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
