@extends('mobile.mobile-app')
@section('content')

<style>
.po-page {
    background: #f7f8fa;
    padding: 16px 16px 24px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.po-container { max-width: 720px; margin: 0 auto; }

.po-title { font-size: 22px; font-weight: 800; color: #101828; margin: 0 0 4px; }
.po-subtitle { font-size: 13px; color: #667085; margin-bottom: 18px; }

/* ===== Tab toggle ===== */
.po-tabs { display: flex; gap: 8px; margin-bottom: 16px; }
.po-tab {
    flex: 1; text-align: center; padding: 12px 8px; border-radius: 10px;
    font-size: 13.5px; font-weight: 700; color: #344054;
    background: #fff; border: 1px solid #eef0f3; cursor: pointer;
}
.po-tab.active.overdue { background: #e2571f; color: #fff; border-color: #e2571f; }
.po-tab.active.not-overdue { background: #1d9e75; color: #fff; border-color: #1d9e75; }

/* ===== Summary card ===== */
.po-summary-card {
    border-radius: 14px; padding: 16px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
}
.po-summary-card.overdue { background: #fdecea; }
.po-summary-card.not-overdue { background: #e3f8ec; }
.po-summary-label { font-size: 11.5px; color: #667085; margin-bottom: 4px; }
.po-summary-value { font-size: 20px; font-weight: 800; }
.po-summary-value.overdue { color: #e0442e; }
.po-summary-value.not-overdue { color: #1d9e75; }
.po-summary-days { display: flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 700; }
.po-summary-days.overdue { color: #e0442e; }
.po-summary-days.not-overdue { color: #1d9e75; }

.po-section-label { font-size: 15px; font-weight: 700; color: #101828; margin-bottom: 12px; }

/* ===== Month group card ===== */
.po-month-card {
    background: #fff; border-radius: 14px; padding: 16px; margin-bottom: 14px;
}
.po-month-card.overdue { border: 1.5px solid #fbcfc7; background: #fef7f6; }
.po-month-card.not-overdue { border: 1.5px solid #cdeede; background: #f6fcf9; }

.po-month-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 6px; }
.po-month-left { display: flex; align-items: center; gap: 10px; }
.po-month-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.po-month-icon.overdue { background: #fdecea; color: #e0442e; }
.po-month-icon.not-overdue { background: #e3f8ec; color: #1d9e75; }
.po-month-name-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.po-month-name { font-size: 15px; font-weight: 800; color: #101828; }
.po-month-badge { font-size: 10px; font-weight: 700; padding: 2px 9px; border-radius: 100px; }
.po-month-badge.overdue { background: #fdecea; color: #e0442e; }
.po-month-invoice-count { font-size: 12px; color: #667085; margin-top: 2px; }

.po-month-amount-wrap { text-align: right; flex-shrink: 0; }
.po-month-amount-label { font-size: 11px; color: #98a2b3; }
.po-month-amount { font-size: 16px; font-weight: 800; }
.po-month-amount.overdue { color: #e0442e; }
.po-month-amount.not-overdue { color: #1d9e75; }

.po-month-due-row { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #667085; margin-bottom: 12px; }

.po-month-actions { display: flex; gap: 10px; }
.po-btn-view-invoices {
    flex: 1; background: #fff; border: 1px solid #e4e7ec; border-radius: 9px;
    padding: 10px; font-size: 12.5px; font-weight: 700; color: #344054; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.po-btn-pay-now {
    flex: 1; border: none; border-radius: 9px; padding: 10px;
    font-size: 12.5px; font-weight: 700; color: #fff; cursor: pointer;
}
.po-btn-pay-now.overdue { background: #e2571f; }
.po-btn-pay-now.not-overdue { background: #1d9e75; }

/* ===== Info banner ===== */
.po-info-banner {
    display: flex; gap: 10px; border-radius: 12px; padding: 14px; margin: 20px 0;
    font-size: 12.5px; line-height: 1.5;
}
.po-info-banner.overdue { background: #fdecea; color: #a4161a; }
.po-info-banner.not-overdue { background: #e3ecfd; color: #2f5ede; }
.po-info-banner svg { flex-shrink: 0; }

/* ===== Need Help ===== */
.po-help-label { font-size: 15px; font-weight: 700; color: #101828; margin-bottom: 10px; }
.po-help-card {
    display: flex; align-items: center; gap: 14px;
    background: #fff; border: 1px solid #eef0f3; border-radius: 14px; padding: 16px;
    text-decoration: none;
}
.po-help-icon { width: 42px; height: 42px; border-radius: 50%; background: #e3ecfd; color: #2f5ede; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.po-help-title { font-size: 14px; font-weight: 700; color: #101828; }
.po-help-sub { font-size: 12px; color: #98a2b3; }
.po-help-chevron { margin-left: auto; color: #98a2b3; }

/* ===== Invoice drill-down ===== */
.po-invoice-card { background: #fff; border: 1px solid #eef0f3; border-radius: 14px; padding: 16px; }
.po-invoice-header { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.po-invoice-back { color: #101828; cursor: pointer; display: flex; }
.po-invoice-title { font-size: 16px; font-weight: 800; color: #101828; flex: 1; }
.po-invoice-filter-btn { color: #667085; cursor: pointer; }

.po-invoice-summary-row {
    display: flex; justify-content: space-between; align-items: center;
    border-radius: 10px; padding: 12px 14px; margin-bottom: 14px;
}
.po-invoice-summary-row.overdue { background: #fdecea; }
.po-invoice-summary-row.not-overdue { background: #e3f8ec; }
.po-invoice-summary-count { font-weight: 700; }
.po-invoice-summary-count.overdue { color: #e0442e; }
.po-invoice-summary-count.not-overdue { color: #1d9e75; }
.po-invoice-summary-total { text-align: right; }
.po-invoice-summary-total-val { font-size: 15px; font-weight: 800; }
.po-invoice-summary-total-val.overdue { color: #e0442e; }
.po-invoice-summary-total-val.not-overdue { color: #1d9e75; }
.po-invoice-summary-total-label { font-size: 10.5px; color: #667085; }

.po-invoice-table-wrap { overflow-x: auto; border: 1px solid #eef0f3; border-radius: 10px; }
.po-invoice-table { width: 100%; border-collapse: collapse; font-size: 11.5px; white-space: nowrap; }
.po-invoice-table th {
    background: #f8f5ff; color: #7c4fd6; font-weight: 700; text-align: left;
    padding: 10px 12px; border-bottom: 1px solid #eef0f3;
}
.po-invoice-table td { padding: 10px 12px; border-bottom: 1px solid #f2f3f6; color: #101828; }
.po-invoice-status { font-weight: 700; }
.po-invoice-status.overdue-text { color: #e0442e; }
.po-invoice-status.due-text { color: #1d9e75; }

.po-loading { text-align: center; padding: 30px 0; color: #98a2b3; font-size: 13px; }

@media (min-width: 768px) {
    .po-page { padding: 32px 24px; }
    .po-container { background: #fff; border-radius: 20px; padding: 28px 32px; box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04); }
}
</style>

<div class="po-page">
    <div class="po-container">

        <div class="po-title">Payments & Outstanding</div>
        <div class="po-subtitle">Track your outstanding payments and invoices.</div>

        <!-- ===== Tab Toggle ===== -->
        <div class="po-tabs">
            <div class="po-tab active overdue" data-tab="overdue">Overdue</div>
            <div class="po-tab not-overdue" data-tab="not_overdue">Billed (Not Overdue)</div>
        </div>

        <!-- ===== OVERDUE TAB ===== -->
        <div id="tabContent-overdue">

            <div class="po-summary-card overdue">
                <div>
                    <div class="po-summary-label">Total Overdue Amount</div>
                    <div class="po-summary-value overdue">₹{{ number_format($totalOverdueAmount, 0) }}</div>
                </div>
                <div>
                    <div class="po-summary-label">Overdue Since</div>
                    <div class="po-summary-days overdue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $maxDaysOverdue }} Days
                    </div>
                </div>
            </div>

            <div class="po-section-label">Overdue by Month</div>

            @forelse($overdueGrouped as $group)
                <div class="po-month-card overdue">
                    <div class="po-month-top">
                        <div class="po-month-left">
                            <div class="po-month-icon overdue">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <div>
                                <div class="po-month-name-row">
                                    <span class="po-month-name">{{ $group['month_label'] }}</span>
                                    <span class="po-month-badge overdue">Overdue</span>
                                </div>
                                <div class="po-month-invoice-count">{{ $group['count'] }} Invoices</div>
                            </div>
                        </div>
                        <div class="po-month-amount-wrap">
                            <div class="po-month-amount-label">Overdue Amount</div>
                            <div class="po-month-amount overdue">₹{{ number_format($group['amount'], 0) }}</div>
                        </div>
                    </div>

                    <div class="po-month-due-row">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Due Since {{ $group['max_days_overdue'] }} Days
                    </div>

                    <div class="po-month-actions">
                        <button type="button" class="po-btn-view-invoices" data-month="{{ $group['month_key'] }}" data-type="overdue">
                            View Invoices
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        <button type="button" class="po-btn-pay-now overdue" data-amount="{{ $group['amount'] }}" data-month="{{ $group['month_key'] }}">
                            Pay Now ₹{{ number_format($group['amount'], 0) }}
                        </button>
                    </div>
                </div>
            @empty
                <div style="padding:20px; text-align:center; color:#98a2b3; font-size:13px;">No overdue invoices. You're all caught up! 🎉</div>
            @endforelse

            @if(count($overdueGrouped) > 0)
            <div class="po-info-banner overdue">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Please clear your overdue payments at the earliest to avoid any service interruption.
            </div>
            @endif
        </div>

        <!-- ===== BILLED (NOT OVERDUE) TAB ===== -->
        <div id="tabContent-not_overdue" style="display:none;">

            <div class="po-summary-card not-overdue">
                <div>
                    <div class="po-summary-label">Total Outstanding</div>
                    <div class="po-summary-value not-overdue">₹{{ number_format($totalOutstandingAmount, 0) }}</div>
                </div>
                <div>
                    <div class="po-summary-label">Due Within</div>
                    <div class="po-summary-days not-overdue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $minDaysUntilDue }} Days
                    </div>
                </div>
            </div>

            <div class="po-section-label">Billed (Not Overdue) by Month</div>

            @forelse($notOverdueGrouped as $group)
                <div class="po-month-card not-overdue">
                    <div class="po-month-top">
                        <div class="po-month-left">
                            <div class="po-month-icon not-overdue">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <div>
                                <div class="po-month-name">{{ $group['month_label'] }}</div>
                                <div class="po-month-invoice-count">{{ $group['count'] }} Invoices</div>
                            </div>
                        </div>
                        <div class="po-month-amount-wrap">
                            <div class="po-month-amount-label">Outstanding Amount</div>
                            <div class="po-month-amount not-overdue">₹{{ number_format($group['amount'], 0) }}</div>
                        </div>
                    </div>

                    <div class="po-month-due-row">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Due Date: {{ $group['due_date'] }}
                    </div>

                    <div class="po-month-actions">
                        <button type="button" class="po-btn-view-invoices" data-month="{{ $group['month_key'] }}" data-type="not_overdue">
                            View Invoices
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        <button type="button" class="po-btn-pay-now not-overdue" data-amount="{{ $group['amount'] }}" data-month="{{ $group['month_key'] }}">
                            Pay Now ₹{{ number_format($group['amount'], 0) }}
                        </button>
                    </div>
                </div>
            @empty
                <div style="padding:20px; text-align:center; color:#98a2b3; font-size:13px;">No upcoming billed invoices.</div>
            @endforelse

            @if(count($notOverdueGrouped) > 0)
            <div class="po-info-banner not-overdue">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                These payments are not overdue. Please make payment before the due date.
            </div>
            @endif
        </div>

        <!-- ===== Need Help ===== -->
        <div class="po-help-label">Need Help?</div>
        <a href="#" class="po-help-card">
            <div class="po-help-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
            </div>
            <div>
                <div class="po-help-title">Contact our support team</div>
                <div class="po-help-sub">We're here to help you</div>
            </div>
            <svg class="po-help-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

        <!-- ===== Invoice Drill-down ===== -->
        <div class="po-invoice-card" id="invoiceDetailCard" style="display:none; margin-top:20px;">
            <div class="po-invoice-header">
                <span class="po-invoice-back" id="closeInvoiceDetail">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </span>
                <span class="po-invoice-title" id="invoiceDetailTitle">Invoices</span>
            </div>

            <div class="po-invoice-summary-row" id="invoiceSummaryRow">
                <div class="po-invoice-summary-count" id="invoiceSummaryCount">0 Invoices</div>
                <div class="po-invoice-summary-total">
                    <div class="po-invoice-summary-total-val" id="invoiceSummaryTotal">₹0</div>
                    <div class="po-invoice-summary-total-label" id="invoiceSummaryLabel">Total</div>
                </div>
            </div>

            <div id="invoiceLoading" class="po-loading">Loading invoices...</div>

            <div class="po-invoice-table-wrap" id="invoiceTableWrap" style="display:none;">
                <table class="po-invoice-table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Invoice Date</th>
                            <th>Delivered Date</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceTableBody"></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    // ===== Tab toggle =====
    $('.po-tab').on('click', function () {
        const tab = $(this).data('tab');
        $('.po-tab').removeClass('active');
        $(this).addClass('active');
        $('#tabContent-overdue, #tabContent-not_overdue').hide();
        $('#tabContent-' + tab).show();
        $('#invoiceDetailCard').hide();
    });

    // ===== View Invoices — real drill-down =====
    $(document).on('click', '.po-btn-view-invoices', function () {
        const monthKey = $(this).data('month');
        const type = $(this).data('type');
        const isOverdue = type === 'overdue';

        $('#invoiceDetailCard').show();
        $('#invoiceLoading').show();
        $('#invoiceTableWrap').hide();

        $.get('{{ route('web.payments-outstanding.month-invoices') }}', { month: monthKey, type: type }, function (res) {
            $('#invoiceLoading').hide();
            $('#invoiceTableWrap').show();

            $('#invoiceDetailTitle').text(res.month_label + ' Invoices');
            $('#invoiceSummaryCount').text(res.count + ' Invoices');
            $('#invoiceSummaryTotal').text('₹' + Number(res.total_amount).toLocaleString('en-IN', {maximumFractionDigits: 0}));
            $('#invoiceSummaryLabel').text(isOverdue ? 'Total Overdue' : 'Total Outstanding');

            $('#invoiceSummaryRow').removeClass('overdue not-overdue').addClass(isOverdue ? 'overdue' : 'not-overdue');
            $('#invoiceSummaryCount').removeClass('overdue not-overdue').addClass(isOverdue ? 'overdue' : 'not-overdue');
            $('#invoiceSummaryTotal').removeClass('overdue not-overdue').addClass(isOverdue ? 'overdue' : 'not-overdue');

            const $body = $('#invoiceTableBody');
            $body.empty();

            res.rows.forEach(function (row) {
                const statusClass = isOverdue ? 'overdue-text' : 'due-text';
                $body.append(`
                    <tr>
                        <td>${row.invoice_no}</td>
                        <td>${row.invoice_date}</td>
                        <td>${row.delivered_date}</td>
                        <td>${row.due_date}</td>
                        <td>₹${Number(row.amount).toLocaleString('en-IN', {maximumFractionDigits: 0})}</td>
                        <td><span class="po-invoice-status ${statusClass}">${row.status_label}</span></td>
                    </tr>
                `);
            });
        }).fail(function () {
            $('#invoiceLoading').text('Could not load invoices.');
        });

        $('html, body').animate({ scrollTop: $('#invoiceDetailCard').offset().top - 20 }, 300);
    });

    $('#closeInvoiceDetail').on('click', function () {
        $('#invoiceDetailCard').hide();
    });

    // ===== Pay Now — needs a bulk-payment endpoint (see note below) =====
    $(document).on('click', '.po-btn-pay-now', function () {
        Swal.fire({
            icon: 'info',
            title: 'Bulk Payment',
            text: 'This will initiate payment for all invoices in this month. Bulk-payment checkout is not yet wired up — see the note in the response for what is needed.',
            confirmButtonColor: '#4f5fff'
        });
    });

});
</script>
@endsection
