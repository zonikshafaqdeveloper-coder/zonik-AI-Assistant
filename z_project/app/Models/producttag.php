<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class producttag extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'tag_name', 'subcategory_id'
    ];
}
