<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAssistantMessage extends Model
{
    protected $fillable = [
        'user_id',
        'outlet_id',
        'conversation_id',
        'role',
        'message',
        'product_data',
    ];

    protected $casts = [
        'product_data' => 'array',
    ];
}
