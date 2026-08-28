<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/invoice.css">


<meta charset="utf-8">
<title>Tax Invoice - {{ $orders->first()->invoice_id }}</title>

<style>
/* ===================== PAGE / TOP-SPACE FIX ===================== */
@page {
    margin: 8px 45px 45px 45px; 
}

* {
    box-sizing: border-box;
}

html, body {
    /*margin: 0 !important;*/
    padding: 0 !important;
}

body, table, th, td {
    font-family: 'Trebuchet MS', sans-serif;
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

/* PDF Page break handling */
table { page-break-inside:auto; }
tr { page-break-inside:avoid; page-break-after:auto; }
thead { display:table-header-group; }
.no-break { page-break-inside: avoid; }

.text-center { text-align:center; }
.bold { font-weight:bold; }

.container1 {
    margin: 0 !important;
    padding-top: 0 !important;
}

h2 {
    margin: 0;
    padding: 0;
    line-height: 1;
}

img {
    display: block;
    margin: 0;
    padding: 0;
}

.header-table {
    border: none !important;
    /*margin: 0 !important;*/
    padding: 0 !important;
    border-collapse: collapse;
}
.header-table td {
    border: none !important;
    margin: 0 !important;
    padding: 0 !important;
    /*vertical-align: top;*/
}
</style>
</head>
<body>

@php
/* ===================== GLOBAL VARIABLES ===================== */
$deliveryCharges = $orders->first()->delivery_charges ?? 0;
$couponDiscount  = $orderInvoice->first()->order->coupon_discount ?? 0;
$productDiscount  = $orderInvoice->first()->order->product_discount ?? 0;
$packingCharges   = $orderInvoice->first()->order->packing_charges ?? 0;
$otherCharges     = $orderInvoice->first()->order->others_charges ?? 0;

$finalamount = 0;
$totalpretaxamount = 0;
$totalcgstamount = 0;
$totalsgstamount = 0;
$totaligstamount = 0;
$totalcessamount = 0;
$totaltaxamount = 0;
@endphp


<div class="container1">

    {{-- ================= HEADER ================= --}}
    <table class="header-table">
        <tr>
            <td style="width:150px;">
                <img src="{{ asset('frontweb/assests/images/Adobe Express - file.png')}}" width="150">
            </td>
            <td style="text-align:right;">
                <h2 style="font-size:30px;">Tax Invoice</h2>
            </td>
        </tr>
    </table>


    {{-- ================= DETAILS TABLE ================= --}}
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Invoice No<br><b>{{ $orders->first()->invoice_id }}</b></td>
                <td>Order No<br><b>{{ $orders->first()->order_id }}</b></td>
                <td colspan="2">Invoice Date<br>
                    <b>{{ $orders->first()->invoice_date ? \Carbon\Carbon::parse($orders->first()->invoice_date)->format('d-m-Y') : '' }}</b>
                </td>
                <td>Delivery Date<br>
                    <b>{{ $orders->first()->delivery_date ? \Carbon\Carbon::parse($orders->first()->delivery_date)->format('d-m-Y') : '' }}</b>
                </td>
            </tr>
            <tr>
                <td>Order Date<br><b>{{ $orderInvoice->first()->order->created_at->format('d-m-Y') }}</b></td>
                <td>Delivery Slot<br><b>{{ $orders->first()->delivery_time_slot }}</b></td>
                <td>Pending Invoices<br><b>{{ $lastpayment->count() }}</b></td>
                <td>Outstanding<br><b>{{ $lastpayment->sum('total_due_amount') }}</b></td>
                <td>Payment Status<br><b>{{ $orders->first()->payment_status }}</b></td>
            </tr>
        </tbody>
    </table>

{{-- ================= BILL & SHIPPED FROM + PAYMENT DETAILS ================= --}}
<table class="table table-bordered no-break">
    <tbody>
        <tr>
            <td width="70%" style="color:#000; vertical-align:top;">
                <p style="margin:0 0 4px 0;"><b>Bill &amp; Shipped From :</b></p>
                <p style="margin:0 0 8px 0;">Infigourmet networks private limited</p>
                <p style="margin:0 0 4px 0;">Address :Unit no 42  (Near Panchal Furniture), Nav Nandanvan
                <br>Industrial estate, Asha Nagar, Mulund West,
                 ( Landmark : Gold Gym Mulund West.) Mumbai 400080.</p>
                 <p style="margin:0 0 4px 0;">GSTIN : 27AAICI2086H1ZE</p>
                <p style="margin:0;">FSSAI :  11525009000305</p>
            </td>

            <td width="30%" style="color:#000; vertical-align:top; position:relative;">
                <p style="margin:0 0 8px 0;"><b>Payment Details :</b></p>
                <img src="{{ asset('frontweb/assests/images/idfc_bank_qr.png')}}"
                     style="width:80px; height:80px; float:right; margin-left:8px;">
                <p style="margin:0; text-align:left;">
                    IFSC: BARB0MULWES<br>
                    Account No: 38350500000037<br>
                    Name: INFIGOURMET NETWORKS PRIVATE LIMITED<br>
                    Bank: Bank Of Baroda
                </p>
                <div style="clear:both;"></div>
            </td>
        </tr>
    </tbody>
</table>

{{-- ================= BILL TO / SHIP TO ================= --}}
<table class="table table-bordered">
    <tbody>
        <tr>
            <th colspan="2">Bill To</th>
            <th colspan="2">Ship To</th>
        </tr>

        <tr>
            <td colspan="2">
                <p>Company Name : {{ $company_name1 ?? 'N/A' }}</p>
                <p>User : {{ $orders->first()->user?->name }}</p>
                <p>Outlet : {{ $orders->first()->user?->outlet_name }}</p>
                <p>Address : {{ $orderInvoice->first()->order->billing_address }}</p>
                <p>Pincode : {{ $orders->first()->user?->pincode }}</p>
                <p>Phone Number : {{ $orders->first()->user?->mobile_number ?? '' }}</p>
                <p>Place Of Supply : {{ $orderss->first()->fssai ?? 'NA' }}</p>
                <p>GSTIN : {{ $orderss->first()->gst_no ?? 'NA' }}</p>
            </td>

            <td colspan="2">
                <p>Company Name : {{ $company_name1 ?? 'N/A' }}</p>
                <p>User : {{ $orders->first()->user?->name }}</p>
                <p>Outlet : {{ $orders->first()->user?->outlet_name }}</p>
                <p>Address : {{ $orderInvoice->first()->order->shipping_address }}</p>
                <p>Pincode : {{ $orderInvoice->first()->order->shipping_pincode }}</p>
                <p>Phone Number : {{ $orders->first()->user?->mobile_number ?? '' }}</p>
                <p>Place Of Supply : {{ $orderss->first()->fssai ?? 'NA' }}</p>
                <p>GSTIN : {{ $orderss->first()->gst_no ?? 'NA' }}</p>
            </td>
        </tr>
    </tbody>
</table>


    {{-- ================= PRODUCT TABLE ================= --}}
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Sr</th>
            <th>Description</th>
            <th colspan="2">HSN</th>
            <th>Qty</th>
            <th>Rate</th>
            <th colspan="2">Pre Tax</th>
            <th colspan="2">GST %</th>
            <th>Tax</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>

        @php $serial = 1; @endphp

        @foreach($orderInvoice as $item)

        @if($item->quantity <= 0)
            @continue
        @endif

        @php
            $pretax = $item->quantity * $item->offer_price;
            $totalpretaxamount += $pretax;

            $cgst = $item->product->cgst ?? 0;
            $sgst = $item->product->sgst ?? 0;
            $igst = $item->product->igst ?? 0;
            $cess = $item->product->cess ?? 0;


            if ($maharashtrian === 'False') {

                $cgst = 0;
                $sgst = 0;
                $taxRate = $igst;
            } else {

                $igst = 0;
                $taxRate = $cgst + $sgst;
            }

            $cessAmt = ($pretax * $cess) / 100;
            $taxAmt = ($pretax * $taxRate) / 100 + $cessAmt;
            $totaltaxamount += $taxAmt;

            // Split totals correctly
            $totalcgstamount += ($pretax * $cgst) / 100;
            $totalsgstamount += ($pretax * $sgst) / 100;
            $totaligstamount += ($pretax * $igst) / 100;
            $totalcessamount += $cessAmt;

            $lineTotal = $pretax + $taxAmt;
            $finalamount += $lineTotal;
        @endphp


        <tr>
            <td>{{ $serial++ }}</td>
            <td>{{ $item->product->product_name ?? 'N/A' }}</td>
            <td colspan="2">{{ $item->product->hsn_code ?? 'N/A' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->offer_price,2) }}</td>
            <td colspan="2">{{ number_format($pretax,2) }}</td>
            <td colspan="2">
    {{ collect([$cgst, $sgst, $igst, $cess])
        ->filter(fn($tax) => $tax > 0)
        ->implode('+') ?: '0' }}
