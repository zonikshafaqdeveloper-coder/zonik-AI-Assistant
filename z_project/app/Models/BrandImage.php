<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_name',
        'brand_image',
        'category_id',
        'festival_and_offer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function festivalandoffer()
    {
        return $this->belongsTo(FestivalandOffers::class, 'festival_and_offer');
    }
}
