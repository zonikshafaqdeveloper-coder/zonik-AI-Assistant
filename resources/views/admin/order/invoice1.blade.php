<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}" type="text/css">
    <style>
        .text-center {
            text-align: center
        }

        .f-left {
            float: left;
        }

        .f-right {
            text-align: right
        }

        .table-bordered td {
            padding: 5px 10px
        }

        .table-bordered th {
            vertical-align: middle;
            text-align: center
        }
    </style>
</head>

<body>
    
    
    
@php
    $serialNumber = 1;
    $totalOutstanding = 0;
    $maxOverdueDays = 0;
    $totalOverdue = 0;
@endphp

@php
    $maxOverdueDays = 0;

    foreach($orderInvoice as $orderItem) {

        if($hasNewPaymentTerm) {

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

  <table class="w-full">
    <tr>
        <td class="w-half">
            <img src="{{ asset('frontweb/assests/images/Adobe Express - file.png')}}" width="200" />
        </td>
        <td class="w-half text-right">
            <h4>Outstanding Id: {{ uniqid('OUT-') }}</h4>
            <h4>
                Outstanding Date:
                {{ optional($orderss->first())->created_at?->format('Y-m-d') ?? 'N/A' }}
            </h4>

           <h4>
    Longest Overdue:
    <span style="color:red;">
        {{ $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : 'No Overdue' }}
    </span>
</h4>
        </td>
    </tr>
</table>

    <div class="margin-top">
        <table class="w-full table-bordered">
            <tr>
                <td class="w-half">
                    <div>
                        <h4>Billing To:</h4>
                    </div>
                </td>
                <td>

                    <div> <b>Company Name </b>: {{ $company_name1 }} </div>

                </td>
            </tr>
            <tr>
                <td>
                     <div> <b>Outlet Contact </b>: {{ $mobileNumber }} </div>
                </td>
                <td>
                    <div> <b>GST No </b>: {{ $orderss->first()->gst_no ?? 'N/A' }} </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>User name </b> : {{$orderInvoice->first()->user?->name ?? '' }}</div>
                </td>
                <td>
                    <div> <b>Credit Limit </b> : {{$creditLimit }}  , <b>Credit Days</b> :

@if($hasNewPaymentTerm)

@php
    // Build an array with only non-empty numeric values
    $parts = [];

    if (!empty($paymentTerm->from_range)) {
        $parts[] = (int) $paymentTerm->from_range;
    }
    if (!empty($paymentTerm->to_range)) {
        $parts[] = (int) $paymentTerm->to_range;
    }
    if (!empty($paymentTerm->days)) {
        $parts[] = (int) $paymentTerm->days;
    }

    $displayText = implode(' + ', $parts);

    
    $custome_total = array_sum($parts);
@endphp

@if(!empty($displayText))
    {{ $displayText }} = <b>{{ $custome_total }}</b>
@endif



@else

    {{ $due_days_limit ?? 0 }}

@endif

  </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Outlet name </b> : {{$orderInvoice->first()->user?->outlet_name ?? '' }}</div>
                </td>
                <td>
                    <div><b>User Email </b> : {{$orderInvoice->first()->user?->email ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>

  <div class="margin-top table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Sr.</th>
            <th>Invoice ID</th>
            <th>Invoice Date</th>
            <th>Delivery Date</th>
            <th>Due Date</th>
            <th>Number of Days Outstanding</th>
            <th>Due Amount</th>
        </tr>

        @php $serialNumber = 1; $totalOutstanding = 0; @endphp
        @foreach($orderInvoice as $orderItem)
        
@php
    $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date);
@endphp

{{-- ✅ PRIORITY 1: special_credit --}}
@if($orderItem->payment_method === 'special_credit' && $orderItem->custom_due_days)

    @php
        $creditLimit = $orderItem->custom_due_days;

        $dueDate = $deliveryDate->copy()->addDays($creditLimit);

        $currentDate = now();
       $daysDifference = $currentDate->diffInDays($dueDate, false);

        if ($daysDifference < 0) {
            $overdueDays = abs($daysDifference);
            $daysText = 'Overdue by ' . $overdueDays . ' days';
            $color = 'red';
            $maxOverdueDays = max($maxOverdueDays, $overdueDays);

        } elseif ($daysDifference > 0) {
            $daysText = 'Due in ' . $daysDifference . ' days';
            $color = $daysDifference <= 3 ? 'red' : 'orange';

        } else {
            $daysText = 'Today';
            $color = 'green';
        }
    @endphp


{{-- ✅ PRIORITY 2: Outlet Payment Term --}}
@elseif($hasNewPaymentTerm)

    @php
        $deliveryDate = $deliveryDate->startOfDay();

        $dueDay = (int) $paymentTerm->days ?: 1;

        $dueDate = $deliveryDate->copy()
            ->addMonthNoOverflow()
            ->day($dueDay)
            ->startOfDay();

        $today = now()->startOfDay();

        if ($today->gt($dueDate)) {
            $daysOverdue = $today->diffInDays($dueDate);
            $daysText = 'Overdue by ' . $daysOverdue . ' days';
            $color = 'red';
            $maxOverdueDays = max($maxOverdueDays, $daysOverdue);

        } elseif ($today->lt($dueDate)) {
            $daysRemaining = $today->diffInDays($dueDate);
            $daysText = 'Due in ' . $daysRemaining . ' days';
            $color = ($daysRemaining <= 3 ? 'red' : 'orange');

        } else {
            $daysText = 'Today';
            $color = 'green';
        }
    @endphp


{{-- ✅ PRIORITY 3: Normal Credit --}}
@else

    @php
        $creditLimit = $due_days_limit;

        $dueDate = $deliveryDate->copy()->addDays($creditLimit);

        $currentDate = now();
        $daysDifference = $currentDate->diffInDays($dueDate->copy()->addDay(), false);

        if ($daysDifference < 0) {
            $overdueDays = abs($daysDifference);
            $daysText = 'Overdue by ' . $overdueDays . ' days';
            $color = 'red';
            $maxOverdueDays = max($maxOverdueDays, $overdueDays);

        } elseif ($daysDifference > 0) {
            $daysText = 'Due in ' . $daysDifference . ' days';
            $color = $daysDifference <= 3 ? 'red' : 'orange';

        } else {
            $daysText = 'Today';
            $color = 'green';
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
                <span style="color: {{ $color }}" class="font-weight-bold">
                    {{ $daysText }}
                </span>
            </td>
            <td class="text-center">Rs. {{ $orderItem->balance_amount }}</td>
            @php
          
            
            $amount = floatval($orderItem->balance_amount);
            $totalOutstanding += $amount;
            
            if (str_contains($daysText, 'Overdue')) {
                $totalOverdue += $amount;
            }
            @endphp
        </tr>

        @php $serialNumber++ @endphp
        @endforeach

        <tr>
            <td colspan="6" class="text-right">
                <b>Total:</b>
            </td>
            <td class="text-center"><b>Rs. {{  number_format($totalOutstanding) }}</b></td>
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

    <div class="total">

    </div>

    <div class="footer margin-top">
        <div>Terms and conditions</div>
        <div>&copy; Goods once sold will not be taken back or exchanged</div>
    </div>
</body>

</html>
