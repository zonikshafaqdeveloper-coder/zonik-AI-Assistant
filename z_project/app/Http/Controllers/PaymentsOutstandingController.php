<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OutletPaymentTerm;
use App\Models\DairyPaymentTerm;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentsOutstandingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $outletId = $user->selected_outlet_id;

        [$overdueRows, $notOverdueRows] = $this->buildRows($outletId);

        $overdueGrouped = $this->groupByMonth($overdueRows);
        $notOverdueGrouped = $this->groupByMonth($notOverdueRows);

        $totalOverdueAmount = collect($overdueRows)->sum('outstanding');
        $maxDaysOverdue = collect($overdueRows)->max('days_overdue') ?? 0;

        $totalOutstandingAmount = collect($notOverdueRows)->sum('outstanding');
        $minDaysUntilDue = collect($notOverdueRows)->min('days_until_due') ?? 0;

        return view('mobile.payments-outstanding', compact(
            'overdueGrouped', 'notOverdueGrouped',
            'totalOverdueAmount', 'maxDaysOverdue',
            'totalOutstandingAmount', 'minDaysUntilDue'
        ));
    }

    /**
     * AJAX: invoice-level list for a single month + type (overdue/not-overdue),
     * matching the "June 2026 Invoices" drill-down page.
     */
    public function monthInvoices(Request $request)
    {
        $user = $request->user();
        $outletId = $user->selected_outlet_id;

        $monthKey = $request->input('month'); // e.g. "2026-06"
        $type = $request->input('type'); // 'overdue' or 'not_overdue'

        [$overdueRows, $notOverdueRows] = $this->buildRows($outletId);
        $rows = $type === 'overdue' ? $overdueRows : $notOverdueRows;

        $monthRows = collect($rows)->where('month_key', $monthKey)->values();

        return response()->json([
            'month_label'  => $monthRows->first()['month_label'] ?? $monthKey,
            'count'        => $monthRows->count(),
            'total_amount' => $monthRows->sum('outstanding'),
            'rows'         => $monthRows->map(function ($r) use ($type) {
                return [
                    'invoice_no'     => $r['invoice_no'],
                    'invoice_date'   => $r['invoice_date'],
                    'delivered_date' => $r['delivered_date'],
                    'due_date'       => $r['due_date'],
                    'amount'         => $r['outstanding'],
                    'status_label'   => $type === 'overdue'
                        ? 'Overdue'
                        : 'Due in ' . $r['days_until_due'] . ' Days',
                ];
            }),
        ]);
    }

    /**
     * Builds two lists — overdue rows and not-overdue rows — for this
     * outlet's delivered, unpaid/partial orders, using the EXACT same
     * 3-priority due-date logic as buildOverdueSummary()/buildOverdueDetails().
     */
    private function buildRows($outletId): array
    {
        $today = now()->startOfDay();

        $orders = Order::join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
            ->where('orders.outlet_id', $outletId)
            ->whereIn('orders.payment_status', ['unpaid', 'partial'])
            ->where('delivery_management.delivery_status', 'delivered')
            ->select('orders.*')
            ->get();

        $overdueRows = [];
        $notOverdueRows = [];

        foreach ($orders as $order) {

            $payment = Payment::where('order_id', $order->id)->first();
            $totalAmount = $order->total_discount_value;
            $totalPaid = $payment->total_paid ?? 0;
            $balanceAmount = $totalAmount - $totalPaid;

            if ($balanceAmount <= 0) {
                continue;
            }

            $deliveryDate = Carbon::parse($order->delivery_date);

            $paymentTerm = OutletPaymentTerm::where('user_id', $order->outlet_id)
                ->where('is_active', 1)
                ->first();

            $hasNewPaymentTerm = $paymentTerm ? true : false;

            $userData = User::where('id', $order->outlet_id)->select('due_days_limit')->first();
            $due_days_limit = $userData->due_days_limit ?? 0;

            // ===== Same 3-priority due-date logic as buildOverdueDetails() =====
            if ($order->payment_method === 'special_credit') {

                $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
                    ->where('is_active', 1)
                    ->first();

                $customDueDays = ($dairyTerm && $dairyTerm->due_limit_days !== null)
                    ? (int) $dairyTerm->due_limit_days
                    : $due_days_limit;

                $dueDate = $deliveryDate->copy()->addDays($customDueDays)->startOfDay();

            } elseif ($hasNewPaymentTerm) {

                $deliveryDateStart = $deliveryDate->copy()->startOfDay();
                $dueDay = (int) $paymentTerm->days ?: 1;

                $dueDate = $deliveryDateStart->copy()->addMonthNoOverflow()->day($dueDay)->startOfDay();

            } else {
                // +1 day grace, matches buildOverdueSummary()'s invoiceID()-aligned logic
                $dueDate = $deliveryDate->copy()->addDays($due_days_limit)->addDay()->startOfDay();
            }

            $isOverdue = $dueDate->lte($today);
            $daysOverdue = $isOverdue ? $dueDate->diffInDays($today) : 0;
            $daysUntilDue = !$isOverdue ? $today->diffInDays($dueDate) : 0;

            // Group by the due-date's month (matches "June 2026" labeling
            // in the reference — the month the payment was/is due, not
            // necessarily the month the order was placed).
            $monthKey = $dueDate->format('Y-m');
            $monthLabel = $dueDate->format('F Y');

            $row = [
                'order_id'        => $order->id,
                'invoice_no'      => $order->order_id,
                'invoice_date'    => Carbon::parse($order->created_at)->format('d M Y'),
                'delivered_date'  => $deliveryDate->format('d M Y'),
                'due_date'        => $dueDate->format('d M Y'),
                'due_date_raw'    => $dueDate->format('Y-m-d'),
                'outstanding'     => $balanceAmount,
                'days_overdue'    => $daysOverdue,
                'days_until_due'  => $daysUntilDue,
                'month_key'       => $monthKey,
                'month_label'     => $monthLabel,
            ];

            if ($isOverdue) {
                $overdueRows[] = $row;
            } else {
                $notOverdueRows[] = $row;
            }
        }

        return [$overdueRows, $notOverdueRows];
    }

    private function groupByMonth(array $rows): array
    {
        $grouped = [];

        foreach ($rows as $row) {
            $key = $row['month_key'];

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'month_key'   => $key,
                    'month_label' => $row['month_label'],
                    'count'       => 0,
                    'amount'      => 0,
                    'due_date'    => $row['due_date'], // for not-overdue: earliest due date in this month bucket
                    'max_days_overdue' => 0,
                ];
            }

            $grouped[$key]['count']++;
            $grouped[$key]['amount'] += $row['outstanding'];
            $grouped[$key]['max_days_overdue'] = max($grouped[$key]['max_days_overdue'], $row['days_overdue']);
        }

        $result = array_values($grouped);
        usort($result, fn($a, $b) => strcmp($b['month_key'], $a['month_key']));

        return $result;
    }
}
