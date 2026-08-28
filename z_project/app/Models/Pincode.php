<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ZoneProcessing; // Corrected namespace separator

class pincode extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone_id',
        'pincode'
    ];


    public function zone()
    {
        return $this->belongsTo(ZoneProcessing::class, 'zone_id');
    }

}
