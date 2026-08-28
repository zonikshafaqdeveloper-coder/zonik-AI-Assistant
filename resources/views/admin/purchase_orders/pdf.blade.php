<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/invoice.css">
<title>Purchase Order - {{ $po->purchase_order_number }}</title>

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

/* PDF Page break handling */
table { page-break-inside:auto; }
tr { page-break-inside:avoid; page-break-after:auto; }
thead { display:table-header-group; }
.no-break { page-break-inside: avoid; }

.text-center { text-align:center; }
.bold { font-weight:bold; }
</style>
</head>

<body>
<div class="container1">

{{-- ================= HEADER ================= --}}
<table style="border:none;">
    <tr>
        <td style="border:none;">
            <img src="{{ public_path('frontweb/assests/images/Adobe Express - file.png') }}" width="150">
        </td>
        <td style="border:none; text-align:center;">
            <h2 style="font-size:30px;">Purchase Order</h2>
        </td>
    </tr>
</table>

{{-- ================= DETAILS ================= --}}
<table class="table table-bordered">
    <tr>
        <td>PO No<br><b>{{ $po->purchase_order_number }}</b></td>
        <td>Reference No<br><b>{{ $po->reference ?? '-' }}</b></td>
        <td colspan="2">PO Date<br>
            <b>{{ \Carbon\Carbon::parse($po->po_date)->format('d-m-Y') }}</b>
        </td>
        <td>Delivery Date<br>
            <b>{{ \Carbon\Carbon::parse($po->delivery_date)->format('d-m-Y') }}</b>
        </td>
    </tr>
    <tr>
        <td>Status<br><b>{{ strtoupper($po->status) }}</b></td>
        <td>Payment Method<br>
            <b>{{ ucwords(str_replace('_',' ',$po->payment_method)) }}</b>
        </td>
        <td>Location<br><b>{{ $po->location }}</b></td>
        <td>Pincode<br><b>{{ $po->pincode }}</b></td>
        <td>Currency<br><b>INR</b></td>
    </tr>
</table>

{{-- ================= BILL FROM / SHIPPED FROM ================= --}}
<table class="table table-bordered">
    <tr>
        <td colspan="4">
            <p><b>Bill From :</b> Infigourmet Networks Private Limited</p>
            <p>Address : Unit no 42  (Near Panchal Furniture),Nav Nandanvan Industrial estate,<br>
                Asha Nagar, Mulund West,Mumbai 400080. ( Landmark : Gold Gym Mulund West.)</p>
            <p>GSTIN : 27AAICI2086H1ZE</p>
            <p>FSSAI : 11525009000305</p>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <p><b>Shipped From :</b> Infigourmet Networks Private Limited</p>
            <p>Address : Unit no 42  (Near Panchal Furniture),Nav Nandanvan Industrial estate,<br>
                Asha Nagar, Mulund West,Mumbai 400080.( Landmark : Gold Gym Mulund West.)</p>
            <p>GSTIN : 27AAICI2086H1ZE</p>
            <p>FSSAI : 11525009000305</p>
        </td>
    </tr>

    <tr>
        <th colspan="2">Bill To</th>
        <th colspan="2">Ship To</th>
    </tr>

    <tr>
        <td colspan="2">
            <p>Vendor Name : {{ $po->vendor->name ?? 'NA' }}</p>
            <!-- <p>User : Infi-para solutions inc</p> -->
            <!-- <p>Outlet : bOTin</p> -->
            <p>Address : {{ $po->location }}</p>
            <p>Pincode : {{ $po->pincode }}</p>
            <p>Phone Number : {{ $po->vendor->mobile ?? 'NA' }}</p>
            <!-- <p>Place Of Supply : NA</p> -->
            <p>GSTIN : {{ $po->vendor->gst_number ?? 'NA' }}</p>
        </td>

        <td colspan="2">
            <p>Vendor Name : {{ $po->vendor->name ?? 'NA' }}</p>
            <!-- <p>User : Infi-para solutions inc</p> -->
            <!-- <p>Outlet : bOTin</p> -->
            <p>Address : {{ $po->location }}</p>
            <p>Pincode : {{ $po->pincode }}</p>
            <p>Phone Number : {{ $po->vendor->mobile ?? 'NA' }}</p>
            <!-- <p>Place Of Supply : NA</p> -->
            <p>GSTIN : {{ $po->vendor->gst_number ?? 'NA' }}</p>
        </td>
    </tr>
</table>

{{-- ================= PRODUCTS ================= --}}
@php
    $hasFreeQty = $po->items->contains(function ($item) {
        return $item->free_quantity > 0;
    });
