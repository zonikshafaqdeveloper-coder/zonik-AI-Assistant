<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width:100%; border-collapse:collapse; }
    th, td { border:1px solid #000; padding:6px; text-align:center; }
    th { background:#f1f1f1; }
    .header { display:flex; align-items:center; border-bottom:2px solid #000; padding-bottom:8px; }
    .logo { width:100px; }
    .title { text-align:center; font-size:16px; font-weight:bold; margin:10px 0; }
</style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('frontweb/assests/images/Adobe Express - file.png') }}" class="logo">
    <div style="margin-left:auto; text-align:right;">
        <strong> Infigourmet networks private limited.</strong><br>
        Pick List Preview
    </div>
</div>

<div class="title">PICK LIST</div>

<p>
<strong>Order ID:</strong> {{ $order->order_id ?? $order->id }}<br>
<strong>Date:</strong> {{ now()->format('d-m-Y') }}<br>
<strong>Outlet Name:</strong> {{ $order->outlet->outlet_name ?? '-' }}
</p>

<table style="width:100%; margin-bottom:10px;">
    <tr>
        <td><strong>Dispatch Bin No:</strong> {{ $dispatch->dispatched_rack ?? '-' }}</td>
        <td><strong>No. of Boxes:</strong> {{ $dispatch->number_of_boxes ?? '-' }}</td>
    </tr>
</table>


<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Bay</th>
            <th>Column </th>
            <th>Floor</th>
            <th>Batch</th>
            <th>Expiry</th>
            <th>Order Qty</th>
            <th>Stock In Hand</th>
            <th>Pick Qty</th>
        </tr>
    </thead>
    <tbody>
    @foreach($pickData as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td style="text-align:left">{{ $row['product'] }}</td>
            <td>{{ $row['rack_no'] }}</td>
            <td>{{ $row['level_no'] }}</td>
            <td>{{ $row['slot_no'] }}</td>
            <td>{{ $row['batch_no'] ?? '-' }}</td>
            <td>{{ $row['expiry'] ?? '-' }}</td>
            <td>{{ number_format($row['needed'],2) }}</td> {{-- NEW --}}
            <td>{{ $row['stock_in_hand'] }}</td>
            <td>{{ number_format($row['pick_qty'],2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>
<textarea name="remark" class="form-control" placeholder="Enter remark (optional)"></textarea>

<br><br>
<table style="width:100%; border:none;">
<tr>
    <td style="border:none; text-align:center;">
        _____________________<br>
        Picked By
    </td>
    <td style="border:none; text-align:center;">
        _____________________<br>
        Verified By
    </td>
    <td style="border:none; text-align:center;">
        _____________________<br>
        Packed By
    </td>
</tr>
</table>

</body>
</html>
