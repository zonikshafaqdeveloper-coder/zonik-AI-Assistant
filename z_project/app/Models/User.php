<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'designation',
        'pincode',
        'location',
        'type',
        'customer_id',
        'email_verified_at',
        'password',
        'remember_token',
        'outlet_name',
        'priority',
        'verified_status',
        'user_verified',
        'new_user',
        'credit_status',
        'credit_limit',
        'due_days_limit',
        'status',
        'verified_status',
        'selected_outlet_id',
    ];


    public function quoteItems()
    {
        return $this->hasMany(Quote::class);
    }
    public function kycdocuments()
    {
        return $this->hasMany(KYCDocument::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
        public function outlet()
    {
        return $this->belongsTo(User::class, 'priority', 'id');
    }

    
    
    public function adminNotifications()
    {
        return $this->hasMany(AdminNotification::class, 'user_id');
    }
    
    public function unreadAdminNotifications()
    {
        return $this->hasMany(AdminNotification::class, 'user_id')
                    ->where('is_read', 0);
    }
    
    public function parentCustomer()
    {
        return $this->belongsTo(User::class, 'priority');
    }
    
      public function outletPaymentTerm()
{
    return $this->hasOne(OutletPaymentTerm::class, 'user_id');
}

    
    public function dairyPaymentTerm()
    {
        return $this->hasOne(DairyPaymentTerm::class);
    }


}
