<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FestivalandOffers extends Model
{
    use HasFactory;
    
    
   protected $table = 'festivaland_offers';

    public function brandImages()
    {
        return $this->hasMany(BrandImage::class, 'festival_and_offer', 'id');
    }
    
}
