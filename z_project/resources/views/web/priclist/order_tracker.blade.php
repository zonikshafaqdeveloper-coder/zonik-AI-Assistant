@extends('mobile.mobile-app')
@section('content')

<style>
.ot-page {
    background: #f7f8fa;
    padding: 16px 16px 24px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.ot-container { max-width: 720px; margin: 0 auto; }

.ot-title { font-size: 26px; font-weight: 800; color: #101828; margin: 0 0 16px; }

/* ===== Pill Tabs ===== */
.ot-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}
.ot-tab {
    flex: 1;
    text-align: center;
    padding: 12px 8px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 700;
    color: #667085;
    background: #fff;
    border: 1px solid #eef0f3;
    cursor: pointer;
    white-space: nowrap;
}
.ot-tab.active { background: #2f5ede; color: #fff; border-color: #2f5ede; }

@media (max-width: 380px) {
    .ot-tab { font-size: 11.5px; padding: 10px 4px; }
}

/* ===== Status Summary (single card, 5 columns) ===== */
.ot-status-card {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 18px 8px;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    margin-bottom: 16px;
}
.ot-status-col {
    text-align: center;
    border-right: 1px solid #f2f3f6;
    padding: 0 4px;
    cursor: pointer;
}
.ot-status-col:last-child { border-right: none; }
.ot-status-col.active { background: #f7f9ff; border-radius: 10px; }
.ot-status-icon { margin-bottom: 8px; }
.ot-status-icon svg { width: 22px; height: 22px; }
.ot-status-label { font-size: 10.5px; font-weight: 600; color: #344054; margin-bottom: 6px; }
.ot-status-count { font-size: 17px; font-weight: 800; }

@media (max-width: 400px) {
    .ot-status-icon svg { width: 18px; height: 18px; }
    .ot-status-label { font-size: 9px; }
    .ot-status-count { font-size: 14px; }
}

/* ===== Action row: Filter trigger + Sort ===== */
.ot-action-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 16px;
}
.ot-filter-trigger {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 10px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 700;
    color: #344054;
    cursor: pointer;
    position: relative;
}
.ot-filter-trigger svg { width: 16px; height: 16px; color: #667085; }
.ot-filter-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #e0442e;
    position: absolute;
    top: 6px; right: 6px;
    display: none;
}
.ot-sort-wrap { position: relative; }
.ot-sort {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; color: #344054; cursor: pointer; white-space: nowrap;
    background: #fff; border: 1px solid #eef0f3; border-radius: 10px; padding: 10px 14px;
}
.ot-sort strong { color: #2f5ede; font-weight: 700; }

/* ===== Order Card (compact, tap to expand) ===== */
.ot-order-card {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 14px;
    cursor: pointer;
}
.ot-order-top-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ot-order-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ot-order-mid { flex: 1; min-width: 0; }
.ot-order-id-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 3px; }
.ot-order-id { font-size: 15.5px; font-weight: 800; color: #101828; }
.ot-status-badge {
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px; white-space: nowrap;
}
.ot-order-outlet { font-size: 12.5px; color: #667085; }
.ot-order-chevron { color: #98a2b3; flex-shrink: 0; transition: transform .2s; }
.ot-order-card.expanded .ot-order-chevron { transform: rotate(90deg); }

.ot-order-divider { height: 1px; background: #f2f3f6; margin: 12px 0; }
.ot-order-meta-row {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 8px 14px;
    font-size: 12px; color: #667085;
}
.ot-order-meta-row span { display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; }
.ot-order-meta-amount { font-weight: 800; color: #101828; font-size: 13px; margin-left: auto; }

/* ===== Expanded detail section ===== */
.ot-order-expanded {
    display: none;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px dashed #eef0f3;
}
.ot-order-card.expanded .ot-order-expanded { display: block; }

.ot-stepper {
    display: flex; align-items: flex-start; justify-content: space-between;
    position: relative; margin-bottom: 16px;
}
.ot-stepper::before {
    content: ''; position: absolute; top: 13px; left: 13px; right: 13px;
    height: 2px; background: #e4e7ec; z-index: 0;
}
.ot-stepper-line-fill { position: absolute; top: 13px; left: 13px; height: 2px; background: #1d9e75; z-index: 0; }
.ot-step { display: flex; flex-direction: column; align-items: center; gap: 6px; position: relative; z-index: 1; flex: 1; }
.ot-step-dot {
    width: 26px; height: 26px; border-radius: 50%; background: #fff; border: 2px solid #d0d5dd;
    display: flex; align-items: center; justify-content: center; color: #d0d5dd;
}
.ot-step.done .ot-step-dot { border-color: #1d9e75; background: #1d9e75; color: #fff; }
.ot-step.current .ot-step-dot { border-color: #e0442e; color: #e0442e; background: #fff; }
.ot-step-label { font-size: 9.5px; font-weight: 600; color: #98a2b3; text-align: center; }
.ot-step.done .ot-step-label, .ot-step.current .ot-step-label { color: #344054; }

.ot-cancelled-badge {
    background: #fdecea; color: #a4161a; font-size: 11px; font-weight: 700;
    padding: 5px 12px; border-radius: 100px; display: inline-block; margin-bottom: 12px;
}

.ot-expanded-actions { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
.ot-expanded-link { color: #4f5fff; font-weight: 600; font-size: 12.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.ot-cancel-icon { color: #e0442e; cursor: pointer; font-size: 14px; }
.ot-pay-now-btn {
    background: #2f3fae; color: #fff; border: none; border-radius: 8px;
    padding: 8px 16px; font-size: 12.5px; font-weight: 700; cursor: pointer; white-space: nowrap;
}
.ot-payment-badge {
    font-size: 10.5px; font-weight: 700; padding: 3px 9px; border-radius: 100px; display: inline-block;
}

.ot-order-items-table { margin-top: 12px; border: 1px solid #eef0f3; border-radius: 10px; overflow: hidden; }
.ot-order-items-table table { width: 100%; border-collapse: collapse; font-size: 12px; }
.ot-order-items-table th { background: #f7f8fa; color: #344054; font-weight: 700; text-align: left; padding: 8px 10px; border-bottom: 1px solid #eef0f3; }
.ot-order-items-table td { padding: 8px 10px; border-bottom: 1px solid #f2f3f6; color: #344054; }
.ot-order-items-table tr:last-child td { border-bottom: none; }

.ot-empty-state {
    background: #fff; border: 1px dashed #eef0f3; border-radius: 14px;
    padding: 40px 20px; text-align: center; color: #98a2b3; font-size: 13.5px;
}

@media (min-width: 768px) {
    .ot-page { padding: 32px 24px; }
    .ot-container { background: #fff; border-radius: 20px; padding: 28px 32px; box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04); }
}

/* ===== Filter Modal (bottom sheet) ===== */
.ot-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(16,24,40,0.45);
    z-index: 1000;
    align-items: flex-end;
    justify-content: center;
}
.ot-modal-overlay.open { display: flex; }
.ot-modal {
    background: #fff;
    width: 100%;
    max-width: 480px;
    max-height: 85vh;
    border-radius: 18px 18px 0 0;
    display: flex;
    flex-direction: column;
    animation: ot-modal-slide-up .22s ease-out;
}
@media (min-width: 768px) {
    .ot-modal-overlay { align-items: center; }
    .ot-modal { border-radius: 18px; max-height: 90vh; }
}
@keyframes ot-modal-slide-up {
    from { transform: translateY(24px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.ot-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px;
    border-bottom: 1px solid #f2f3f6;
    flex-shrink: 0;
}
.ot-modal-title { font-size: 16px; font-weight: 800; color: #101828; }
.ot-modal-close {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: #f7f8fa; color: #667085; cursor: pointer;
    font-size: 18px; line-height: 1;
}
.ot-modal-body {
    padding: 18px 20px;
    overflow-y: auto;
    flex: 1;
}
.ot-modal-field {
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #eef0f3;
    border-radius: 10px;
    padding: 10px 12px;
    margin-bottom: 12px;
}
.ot-modal-field svg { width: 17px; height: 17px; color: #667085; flex-shrink: 0; }
.ot-modal-field-text { flex: 1; min-width: 0; }
.ot-modal-field-label { font-size: 10.5px; color: #98a2b3; margin-bottom: 2px; }
.ot-modal-field select,
.ot-modal-field input {
    border: none; outline: none; background: transparent;
    font-size: 13.5px; font-weight: 700; color: #101828;
    width: 100%; padding: 0;
}
.ot-modal-field select { -webkit-appearance: none; appearance: none; }
.ot-modal-footer {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 20px;
    border-top: 1px solid #f2f3f6;
    flex-shrink: 0;
}
.ot-btn-apply {
    flex: 1;
    background: #e2571f; color: #fff; border: none; border-radius: 10px;
    padding: 12px 20px; font-size: 13.5px; font-weight: 700; cursor: pointer;
}
.ot-btn-reset {
    background: #fff; color: #344054; border: 1px solid #e4e7ec; border-radius: 10px;
    padding: 12px 20px; font-size: 13.5px; font-weight: 700; cursor: pointer;
}
</style>

@php
    $statusSteps = ['in_review', 'in_progress', 'ready_dispatch', 'dispatched', 'delivered'];
    $statusLabels = [
        'in_review'      => 'In Review',
        'in_progress'    => 'In Progress',
        'ready_dispatch' => 'Ready to Dispatch',
        'dispatched'     => 'Dispatched',
        'delivered'      => 'Delivered',
    ];
    $statusColors = [
        'in_review'      => ['bg' => '#eef2ff', 'fg' => '#2f5ede'],
        'in_progress'    => ['bg' => '#fdecea', 'fg' => '#e0442e'],
        'ready_dispatch' => ['bg' => '#fff3e0', 'fg' => '#d97706'],
        'dispatched'     => ['bg' => '#eef2ff', 'fg' => '#2f5ede'],
        'delivered'      => ['bg' => '#e8f8f0', 'fg' => '#1d9e75'],
    ];
@endphp

<div class="ot-page">
    <div class="ot-container">

        <div class="ot-title">Your Orders</div>

        <!-- ===== Pill Tabs ===== -->
        <div class="ot-tabs">
            <div class="ot-tab active" data-tab="live">Live Orders ({{ count($liveOrders) }})</div>
            <div class="ot-tab" data-tab="history">Order History ({{ count($historyOrders) }})</div>
            <div class="ot-tab" data-tab="cancelled">Cancelled ({{ count($cancelledOrders) }})</div>
        </div>

        <!-- ===== Status Summary (single card) ===== -->
        <div class="ot-status-card">
            <div class="ot-status-col ot-status-clickable" data-status="in_review">
                <div class="ot-status-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#2f5ede" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><circle cx="11.5" cy="14.5" r="2.5"/></svg></div>
                <div class="ot-status-label">Review</div>
                <div class="ot-status-count" style="color:#2f5ede;">{{ $statusCounts['in_review'] }}</div>
            </div>
            <div class="ot-status-col ot-status-clickable" data-status="in_progress">
                <div class="ot-status-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#e0442e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></div>
                <div class="ot-status-label">In Progress</div>
                <div class="ot-status-count" style="color:#101828;">{{ $statusCounts['in_progress'] }}</div>
            </div>
            <div class="ot-status-col ot-status-clickable" data-status="dispatched">
                <div class="ot-status-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                <div class="ot-status-label">Ready</div>
                <div class="ot-status-count" style="color:#d97706;">{{ $statusCounts['ready_dispatch'] }}</div>
            </div>
            <div class="ot-status-col ot-status-clickable" data-status="dispatched">
                <div class="ot-status-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#2f5ede" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                <div class="ot-status-label">Dispatched</div>
                <div class="ot-status-count" style="color:#2f5ede;">{{ $statusCounts['dispatched'] }}</div>
            </div>
            <div class="ot-status-col ot-status-clickable" data-status="delivered">
                <div class="ot-status-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#1d9e75" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg></div>
                <div class="ot-status-label">Delivered</div>
                <div class="ot-status-count" style="color:#1d9e75;">{{ $statusCounts['delivered'] }}</div>
            </div>
        </div>

        <!-- ===== Action Row: Filter trigger + Sort ===== -->
        <div class="ot-action-row">
            <div class="ot-filter-trigger" id="openFilterModal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filter
                <span class="ot-filter-dot" id="filterActiveDot"></span>
            </div>

            <div class="ot-sort-wrap">
                <div class="ot-sort" id="sortToggle">
                    Sort by: <strong id="sortLabel">Latest</strong>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div id="sortDropdown" style="display:none; position:absolute; top:calc(100% + 6px); right:0; background:#fff; border:1px solid #eef0f3; border-radius:10px; box-shadow:0 12px 32px rgba(16,24,40,0.14); z-index:150; min-width:180px; overflow:hidden;">
                    <div class="ot-sort-option" data-sort="latest" style="padding:10px 14px; font-size:12.5px; color:#344054; cursor:pointer; border-bottom:1px solid #f2f3f6;">Latest</div>
                    <div class="ot-sort-option" data-sort="oldest" style="padding:10px 14px; font-size:12.5px; color:#344054; cursor:pointer; border-bottom:1px solid #f2f3f6;">Oldest</div>
                    <div class="ot-sort-option" data-sort="amount_high" style="padding:10px 14px; font-size:12.5px; color:#344054; cursor:pointer; border-bottom:1px solid #f2f3f6;">Amount: High to Low</div>
                    <div class="ot-sort-option" data-sort="amount_low" style="padding:10px 14px; font-size:12.5px; color:#344054; cursor:pointer;">Amount: Low to High</div>
                </div>
            </div>
        </div>

        @php
            $sections = [
                'live'      => ['label' => 'Live Orders', 'orders' => $liveOrders],
                'history'   => ['label' => 'Order History', 'orders' => $historyOrders],
                'cancelled' => ['label' => 'Cancelled Orders', 'orders' => $cancelledOrders],
            ];
        @endphp

        @foreach($sections as $sectionKey => $section)
        <div id="tabContent-{{ $sectionKey }}" style="{{ $sectionKey === 'live' ? '' : 'display:none;' }}">

            @if(empty($section['orders']))
                <div class="ot-empty-state">No orders in this section.</div>
            @endif

            @foreach($section['orders'] as $order)
                @php
                    $isCancelled = $sectionKey === 'cancelled';
                    $isDelivered = ($order['status'] ?? '') === 'delivered';
                    $currentIndex = $isCancelled ? -1 : array_search($order['status'], $statusSteps);
                    $color = $statusColors[$order['status']] ?? ['bg' => '#f1f2f6', 'fg' => '#667085'];
                    $fillPercent = (!$isCancelled && $currentIndex > 0) ? ($currentIndex / (count($statusSteps) - 1)) * 100 : 0;
                @endphp

                <div class="ot-order-card"
                     data-order-card
                     data-status="{{ $order['status'] }}"
                     data-payment="{{ $order['payment_status'] === 'Paid' ? 'paid' : 'unpaid' }}"
                     data-date-raw="{{ \Carbon\Carbon::parse($order['date'])->timestamp }}"
                     data-date-str="{{ \Carbon\Carbon::parse($order['date'])->format('Y-m-d') }}"
                     data-amount="{{ $order['total'] }}">
                    <div class="ot-order-top-row" data-toggle-card>
                        <div class="ot-order-icon" style="background: {{ $color['bg'] }}; color: {{ $color['fg'] }};">
                            @if($isCancelled)
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            @elseif($order['status'] === 'in_review')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><circle cx="11.5" cy="14.5" r="2.5"/></svg>
                            @elseif($order['status'] === 'in_progress')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            @elseif($order['status'] === 'ready_dispatch')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            @elseif($order['status'] === 'dispatched')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
                            @endif
                        </div>

                        <div class="ot-order-mid">
                            <div class="ot-order-id-row">
                                <span class="ot-order-id">{{ $order['id'] }}</span>
                                <span class="ot-status-badge" style="background: {{ $color['bg'] }}; color: {{ $color['fg'] }};">
                                    {{ $isCancelled ? 'Cancelled' : $statusLabels[$order['status']] }}
                                </span>
                            </div>
                            <div class="ot-order-outlet">Outlet: {{ $order['outlet'] }}</div>
                        </div>

                        <svg class="ot-order-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>

                    <div class="ot-order-divider"></div>
                    <div class="ot-order-meta-row">
                        <span>📅 {{ \Carbon\Carbon::parse(str_replace(', ', ' ', $order['date']))->format('d M Y') ?? $order['date'] }}</span>
                        <span>🕐 {{ \Carbon\Carbon::parse($order['date'])->format('h:i A') }}</span>
                        <span>📦 {{ $order['items'] }} Items</span>
                        <span class="ot-order-meta-amount">₹{{ number_format($order['total'], 0) }}</span>
                    </div>

                    <!-- ===== Expanded detail (tap top row to toggle) ===== -->
                    <div class="ot-order-expanded">
                        @if($isCancelled)
                            <span class="ot-cancelled-badge">This order was cancelled</span>
                        @else
                            <div class="ot-stepper">
                                <div class="ot-stepper-line-fill" style="width: calc({{ $fillPercent }}% - {{ $fillPercent > 0 ? '26px' : '0px' }});"></div>
                                @foreach($statusSteps as $index => $step)
                                    @php
                                        $stepState = $index < $currentIndex ? 'done' : ($index === $currentIndex ? ($isDelivered ? 'done' : 'current') : '');
                                    @endphp
                                    <div class="ot-step {{ $stepState }}">
                                        <div class="ot-step-dot">
                                            @if($stepState === 'done')
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <div class="ot-step-label">{{ $statusLabels[$step] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="ot-expanded-actions">
                            <a href="#" class="ot-expanded-link" data-toggle-id="{{ $order['real_id'] }}">👁 View Order</a>

                            @if($order['can_invoice'] ?? false)
                                <a href="{{ route('generateInvoiceAndDeliveryCharges.list', ['id' => $order['real_id']]) }}"
                                   onclick="event.stopPropagation(); window.open(this.href,'_blank','width=800,height=600'); return false;"
                                   class="ot-expanded-link">View Invoice →</a>
                            @endif

                            @if($order['can_cancel'] ?? false)
                                <i class="fa-solid fa-circle-xmark ot-cancel-icon" title="Cancel your order" onclick="event.stopPropagation(); cancelOrder('{{ $order['real_id'] }}')"></i>
                            @endif

                            <span class="ot-payment-badge" style="background: {{ $order['payment_status'] === 'Paid' ? '#e8f8f0' : '#fff3e0' }}; color: {{ $order['payment_status'] === 'Paid' ? '#1d9e75' : '#b5650a' }};">
                                {{ $order['payment_status'] }}
                            </span>

                            @if($order['can_pay'] ?? false)
                                <button type="button" class="ot-pay-now-btn checkout_pay" data-order-id="{{ $order['real_id'] }}" data-amount="{{ $order['remaining'] ?? $order['total'] }}">
                                    Pay Now ₹{{ number_format($order['remaining'] ?? $order['total'], 2) }}
                                </button>
                            @endif
                        </div>

                        <div class="ot-order-items-table" id="orderDetails_{{ $order['real_id'] }}" style="display:none;">
                            <table>
                                <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
                                <tbody>
                                    @forelse($order['order_items'] ?? [] as $item)
                                        <tr>
                                            <td>{{ $item['product']['product_name'] ?? 'N/A' }}</td>
                                            <td>{{ $item['quantity'] ?? '-' }}</td>
                                            <td>₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3">No items found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endforeach

    </div>
</div>

<!-- ===== Filter Modal ===== -->
<div class="ot-modal-overlay" id="filterModalOverlay">
    <div class="ot-modal">
        <div class="ot-modal-header">
            <div class="ot-modal-title">Filter Orders</div>
            <div class="ot-modal-close" id="closeFilterModal">&times;</div>
        </div>

        <div class="ot-modal-body">
            <div class="ot-modal-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v10h16V9"/><path d="M9 21v-6h6v6"/></svg>
                <div class="ot-modal-field-text">
                    <div class="ot-modal-field-label">Outlet</div>
                    <select id="filterOutlet">
                        <option value="">All Outlets</option>
                        @foreach($outlets ?? [] as $o)
                            <option value="{{ $o->id }}" @if($o->id == ($filterOutlet ?? '')) selected @endif>{{ $o->outlet_name ?? $o->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="ot-modal-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <div class="ot-modal-field-text">
                    <div class="ot-modal-field-label">Order ID</div>
                    <input type="text" id="filterOrderId" placeholder="Search ID" value="{{ $filterOrderId ?? '' }}">
                </div>
            </div>

            <div class="ot-modal-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <div class="ot-modal-field-text">
                    <div class="ot-modal-field-label">Date Range</div>
                    <input type="date" id="filterDateRange" value="{{ $filterDate ?? '' }}">
                </div>
            </div>

            <div class="ot-modal-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <div class="ot-modal-field-text">
                    <div class="ot-modal-field-label">Status</div>
                    <select id="filterStatus">
                        <option value="">All Statuses</option>
                        <option value="in_review">In Review</option>
                        <option value="in_progress">In Progress</option>
                        <option value="ready_dispatch">Ready to Dispatch</option>
                        <option value="dispatched">Dispatched</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>
            </div>

            <div class="ot-modal-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <div class="ot-modal-field-text">
                    <div class="ot-modal-field-label">Payment</div>
                    <select id="filterPayment">
                        <option value="">All</option>
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="ot-modal-footer">
            <button type="button" class="ot-btn-reset" id="resetFiltersBtn">Reset</button>
            <button type="button" class="ot-btn-apply" id="applyFiltersBtn">Apply Filters</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
$(document).ready(function () {

    // ===== Pill tabs =====
    $('.ot-tab').on('click', function () {
        const tab = $(this).data('tab');
        $('.ot-tab').removeClass('active');
        $(this).addClass('active');
        $('#tabContent-live, #tabContent-history, #tabContent-cancelled').hide();
        $('#tabContent-' + tab).show();
    });

    // ===== Tap order card top row to expand/collapse =====
    $(document).on('click', '[data-toggle-card]', function () {
        $(this).closest('.ot-order-card').toggleClass('expanded');
    });

    // ===== View Order — toggle item table (stop propagation so it
    // doesn't also collapse the card) =====
    $(document).on('click', '[data-toggle-id]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const id = $(this).data('toggle-id');
        $('#orderDetails_' + id).slideToggle();
    });

    // ===== Status summary — click to filter Live Orders list =====
    $('.ot-status-clickable').on('click', function () {
        const $col = $(this);
        const status = $col.data('status');
        const wasActive = $col.hasClass('active');

        $('.ot-status-clickable').removeClass('active');

        $('.ot-tab').removeClass('active');
        $('.ot-tab[data-tab="live"]').addClass('active');
        $('#tabContent-live, #tabContent-history, #tabContent-cancelled').hide();
        $('#tabContent-live').show();

        if (wasActive) {
            $('#tabContent-live .ot-order-card').show();
            return;
        }

        $col.addClass('active');

        $('#tabContent-live .ot-order-card').each(function () {
            const badge = $(this).find('.ot-status-badge').text().trim();
            const matches = (status === 'in_review' && badge === 'In Review')
                || (status === 'in_progress' && badge === 'In Progress')
                || (status === 'dispatched' && (badge === 'Ready to Dispatch' || badge === 'Dispatched'))
                || (status === 'delivered' && badge === 'Delivered');
            $(this).toggle(matches);
        });
    });

    // ===== Filter modal open/close =====
    function openFilterModal() {
        $('#filterModalOverlay').addClass('open');
        $('body').css('overflow', 'hidden');
    }
    function closeFilterModal() {
        $('#filterModalOverlay').removeClass('open');
        $('body').css('overflow', '');
    }

    $('#openFilterModal').on('click', openFilterModal);
    $('#closeFilterModal').on('click', closeFilterModal);

    // close when tapping outside the sheet
    $('#filterModalOverlay').on('click', function (e) {
        if (e.target === this) closeFilterModal();
    });

    // ===== Sort dropdown =====
    $('#sortToggle').on('click', function (e) {
        e.stopPropagation();
        $('#sortDropdown').toggle();
    });
    $(document).on('click', function () {
        $('#sortDropdown').hide();
    });
    $('#sortDropdown').on('click', function (e) { e.stopPropagation(); });

    $('.ot-sort-option').on('click', function () {
        const sortType = $(this).data('sort');
        $('#sortLabel').text($(this).text());
        $('#sortDropdown').hide();
        applySort(sortType);
    });

    function applySort(sortType) {
        $('#tabContent-live, #tabContent-history, #tabContent-cancelled').each(function () {
            const $container = $(this);
            const $cards = $container.find('.ot-order-card').get();

            $cards.sort(function (a, b) {
                const dateA = parseInt($(a).data('date-raw')) || 0;
                const dateB = parseInt($(b).data('date-raw')) || 0;
                const amtA = parseFloat($(a).data('amount')) || 0;
                const amtB = parseFloat($(b).data('amount')) || 0;

                switch (sortType) {
                    case 'oldest': return dateA - dateB;
                    case 'amount_high': return amtB - amtA;
                    case 'amount_low': return amtA - amtB;
                    case 'latest':
                    default: return dateB - dateA;
                }
            });

            $.each($cards, function (i, card) {
                $container.append(card);
            });
        });
    }

    // ===== Apply Filters (from modal) — combines Outlet, Order ID,
    // Date Range, Status, and Payment Status all together =====
    function updateFilterDot() {
        const anyActive = $('#filterOutlet').val() || $('#filterOrderId').val().trim()
            || $('#filterDateRange').val() || $('#filterStatus').val() || $('#filterPayment').val();
        $('#filterActiveDot').toggle(!!anyActive);
    }

    $('#applyFiltersBtn').on('click', function () {
        // Use the <option> VALUE to decide whether an outlet is actually
        // selected (value is '' for "All Outlets"). Using .text() here was
        // always truthy ("All Outlets"), which made the outlet check run
        // even with nothing selected and hide every card — masking the
        // other filters entirely.
        const outletId = $('#filterOutlet').val();
        const outletName = $('#filterOutlet option:selected').text().trim().toLowerCase();

        const orderIdVal = $('#filterOrderId').val().trim().toLowerCase();
        const dateVal = $('#filterDateRange').val();
        const statusVal = $('#filterStatus').val();
        const paymentVal = $('#filterPayment').val();

        $('.ot-order-card').each(function () {
            const $card = $(this);
            let visible = true;

            if (outletId && $card.find('.ot-order-outlet').text().toLowerCase().indexOf(outletName) === -1) {
                visible = false;
            }
            if (orderIdVal && $card.find('.ot-order-id').text().toLowerCase().indexOf(orderIdVal) === -1) {
                visible = false;
            }
            if (dateVal) {
                // Compare plain Y-m-d strings rendered server-side, so this
                // matches the date shown on the card regardless of the
                // browser's timezone (a UTC-converted timestamp compare was
                // off by a day for late/early orders).
                const cardDate = $card.data('date-str');
                if (cardDate !== dateVal) visible = false;
            }
            if (statusVal && $card.data('status') !== statusVal) {
                visible = false;
            }
            if (paymentVal && $card.data('payment') !== paymentVal) {
                visible = false;
            }

            $card.toggle(visible);
        });

        updateFilterDot();
        closeFilterModal();
    });

    $('#resetFiltersBtn').on('click', function () {
        $('#filterOutlet').val('');
        $('#filterOrderId').val('');
        $('#filterDateRange').val('');
        $('#filterStatus').val('');
        $('#filterPayment').val('');
        $('.ot-order-card').show();
        updateFilterDot();
        closeFilterModal();
    });

    // ===== Cancel order =====
    window.cancelOrder = function (orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f5fff',
            cancelButtonColor: '#e0442e',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/cancel-order/' + orderId,
                    type: 'POST',
                    data: { '_token': '{{ csrf_token() }}' },
                    success: function () {
                        Swal.fire('Cancelled!', 'Your order has been cancelled.', 'success')
                            .then(() => { window.location.reload(); });
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    };

    // ===== Pay Now — Razorpay flow =====
    $(document).on('click', '.checkout_pay', function () {
        const orderId = $(this).data('order-id');
        const totalDiscountValue = $(this).data('amount');

        fetch('/updatepay-order', {
            method: 'POST',
            body: JSON.stringify({ order_id: orderId, payment_status: 'paid', totalDiscountValue: totalDiscountValue }),
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(responseData => {
            if (!responseData.order_id || !responseData.amount) {
                Swal.fire({ title: 'Error', text: 'Invalid payment details. Please try again.', icon: 'error' });
                return;
            }
            const options = {
                key: responseData.razorpay_key,
                amount: responseData.amount,
                currency: 'INR',
                name: 'Zonik',
                description: 'Order Payment',
                order_id: responseData.order_id,
                callback_url: '/handle-payment-update',
                theme: { color: '#4f5fff' }
            };
            if (typeof Razorpay !== 'undefined') {
                new Razorpay(options).open();
            } else {
                Swal.fire({ title: 'Error', text: 'Payment gateway failed to load.', icon: 'error' });
            }
        })
        .catch(() => {
            Swal.fire({ title: 'Error', text: 'An error occurred. Please try again.', icon: 'error' });
        });
    });

});
</script>
@endsection
