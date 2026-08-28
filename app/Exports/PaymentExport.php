<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $type;
    protected $id;

    public function __construct($startDate, $endDate, $type, $id)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->type      = $type;
        $this->id        = $id;
    }

    public function collection()
    {
        $orders = Order::with([
                'items.product',
                'user',
                'payment.histories'
            ])
            ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
            ->where(function ($query) {
                $query->where('orders.user_id', $this->id)
                      ->orWhere('orders.outlet_id', $this->id);
            })
            ->where('delivery_management.delivery_status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->select('orders.*')
            ->orderBy('orders.created_at', 'asc')
            ->get();

        if ($orders->isEmpty()) {
            return new Collection([]);
        }

        $exportData = new Collection();

        $orders->each(function ($order) use ($exportData) {

            $payment   = $order->payment;
            $histories = $payment ? $payment->histories : collect([]);

            $invoiceDate  = Carbon::parse($order->created_at)->format('Y-m-d');
            $deliveryDate = $order->delivery_date
                ? Carbon::parse($order->delivery_date)
                : Carbon::parse($order->created_at);

            // CREDIT TERM
           // CREDIT TERM
            $today = Carbon::today();

            // If payment already fully paid
            if ($payment && $payment->payment_status === 'paid') {
                $daysOutstanding = 'Paid';
                $dueDate = null;
            } else {

                // CUSTOM PAYMENT TERM
                $paymentTerm = \App\Models\OutletPaymentTerm::where('user_id', $order->outlet_id)
                                ->where('is_active', 1)
                                ->first();

                if ($paymentTerm) {
                    $parts = [];

                    if (!empty($paymentTerm->from_range)) $parts[] = (int) $paymentTerm->from_range;
                    if (!empty($paymentTerm->to_range))   $parts[] = (int) $paymentTerm->to_range;
                    if (!empty($paymentTerm->days))       $parts[] = (int) $paymentTerm->days;

                    $dueDay = array_sum($parts);

                    $deliveryDate = Carbon::parse($order->delivery_date);
                    $dueDate      = $deliveryDate->copy()->addDays($dueDay);

                } 
                // DEFAULT PAYMENT TERM
                else {
                    $deliveryDate = Carbon::parse($order->delivery_date);
                    $limit        = $order->user->due_days_limit ?? 0;
                    $dueDate      = $deliveryDate->copy()->addDays($limit);
                }

                $daysDifference = $today->diffInDays($dueDate->copy()->addDay(), false);

                if ($daysDifference < 0) {
                    $daysOutstanding = 'Overdue by ' . abs($daysDifference) . ' days';
                } elseif ($daysDifference > 0) {
                    $daysOutstanding = 'Due in ' . $daysDifference . ' days';
                } else {
                    $daysOutstanding = 'Today';
                }
            }


            $totalAmount = (float) ($order->total_discount_value ?? 0);
            $runningPaid = 0;

            // BASE ROW
            $baseRow = [
                'Order ID'         => $order->order_id,
                'Invoice Date'     => $invoiceDate,
                'Delivery Date'    => $deliveryDate->format('Y-m-d'),
                'Due Date'         => $dueDate ? $dueDate->format('Y-m-d') : 'N/A',
                'User Name'        => $order->user->name ?? 'N/A',
                'Outlet Name'      => $order->user->outlet_name ?? 'N/A',
                'Payment Status'   => $order->payment_status,
                'Days Outstanding' => $daysOutstanding,
            ];

            if ($histories->isNotEmpty()) {
                foreach ($histories as $history) {

                    $runningPaid += (float) $history->paid_amount;

                    $exportData->push(array_merge($baseRow, [
                        'Payment Date'   => Carbon::parse($history->created_at)->format('Y-m-d'),
                        'Payment Mode'   => $history->payment_mode ?? 'N/A',
                        'Total Amount'   => number_format($totalAmount, 2),
                        'Total Paid'     => number_format($runningPaid, 2),
                        'Balance Amount' => number_format($totalAmount - $runningPaid, 2),
                    ]));
                }
            } else {
                $exportData->push(array_merge($baseRow, [
                    'Payment Date'   => 'N/A',
                    'Payment Mode'   => 'N/A',
                    'Total Amount'   => number_format($totalAmount, 2),
                    'Total Paid'     => '0.00',
                    'Balance Amount' => number_format($totalAmount, 2),
                ]));
            }
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
            'Days Outstanding',

            'Payment Date',
            'Payment Mode',

            'Total Amount',
            'Total Paid',
            'Balance Amount',
        ];
    }
}
