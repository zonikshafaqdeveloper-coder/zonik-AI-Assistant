<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebitNote extends Model
{
    protected $fillable = [
        'stock_receiving_id',
        'vendor_id',
        'debit_note_no',
        'is_opening_stock',
        'total_amount'
    ];

    protected $casts = [
        'is_opening_stock' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(DebitNoteItem::class);
    }

    public function receiving()
    {
        return $this->belongsTo(
            \App\Models\StockReceiving::class,
            'stock_receiving_id'
        );
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}