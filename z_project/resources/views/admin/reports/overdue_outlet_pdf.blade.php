<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Overdue Outlet Statement</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { margin-bottom: 10px; }
        .header-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .header-table td { vertical-align: top; padding: 4px 0; }
        .header-table .text-end { text-align: right; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #999; padding: 6px; font-size: 11px; }
        table.data th { background: #333; color: #fff; text-align: center; }
        .text-center { text-align: center; }
        .text-danger { color: #c00; }
        .badge { padding: 2px 8px; border-radius: 3px; color: #fff; font-size: 10px; display: inline-block; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; color:#000; }
        .bg-success { background-color: #28a745; }
        tfoot td { font-weight: bold; background: #f5f5f5; }
    </style>
</head>
<body>

    <h2>Outstanding Details</h2>

    @php
        $maxOverdueDays = 0;

        foreach ($orderInvoice as $orderItem) {
            $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date);

            if ($orderItem->payment_method === 'special_credit' && $orderItem->custom_due_days) {
                $dueDate = $deliveryDate->copy()->addDays($orderItem->custom_due_days);
                $daysDifference = now()->diffInDays($dueDate, false);
                if ($daysDifference < 0) {
                    $maxOverdueDays = max($maxOverdueDays, abs($daysDifference));
                }
            } elseif ($hasNewPaymentTerm) {
                $deliveryDateStart = $deliveryDate->copy()->startOfDay();
                $dueDay = (int) $paymentTerm->days ?: 1;
                $dueDate = $deliveryDateStart->copy()->addMonthNoOverflow()->day($dueDay)->startOfDay();
                $today = now()->startOfDay();
                if ($today->gt($dueDate)) {
                    $maxOverdueDays = max($maxOverdueDays, $today->diffInDays($dueDate));
                }
            } else {
                $dueDate = $deliveryDate->copy()->addDays($due_days_limit);
                $daysDifference = now()->diffInDays($dueDate->copy()->addDay(), false);
                if ($daysDifference < 0) {
                    $maxOverdueDays = max($maxOverdueDays, abs($daysDifference));
                }
            }
        }
    @endphp

    <table class="header-table">
        <tr>
            <td style="width:50%">
                <div><strong>Company Name:</strong> {{ $company_name1 }}</div>
                <div><strong>Outlet Name:</strong> {{ $orderInvoice->first()->user?->outlet_name ?? '' }}</div>
                <div><strong>Outlet Contact:</strong> {{ $mobileNumber }}</div>
                <div><strong>GST No:</strong> {{ $orderss->first()->gst_no ?? 'N/A' }}</div>
            </td>
            <td class="text-end">
                <div><strong>Credit Limit:</strong> {{ $creditLimit }}</div>
                <div>
                    <strong>Credit Days:</strong>
                    @if($hasNewPaymentTerm)
                        @php
                            $parts = [];
                            if (!empty($paymentTerm->from_range)) $parts[] = (int) $paymentTerm->from_range;
                            if (!empty($paymentTerm->to_range))   $parts[] = (int) $paymentTerm->to_range;
                            if (!empty($paymentTerm->days))       $parts[] = (int) $paymentTerm->days;
                            $displayText = implode(' + ', $parts);
                            $custome_total = array_sum($parts);
                        @endphp
                        @if(!empty($displayText))
                            {{ $displayText }} = <strong>{{ $custome_total }}</strong>
                        @endif
                    @else
                        {{ $due_days_limit ?? 0 }}
                    @endif
                </div>
                <div>
                    <strong>Longest Overdue:</strong>
                    <span class="text-danger">
                        {{ $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : 'No Overdue' }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Invoice ID</th>
                <th>Invoice Date</th>
                <th>Delivery Date</th>
                <th>Due Date</th>
                <th>Days Outstanding</th>
                <th>Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $serialNumber = 1; $totalOutstanding = 0; $totalOverdue = 0; @endphp
            @foreach($orderInvoice as $orderItem)

                @php $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date); @endphp

                @if($orderItem->payment_method === 'special_credit' && $orderItem->custom_due_days)
                    @php
                        $dueDate = $deliveryDate->copy()->addDays($orderItem->custom_due_days);
                        $daysDifference = now()->diffInDays($dueDate, false);
                        if ($daysDifference < 0) {
                            $daysText = 'Overdue by ' . abs($daysDifference) . ' days';
                            $color = 'danger';
                        } elseif ($daysDifference > 0) {
                            $daysText = 'Due in ' . $daysDifference . ' days';
                            $color = $daysDifference <= 3 ? 'danger' : 'warning';
                        } else {
                            $daysText = 'Today';
                            $color = 'success';
                        }
                    @endphp
                @elseif($hasNewPaymentTerm)
                    @php
                        $deliveryDateStart = $deliveryDate->copy()->startOfDay();
                        $dueDay = (int) $paymentTerm->days ?: 1;
                        $dueDate = $deliveryDateStart->copy()->addMonthNoOverflow()->day($dueDay)->startOfDay();
                        $today = now()->startOfDay();
                        if ($today->gt($dueDate)) {
                            $daysOverdue = $today->diffInDays($dueDate);
                            $daysText = 'Overdue by ' . $daysOverdue . ' days';
                            $color = 'danger';
                        } elseif ($today->lt($dueDate)) {
                            $daysRemaining = $today->diffInDays($dueDate);
                            $daysText = 'Due in ' . $daysRemaining . ' days';
                            $color = $daysRemaining <= 3 ? 'danger' : 'warning';
                        } else {
                            $daysText = 'Today';
                            $color = 'success';
                        }
                    @endphp
                @else
                    @php
                        $dueDate = $deliveryDate->copy()->addDays($due_days_limit);
                        $daysDifference = now()->diffInDays($dueDate->copy()->addDay(), false);
                        if ($daysDifference < 0) {
                            $daysText = 'Overdue by ' . abs($daysDifference) . ' days';
                            $color = 'danger';
                        } elseif ($daysDifference > 0) {
                            $daysText = 'Due in ' . $daysDifference . ' days';
                            $color = $daysDifference <= 3 ? 'danger' : 'warning';
                        } else {
                            $daysText = 'Today';
                            $color = 'success';
                        }
                    @endphp
                @endif

                <tr>
                    <td class="text-center">{{ $serialNumber }}</td>
                    <td class="text-center">{{ $orderItem->invoice_id }}</td>
                    <td class="text-center">{{ $orderItem->created_at->format('Y-m-d') }}</td>
                    <td class="text-center">{{ $deliveryDate->format('Y-m-d') }}</td>
                    <td class="text-center">{{ $dueDate->format('Y-m-d') }}</td>
                    <td class="text-center"><span class="badge bg-{{ $color }}">{{ $daysText }}</span></td>
                    <td class="text-center">Rs. {{ number_format($orderItem->balance_amount, 2) }}</td>
                </tr>

                @php
                    $totalOutstanding += floatval($orderItem->balance_amount);
                    if (str_contains($daysText, 'Overdue')) {
                        $totalOverdue += floatval($orderItem->balance_amount);
                    }
                    $serialNumber++;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-center">Total:</td>
                <td class="text-center">Rs. {{ number_format($totalOutstanding, 2) }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center">Max Overdue:</td>
                <td class="text-center text-danger">{{ $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : '0' }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-center">Total Overdue Amount:</td>
                <td class="text-center text-danger">Rs. {{ number_format($totalOverdue, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>