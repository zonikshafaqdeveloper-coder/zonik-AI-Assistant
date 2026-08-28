<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #101828; }
    .header { text-align: center; margin-bottom: 20px; }
    .header h1 { font-size: 18px; margin: 0 0 4px; color: #101828; }
    .header .sub { font-size: 11px; color: #667085; }
    .meta-row { display: table; width: 100%; margin: 14px 0 20px; font-size: 10.5px; color: #475467; }
    .meta-row .cell { display: table-cell; }
    .meta-row .cell.right { text-align: right; }

    .month-block { margin-bottom: 22px; page-break-inside: avoid; }
    .month-title-row {
        background: #f8f5ff; padding: 8px 10px; border-radius: 4px;
        font-weight: bold; font-size: 12px; color: #101828;
        margin-bottom: 6px;
    }
    .month-title-row .amt { float: right; }
    .month-title-row .status { font-size: 10px; padding: 2px 8px; border-radius: 10px; margin-left: 8px; }
    .status.paid { background: #e3f8ec; color: #1d9e75; }
    .status.partial { background: #fdecd6; color: #e2711d; }
    .status.unpaid { background: #fdecea; color: #dc3545; }

    table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    th { background: #f8f5ff; color: #7c4fd6; text-align: left; padding: 6px 8px; font-size: 9.5px; border-bottom: 1px solid #eef0f3; }
    td { padding: 6px 8px; font-size: 9.5px; border-bottom: 1px solid #f2f3f6; }
    td.right, th.right { text-align: right; }

    .footer-note { margin-top: 24px; font-size: 9px; color: #98a2b3; text-align: center; }
</style>
</head>
<body>

    <div class="header">
        <h1>Account Statement</h1>
        <div class="sub">{{ $outletData->outlet_name ?? $outletData->name ?? '-' }}</div>
    </div>

    <div class="meta-row">
        <div class="cell">Period: {{ $startDate }} — {{ $endDate }}</div>
        <div class="cell right">Generated on: {{ $generatedOn }}</div>
    </div>

    @forelse($monthlyDetails as $month)
        <div class="month-block">
            <div class="month-title-row">
                {{ $month['month_label'] }}
                <span class="status {{ strtolower($month['status']) }}">{{ $month['status'] }}</span>
                <span class="amt">
                    Total: ₹{{ number_format($month['total'], 2) }}
                    &nbsp;|&nbsp;
                    Outstanding: ₹{{ number_format($month['outstanding'], 2) }}
                </span>
            </div>

            @if(count($month['invoices']) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Invoice No.</th>
                        <th>Invoice Date</th>
                        <th>Delivered Date</th>
                        <th class="right">Amount (₹)</th>
                        <th class="right">Paid (₹)</th>
                        <th class="right">Outstanding (₹)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($month['invoices'] as $inv)
                        <tr>
                            <td>{{ $inv['invoice_no'] }}</td>
                            <td>{{ $inv['invoice_date'] }}</td>
                            <td>{{ $inv['delivered_date'] }}</td>
                            <td class="right">{{ number_format($inv['amount'], 2) }}</td>
                            <td class="right">{{ number_format($inv['paid'], 2) }}</td>
                            <td class="right">{{ number_format($inv['outstanding'], 2) }}</td>
                            <td>{{ $inv['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div style="font-size:10px; color:#98a2b3; padding:6px;">No invoices in this month.</div>
            @endif
        </div>
    @empty
        <p style="text-align:center; color:#98a2b3;">No transactions found for this period.</p>
    @endforelse

    <div class="footer-note">
        This statement includes details of all your transactions on Zonik for the selected period.
    </div>

</body>
</html>