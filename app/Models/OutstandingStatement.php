<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutstandingStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'outlet_id',
        'user_id',
        'total_due_amount',
        'order_id',
        'outstanding_date'
    ];
}
