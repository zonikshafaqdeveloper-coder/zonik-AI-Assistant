<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPrice extends Model
{
    use HasFactory;

     protected $fillable = [
        'customer_id',
        'outlet_id',
        'product_id',
        'product_price',
    ];
     
     public function outlet()
    {
        return $this->belongsTo(User::class, 'outlet_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
        public function users()
    {
        return $this->hasMany(User::class, 'priority', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
