<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body {
    font-family: DejaVu Sans;
    font-size: 11px;
}

.center { text-align: center; }

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th {
    background: #e6e6e6;
    padding: 6px;
    text-align: left;
    border-bottom: 1px solid #999;
}

td {
    padding: 6px;
    border-bottom: 1px solid #ddd;
}

.right { text-align: right; }

.total-row td {
    font-weight: bold;
    border-top: 2px solid #000;
}
</style>

</head>
<body>

<h3 class="center">Infigourmet networks private limited</h3>

<h4 class="center">Sales by Customer</h4>

<p class="center">
Customer: {{ $customer->name }} <br>

@if($from && $to)
From {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }}
To {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}
@else
All Time
@endif
</p>

<table>
<thead>
<tr>
    <th>Name</th>
    <th>Invoice Count</th>
    <th>Sales</th>
    <th>Sales With Tax</th>
    <th>Invoice Amount</th>
    <th>GST Treatment</th>
</tr>
</thead>

<tbody>

<tr>
    <td>{{ $customer->name }}</td>

    <td class="right">{{ $data->invoice_count }}</td>

    <td class="right">
        ₹{{ number_format($data->sales, 2) }}
    </td>

    <td class="right">
        ₹{{ number_format($data->sales_with_tax, 2) }}
    </td>

    <td class="right">
        ₹{{ number_format($data->invoice_amount, 2) }}
    </td>

    <td>Registered Business - Regular</td>
</tr>

<tr class="total-row">
    <td>TOTAL</td>

    <td class="right">{{ $totals['invoice_count'] }}</td>

    <td class="right">
        ₹{{ number_format($totals['sales'], 2) }}
    </td>

    <td class="right">
        ₹{{ number_format($totals['sales_with_tax'], 2) }}
    </td>

    <td class="right">
        ₹{{ number_format($totals['invoice_amount'], 2) }}
    </td>

    <td></td>
</tr>

</tbody>
</table>

</body>
</html>