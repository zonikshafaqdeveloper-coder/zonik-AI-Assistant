<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Overdue Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}" type="text/css">
    <style>
        .text-center { text-align: center }
        .f-left      { float: left; }
        .f-right     { text-align: right }
        .table-bordered td { padding: 5px 10px }
        .table-bordered th { vertical-align: middle; text-align: center }
    </style>
</head>

<body>

@php
    $serialNumber   = 1;
    $totalOutstanding = 0;
    $totalOverdue   = 0;
    $maxOverdueDays = 0;

    // ── Pre-pass: find the longest overdue across all items ──────────────────
    foreach ($orderInvoice as $orderItem) {

        if ($hasNewPaymentTerm) {

            $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date)->startOfDay();
            $dueDay       = (int) ($paymentTerm->days ?: 1);
            $dueDate      = $deliveryDate->copy()->addMonthNoOverflow()->day($dueDay)->startOfDay();
            $today        = now()->startOfDay();

            if ($today->gt($dueDate)) {
                $maxOverdueDays = max($maxOverdueDays, $today->diffInDays($dueDate));
            }

        } else {

            $deliveryDate    = \Carbon\Carbon::parse($orderItem->delivery_date);
            $dueDate         = $deliveryDate->copy()->addDays($due_days_limit);
            $daysDifference  = now()->diffInDays($dueDate->copy()->addDay(), false);

            if ($daysDifference < 0) {
                $maxOverdueDays = max($maxOverdueDays, abs($daysDifference));
            }
        }
    }
@endphp

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('frontweb/assests/images/Adobe Express - file.png') }}" width="200" />
            </td>
            <td class="w-half text-right">
                <h4>Outstanding Id: {{ uniqid('OUT-') }}</h4>
                <h4>Outstanding Date: {{ optional($orderss->first())->created_at?->format('Y-m-d') ?? 'N/A' }}</h4>
                <h4>
                    Longest Overdue:
                    <span style="color:red;">
                        {{ $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : 'No Overdue' }}
                    </span>
                </h4>
            </td>
        </tr>
    </table>

    {{-- ── Billing info ─────────────────────────────────────────────────────── --}}
    <div class="margin-top">
        <table class="w-full table-bordered">
            <tr>
                <td class="w-half"><h4>Billing To:</h4></td>
                <td><b>Company Name</b>: {{ $company_name1 }}</td>
            </tr>
            <tr>
                <td><b>Outlet Contact</b>: {{ $mobileNumber }}</td>
                <td><b>GST No</b>: {{ $orderss->first()->gst_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><b>User name</b>: {{ $orderInvoice->first()->user?->name ?? '' }}</td>
                <td>
                    <b>Credit Limit</b>: {{ $creditLimit }} ,
                    <b>Credit Days</b>:

                    @if ($hasNewPaymentTerm)
                        @php
                            $parts = array_filter([
                                !empty($paymentTerm->from_range) ? (int) $paymentTerm->from_range : null,
                                !empty($paymentTerm->to_range)   ? (int) $paymentTerm->to_range   : null,
                                !empty($paymentTerm->days)       ? (int) $paymentTerm->days        : null,
                            ]);
                            $displayText   = implode(' + ', $parts);
                            $custome_total = array_sum($parts);
                        @endphp
                        @if (!empty($displayText))
                            {{ $displayText }} = <b>{{ $custome_total }}</b>
                        @endif
                    @else
                        {{ $due_days_limit ?? 0 }}
                    @endif
                </td>
            </tr>
            <tr>
                <td><b>Outlet name</b>: {{ $orderInvoice->first()->user?->outlet_name ?? '' }}</td>
                <td><b>User Email</b>: {{ $orderInvoice->first()->user?->email ?? '' }}</td>
            </tr>
        </table>
    </div>

    {{-- ── Overdue invoice table ────────────────────────────────────────────── --}}
    <div class="margin-top table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Sr.</th>
                <th>Invoice ID</th>
                <th>Invoice Date</th>
                <th>Delivery Date</th>
                <th>Due Date</th>
                <th>Overdue Days</th>
                <th>Due Amount</th>
            </tr>

            @php $serialNumber = 1; $totalOutstanding = 0; @endphp

            @foreach ($orderInvoice as $orderItem)

@php
    $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date);

    // default
    $overdueDays = 0;

  if ($orderItem->payment_method === 'special_credit' && $orderItem->custom_due_days) {

    $dueDate = $deliveryDate->copy()->addDays($orderItem->custom_due_days);

    $overdueDays = now()->gt($dueDate)
        ? now()->diffInDays($dueDate)
        : 0;


    } elseif ($hasNewPaymentTerm) {

        $deliveryDate = $deliveryDate->startOfDay();

        $dueDay = (int) ($paymentTerm->days ?: 1);

        $dueDate = $deliveryDate->copy()
            ->addMonthNoOverflow()
            ->day($dueDay)
            ->startOfDay();

        $today = now()->startOfDay();

        $overdueDays = $today->gt($dueDate)
            ? $today->diffInDays($dueDate)
            : 0;

    } else {

        $dueDate = $deliveryDate->copy()->addDays($due_days_limit);

        $daysDiff = now()->diffInDays($dueDate->copy()->addDay(), false);

        $overdueDays = $daysDiff < 0 ? abs($daysDiff) : 0;
    }
@endphp

                {{-- Skip rows that are not overdue --}}
                @if ($overdueDays <= 0)
                    @continue
                @endif

                <tr>
                    <td class="text-center">{{ $serialNumber }}</td>
                    <td class="text-center">{{ $orderItem->invoice_id }}</td>
                    <td class="text-center">{{ $orderItem->created_at->format('Y-m-d') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($orderItem->delivery_date)->format('Y-m-d') }}</td>
                    <td class="text-center">{{ $dueDate->format('Y-m-d') }}</td>
                    <td class="text-center">
                        <span style="color:red;" class="font-weight-bold">
                            Overdue by {{ $overdueDays }} days
                        </span>
                    </td>
                    <td class="text-center">Rs. {{ $orderItem->balance_amount }}</td>
                </tr>

                @php
                    $amount            = floatval($orderItem->balance_amount);
                    $totalOutstanding += $amount;
                    $totalOverdue     += $amount;   // every row here is overdue
                    $serialNumber++;
                @endphp

            @endforeach

            <tr>
                <td colspan="6" class="text-right"><b>Total:</b></td>
                <td class="text-center"><b>Rs. {{ number_format($totalOutstanding) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Max Overdue:</b></td>
                <td class="text-center">
                    <b style="color:red;">
                        {{ $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : '0' }}
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Total Overdue Amount:</b></td>
                <td class="text-center">
                    <b style="color:red;">Rs. {{ number_format($totalOverdue) }}</b>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer margin-top">
        <div>Terms and conditions</div>
        <div>&copy; Goods once sold will not be taken back or exchanged</div>
    </div>

</body>
</html>