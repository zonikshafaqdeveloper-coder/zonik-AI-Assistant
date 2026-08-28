<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_name', 'subcategory_id'
    ];

    public function subcategory() 
    {
    return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

   public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
