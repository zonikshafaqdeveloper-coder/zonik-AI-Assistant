<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Debit Note - {{ $note->debit_note_no }}</title>

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

@php
$receiving = $note->receiving;
$sr = 1;
$grandSubtotal = 0;
$grandCGST = 0;
$grandSGST = 0;
$grandTotal = 0;
@endphp

{{-- HEADER --}}
<table>
<tr>
    <td colspan="9" class="header">DEBIT NOTE</td>
</tr>
</table>

{{-- COMPANY --}}
<table>
<tr>
<td>
    <b>Infigourmet Networks Private Limited</b><br>
    Unit no 42  (Near Panchal Furniture),<br>
    Nav Nandanvan Industrial estate,<br>
    Asha Nagar, Mulund West,Mumbai 400080.<br>
    ( Landmark : Gold Gym Mulund West.)<br>
    Fssai No - 11525009000305<br>
    GSTIN/UIN: 27AAICI2086H1ZE<br>
    State Name : Maharashtra, Code : 27<br>
    Contact : +91-9869612312
</td>   
</tr>
</table>

{{-- VENDOR --}}
<table>
<tr>
    <th width="50%">Consignee (Ship to)</th>
    <th width="50%">Buyer (Bill to)</th>
</tr>

<tr>
<td>
    <p><b>{{ $note->vendor->name }}</b></p>
    <p>GSTIN/UIN : {{ $note->vendor->gst_number ?? 'NA' }}</p>
    <p>State Name : Maharashtra, Code : 27</p>
    <p>Fssai No : {{ $note->vendor->fssai_number ?? 'NA' }}</p>
    <p>Contact : {{ $note->vendor->mobile }}</p>
</td>

<td>
    <p><b>{{ $note->vendor->name }}</b></p>
    <p>GSTIN/UIN : {{ $note->vendor->gst_number ?? 'NA' }}</p>
    <p>State Name : Maharashtra, Code : 27</p>
    <p>Fssai No : {{ $note->vendor->fssai_number ?? 'NA' }}</p>
    <p>Contact : {{ $note->vendor->mobile }}</p>
</td>
</tr>
</table>

{{-- DETAILS --}}
<table>
<tr>
<td width="50%">
    <b>Debit Note No.</b> :
    {{ $note->debit_note_no }}
</td>

<td width="50%">
    <b>Dated</b> :
    {{ \Carbon\Carbon::parse($note->created_at)->format('d-M-Y') }}
</td>
</tr>

<tr>
<td>
    <b>Original Invoice No. & Date</b> :
    {{ $receiving->bill_no ?? '' }}
    dt. {{ $receiving->bill_date ?? '' }}
</td>

<td>
    <b>GRN No.</b> :
    GRN-{{ str_pad($receiving->id, 4, '0', STR_PAD_LEFT) }}
</td>
</tr>

<tr>
<td>
    <b>Receipt Date</b> :
    {{ $receiving->receipt_date ?? '' }}
</td>

<td>
    <b>Bill Date</b> :
    {{ $receiving->bill_date ?? '' }}
</td>
</tr>
</table>

{{-- PRODUCTS --}}
<table>
<thead>
<tr>
    <th>#</th>
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

@foreach($note->items as $item)

@php
$product = $item->product;

$qty = $item->quantity;
$rate = $item->rate ?? 0;
$amount = $qty * $rate;

// Default GST (you can later fetch from DB)
$taxPercent = 18;

$cgstRate = $taxPercent / 2;
$sgstRate = $taxPercent / 2;

$cgstAmount = ($amount * $cgstRate) / 100;
$sgstAmount = ($amount * $sgstRate) / 100;

$rowTotal = $amount + $cgstAmount + $sgstAmount;

$grandSubtotal += $amount;
$grandCGST += $cgstAmount;
$grandSGST += $sgstAmount;
$grandTotal += $rowTotal;
@endphp

<tr>
<td>{{ $sr++ }}</td>
<td>{{ $product->product_name ?? '-' }}</td>
<td>{{ $product->hsn_code ?? '-' }}</td>
<td>{{ $qty }}</td>
<td>{{ number_format($rate,2) }}</td>
<td>{{ number_format($amount,2) }}</td>
<td>{{ $taxPercent }}%</td>
<td>{{ number_format($cgstAmount + $sgstAmount,2) }}</td>
<td>{{ number_format($rowTotal,2) }}</td>
</tr>

@endforeach

</tbody>
</table>

{{-- SUMMARY --}}
<table>
<tr>
<td colspan="7" class="text-right"><b>Total Amount</b></td>
<td colspan="2"><b>{{ number_format($grandTotal,2) }}</b></td>
</tr>

<tr>
<td colspan="2"><b>Amount in Words</b></td>
<td colspan="7">
{{ app('App\Http\Controllers\OrderController')->numberToWords($grandTotal) }}
</td>
</tr>
</table>

{{-- FOOTER --}}
<table>
<tr>
<td width="50%">E. & O.E</td>
<td width="50%" class="text-center">
For Infigourmet Networks Pvt Ltd<br><br><br>
<b>Authorised Signatory</b>
</td>
</tr>
</table>

</body>
</html>