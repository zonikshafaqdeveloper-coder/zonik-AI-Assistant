<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Brand extends Model
{
   use HasFactory;

   protected $fillable = [
    'name', 'subcategory_id'
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
