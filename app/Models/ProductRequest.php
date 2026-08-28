<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'user_id',
        'product_details',
        'status',
    ];

    public function user()
    {
    return $this->belongsTo(User::class, 'user_id');
    }


}
