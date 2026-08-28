<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}" type="text/css">

</head>
<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('frontweb/assests/images/new_logo.png')}}" alt="laravel daily" width="200" />
            </td>
            <td class="w-half">
                <h4>Outstanding Id: 834847473</h4><br>

            </td>
            <td class="w-half">
            <h4>Outstanding Date: {{ $orderss->first()->created_at->format('Y-m-d') }}</h4><br>

            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div><h4>Billing To:</h4></div><br>
                    <div><b>Outlate name </b> : {{$orderInvoice->first()->user?->name }}</div>

                    <div> <b>Outlet Contact </b>: {{ $orderss->first()->phone }} </div>
                    <div> <b>GST No </b>: {{ $orderss->first()->gst_no }} </div>





                    <div>{{ $orderss->first()->billing_address }}</div>
                </td>

            </tr>
        </table>
    </div>

    <div class="margin-top">
        <table class="products">
            <tr>
                <th>Sr.</th>
                <th>Invoice ID</th>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th>Number of Date outstanding</th>
                <th>Due Amount</th>
            </tr>

            @php $serialNumber = 1 @endphp
            @foreach($orderInvoice as $orderItem)
            <tr class="items">



                                    <td class="text-center">{{ $serialNumber }}</td>
                                    <td class="text-center">{{ $orderItem->invoice_id }}</td>

                                    <td class="text-center">{{  $orderItem->created_at->format('Y-m-d') }}</td>
                                    <td class="text-center"> {{ $orderItem->outstanding_date }}</td>
                                    <td class="text-center">

                                            @php
                                                $outstandingDate = \Carbon\Carbon::parse( $orderItem->outstanding_date);
                                                $currentDate = \Carbon\Carbon::now();
                                                $daysDifference = $currentDate->diffInDays($outstandingDate, false);
                                                $status = '';

                                                if ($daysDifference < 0) {
                                                    $status = 'overdue';
                                                    $daysText = 'Overdue by ' . abs($daysDifference) . ' days';
                                                    $color = 'red';
                                                }elseif ($daysDifference > 0) {
                                                    $status = 'due';
                                                    $daysText = 'Due in ' . abs($daysDifference) . ' days';
                                                    if ($daysDifference <= 3) {
                                                        $color = 'red';
                                                    } else {
                                                        $color = 'orange';
                                                    }
                                                } else {
                                                    $status = 'today';
                                                    $daysText = 'Today';
                                                    $color = 'red';
                                                }
                                            @endphp

                                            <span style="color: {{ $color }}" class="font-weight-bold">
                                                @if ($status == 'overdue' || $status == 'due' ||  $status = 'today')
                                                    {{ $daysText }}
                                                @endif
                                            </span>




                                    </td>

                                    <td class="text-center">{{ $orderItem->total_discount_value }}</td>


        </tr>
        @php $serialNumber++ @endphp
        @endforeach
        </table>
    </div>

    <div class="total">
        Total: {{ $outstandingList->first()->total_due_amount }}
    </div>

    <div class="footer margin-top">
        <div>Terms and conditions</div>
        <div>&copy; Goods once sold will not be taken back or exchanged</div>
    </div>
</body>
</html>