</td>
            <td>{{ number_format($taxAmt,2) }}</td>
            <td>{{ number_format($lineTotal,2) }}</td>
        </tr>
        @endforeach

        </tbody>
    </table>


    {{-- ================= CHARGES SUMMARY ================= --}}
    <table class="table table-bordered no-break">
        <tbody>
       @if($couponDiscount > 0)
        <tr>
            <td colspan="9"></td>
            <td colspan="3"><b>Coupon Discount</b></td>
            <td>{{ number_format($couponDiscount, 2) }}</td>
        </tr>
        @endif

        @if($productDiscount > 0)
        <tr>
            <td colspan="9"></td>
            <td colspan="3"><b>Product Discount</b></td>
            <td>{{ number_format($productDiscount, 2) }}</td>
        </tr>
        @endif

        @if($deliveryCharges > 0)
        <tr>
            <td colspan="9"></td>
            <td colspan="3"><b>Delivery Charges</b></td>
            <td>{{ number_format($deliveryCharges, 2) }}</td>
        </tr>
        @endif

        @if($packingCharges > 0)
        <tr>
            <td colspan="9"></td>
            <td colspan="3"><b>Packing Charges</b></td>
            <td>{{ number_format($packingCharges, 2) }}</td>
        </tr>
        @endif

        @if($otherCharges > 0)
        <tr>
            <td colspan="9"></td>
            <td colspan="3"><b>Other Charges</b></td>
            <td>{{ number_format($otherCharges, 2) }}</td>
        </tr>
        @endif


        @php

            $finalamount = $finalamount
                         - $couponDiscount
                         + $deliveryCharges
                         + $packingCharges
                         + $otherCharges;
        @endphp

        <tr>
            <td colspan="9"></td>
            <td colspan="3"><b>Total Chargeable Value</b></td>
            <td><b>{{ number_format($finalamount,2) }}</b></td>
        </tr>
        <tr>
            <td colspan="3"><b>Amount in Words</b></td>
            <td colspan="10">
                {{ app('App\Http\Controllers\OrderController')->numberToWords($finalamount) }}
            </td>
        </tr>
        </tbody>
    </table>


    {{-- ================= TAX SUMMARY ================= --}}
    <table class="table table-bordered no-break">
        <tbody>
        <tr>
            <th colspan="2">Taxable</th>
            <th colspan="2">CGST</th>
            <th colspan="3">SGST</th>
            <th colspan="2">IGST</th>
            <th colspan="2">CESS</th>
            <th colspan="2">Total Tax</th>
        </tr>
        <tr>
            <td colspan="2">{{ number_format($totalpretaxamount,2) }}</td>
            <td colspan="2">{{ number_format($totalcgstamount,2) }}</td>
            <td colspan="3">{{ number_format($totalsgstamount,2) }}</td>
            <td colspan="2">{{ number_format($totaligstamount,2) }}</td>
            <td colspan="2">{{ number_format($totalcessamount,2) }}</td>
            <td colspan="2">{{ number_format($totaltaxamount,2) }}</td>
        </tr>
        </tbody>
    </table>


    {{-- ================= TERMS & CONDITIONS ================= --}}
    <table class="table table-bordered no-break">
        <tr>
            <td style="padding-bottom:10px;">
                <b>Terms &amp; Conditions:</b>
                <ul style="margin:6px 0 0 16px; padding:0;">
                    <li>Please give acceptance with stamp and sign once material is received.</li>
                    <li>Item once delivered cannot be returned back</li>
                    <li>Payment if delayed post payment term then 18% interest from the date of overdue will be applicable</li>
                    <li>Item can be returned only during delivery , if damaged , expired or wrong item is supplied. No other reason will be acceptable</li>
                </ul>
            </td>
        </tr>
    </table>


</div>

</body>
</html>
