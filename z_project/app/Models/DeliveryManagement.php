<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryManagement extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'delivery_status',
        'delivery_address',
        'delivery_person_id',
        'delivery_notes',
        'delivery_date',
        'confirmation_doc',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    public function kycdocuments()
    {
        return $this->belongsTo(KYCDocument::class);
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

           protected $casts = [
            'confirmation_doc' => 'array',
        ];
        


}
