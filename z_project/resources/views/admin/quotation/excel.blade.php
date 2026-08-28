<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>

<table border="1">
    <tr>
        <td colspan="4"><strong>Quotation: {{ $quotation->quotation_number }}</strong></td>
    </tr>
    <tr>
        <td colspan="4">Date: {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-m-Y') }}</td>
    </tr>
    <tr><td colspan="4"></td></tr>

    <tr>
        <td><strong>Outlet Name</strong></td>
        <td>{{ $quotation->leadCustomer->outlet_name ?? '-' }}</td>
        <td><strong>Payment Term</strong></td>
        <td>{{ $quotation->leadCustomer->payment_term ? $quotation->leadCustomer->payment_term . ' Days' : '-' }}</td>
    </tr>
    <tr>
        <td><strong>Purchaser Name</strong></td>
        <td>{{ $quotation->leadCustomer->customer_name ?? '-' }}</td>
        <td><strong>Mobile Number</strong></td>
        <td>{{ $quotation->leadCustomer->mobile_number ?? '-' }}</td>
    </tr>
    <tr>
        <td><strong>Address</strong></td>
        <td colspan="3">{{ $quotation->leadCustomer->address ?? '-' }}</td>
    </tr>
    <tr><td colspan="4"></td></tr>

    <tr>
        <th>#</th>
        <th>Product</th>
        <th>Brand</th>
        <th>Category</th>
        <th>Sale Price (Basic)</th>
    </tr>

    @foreach($quotation->items as $i => $item)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $item->product->product_name ?? 'Unknown Product' }}</td>
        <td>{{ $item->brand }}</td>
        <td>{{ $item->category }}</td>
        <td>{{ number_format($item->sale_price_basic, 2) }}</td>
    </tr>
    @endforeach

</table>

</body>
</html>