@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Outstanding Details</h3>
                            <div>
                                <a href="{{ route('admin.reports.overdue-outlet-detail.pdf', $orderInvoice->first()->outlet_id ?? request()->route('id')) }}"
                                target="_blank" class="btn btn-danger">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </a>
                                <a href="{{ route('admin.reports.overdue-outlet-detail.excel', $orderInvoice->first()->outlet_id ?? request()->route('id')) }}"
                                class="btn btn-success">
                                    <i class="fa fa-file-excel"></i> Export Excel
                                </a>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back to Report</a>
                            </div>
                        </div>

                            @php
                                $serialNumber = 1;
                                $totalOutstanding = 0;
                                $maxOverdueDays = 0;
                                $totalOverdue = 0;
                            @endphp

                            @php
                                foreach ($orderInvoice as $orderItem) {

                                    if ($hasNewPaymentTerm) {

                                        $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date)->startOfDay();
                                        $dueDay = (int) $paymentTerm->days ?: 1;

                                        $dueDate = $deliveryDate->copy()
                                            ->addMonthNoOverflow()
                                            ->day($dueDay)
                                            ->startOfDay();

                                        $today = now()->startOfDay();

                                        if ($today->gt($dueDate)) {
                                            $daysOverdue = $today->diffInDays($dueDate);
                                            $maxOverdueDays = max($maxOverdueDays, $daysOverdue);
                                        }

                                    } else {

                                        $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date);
                                        $dueDate = $deliveryDate->copy()->addDays($due_days_limit);

                                        $currentDate = now();
                                        $daysDifference = $currentDate->diffInDays($dueDate->copy()->addDay(), false);

                                        if ($daysDifference < 0) {
                                            $overdueDays = abs($daysDifference);
                                            $maxOverdueDays = max($maxOverdueDays, $overdueDays);
                                        }
                                    }
                                }
                            @endphp

                            <table class="table table-borderless mb-3">
                                <tr>
                                    <td class="w-50">
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
                                            <span class="text-danger fw-bold">
                                                {{ $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : 'No Overdue' }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="nonRunningTable">
                                    <thead class="table-dark">
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
                                        @php $serialNumber = 1; $totalOutstanding = 0; @endphp
                                        @foreach($orderInvoice as $orderItem)

                                            @php
                                                $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date);
                                            @endphp

                                            @if($orderItem->payment_method === 'special_credit' && $orderItem->custom_due_days)

                                                @php
                                                    $creditLimit = $orderItem->custom_due_days;
                                                    $dueDate = $deliveryDate->copy()->addDays($creditLimit);
                                                    $currentDate = now();
                                                    $daysDifference = $currentDate->diffInDays($dueDate, false);

                                                    if ($daysDifference < 0) {
                                                        $overdueDays = abs($daysDifference);
                                                        $daysText = 'Overdue by ' . $overdueDays . ' days';
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
                                                    $deliveryDate = $deliveryDate->startOfDay();
                                                    $dueDay = (int) $paymentTerm->days ?: 1;
                                                    $dueDate = $deliveryDate->copy()->addMonthNoOverflow()->day($dueDay)->startOfDay();
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
                                                    $creditLimit = $due_days_limit;
                                                    $dueDate = $deliveryDate->copy()->addDays($creditLimit);
                                                    $currentDate = now();
                                                    $daysDifference = $currentDate->diffInDays($dueDate->copy()->addDay(), false);

                                                    if ($daysDifference < 0) {
                                                        $overdueDays = abs($daysDifference);
                                                        $daysText = 'Overdue by ' . $overdueDays . ' days';
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
                                                <td class="text-center">{{ \Carbon\Carbon::parse($orderItem->delivery_date)->format('Y-m-d') }}</td>
                                                <td class="text-center">{{ $dueDate->format('Y-m-d') }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $color }}">{{ $daysText }}</span>
                                                </td>
                                                <td class="text-center">₹{{ number_format($orderItem->balance_amount, 2) }}</td>
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
                                        <tr class="table-light">
                                            <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                            <td class="text-center"><strong>₹{{ number_format($totalOutstanding, 2) }}</strong></td>
                                        </tr>
                                        <tr class="table-light">
                                            <td colspan="6" class="text-end"><strong>Max Overdue:</strong></td>
                                            <td class="text-center text-danger fw-bold">
                                                {{ $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : '0' }}
                                            </td>
                                        </tr>
                                        <tr class="table-light">
                                            <td colspan="6" class="text-end"><strong>Total Overdue Amount:</strong></td>
                                            <td class="text-center text-danger fw-bold">
                                                ₹{{ number_format($totalOverdue, 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection