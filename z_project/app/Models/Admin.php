<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $hidden = [
        'password'
    ];

    // If you are using plain text password now (not recommended),
    // later we will convert it to bcrypt hashing properly.

    /*
     |--------------------------------------------------
     | Relationships (only keep if actually needed)
     |--------------------------------------------------
     */

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

        public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

}
