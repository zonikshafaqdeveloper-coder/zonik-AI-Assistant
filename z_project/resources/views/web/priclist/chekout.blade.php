@extends('mobile.mobile-app')
@section('content')

<style>
.co-page {
    background: #f7f8fa;
    padding: 16px 16px 24px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.co-container { max-width: 640px; margin: 0 auto; }

.co-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 16px;
    border-bottom: 1px solid #eef0f3;
    margin-bottom: 16px;
}
.co-top-left { display: flex; align-items: center; gap: 14px; }
.co-back { color: #101828; display: flex; align-items: center; text-decoration: none; }
.co-title { font-size: 22px; font-weight: 800; color: #101828; margin: 0; }
.co-secure { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #667085; white-space: nowrap; }

.co-card { background: #fff; border: 1px solid #eef0f3; border-radius: 14px; padding: 16px; margin-bottom: 14px; }
.co-card-label { font-size: 13.5px; font-weight: 700; color: #101828; margin-bottom: 12px; }

/* ===== Delivery date dropdown (custom, matches desktop's slot logic) ===== */
.co-delivery-select {
    display: flex; align-items: center; justify-content: space-between;
    border: 1.5px solid #e4e7ec; border-radius: 10px; padding: 12px 14px; cursor: pointer;
    position: relative;
}
.co-delivery-select-left { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #101828; }
.co-delivery-select svg.truck { color: #e2571f; }
.co-delivery-select svg.chevron { color: #667085; transition: transform .15s; }
.co-delivery-select.open svg.chevron { transform: rotate(180deg); }
.co-delivery-options {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0; right: 0;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 10px;
    box-shadow: 0 12px 32px rgba(16,24,40,0.14);
    max-height: 260px;
    overflow-y: auto;
    z-index: 100;
}
.co-delivery-options.open { display: block; }
.co-delivery-option {
    padding: 12px 14px;
    font-size: 13.5px;
    color: #344054;
    cursor: pointer;
    border-bottom: 1px solid #f2f3f6;
}
.co-delivery-option:last-child { border-bottom: none; }
.co-delivery-option:hover { background: #fafbfe; }
.co-delivery-error { font-size: 12px; color: #e0442e; margin-top: 6px; display: none; }

/* ===== Delivery Slot Bottom Sheet ===== */
.co-slot-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,0.5);
    z-index: 998;
    opacity: 0; pointer-events: none;
    transition: opacity .25s ease;
}
.co-slot-overlay.open { opacity: 1; pointer-events: auto; }

.co-slot-sheet {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    max-width: 640px;
    margin: 0 auto;
    background: #fff;
    border-radius: 20px 20px 0 0;
    z-index: 999;
    transform: translateY(100%);
    transition: transform .3s cubic-bezier(.16,1,.3,1);
    max-height: 88vh;
    display: flex;
    flex-direction: column;
}
.co-slot-sheet.open { transform: translateY(0); }

.co-slot-handle {
    width: 40px; height: 4px;
    background: #d0d5dd;
    border-radius: 100px;
    margin: 10px auto 0;
    flex-shrink: 0;
}

.co-slot-header { padding: 16px 20px 12px; flex-shrink: 0; }
.co-slot-header-title { font-size: 18px; font-weight: 800; color: #101828; margin: 0 0 4px; }
.co-slot-header-sub { font-size: 13px; color: #667085; }

.co-slot-body { flex: 1; overflow-y: auto; padding: 4px 20px 20px; }

.co-slot-section-label {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 700; color: #1d9e75;
    margin: 16px 0 10px;
}
.co-slot-section-label.upcoming { color: #2f5ede; }

.co-slot-card {
    position: relative;
    display: flex; align-items: center; gap: 14px;
    border: 1.5px solid #eef0f3; border-radius: 14px;
    padding: 14px; margin-bottom: 10px; cursor: pointer;
}
.co-slot-card.selected { border-color: #1d9e75; background: #f3fbf7; }

.co-slot-badge {
    position: absolute; top: -10px; right: 14px;
    background: #1d9e75; color: #fff;
    font-size: 10px; font-weight: 700;
    padding: 3px 10px; border-radius: 100px;
}
.co-slot-icon {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.co-slot-icon.green { background: #e3f8ec; color: #1d9e75; }
.co-slot-icon.orange { background: #fdecd6; color: #e2711d; }
.co-slot-icon.blue { background: #e3ecfd; color: #2f5ede; }

.co-slot-info { flex: 1; min-width: 0; }
.co-slot-date { font-size: 15px; font-weight: 700; color: #101828; margin-bottom: 4px; }
.co-slot-tag {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 700;
    padding: 3px 9px; border-radius: 100px;
}
.co-slot-tag.green { background: #e3f8ec; color: #1d9e75; }
.co-slot-tag.orange { background: #fdecd6; color: #e2711d; }

.co-slot-radio {
    width: 24px; height: 24px; border-radius: 50%;
    border: 2px solid #d0d5dd;
    display: flex; align-items: center; justify-content: center;
    margin-left: auto; flex-shrink: 0;
}
.co-slot-card.selected .co-slot-radio { border-color: #1d9e75; background: #1d9e75; }
.co-slot-card.selected .co-slot-radio svg { display: block; }
.co-slot-radio svg { display: none; width: 14px; height: 14px; color: #fff; }

.co-slot-footer {
    border-top: 1px solid #eef0f3;
    padding: 14px 20px;
    display: flex; align-items: center; justify-content: space-between; gap: 14px;
    flex-shrink: 0; background: #fff;
}
.co-slot-footer-info { min-width: 0; flex: 1; overflow: hidden; }
.co-slot-footer-label { font-size: 11px; color: #98a2b3; margin-bottom: 2px; }
.co-slot-footer-value {
    font-size: 13.5px; font-weight: 700; color: #101828;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width: 100%;
}
.co-slot-footer-sub { font-size: 11px; color: #667085; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.co-slot-confirm-btn {
    background: #e2571f; color: #fff; border: none;
    border-radius: 10px; padding: 12px 24px;
    font-size: 14px; font-weight: 700; cursor: pointer; white-space: nowrap; flex-shrink: 0;
}
.co-slot-confirm-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.co-info-row { display: flex; gap: 12px; }
.co-info-icon { width: 42px; height: 42px; border-radius: 50%; background: #eef2ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.co-info-icon.location { background: #eef2ff; color: #4f5fff; }
.co-info-body { flex: 1; min-width: 0; }
.co-info-label { font-size: 11.5px; color: #98a2b3; margin-bottom: 2px; }
.co-info-name { font-size: 14.5px; font-weight: 700; color: #101828; margin-bottom: 4px; }
.co-info-address { font-size: 12.5px; color: #667085; line-height: 1.5; }
.co-info-edit { display: inline-flex; align-items: center; gap: 4px; font-size: 12.5px; font-weight: 600; color: #4f5fff; text-decoration: none; white-space: nowrap; flex-shrink: 0; }
.co-address-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }

.co-summary-head { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
.co-summary-head svg { color: #101828; }
.co-summary-title { font-size: 15px; font-weight: 700; color: #101828; }

.co-summary-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: 13.5px; }
.co-summary-row.subtotal { color: #e0442e; font-weight: 700; }
.co-summary-row.subtotal .co-summary-value { color: #101828; font-weight: 700; }
.co-summary-row .co-summary-label { color: #344054; }
.co-summary-row .co-summary-value { color: #101828; font-weight: 600; }
.co-summary-divider { height: 1px; background: #eef0f3; margin: 4px 0; }
.co-summary-row.grand-total { padding-top: 12px; font-size: 15px; }
.co-summary-row.grand-total .co-summary-label,
.co-summary-row.grand-total .co-summary-value { color: #e0442e; font-weight: 800; }

.co-pay-option { display: flex; align-items: center; gap: 12px; border: 1.5px solid #eef0f3; border-radius: 12px; padding: 14px; margin-bottom: 10px; cursor: pointer; }
.co-pay-option.selected { border-color: #4f5fff; background: #fafbff; }
.co-pay-option.disabled { opacity: 0.5; pointer-events: none; }
.co-pay-icon { width: 30px; height: 30px; flex-shrink: 0; color: #4f5fff; display: flex; align-items: center; justify-content: center; }
.co-pay-body { flex: 1; min-width: 0; }
.co-pay-title-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.co-pay-title { font-size: 14px; font-weight: 700; color: #101828; }
.co-pay-badge { background: #ffe1d1; color: #d85a30; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 100px; }
.co-pay-desc { font-size: 12px; color: #98a2b3; margin-top: 2px; }
.co-pay-amount { font-size: 14px; font-weight: 700; color: #4f5fff; white-space: nowrap; }
.co-pay-radio { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #d0d5dd; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.co-pay-option.selected .co-pay-radio { border-color: #4f5fff; }
.co-pay-radio-dot { width: 10px; height: 10px; border-radius: 50%; background: #4f5fff; display: none; }
.co-pay-option.selected .co-pay-radio-dot { display: block; }

.co-not-servicable { background: #fdecea; color: #a4161a; border-radius: 10px; padding: 12px 14px; font-size: 13px; margin-bottom: 14px; }

.co-security-note { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #667085; padding: 14px 4px; }
.co-security-note svg { color: #4f5fff; flex-shrink: 0; }

.co-pay-btn {
    width: 100%; background: #2f3fae; color: #fff; border: none; border-radius: 12px;
    padding: 16px; font-size: 15px; font-weight: 700; display: flex;
    align-items: center; justify-content: center; gap: 8px; cursor: pointer;
}
.co-pay-btn:disabled { opacity: 0.5; cursor: not-allowed; }

@media (min-width: 768px) {
    .co-page { padding: 32px 24px; }
    .co-container { background: #fff; border-radius: 20px; padding: 28px 32px; box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04); }
}
.oa-loading-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 0 4px;
}
.oa-pulse-ring {
    position: relative;
    width: 84px;
    height: 84px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}
.oa-pulse-ring::before,
.oa-pulse-ring::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: #4f5fff;
    opacity: 0.25;
    animation: oaPulse 1.6s ease-out infinite;
}
.oa-pulse-ring::after {
    animation-delay: 0.5s;
}
.oa-pulse-core {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #4f5fff;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    box-shadow: 0 8px 20px rgba(79,95,255,0.35);
}
.oa-pulse-core svg {
    width: 28px;
    height: 28px;
    animation: oaBagBounce 1.2s ease-in-out infinite;
}
@keyframes oaPulse {
    0% { transform: scale(0.7); opacity: 0.35; }
    100% { transform: scale(1.6); opacity: 0; }
}
@keyframes oaBagBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
}

.oa-title { font-size: 16px; font-weight: 800; color: #101828; margin: 0 0 6px; }
.oa-dots { display: inline-flex; gap: 4px; }
.oa-dots span {
    width: 6px; height: 6px; border-radius: 50%;
    background: #4f5fff;
    animation: oaDotBlink 1.4s ease-in-out infinite;
}
.oa-dots span:nth-child(2) { animation-delay: 0.2s; }
.oa-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes oaDotBlink {
    0%, 80%, 100% { opacity: 0.25; transform: scale(0.8); }
    40% { opacity: 1; transform: scale(1); }
}
.oa-subtext { font-size: 12.5px; color: #98a2b3; margin-top: 4px; }

/* ===== Success state ===== */
.oa-success-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 6px 0 4px;
}
.oa-success-circle {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: #e8f8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    animation: oaCirclePop 0.4s cubic-bezier(.34,1.56,.64,1);
}
.oa-success-circle svg {
    width: 38px;
    height: 38px;
}
.oa-success-circle .oa-check-path {
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: oaCheckDraw 0.5s 0.25s ease forwards;
}
@keyframes oaCirclePop {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}
@keyframes oaCheckDraw {
    to { stroke-dashoffset: 0; }
}
.oa-success-title { font-size: 18px; font-weight: 800; color: #101828; margin: 0 0 6px; }
.oa-success-sub { font-size: 13px; color: #667085; text-align: center; line-height: 1.5; }


</style>

@php
    $mobilePaymentMethod = 'online'; // default selection
    $isServicable = !session('not_servicable');

    $canPayOnDelivery = $zoneProcessingData
        && $totalDiscountValue <= $zoneProcessingData->order_above
        && $zoneProcessingData->pay_on_delivery == 'yes';

    $canPayOnCredit = $outletData->credit_status == 'Active'
        && ($totalDiscountValue + $totalDueAmount) <= $outletData->credit_limit;
@endphp

<div class="co-page">
    <div class="co-container">

        <!-- ===== Top bar ===== -->
        <div class="co-top">
            <div class="co-top-left">
                <a href="{{ url()->previous() }}" class="co-back" aria-label="Back">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </a>
                <h1 class="co-title">Checkout</h1>
            </div>
            <div class="co-secure">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Secure Checkout
            </div>
        </div>

        @if(session('not_servicable'))
            <div class="co-not-servicable">{{ session('not_servicable') }}</div>
        @endif

         <!-- ===== Delivery Slot Trigger Card (opens bottom sheet) ===== -->
        <div class="co-card">
            <div class="co-card-label">Select Delivery Option</div>
            <div class="co-delivery-select" id="openSlotSheetBtn">
                <div class="co-delivery-select-left">
                    <svg class="truck" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    <span id="deliveryOptionLabel">Select Delivery Slot</span>
                </div>
                <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="co-delivery-error" id="deliveryDateError">Please select a delivery date</div>
            <input type="hidden" id="delivery_date">
            <input type="hidden" id="delivery_time_slot">
        </div>

        <!-- ===== Delivery Slot Bottom Sheet ===== -->
        <div class="co-slot-overlay" id="slotOverlay"></div>
        <div class="co-slot-sheet" id="slotSheet">
            <div class="co-slot-handle"></div>

            <div class="co-slot-header">
                <h3 class="co-slot-header-title">Select Delivery Slot</h3>
                <div class="co-slot-header-sub">Choose when you want your order delivered</div>
            </div>

            <div class="co-slot-body" id="slotBody"></div>

            <div class="co-slot-footer">
                <div class="co-slot-footer-info">
                    <div class="co-slot-footer-label">Selected Slot</div>
                    <div class="co-slot-footer-value" id="footerSlotLabel">None selected</div>
                    <div class="co-slot-footer-sub" id="footerSlotSub"></div>
                </div>
                <button type="button" class="co-slot-confirm-btn" id="confirmSlotBtn" disabled>Confirm Slot</button>
            </div>
        </div>


        <!-- ===== Outlet (no Change Outlet — already inside checkout for this outlet) ===== -->
        <div class="co-card">
            <div class="co-info-row">
                <div class="co-info-icon">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#4f5fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v10h16V9"/><path d="M9 21v-6h6v6"/></svg>
                </div>
                <div class="co-info-body">
                    <div class="co-info-label">Outlet Name</div>
                    <div class="co-info-name">{{ $outletData->outlet_name ?? $outletData->name }}</div>
                </div>
            </div>
        </div>

        <!-- ===== Delivery Address ===== -->
        <div class="co-card">
            <div class="co-address-top">
                <div class="co-info-row" style="flex:1;">
                    <div class="co-info-icon location">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4f5fff" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div class="co-info-body">
                        <div class="co-info-label">Delivery Address</div>
                        <div class="co-info-name">{{ $outletData->name }}</div>
                        <div class="co-info-address">
                            {{ $shippingAddress }}<br>
                            Mobile: {{ $outletData->mobile_number }}
                        </div>
                    </div>
                </div>
                <a href="#" class="co-info-edit" data-bs-toggle="modal" data-bs-target="#shippingAddressModal">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
                    Change Address
                </a>
            </div>
        </div>

        <!-- ===== Order Summary — real values ===== -->
        <div class="co-card">
            <div class="co-summary-head">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                <div class="co-summary-title">Order Summary</div>
            </div>

            <div class="co-summary-row subtotal">
                <span class="co-summary-label">Subtotal (Basic)</span>
                <span class="co-summary-value">₹{{ number_format($subTotalAmt, 2) }}</span>
            </div>
            <div class="co-summary-divider"></div>

            <div class="co-summary-row">
                <span class="co-summary-label">Product Discounts</span>
                <span class="co-summary-value">- ₹{{ number_format($totalproductDiscount, 2) }}</span>
            </div>
            <div class="co-summary-row">
                <span class="co-summary-label">CGST + SGST</span>
                <span class="co-summary-value">+ ₹{{ number_format($result, 2) }}</span>
            </div>
            @if($packingcharges > 0)
            <div class="co-summary-row">
                <span class="co-summary-label">Packing Charges</span>
                <span class="co-summary-value">+ ₹{{ number_format($packingcharges, 2) }}</span>
            </div>
            @endif
            <div class="co-summary-row">
                <span class="co-summary-label">Coupon Discount</span>
                <span class="co-summary-value">- ₹{{ number_format($coupn->first()->coupon_discount ?? 0, 2) }}</span>
            </div>
            @if($otherscharges > 0)
            <div class="co-summary-row">
                <span class="co-summary-label">Others Charges</span>
                <span class="co-summary-value">+ ₹{{ number_format($otherscharges, 2) }}</span>
            </div>
            @endif
            <div class="co-summary-row">
                <span class="co-summary-label">Delivery Charges</span>
                <span class="co-summary-value">+ ₹{{ number_format($deliveryChargeApplied, 2) }}</span>
            </div>
            <div class="co-summary-divider"></div>

            <div class="co-summary-row grand-total">
                <span class="co-summary-label">Grand Total ({{ $totalProduct }} Items)</span>
                <span class="co-summary-value">₹{{ number_format($totalDiscountValue, 2) }}</span>
            </div>
        </div>
        
        @if($canPayOnCredit)
        <div class="co-card">
            <div class="co-card-label">Credit Details</div>

            <div class="co-summary-row">
                <span class="co-summary-label">Credit Limit</span>
                <span class="co-summary-value">₹{{ number_format($outletData->credit_limit, 2) }}</span>
            </div>
            <div class="co-summary-row">
                <span class="co-summary-label">Overdue Amount</span>
                <span class="co-summary-value" style="color:#e0442e;">₹{{ number_format($totalDueAmount, 2) }}</span>
            </div>
            <div class="co-summary-divider"></div>
            <div class="co-summary-row">
                <span class="co-summary-label">Available Credit</span>
                <span class="co-summary-value" style="color:#1d9e75; font-weight:700;">₹{{ number_format($outletData->credit_limit - $totalDueAmount, 2) }}</span>
            </div>
        </div>
        @endif

        <!-- ===== Payment Methods — visibility matches desktop's real conditions ===== -->
        @if($canPayOnDelivery)
        <div class="co-pay-option {{ !$isServicable ? 'disabled' : '' }}" data-method="cod">
            <div class="co-pay-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="14" height="12" rx="2"/><path d="M16 10h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4"/></svg>
            </div>
            <div class="co-pay-body">
                <div class="co-pay-title-row"><span class="co-pay-title">Pay on Delivery</span></div>
                <div class="co-pay-desc">Pay cash when your order is delivered.</div>
            </div>
            <div class="co-pay-radio"><div class="co-pay-radio-dot"></div></div>
        </div>
        @endif

        <div class="co-pay-option selected {{ !$isServicable ? 'disabled' : '' }}" data-method="online">
            <div class="co-pay-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
            <div class="co-pay-body">
                <div class="co-pay-title-row">
                    <span class="co-pay-title">Pay Now</span>
                    <span class="co-pay-badge">Recommended</span>
                </div>
                <div class="co-pay-desc">Complete payment now and confirm your order.</div>
            </div>
            <div class="co-pay-amount">₹{{ number_format($totalDiscountValue, 2) }}</div>
            <div class="co-pay-radio"><div class="co-pay-radio-dot"></div></div>
        </div>

        @if($canPayOnCredit)
        <div class="co-pay-option {{ !$isServicable ? 'disabled' : '' }}" data-method="credit">
            <div class="co-pay-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><circle cx="17" cy="18" r="3"/><path d="M17 17v1l.5.5"/></svg>
            </div>
            <div class="co-pay-body">
                <div class="co-pay-title-row"><span class="co-pay-title">Place Order on Credit</span></div>
                <div class="co-pay-desc">Place order now and pay within credit terms.</div>
            </div>
            <div class="co-pay-radio"><div class="co-pay-radio-dot"></div></div>
        </div>
        @endif

        <div class="co-security-note">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Your payment information is 100% secure with us.
        </div>

        <button type="button" class="co-pay-btn" id="payBtn" {{ !$isServicable ? 'disabled' : '' }}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span id="payBtnLabel">Pay Now ₹{{ number_format($totalDiscountValue, 2) }}</span>
        </button>

    </div>
</div>

<!-- ===== Shipping Address Modal ===== -->
<div class="modal fade" id="shippingAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Shipping Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="shippingForm" action="{{ route('update_shipping_address') }}" method="post">
                    @csrf
                    <input type="hidden" name="outlet_id" value="{{ $outletId }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Shipping Address</label>
                        <textarea name="shippingAddress" class="form-control" rows="3">{{ $mainshippingAddress }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pincode</label>
                        <input type="text" name="pincode" class="form-control" value="{{ $mainshippingPincode }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
$(document).ready(function () {
    

    function showOrderPlacingAnimation() {
    Swal.fire({
        html: `
            <div class="oa-loading-wrap">
                <div class="oa-pulse-ring">
                    <div class="oa-pulse-core">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                </div>
                <div class="oa-title">Placing your order</div>
                <div class="oa-dots"><span></span><span></span><span></span></div>
                <div class="oa-subtext">Hang tight, almost there</div>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        padding: '28px 20px'
    });
    }
    
    function showOrderSuccessAnimation(message, onConfirm) {
    Swal.fire({
        html: `
            <div class="oa-success-wrap">
                <div class="oa-success-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1d9e75" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline class="oa-check-path" points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <div class="oa-success-title">Order Placed!</div>
                <div class="oa-success-sub">${message}</div>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'View Order',
        confirmButtonColor: '#4f5fff',
        allowOutsideClick: false,
        padding: '28px 20px'
        }).then((result) => {
            if (result.isConfirmed && onConfirm) {
                onConfirm();
            }
        });
    }


    let selectedMethod = 'online';

     // ===== Delivery Slot Bottom Sheet =====
    const deliveryOptions = @json($deliveryOptions);

    function renderSlots() {
        const $body = $('#slotBody');
        $body.empty();

        if (deliveryOptions.length === 0) {
            $body.append('<p class="text-muted text-center py-4">No delivery slots available.</p>');
            return;
        }

        let recommendedHtml = '';
        let upcomingHtml = '';

        deliveryOptions.forEach(function (opt, idx) {
            const isRecommended = idx === 0;
            const iconClass = isRecommended ? 'green' : (idx === 1 ? 'orange' : 'blue');
            const tagHtml = isRecommended
                ? '<span class="co-slot-tag green">⚡ Earliest Delivery</span>'
                : (idx === 1 ? ' ' : '');

            const cardHtml = `
                <div class="co-slot-card" data-idx="${idx}" data-date="${opt.date}" data-time="${opt.time_only}">
                    ${isRecommended ? '<span class="co-slot-badge">★ RECOMMENDED</span>' : ''}
                    <div class="co-slot-icon ${iconClass}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div class="co-slot-info">
                        <div class="co-slot-date">${opt.slot}</div>
                        ${tagHtml}
                    </div>
                    <div class="co-slot-radio">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
            `;

            if (idx < 2) {
                recommendedHtml += cardHtml;
            } else {
                upcomingHtml += cardHtml;
            }
        });

        if (recommendedHtml) {
            $body.append('<div class="co-slot-section-label">⚡ Recommended for you</div>' + recommendedHtml);
        }
        if (upcomingHtml) {
            $body.append('<div class="co-slot-section-label upcoming">📅 Upcoming dates</div>' + upcomingHtml);
        }

        // Default-select the recommended (first) slot so the sheet opens
        // with it already chosen, matching the reference behavior.
        const $firstCard = $body.find('.co-slot-card').first();
        if ($firstCard.length) {
            $firstCard.trigger('click');
        }
    }

    // Pre-select the recommended slot on page load too — so the trigger
    // card and hidden inputs already reflect it even before the user
    // opens the sheet at all.
    if (deliveryOptions.length > 0) {
        const first = deliveryOptions[0];
        $('#deliveryOptionLabel').text(first.slot);
        $('#delivery_date').val(first.date);
        $('#delivery_time_slot').val(first.time_only);
    }

    $('#openSlotSheetBtn').on('click', function () {
        renderSlots();
        $('#slotOverlay').addClass('open');
        $('#slotSheet').addClass('open');
    });

    $('#slotOverlay').on('click', function () {
        $('#slotOverlay').removeClass('open');
        $('#slotSheet').removeClass('open');
    });

    $(document).on('click', '.co-slot-card', function () {
        $('.co-slot-card').removeClass('selected');
        $(this).addClass('selected');

        const date = $(this).data('date');
        const slotText = $(this).find('.co-slot-date').text();

        $('#footerSlotLabel').text(slotText);
        $('#footerSlotSub').text('Expected delivery on ' + date);
        $('#confirmSlotBtn').prop('disabled', false);
    });

    $('#confirmSlotBtn').on('click', function () {
        const $selected = $('.co-slot-card.selected');
        if (!$selected.length) return;

        const date = $selected.data('date');
        const time = $selected.data('time');
        const label = $selected.find('.co-slot-date').text();

        $('#deliveryOptionLabel').text(label);
        $('#delivery_date').val(date);
        $('#delivery_time_slot').val(time);
        $('#deliveryDateError').hide();

        $('#slotOverlay').removeClass('open');
        $('#slotSheet').removeClass('open');
    });


    // ===== Payment method selection =====
    $('.co-pay-option').on('click', function () {
        if ($(this).hasClass('disabled')) return;

        $('.co-pay-option').removeClass('selected');
        $(this).addClass('selected');
        selectedMethod = $(this).data('method');

        const grandTotal = '{{ number_format($totalDiscountValue, 2) }}';

        if (selectedMethod === 'cod') {
            $('#payBtnLabel').text('Place Order (Pay on Delivery)');
        } else if (selectedMethod === 'credit') {
            $('#payBtnLabel').text('Place Order on Credit');
        } else {
            $('#payBtnLabel').text('Pay Now ₹' + grandTotal);
        }
    });

    // ===== Shared order data payload =====
    function buildOrderData(paymentStatus) {
        return {
            deliveryDate: $('#delivery_date').val(),
            delivery_time_slot: $('#delivery_time_slot').val(),
            billingAddress: @json($billingAddress),
            shippingAddress: @json($shippingAddress),
            subtotal: {{ $subTotalAmt }},
            user_id: {{ $outletId }},
            productDiscount: {{ $totalproductDiscount }},
            cgstSgst: {{ $result }},
            packingCharges: {{ $packingcharges }},
            othersCharges: {{ $otherscharges }},
            deliveryCharges: {{ $deliveryChargeApplied }},
            shipping_pincode: @json($mainshippingPincode),
            totalDiscountValue: {{ $totalDiscountValue }},
            payment_status: paymentStatus,
            cart: @json($cart)
        };
    }

    function validateDeliveryDate() {
        if (!$('#delivery_date').val()) {
            $('#deliveryDateError').show();
            Swal.fire({ text: 'Select Date for Delivery', icon: 'info', confirmButtonText: 'OK' });
            return false;
        }
        return true;
    }

    // ===== Main Pay button — dispatches based on selected method =====
    $('#payBtn').on('click', function () {

        if (!validateDeliveryDate()) return;

        if (selectedMethod === 'online') {
            handleOnlinePayment();
        } else if (selectedMethod === 'cod') {
            handleInsertOrder('pay_on_delivery');
        } else if (selectedMethod === 'credit') {
            handleInsertOrder('credit');
        }
    });

    function handleOnlinePayment() {
        const data = buildOrderData('paid');
        
        showOrderPlacingAnimation();

        // Swal.fire({
        //     title: 'Placing Order...',
        //     text: 'Please wait while we process your order',
        //     allowOutsideClick: false,
        //     allowEscapeKey: false,
        //     didOpen: () => Swal.showLoading()
        // });

        fetch('/create-order', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(responseData => {
            Swal.close();

            if (responseData.error) {
                Swal.fire({ title: 'Error', text: responseData.error, icon: 'error' });
                return;
            }

            const options = {
                key: '{{ env('RAZORPAY_KEY') }}',
                amount: responseData.amount,
                currency: 'INR',
                name: 'Zonik',
                description: 'Infipara Solutions',
                order_id: responseData.order_id,
                callback_url: '{{ route('razorpay.payment.success') }}',
                prefill: {
                    name: '{{ $outletData->name }}',
                    email: '{{ $outletData->email }}',
                    contact: '{{ $outletData->mobile_number }}'
                },
                theme: { color: '#4f5fff' }
            };

            new Razorpay(options).open();
        })
        .catch(() => {
            Swal.close();
            Swal.fire({ title: 'Error', text: 'Something went wrong', icon: 'error' });
        });
    }

function handleInsertOrder(paymentStatus) {
    const data = buildOrderData(paymentStatus);

    showOrderPlacingAnimation();

    $.ajax({
        url: '/insert-order',
        method: 'POST',
        data: data,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function (response) {
            Swal.close();
            showOrderSuccessAnimation(response.success, function () {
                window.location.href = '{{ route('web.order.tracker') }}';
            });
        },
        error: function () {
            Swal.close();
            Swal.fire({ title: 'Error', text: 'Could not place order.', icon: 'error' });
        }
    });
}

});
</script>
@endsection
