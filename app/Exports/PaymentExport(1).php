<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OutstandingStatement;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PaymentExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $type;
    protected $id;

    public function __construct($startDate, $endDate, $type, $id)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type;
        $this->id = $id;
    }

    public function collection()
    {
      $orders = Order::with(['items', 'user'])
            ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
            ->where(function ($query) {
                $query->where(function ($q) {
                    // $q->where('delivery_management.delivery_status', 'delivered')
                      $q->where('orders.payment_status', 'unpaid');
                });
                // ->orWhere(function ($q) {
                    // $q->where('delivery_management.delivery_status', 'pending')
                    //   $q->where('orders.payment_method', 'credit');
                // });
            })
            ->where(function ($query) {
                $query->where('user_id', $this->id)
                      ->orWhere('outlet_id', $this->id);
            })
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
             ->where('delivery_management.delivery_status', '!=', 'cancelled') // Exclude cancelled orders
             ->select('orders.*') // Ensure only order columns are selected
             ->orderBy('orders.created_at', 'asc') // Sort correctly
            ->get();
            
            // dd($orders);

        if ($orders->isEmpty()) {
            return new Collection([]);
        }

       $exportData = new Collection();

$orders->each(function ($order) use ($exportData) {
    $invoiceDate = Carbon::parse($order->created_at)->format('Y-m-d');
    $deliveryDate = $order->delivery_date ? Carbon::parse($order->delivery_date)->format('Y-m-d') : 'N/A';
    $dueDaysLimit = $order->user->due_days_limit ?? 0;

    $dueDate = $order->delivery_date 
        ? Carbon::parse($order->delivery_date)->addDays($dueDaysLimit)->format('Y-m-d') 
        : 'N/A';

    $currentDate = Carbon::now();
    $dueDateCarbon = $order->delivery_date ? Carbon::parse($dueDate) : null;

    if ($dueDateCarbon) {
        $daysDifference = $currentDate->diffInDays($dueDateCarbon->copy()->addDay(), false);
        $daysText = $daysDifference < 0 
            ? 'Overdue by ' . abs($daysDifference) . ' days'
            : ($daysDifference > 0 ? 'Due in ' . $daysDifference . ' days' : 'Today');
    } else {
        $daysText = 'N/A';
    }

    $dueAmount = $order->total_discount_value ?? 0;

    $orderData = [
        'Order ID' => $order->order_id,
        'Invoice Date' => $invoiceDate,
        'Delivery Date' => $deliveryDate,
        'Due Date' => $dueDate,
        'User Name' => $order->user->name ?? 'N/A',
        'Outlet Name' => $order->user->outlet_name ?? 'N/A',
        'Payment Status' => $order->payment_status,
        'Number of Days Outstanding' => $daysText, 
    ];

    $order->items->each(function ($item) use ($exportData, $orderData, $dueAmount) {
        if (!$item) return;

        $totalPrice = $item->price ?? 0;
        $rowData = [
            'Product Name' => $item->product->product_name ?? 'N/A',
            'Quantity' => $item->quantity ?? 0,
            'Sale Price' => $totalPrice,
            'MRP' => $item->product->product_mrp ?? 0,
            'Due Amount' => $dueAmount,
        ];

        $exportData->push(array_merge($orderData, $rowData));
    });
});



// ✅ Fix Sorting to Ascending Order
$exportData = $exportData->sortBy(function ($order) {
    return intval(preg_replace('/\D/', '', $order['Order ID']));
});


return $exportData;
}

    public function headings(): array
    {
        return [
            'Order ID',
            'Invoice Date',
            'Delivery Date',
            'Due Date',
            'User Name',
            'Outlet Name',
            'Payment Status',
            'Number of Days Outstanding',
            'Product Name',
            'Quantity',
            'Purchase Price',
            'MRP',
            'Due Amount',
        ];
    }
}
