<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['subcategory_id','tag_name'];

    public function subcategory() 
    {
    return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

   public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
