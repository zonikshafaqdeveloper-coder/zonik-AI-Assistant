<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'image', 'category_name', 'category_slug'
    ];

    //use HasFactory;
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
}
