<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogisticsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Order::with([
                'outlet',
                'pickList',
                'latestDelivery',
                'logistic.mode',
                'logistics'
            ])
            ->withSum('items as picked_qty', 'quantity')
            ->withSum('originalItems as ordered_qty', 'quantity')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Order ID',
            'Outlet Name',
            'Delivery Location',
            'Delivery Selected',
            'Delivery Date',
            'Fulfilment %',
            'Order Status',
            'Picked Status',
            'Rack No',
            'No of Box',
            'Delivery Priority',
            'Mode of Delivery',
            'Invoice Value',
        ];
    }

    public function map($order): array
    {
        static $i = 0;
        $i++;

        $picked  = $order->picked_qty ?? 0;
        $ordered = $order->ordered_qty ?? 0;

        $percent = $ordered > 0 ? round(($picked / $ordered) * 100, 2) : 0;

        $deliveryStatus = optional($order->latestDelivery)->delivery_status ?? 'pending';

        $statusMap = [
            'pending'=>'Received',
            'in_progress'=>'Accepted',
            'ready_for_dispatch'=>'Dispatched',
            'delivered'=>'Delivered',
            'cancelled'=>'Cancelled'
        ];

        $pickedStatus = $order->pickList->status ?? 'PENDING';

        return [
            $i,
            $order->order_id,
            $order->outlet->outlet_name ?? '-',
            $order->shipping_address,
            $order->delivery_slot_type ?: Carbon::parse($order->delivery_date)->format('d M Y'),
            $order->delivery_date ? Carbon::parse($order->delivery_date)->format('d-m-Y') : '-',
            $percent . '%',
            $statusMap[$deliveryStatus] ?? 'Pending',
            $pickedStatus === 'PICKED' ? 'Completed' : 'Pending',
            $order->logistics->dispatched_rack ?? '-',
            $order->logistics->number_of_boxes ?? '-',
            $order->logistic->delivery_priority ?? '-',
            $order->logistic?->mode?->name ?? '-',
            $order->total_discount_value,
        ];
    }
}
