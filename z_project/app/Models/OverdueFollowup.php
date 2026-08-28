<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverdueFollowup extends Model
{
    use HasFactory;

     protected $fillable = ['outlet_id', 'payment_date_committed', 'followup_feedback', 'followup_date'];
}
