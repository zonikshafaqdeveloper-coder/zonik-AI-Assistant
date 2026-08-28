<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMaterialShortLog extends Model
{
    use HasFactory;

     protected $fillable = [
        'order_id',
        'product_id',
        'short_qty',
        'comment',
        'lost_value'
    ];
}