@endphp
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th colspan="2">HSN</th>
            <th>Qty</th>
             @if($hasFreeQty)
                <th>Free Qty</th>
            @endif
            <th>Rate</th>
            <th colspan="2">Pre Tax</th>
            <th colspan="2">GST %</th>
            <th>Tax</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    @foreach($po->items as $index => $item)
        @php
            $pretax = $item->vendor_price * $item->quantity;
            $taxAmt = ($pretax * $item->row_tax) / 100;
            $lineTotal = $pretax + $taxAmt;
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->product->product_name ?? 'N/A' }}</td>
            <td colspan="2">{{ $item->product->hsn_code ?? '-' }}</td>
            <td class="text-right">{{ $item->quantity }}</td>
                @if($hasFreeQty)
                <td class="text-center">
                    {{ $item->free_quantity ?? 0 }}
                </td>
            @endif
            <td class="text-right">{{ number_format($item->vendor_price,2) }}</td>
            <td colspan="2" class="text-right">{{ number_format($pretax,2) }}</td>
            <td colspan="2" class="text-center">{{ $item->row_tax }}%</td>
            <td class="text-right">{{ number_format($taxAmt,2) }}</td>
            <td class="text-right">{{ number_format($lineTotal,2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- ================= TOTALS ================= --}}
<table class="table table-bordered no-break">
    <tr>
        <td colspan="9"></td>
        <td colspan="3"><b>Subtotal</b></td>
        <td>{{ number_format($po->subtotal_basic,2) }}</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="3"><b>Total Tax</b></td>
        <td>{{ number_format($po->tax_total,2) }}</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="3">
            <b>Product Discount ({{ $po->product_discount }}%)</b>
        </td>
        <td>
            {{ number_format((($po->subtotal_basic + $po->tax_total) * $po->product_discount / 100),2) }}
        </td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="3"><b>Delivery Charges</b></td>
        <td>{{ number_format($po->delivery_charges,2) }}</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="3"><b>Grand Total</b></td>
        <td><b>{{ number_format($po->grand_total,2) }}</b></td>
    </tr>
</table>

{{-- ================= AMOUNT IN WORDS ================= --}}
<table class="table table-bordered no-break">
    <tr>
        <td><b>Amount in Words</b></td>
        <td>
            {{ app('App\Http\Controllers\OrderController')->numberToWords($po->grand_total) }}
        </td>
    </tr>
</table>

{{-- ================= TAX SUMMARY ================= --}}
@php
    $taxable = $po->subtotal_basic;
    $cgst = $po->tax_total / 2;
    $sgst = $po->tax_total / 2;
    $igst = 0;
    $cess = 0;
@endphp

<table class="table table-bordered no-break">
    <tr>
        <th>Taxable</th>
        <th>CGST</th>
        <th>SGST</th>
        <th>IGST</th>
        <th>CESS</th>
        <th>Total Tax</th>
    </tr>
    <tr>
        <td>{{ number_format($taxable,2) }}</td>
        <td>{{ number_format($cgst,2) }}</td>
        <td>{{ number_format($sgst,2) }}</td>
        <td>{{ number_format($igst,2) }}</td>
        <td>{{ number_format($cess,2) }}</td>
        <td>{{ number_format($po->tax_total,2) }}</td>
    </tr>
</table>

@if($po->rejection_reason)
<table class="table table-bordered no-break">
    <tr>
        <td>
            <b>Rejection Reason:</b><br>
            {{ $po->rejection_reason }}
        </td>
    </tr>
</table>
@endif

{{-- ================= TERMS & CONDITIONS ================= --}}
<table class="table table-bordered no-break">
    <tr>
        <td>
            <b>Terms & Conditions</b><br><br>

            1. Material supplied must have a minimum shelf life of 6 months from the date of delivery. 
            Items not meeting this requirement will be returned to the supplier.<br><br>

            2. Any expired, short-dated, or damaged products will be rejected at the time of delivery. 
            A debit note will be issued accordingly.<br><br>

            3. The issued debit note must be reflected in your upcoming GST filing. 
            If not filed, the corresponding amount will be kept on hold.<br><br>

            4. GST returns must be filed regularly as per statutory norms. 
            Non-compliance may result in payment hold until compliance is completed.
        </td>
    </tr>
</table>


<br><br>

{{-- ================= SIGNATURE ================= --}}
<table style="border:none;">
    <tr>
        <td style="border:none; text-align:center;">
            ___________________________<br>
            Prepared By
        </td>
        <td style="border:none; text-align:center;">
            ___________________________<br>
            Authorized Signatory
        </td>
    </tr>
</table>

</div>
</body>
</html>
