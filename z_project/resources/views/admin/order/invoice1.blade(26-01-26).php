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
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('frontweb/assests/images/Adobe Express - file.png')}}" alt="laravel daily" width="200" />
            </td>
            <td class="w-half text-right">
                <h4>Outstanding Id: 834847473</h4><br>
                <h4>Outstanding Date: {{ $orderss->first()->created_at->format('Y-m-d') }}</h4><br>
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
        
@if($hasNewPaymentTerm)


@php
    $creditLimit = (int) $custome_total;  // example: 22
    $today       = \Carbon\Carbon::now();

 
    $dueDate = \Carbon\Carbon::create($today->year, $today->month, 1);
    $dueDate->day = min($creditLimit, $dueDate->daysInMonth); // Safe set day
    
    

   
    if ($today->day > $creditLimit) {
        $dueDate->addMonth();
    }

    // STEP 3: Calculate day difference
  
    
      $daysDifference = $today->diffInDays($dueDate->copy()->addDay(), false);

    // STEP 4: Decide status
    if ($daysDifference < 0) {
        $status   = 'overdue';
        $daysText = 'Overdue by ' . abs($daysDifference) . ' days';
        $color    = 'red';

    } elseif ($daysDifference > 0) {
        $status   = 'due';
        $daysText = 'Due in ' . $daysDifference . ' days';
        $color    = ($daysDifference <= 3 ? 'red' : 'orange');

    } else {
        $status   = 'today';
        $daysText = 'Today';
        $color    = 'green';
    }
@endphp




@else


@php
    $deliveryDate = \Carbon\Carbon::parse($orderItem->delivery_date);
    
    $creditLimit = $due_days_limit; // Default to 0 if credit_limit is null

    // Calculate the due date by adding the credit limit to the delivery date
    
    $dueDate = $deliveryDate->addDays($creditLimit);
    
    $currentDate = \Carbon\Carbon::now();
    

    // Adjust the overdue logic to start counting from the day after the due date
    $daysDifference = $currentDate->diffInDays($dueDate->copy()->addDay(), false);


    // Determine status and text
    if ($daysDifference < 0) {
        $status = 'overdue';
        $daysText = 'Overdue by ' . abs($daysDifference) . ' days';
        $color = 'red';
    } elseif ($daysDifference > 0) {
        $status = 'due';
        $daysText = 'Due in ' . $daysDifference . ' days';
        $color = $daysDifference <= 3 ? 'red' : 'orange';
    } else {
        $status = 'today';
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
            $totalOutstanding += floatval($orderItem->balance_amount);
            @endphp
        </tr>

        @php $serialNumber++ @endphp
        @endforeach

        <tr>
            <td colspan="6" class="text-right">
                <b>Total:</b>
            </td>
            <td class="text-center"><b>Rs. {{ $totalOutstanding }}</b></td>
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
