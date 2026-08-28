<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
  
    protected $fillable = [
        'name', 'image', 'category_id','slug'
    ];
 //   use HasFactory;
 public function category()
 {
     return $this->belongsTo(Category::class, 'category_id');
 }

 
public function products()
{
    return $this->hasMany(Product::class);
}

public function brands() {
    return $this->hasMany(Brand::class);
}

}
