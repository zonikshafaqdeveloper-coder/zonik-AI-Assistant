<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quotation Invoice - {{ $quotation->quotation_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; padding: 30px; }
        .top-banner { width: 100%; margin-bottom: 20px; }
        .top-banner img { width: 100%; height: auto; display: block; }
        .invoice-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e97457; padding-bottom: 15px; margin-bottom: 20px; }
        .invoice-header h2 { margin: 0; color: #e97457; }
        .details-table { width: 100%; margin-bottom: 20px; }
        .details-table td { padding: 4px 0; vertical-align: top; }
        .details-table td.label { font-weight: bold; width: 150px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        table.items th, table.items td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 13px; word-wrap: break-word; }
        table.items th { background: #f8f9fa; }
        table.items th.col-num { width: 4%; }
        table.items th.col-product { width: 32%; }
        table.items th.col-brand { width: 12%; }
        table.items th.col-category { width: 14%; }
        table.items th.col-price { width: 14%; }
        .text-end { text-align: right; }
        .print-btn { margin-bottom: 20px; }
        @media print {
             .print-btn { display: none !important; }
        }
        .top-banner { display: none; }
        @media print {
            .top-banner {
                display: block;
                width: 100%;
                margin-bottom: 20px;
            }
            .top-banner img {
                width: 100%;
                height: auto;
                display: block;
            }
        }
        
    table.items tfoot td {
        border-top: 2px solid #e97457;
        background: #f8f9fa;
    }
    
     .print-btn {
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
}

.btn-action {
    display: inline-block;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.1s ease;
}

.btn-print {
    background: #e97457;
    color: #fff;
}

.btn-print:hover {
    background: #d9613f;
}

.btn-excel {
    background: #1d6f42;
    color: #fff;
}

.btn-excel:hover {
    background: #155a34;
}

.btn-action:active {
    transform: scale(0.97);
}
    
    </style>
</head>
<body>
<div class="print-btn">
    <button onclick="window.print()" class="btn-action btn-print">Print / Save as PDF</button>
    <a href="{{ route('admin.quotation.excel', $quotation->id) }}" class="btn-action btn-excel">Download Excel</a>
</div>
   <div class="top-banner">
    <img src="{{ asset('frontweb/assests/images/quote-img.jpeg') }}" alt="Zonik">
</div>
    <div class="invoice-header">
        <div>
            <h2>Quotation</h2>
            <div>{{ $quotation->quotation_number }}</div>
        </div>
        <div class="text-end">
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-m-Y') }}</div>
        </div>
    </div>
    <table class="details-table">
        <tr>
            <td class="label">Outlet Name:</td>
            <td>{{ $quotation->leadCustomer->outlet_name ?? '-' }}</td>
            <td class="label">Payment Term:</td>
            <!--<td>{{ $quotation->leadCustomer->payment_term ?? '-' }}</td>-->
            <td>{{ $quotation->leadCustomer->payment_term ? $quotation->leadCustomer->payment_term . ' Days' : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Purchaser Name:</td>
            <td>{{ $quotation->leadCustomer->customer_name ?? '-' }}</td>
            <td class="label">Mobile Number:</td>
            <td>{{ $quotation->leadCustomer->mobile_number ?? '-' }}</td>
        </tr>
        
        <tr>
            <td class="label">Address:</td>
            <td colspan="3">{{ $quotation->leadCustomer->address ?? '-' }}</td>
        </tr>
    </table>
 <table class="items">
    <thead>
        <tr>
            <th class="col-num">#</th>
            <th class="col-product">Product</th>
            <th class="col-brand">Brand</th>
            <th class="col-category">Category</th>
            <th class="col-price">Sale Price (Basic)</th>
            @if($quotation->items->contains(fn($item) => $item->customer_price > 0))
            <!--<th class="col-price">Customer Price</th>-->
            <!--<th class="col-price">Total Saving %</th>-->
            @endif
        </tr>
    </thead>

    <tbody>
        @php
            $totalCustomerPrice = 0;
            $totalSavingPercentSum = 0;
            $itemCount = count($quotation->items);
            $showCustomerPrice = $quotation->items->contains(fn($item) => $item->customer_price > 0);
        @endphp

        @foreach($quotation->items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->product->product_name ?? 'Unknown Product' }}</td>
            <td>{{ $item->brand }}</td>
            <td>{{ $item->category }}</td>
            <td>₹{{ number_format($item->sale_price_basic, 2) }}</td>
            @if($showCustomerPrice)
            <!--<td>₹{{ number_format($item->customer_price, 2) }}</td>-->
            <!--<td>{{ $item->total_saving_percent }}%</td>-->
            @endif
        </tr>
        @php
            $totalCustomerPrice += $item->customer_price;
            $totalSavingPercentSum += $item->total_saving_percent;
        @endphp
        @endforeach
    </tbody>
</table>

{{-- Totals — outside the table, renders ONCE at the true end, never repeats per page --}}
<!--<table class="items" style="margin-top: -1px;">-->
<!--    <tr>-->
<!--        <td class="text-end" style="border:1px solid #ddd; padding:8px; font-size:13px; {{ $showCustomerPrice ? 'width:74%;' : 'width:100%;' }}">-->
<!--            <strong>Total / Average</strong>-->
<!--        </td>-->
<!--        @if($showCustomerPrice)-->
<!--        <td style="border:1px solid #ddd; padding:8px; font-size:13px; background:#f8f9fa;">-->
<!--            <strong>₹{{ number_format($totalCustomerPrice, 2) }}</strong>-->
<!--        </td>-->
<!--        <td style="border:1px solid #ddd; padding:8px; font-size:13px; background:#f8f9fa;">-->
<!--            <strong>{{ $itemCount > 0 ? number_format($totalSavingPercentSum / $itemCount, 2) : '0.00' }}%</strong>-->
<!--        </td>-->
<!--        @endif-->
<!--    </tr>-->
<!--</table>-->

{{-- Note — completely outside the items table, appears once at the end of the document --}}
<div style="margin-top:20px; font-size:14px; line-height:1.8; color:#000;">
    <strong>NOTE:</strong><br>
    <strong>This quotation is valid for 1 month only.</strong><br>
    <strong>If the company rate changes, the quoted rate will change accordingly.</strong>
</div>
</body>
</html>
