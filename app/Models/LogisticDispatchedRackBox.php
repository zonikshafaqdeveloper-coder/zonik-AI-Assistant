<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticDispatchedRackBox extends Model
{
    use HasFactory;
    
    protected $table = 'logistic_dispatched_rack_box';

    protected $fillable = [
        'order_id',
        'dispatched_rack',
        'number_of_boxes',
    ];
}
