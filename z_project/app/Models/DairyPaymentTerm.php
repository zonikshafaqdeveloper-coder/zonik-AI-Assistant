<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DairyPaymentTerm extends Model
{
    use HasFactory;

    protected $table = 'dairy_payment_terms';

    protected $fillable = [
        'user_id',
        'due_limit_days',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
