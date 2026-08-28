<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletPaymentTerm extends Model
{
    use HasFactory;

    protected $table = 'outlet_payment_terms';

    protected $fillable = [
        'user_id',
        'from_range',
        'to_range',
        'days',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
