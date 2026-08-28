<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Credit Note - {{ $returnInvoice->credit_note_no }}</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/invoice.css">

<style>
body, table, th, td {
    font-family: Abadi, sans-serif;
    font-size: 11px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border:1px solid #000;
    padding:6px;
}

thead { display:table-header-group; }
tr { page-break-inside:avoid; }

.text-center { text-align:center; }
.bold { font-weight:bold; }

.header{
    font-size:18px;
    font-weight:bold;
    text-align:center;
    padding:8px;
}
</style>
</head>

<body>

{{-- ===================== GLOBAL VARIABLES ===================== --}}
@php
$deliveryCharges = $orders->first()->delivery_charges ?? 0;
$couponDiscount  = $orderInvoice->first()->order->coupon_discount ?? 0;
$productDiscount = $orderInvoice->first()->order->product_discount ?? 0;
$packingCharges  = $orderInvoice->first()->order->packing_charges ?? 0;
$otherCharges    = $orderInvoice->first()->order->others_charges ?? 0;

$finalamount = 0;
$totalpretaxamount = 0;
$totalcgstamount = 0;
$totalsgstamount = 0;
$totaligstamount = 0;
$totaltaxamount = 0;
@endphp


{{-- ===================== CREDIT NOTE HEADER ===================== --}}
<table>
<tr>
    <td colspan="4" class="header">CREDIT NOTE</td>
</tr>
</table>


{{-- ===================== COMPANY (FROM) ===================== --}}
<table>
<tr>
<td>
    <b>Infigourmet Networks Private Limited</b><br>
        Unit no 42  (Near Panchal Furniture),<br>
                    Nav Nandanvan Industrial estate,<br>
                    Asha Nagar, Mulund West,<br>
                    Mumbai 400080. ( Landmark : Gold Gym Mulund West.)<br>
                    Fssai No - 11525009000305<br>
                    GSTIN/UIN: 27AAICI2086H1ZE<br>
                    State Name : Maharashtra, Code : 27<br>
                    Contact : +91-9869612312
</td>
</tr>
</table>


{{-- ===================== CONSIGNEE & BUYER (REPLACED SECTION) ===================== --}}
<table>
<tr>
    <th width="50%">Consignee (Ship to)</th>
    <th width="50%">Buyer (Bill to)</th>
</tr>

<tr>
<td>
    <p><b>{{ $company_name1 ?? '' }}</b></p>
    <p>User : {{ $orders->first()->user?->name }}</p>
    <p>Outlet : {{ $orders->first()->user?->outlet_name }}</p>
    <p>Address : {{ $orderInvoice->first()->order->shipping_address }}</p>
    <p>Pincode : {{ $orderInvoice->first()->order->shipping_pincode }}</p>
    <p>GSTIN : {{ $orderss->first()->gst_no ?? 'NA' }}</p>
    <p>State Name : Maharashtra, Code : 27<p>
    
</td>

<td>
    <p><b>{{ $company_name1 ?? '' }}</b></p>
    <p>User : {{ $orders->first()->user?->name }}</p>
    <p>Outlet : {{ $orders->first()->user?->outlet_name }}</p>
    <p>Address : {{ $orderInvoice->first()->order->billing_address }}</p>
    <p>Pincode : {{ $orders->first()->user?->pincode }}</p>
    <p>GSTIN : {{ $orderss->first()->gst_no ?? 'NA' }}</p>
    <p>State Name : Maharashtra, Code : 27<p>
</td>
</tr>
</table>


{{-- ===================== CREDIT NOTE DETAILS ===================== --}}
{{-- ===================== CREDIT NOTE DETAILS ===================== --}}
<table style="font-size:10px;">

<tr>
<td width="50%">
    <b>Credit Note No.</b> :
    {{ $orders->first()->invoice_id ?? '' }}
</td>

<td width="50%">
    <b>Dated</b> :
    {{ isset($orders->first()->invoice_date)
        ? \Carbon\Carbon::parse($orders->first()->invoice_date)->format('d-M-Y')
        : '' }}
</td>
</tr>


<tr>
<td>
    <b>Buyer's Order No.</b> :
    {{ $orders->first()->order_id ?? '' }}
</td>

<td>
    <b>Mode/Terms of Payment</b> :
    {{ $orders->first()->payment_status ?? '' }}
</td>
</tr>


<tr>
<td>
    <b>Dispatch Doc No.</b> :
</td>

<td>
    <b>Dated</b> :
</td>
</tr>


<tr>
<td>
    <b>Dispatched through</b> :
</td>

<td>
    <b>Terms of Delivery</b> :
    {{ $orders->first()->delivery_time_slot ?? '' }}
</td>
</tr>


<tr>
<td colspan="2">
    <b>Destination</b> :
    {{ $orders->first()->shipping_address ?? '' }}
</td>
</tr>

</table>


{{-- ===================== PRODUCT TABLE ===================== --}}
<table>
<thead>
<tr>
    <th>Sr</th>
    <th>Description</th>
    <th>HSN</th>
    <th>Qty</th>
    <th>Rate</th>
    <th>Pre Tax</th>
    <th>GST %</th>
    <th>Tax</th>
    <th>Total</th>
</tr>
</thead>

<tbody>

@php $serial = 1; @endphp

@foreach($orderInvoice as $item)

@php
$pretax = $item->quantity * $item->offer_price;
$totalpretaxamount += $pretax;

$cgst = $item->product->cgst ?? 0;
$sgst = $item->product->sgst ?? 0;
$igst = $item->product->igst ?? 0;

if ($maharashtrian === 'False') {
    $cgst = 0; $sgst = 0;
    $taxRate = $igst;
} else {
    $igst = 0;
    $taxRate = $cgst + $sgst;
}

$taxAmt = ($pretax * $taxRate)/100;

$totalcgstamount += ($pretax*$cgst)/100;
$totalsgstamount += ($pretax*$sgst)/100;
$totaligstamount += ($pretax*$igst)/100;

$lineTotal = $pretax + $taxAmt;
$finalamount += $lineTotal;
@endphp

<tr>
<td>{{ $serial++ }}</td>
<td>{{ $item->product->product_name }}</td>
<td>{{ $item->product->hsn_code }}</td>
<td>{{ $item->quantity }}</td>
<td>{{ number_format($item->offer_price,2) }}</td>
<td>{{ number_format($pretax,2) }}</td>
<td>{{ $taxRate }}%</td>
<td>{{ number_format($taxAmt,2) }}</td>
<td>{{ number_format($lineTotal,2) }}</td>
</tr>

@endforeach
</tbody>
</table>


{{-- ===================== SUMMARY ===================== --}}
@php
$finalamount = $finalamount
             - $couponDiscount
             + $deliveryCharges
             + $packingCharges
             + $otherCharges;
@endphp

<table>
<tr>
<td colspan="7" class="text-right"><b>Total Amount</b></td>
<td colspan="2"><b>{{ number_format($finalamount,2) }}</b></td>
</tr>

<tr>
<td colspan="2"><b>Amount in Words</b></td>
<td colspan="7">
{{ app('App\Http\Controllers\OrderController')->numberToWords($finalamount) }}
</td>
</tr>
</table>


{{-- ===================== FOOTER ===================== --}}
<table>
<tr>
<td width="50%">E. & O.E</td>
<td width="50%" class="text-center">
For Infigourmet Networks Private Limited<br><br><br>
<b>Authorised Signatory</b>
</td>
</tr>
</table>

</body>
</html>
