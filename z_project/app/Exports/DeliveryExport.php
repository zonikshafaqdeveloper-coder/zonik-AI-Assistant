<?php

namespace App\Exports;

use App\Models\DeliveryManagement;
use Maatwebsite\Excel\Concerns\FromCollection;


class DeliveryExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DeliveryManagement::with([
        'order',
        'user',
        'user.kycdocuments'
    ])
    ->where('delivery_status', '!=', 'pending') 
    ->orderBy('created_at', 'desc')
    ->get()
    ->map(function ($delivery) {
            return [
                'Order No' => $delivery->order->order_id ?? '',
                'Invoice No' => $delivery->order->invoice_id ?? '',
                'Delivery ID' => $delivery->delivery_id,
                'Status' => $delivery->delivery_status,
                'Address' => $delivery->delivery_address,
                'Customer Name' => $delivery->user->name ?? '',
                'Outlet Name' => $delivery->user->outlet_name ?? '',
                'Contact' => $delivery->user?->kycdocuments?->first()?->phone ?? '',
                'Paid Amount' => $delivery->order->total_discount_value ?? '',
                'Payment Mode' => $delivery->order->payment_method ?? '',
                'Payment Status' => $delivery->order->payment_status ?? '',
                'Expected Delivery Date' => $delivery->delivery_date,
                'Delivery Date' => $delivery->updated_at,
                'Notes' => $delivery->delivery_notes,
            ];
        });
    }
}
