<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}" type="text/css">
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    
     <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('frontweb/assests/images/Adobe Express - file.png')}}" alt="laravel daily" width="200" />
            </td>
           
        </tr>
    </table>

<div class="margin-top">
<table>
    <tr>
        <td>
            <h3>Vendor Outstanding Statement</h3>
        </td>
        <td class="text-right">
            <b>Date:</b> {{ now()->format('Y-m-d') }}
        </td>
    </tr>
</table>
</div>

<table style="margin-top:10px;">
    <tr>
        <td><b>Vendor Name</b></td>
        <td>{{ $vendor->name }}</td>
        <td><b>Mobile</b></td>
        <td>{{ $vendor->mobile }}</td>
    </tr>
    <tr>
        <td><b>Location</b></td>
        <td>{{ $vendor->location ?? 'N/A' }}</td>
        <td><b>Credit Days</b></td>
        <td>
            @if($hasCustomPaymentTerm)
                {{ $displayCreditText }}
            @else
                {{ $creditDays }}
            @endif
        </td>
    </tr>
</table>
<table style="margin-top:15px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Bill No</th>
            <th>Bill Date</th>
            <th>Receipt Date</th>
            <th>Due Date</th>
            <th>Days Status</th>
            <th>Bill Amount</th>
            <th>Paid</th>
            <th>Balance</th>
        </tr>
    </thead>

    <tbody>
        @php $i = 1; @endphp
        @foreach($bills as $bill)
            @if($bill->balance_amount > 0)
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td class="text-center">{{ $bill->bill_no }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($bill->bill_date)->format('Y-m-d') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($bill->receipt_date)->format('Y-m-d') }}</td>
                <td class="text-center">{{ $bill->due_date->format('Y-m-d') }}</td>
                <td class="text-center" style="color: {{ $bill->color }}">
                    {{ $bill->days_text }}
                </td>
                <td class="text-right">₹{{ number_format($bill->grand_total, 2) }}</td>
                <td class="text-right">₹{{ number_format($bill->total_paid, 2) }}</td>
                <td class="text-right"><b>₹{{ number_format($bill->balance_amount, 2) }}</b></td>
            </tr>
            @endif
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="8" class="text-right"><b>Total Outstanding</b></td>
            <td class="text-right"><b>₹{{ number_format($totalOutstanding, 2) }}</b></td>
        </tr>
    </tfoot>
</table>
<p style="margin-top:20px;">
    <b>Note:</b> This is a system-generated vendor outstanding statement.
</p>

</body>
</html>
