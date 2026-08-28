<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNotification extends Model
{
    use HasFactory;
    protected $table = 'order_notifications';

    public function user()
{
    return $this->belongsTo(User::class);
}
}
