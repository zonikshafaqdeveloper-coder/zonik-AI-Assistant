<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYCDocument extends Model
{
    use HasFactory;
    
    protected $table = 'k_y_c_documents';

    protected $fillable = [
        'user_id',
        'pan_no',
        'pan_document',
        'group_id',
        'gst_no',
        'gst_document',
        'fssai',
        'fssai_document',
        'owner_id_document',
        'verified_status',
        'email',
        'phone',
        'outlet_pincode',
        'outlet_address',
        'billing_pincode',
        'billing_address',
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


}
