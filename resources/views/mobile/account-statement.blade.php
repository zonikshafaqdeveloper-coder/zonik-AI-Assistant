@extends('mobile.mobile-app')
@section('content')

<style>
.as-page {
    background: #f7f8fa;
    padding: 16px 16px 24px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.as-container { max-width: 720px; margin: 0 auto; }

.as-top {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px;
}
.as-top-left { display: flex; align-items: center; gap: 14px; }
.as-back { color: #101828; display: flex; text-decoration: none; }
.as-title { font-size: 22px; font-weight: 800; color: #101828; margin: 0; }
.as-icon-btn {
    width: 40px; height: 40px; border-radius: 10px;
    background: #f3ecff; color: #7c4fd6;
    display: flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; text-decoration: none;
}

.as-section-label { font-size: 15px; font-weight: 700; color: #101828; margin-bottom: 12px; }

.as-period-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
.as-chip {
    padding: 9px 18px; border-radius: 100px;
    font-size: 11.5px; font-weight: 700;
    border: 1.5px solid #e4d7fb; color: #7c4fd6;
    background: #fff; cursor: pointer; white-space: nowrap;
}
.as-chip.active { background: #7c4fd6; color: #fff; border-color: #7c4fd6; }

.as-note {
    display: flex; gap: 14px;
    background: #f3ecff; border-radius: 14px;
    padding: 16px; margin-bottom: 20px;
}
.as-note-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: #7c4fd6; color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.as-note-title { font-size: 12.5px; font-weight: 700; color: #101828; margin-bottom: 4px; }
.as-note-text { font-size: 12.5px; color: #475467; line-height: 1.5; }

.as-download-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; background: #fff;
    border: 1.5px solid #7c4fd6; color: #7c4fd6;
    border-radius: 12px; padding: 14px; font-size: 14.5px; font-weight: 700;
    cursor: pointer; text-decoration: none; margin-bottom: 24px;
}

.as-summary-sub { font-size: 12.5px; color: #98a2b3; margin-bottom: 14px; }

.as-table-wrap {
    background: #fff; border: 1px solid #eef0f3; border-radius: 14px;
    overflow: hidden; margin-bottom: 20px;
}
.as-table-head {
    display: grid; grid-template-columns: 1.2fr 1.3fr 1fr 1.2fr 24px;
    background: #f8f5ff; padding: 12px 16px;
    font-size: 11.5px; font-weight: 700; color: #7c4fd6;
    gap: 8px;
}
.as-table-row {
    display: grid; grid-template-columns: 1.2fr 1.3fr 1fr 1.2fr 24px;
    align-items: center;
    padding: 14px 16px;
    font-size: 13px; color: #101828;
    border-top: 1px solid #f2f3f6;
    gap: 8px;
    cursor: pointer;
}
.as-table-row.active-row { background: #f8f5ff; }
.as-table-row .outstanding { font-weight: 700; }
.as-table-row .outstanding.zero { color: #101828; }
.as-table-row .outstanding.due { color: #e0442e; }
.as-table-row .chevron { color: #98a2b3; text-align: right; }

.as-status-badge {
    font-size: 10.5px; font-weight: 700; padding: 3px 10px; border-radius: 100px;
    display: inline-block; width: fit-content;
}
.as-status-badge.paid { background: #e3f8ec; color: #1d9e75; }
.as-status-badge.partial { background: #fdecd6; color: #e2711d; }
.as-status-badge.unpaid { background: #fdecea; color: #dc3545; }

@media (max-width: 480px) {
    .as-table-head, .as-table-row { font-size: 11px; grid-template-columns: 1fr 1fr 0.8fr 1fr 18px; }
    .as-status-badge { font-size: 9px; padding: 2px 7px; }
}

/* ===== Month Detail Drill-down ===== */
.as-detail-card {
    background: #fff; border: 1px solid #eef0f3; border-radius: 14px; padding: 16px;
}
.as-detail-header { display: flex; align-items: center; gap: 12px; margin-bottom: 4px; }
.as-detail-back { color: #101828; cursor: pointer; display: flex; }
.as-detail-title { font-size: 16px; font-weight: 800; color: #101828; }
.as-detail-sub { font-size: 12px; color: #98a2b3; margin-left: 34px; margin-bottom: 14px; }
.as-detail-sub .due { color: #e0442e; font-weight: 700; }

.as-invoice-table-wrap { border: 1px solid #eef0f3; border-radius: 12px; overflow: hidden; }
.as-invoice-head {
    display: grid; grid-template-columns: 1.3fr 1fr 1.2fr 0.8fr;
    background: #f8f5ff; padding: 10px 14px;
    font-size: 11px; font-weight: 700; color: #7c4fd6; gap: 8px;
}
.as-invoice-row {
    display: grid; grid-template-columns: 1.3fr 1fr 1.2fr 0.8fr;
    align-items: center; padding: 12px 14px;
    font-size: 12.5px; color: #101828;
    border-top: 1px solid #f2f3f6; gap: 8px;
}

.as-loading { text-align: center; padding: 30px 0; color: #98a2b3; font-size: 13px; }

@media (min-width: 768px) {
    .as-page { padding: 32px 24px; }
    .as-container { background: #fff; border-radius: 20px; padding: 28px 32px; box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04); }
}
</style>

<div class="as-page">
    <div class="as-container">

        <!-- ===== Top bar ===== -->
        <div class="as-top">
            <div class="as-top-left">
                <a href="{{ url()->previous() }}" class="as-back" aria-label="Back">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </a>
                <h1 class="as-title">Account Statement</h1>
            </div>
            <button type="button" class="as-icon-btn" id="downloadIconBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </button>
        </div>

        <!-- ===== Period Picker ===== -->
        <div class="as-section-label">Choose accounting period</div>
        <div class="as-period-chips">
            <div class="as-chip" data-period="custom">Custom</div>
            <div class="as-chip" data-period="last_month">Last Month</div>
            <div class="as-chip" data-period="current_month">Current Month</div>
            <div class="as-chip" data-period="last_quarter">Last Quarter</div>
            <div class="as-chip" data-period="current_quarter">Current Quarter</div>
        </div>

        <!-- ===== Custom Date Range Picker — shown only when "Custom" is clicked ===== -->
        <div id="customRangeRow" style="display:none; margin-bottom:20px;">
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="flex:1; min-width:140px;">
                    <label style="display:block; font-size:12px; font-weight:700; color:#344054; margin-bottom:6px;">From</label>
                    <input type="date" id="customFrom" style="width:100%; box-sizing:border-box; border:1px solid #e4e7ec; border-radius:9px; padding:10px 12px; font-size:13px;">
                </div>
                <div style="flex:1; min-width:140px;">
                    <label style="display:block; font-size:12px; font-weight:700; color:#344054; margin-bottom:6px;">To</label>
                    <input type="date" id="customTo" style="width:100%; box-sizing:border-box; border:1px solid #e4e7ec; border-radius:9px; padding:10px 12px; font-size:13px;">
                </div>
            </div>
            <button type="button" id="applyCustomRange" class="ot-btn-apply" style="background:#7c4fd6; color:#fff; border:none; border-radius:9px; padding:11px 24px; font-size:13.5px; font-weight:700; cursor:pointer; margin-top:12px;">
                Apply Custom Range
            </button>
        </div>

        <!-- ===== Note ===== -->
        <div class="as-note">
            <div class="as-note-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="15" y2="17"/></svg>
            </div>
            <div>
                <div class="as-note-title">NOTE:</div>
                <div class="as-note-text">1. Account statement includes details of all your transactions on Zonik.</div>
            </div>
        </div>

        <!-- ===== Download ===== -->
        <a href="#" class="as-download-btn" id="downloadStatementBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Statement
        </a>

        <!-- ===== Summary Table ===== -->
        <div class="as-section-label">Last 6 Months Summary</div>
        <div class="as-summary-sub">Overview of your account status</div>

        <div class="as-table-wrap" id="summaryTableWrap">
            <div class="as-table-head">
                <div>Month</div>
                <div>Total Amount (₹)</div>
                <div>Status</div>
                <div>Outstanding (₹)</div>
                <div></div>
            </div>

            @forelse($summary as $row)
                @php
                    $statusClass = strtolower($row['status']);
                    $outstandingClass = $row['outstanding'] > 0 ? 'due' : 'zero';
                @endphp
                <div class="as-table-row" data-month="{{ $row['month_key'] }}" data-month-label="{{ $row['month_label'] }}">
                    <div>{{ $row['month_label'] }}</div>
                    <div>{{ number_format($row['total'], 2) }}</div>
                    <div><span class="as-status-badge {{ $statusClass }}">{{ $row['status'] }}</span></div>
                    <div class="outstanding {{ $outstandingClass }}">
                        {{ $row['outstanding'] > 0 ? '₹' . number_format($row['outstanding'], 2) : '₹0.00' }}
                    </div>
                    <div class="chevron">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>
                </div>
            @empty
                <div style="padding:30px; text-align:center; color:#98a2b3; font-size:13px;">No transactions found for this period.</div>
            @endforelse
        </div>

        <!-- ===== Invoice Detail Drill-down ===== -->
        <div class="as-detail-card" id="monthDetailCard" style="display:none;">
            <div class="as-detail-header">
                <span class="as-detail-back" id="closeMonthDetail">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </span>
                <span class="as-detail-title" id="detailTitle">— Invoice Details</span>
            </div>
            <div class="as-detail-sub" id="detailSub"></div>

            <div id="detailLoading" class="as-loading">Loading invoice details...</div>

            <div class="as-invoice-table-wrap" id="detailTableWrap" style="display:none;">
                <div class="as-invoice-head">
                    <div>Invoice No.</div>
                    <div>Amount (₹)</div>
                    <div>Delivered Date</div>
                    <div>Status</div>
                </div>
                <div id="detailTableBody"></div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    // ===== Period chip selection =====
    $('.as-chip').on('click', function () {
        const period = $(this).data('period');

        if (period === 'custom') {
            // Show the date picker instead of reloading immediately —
            // this IS the fix for "Customize option not working": clicking
            // it previously just reloaded with a silent fallback range
            // with no way to actually pick real dates.
            $('.as-chip').removeClass('active');
            $(this).addClass('active');
            $('#customRangeRow').slideDown(150);
            return;
        }

        window.location.href = '{{ route('web.account-statement') }}?period=' + period;
    });

    $('#applyCustomRange').on('click', function () {
        const from = $('#customFrom').val();
        const to = $('#customTo').val();

        if (!from || !to) {
            Swal.fire({ icon: 'warning', title: 'Select both dates', text: 'Please choose a From and To date.' });
            return;
        }

        window.location.href = '{{ route('web.account-statement') }}?period=custom&from=' + from + '&to=' + to;
    });

    // Reflect current period in the chips (since 'last_6_months' default
    // has no matching chip in the reference — none show active for it)
    const currentPeriod = '{{ $period }}';
    $('.as-chip[data-period="' + currentPeriod + '"]').addClass('active');
    @if($period === 'custom')
        $('#customRangeRow').show();
        $('#customFrom').val('{{ request('from') }}');
        $('#customTo').val('{{ request('to') }}');
    @endif

    // ===== Download — carries from/to too when period is custom, so the
    // downloaded PDF matches exactly whatever range is currently selected =====
    function buildDownloadUrl() {
        let url = '{{ route('web.account-statement.download') }}?period={{ $period }}';
        @if($period === 'custom')
            url += '&from={{ request('from') }}&to={{ request('to') }}';
        @endif
        return url;
    }

    $('#downloadIconBtn').on('click', function () {
        window.location.href = buildDownloadUrl();
    });

    $('#downloadStatementBtn').on('click', function (e) {
        e.preventDefault();
        window.location.href = buildDownloadUrl();
    });

    // ===== Click a month row — load real invoice details =====
    $(document).on('click', '.as-table-row', function () {
        const monthKey = $(this).data('month');
        const monthLabel = $(this).data('month-label');

        $('.as-table-row').removeClass('active-row');
        $(this).addClass('active-row');

        $('#monthDetailCard').show();
        $('#detailTitle').text(monthLabel + ' - Invoice Details');
        $('#detailLoading').show();
        $('#detailTableWrap').hide();
        $('#detailSub').text('');

        $.get('{{ route('web.account-statement.month-details') }}', { month: monthKey }, function (res) {
            $('#detailLoading').hide();
            $('#detailTableWrap').show();

            $('#detailSub').html(
                'Total Amount: ₹' + Number(res.total_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) +
                ' &nbsp;|&nbsp; Outstanding: <span class="due">₹' + Number(res.total_outstanding).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span>'
            );

            const $body = $('#detailTableBody');
            $body.empty();

            if (res.rows.length === 0) {
                $body.append('<div style="padding:16px; text-align:center; color:#98a2b3; font-size:12.5px;">No invoices found for this month.</div>');
                return;
            }

            res.rows.forEach(function (row) {
                const statusClass = row.status.toLowerCase();
                $body.append(`
                    <div class="as-invoice-row">
                        <div>${row.invoice_no}</div>
                        <div>₹${Number(row.amount).toLocaleString('en-IN', {minimumFractionDigits:2})}</div>
                        <div>${row.delivered_date}</div>
                        <div><span class="as-status-badge ${statusClass}">${row.status}</span></div>
                    </div>
                `);
            });
        }).fail(function () {
            $('#detailLoading').text('Could not load invoice details.');
        });

        // Scroll the detail card into view
        $('html, body').animate({ scrollTop: $('#monthDetailCard').offset().top - 20 }, 300);
    });

    $('#closeMonthDetail').on('click', function () {
        $('#monthDetailCard').hide();
        $('.as-table-row').removeClass('active-row');
    });

});
</script>
@endsection
