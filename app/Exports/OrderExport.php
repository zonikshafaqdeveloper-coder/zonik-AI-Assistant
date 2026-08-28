<?php
namespace App\Exports;

use App\Models\Order;
use App\Models\OutstandingStatement;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $orders = Order::with(['items', 'user' ,'deliveries'])->get();
        // dd($orders);
        $exportData = new Collection();


        $orders->each(function ($order) use ($exportData) {
            $overDueAmount = OutstandingStatement::where('outstanding_date', '<=', Carbon::now())
                ->where('outlet_id', $order->outlet_id)
                ->sum('total_due_amount');
            $outstandingTillDate = OutstandingStatement::where('outstanding_date', '>', Carbon::now())
                ->where('outlet_id', $order->outlet_id)
                ->sum('total_due_amount');


            $orderData = [
                'Order ID' => $order->order_id,
                'Outlet Name' => $order->user->name ?? '',
                'User ID' => $order->user->outlet_name ?? '',
                'Delivery Address' => $order->shipping_address,
                'Delivery Date' => $order->delivery_date,
                'Order Date' => \Carbon\Carbon::parse($order->created_at)->format('Y-m-d'),
                'Payment Status' => $order->payment_status,
                'Order Status' => $order->deliveries->first()->delivery_status ?? '',
                'Carton Size' => $order->items->first()->product->carton_size ?? '',
                'Brand' => $order->items->first()->product->brands ?? '',

            ];

            $order->items->each(function ($item) use ($exportData, $orderData, $overDueAmount, $outstandingTillDate) {
                // Check if product exists
                if ($item->product) {
                   $salePrice = $item->price;
                    $purchasePrice = $item->product->cost_per_item ?? 0;
                    $quantity = $item->quantity ?? 0;
                    
                    $totalCost = $purchasePrice * $quantity;
                    
                  
                    if ($totalCost > 0) {
                        $profitMarginPercentage = round((($salePrice - $totalCost) / $totalCost) * 100, 2);
                    } else {
                        $profitMarginPercentage = 0;
                    }
                
                    // Prepare row data for export
                    $rowData = [
                        'Product Name' => $item->product->product_name,
                        'Quantity' => $quantity,
                        'Sale Price' => $salePrice,
                        'Profit Margin (%)' => $profitMarginPercentage . '%',
                        'Purchase Price' => $purchasePrice,
                        'Supplier Name' => $item->product->supplier_traced,
                        'Last Updated(Price)' => $item->product->updated_at,
                        'MRP' => $item->product->product_mrp,
                        'Outstanding Till Date' => $outstandingTillDate,
                        'Over Due Amount' => $overDueAmount,
                    ];
                
            
                    $exportData->push(array_merge($orderData, $rowData));
                }
                
            });


        });

        // dd($exportData);

        $exportData = $exportData->sortByDesc('Order ID');

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'User ID',
            'Outlet Name',
            'Delivery Address',
            'Delivery Date',
            'Order Date',
            'Payment Status',
            'Order Status',
            'Carton Size',
            'Brand',
            'Product Name',
            'Quantity',
            'Sale Price',
            'Profit Margin',
            'Purchase Price',
            'Supplier Name',
            'Last Updated(Price)',
            'MRP',
            'Outstanding Till Date',
            'Over Due Amount',
        ];
    }
}
