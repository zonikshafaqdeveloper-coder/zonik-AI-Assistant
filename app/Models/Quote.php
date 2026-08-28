<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'product_type',
        'quantity',

    ];


    public function user()
    {
        return $this->belongsTo(User::class); // Assuming you have a User model
    }

    public function product()
    {
        return $this->belongsTo(Product::class); // Assuming you have a Product model
    }
}
