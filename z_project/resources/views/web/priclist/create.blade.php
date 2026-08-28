@extends('mobile.mobile-app')
@section('content')

<style>
.pl-page {
    background: #f7f8fa;
    padding: 16px 16px 24px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.pl-container { max-width: 640px; margin: 0 auto; }

/* ===== Header row ===== */
.pl-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}
.pl-title { font-size: 24px; font-weight: 800; color: #101828; margin: 0; }

.pl-outlet-wrap { position: relative; flex-shrink: 0; }
.pl-outlet-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1.5px solid #e4e7ec;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #344054;
    cursor: pointer;
    white-space: nowrap;
}
.pl-outlet-btn svg.pin { color: #4f5fff; flex-shrink: 0; }
.pl-outlet-btn svg.chevron { color: #98a2b3; flex-shrink: 0; transition: transform .15s; }
.pl-outlet-btn.open svg.chevron { transform: rotate(180deg); }

.pl-outlet-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 280px;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(16,24,40,0.14);
    z-index: 200;
    display: none;
    overflow: hidden;
}
.pl-outlet-dropdown.open { display: block; }
.pl-outlet-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    cursor: pointer;
    border-bottom: 1px solid #f2f3f6;
}
.pl-outlet-item:last-child { border-bottom: none; }
.pl-outlet-item:hover { background: #fafbfe; }
.pl-outlet-item.current { background: #eef2ff; }
.pl-outlet-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: #dde3ff; color: #4f5fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.pl-outlet-name { font-size: 13px; font-weight: 700; color: #101828; }
.pl-outlet-loc { font-size: 11px; color: #98a2b3; }
.pl-outlet-check { color: #4f5fff; margin-left: auto; flex-shrink: 0; }

/* ===== Quick action cards ===== */
.pl-quick-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-bottom: 16px;
}
.pl-qa-card {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 12px;
    padding: 14px 6px;
    text-align: center;
    cursor: pointer;
    position: relative;
}
.pl-qa-icon { color: #e2571f; margin-bottom: 8px; }
.pl-qa-label { font-size: 11.5px; font-weight: 700; color: #2f5ede; margin-bottom: 2px; }
.pl-qa-sub { font-size: 9.5px; color: #98a2b3; }
.pl-qa-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #ffe1d1;
    color: #d85a30;
    font-size: 8px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 100px;
}
/* ===== Filter row ===== */
.pl-filter-row {
    display: grid;
    grid-template-columns: 138px 1fr 44px;
    gap: 8px;
    margin-bottom: 14px;
}
.pl-select, .pl-search-box, .pl-filter-btn {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 10px;
    font-size: 13px;
    color: #344054;
    min-width: 0;
}
.pl-select {
    padding: 10px 8px;
    font-weight: 600;
    width: 100%;
    text-overflow: ellipsis;
    overflow: hidden;
}
.pl-search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    min-width: 0;
}
.pl-search-box input {
    border: none;
    outline: none;
    flex: 1;
    font-size: 13px;
    min-width: 0;
    width: 100%;
}
.pl-filter-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 10px 6px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    width: 100%;
}

@media (max-width: 340px) {
    .pl-filter-row {
        grid-template-columns: 1fr;
    }
}

/* ===== Result meta row ===== */
.pl-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.pl-meta-count { font-size: 12.5px; color: #667085; }
.pl-meta-right { display: flex; align-items: center; gap: 10px; }
.pl-sort {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12.5px;
    font-weight: 600;
    color: #344054;
    cursor: pointer;
}
.pl-view-toggle { color: #d85a30; cursor: pointer; }

/* ===== Product list ===== */
.pl-list { background: transparent; }
.pl-item {
    display: flex;
    gap: 12px;
    padding: 14px;
    align-items: flex-start;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    margin-bottom: 12px;
}
.pl-item:last-child { margin-bottom: 0; }

.pl-item-img-wrap {
    width: 64px; height: 64px; border-radius: 10px;
    background: #f4f5f7; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.pl-item-img-real { width: 100%; height: 100%; object-fit: cover; }
.pl-item-emoji { font-size: 28px; }

.pl-item-info { flex: 1; min-width: 0; }
.pl-item-name {
    font-size: 12px;
    font-weight: 700;
    color: #101828;
    white-space: normal;
    line-height: 1.35;
    overflow-wrap: break-word;
}
.pl-item-weight { font-size: 12px; color: #667085; margin-bottom: 3px; }
.pl-item-price-change {
    display: flex; align-items: center; justify-content: flex-start; gap: 4px;
    font-size: 10px; font-weight: 700;
}
.pl-item-price-change-placeholder {
    height: 17px; /* matches the rendered height of .pl-item-price-change at font-size:12px */
}
.pl-item-price-change.down { color: #1d9e75; }
.pl-item-price-change.up { color: #e0442e; }
.pl-item-carton { font-size: 11px; color: #98a2b3; }

.pl-item-right {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
    flex-shrink: 0;
}
.pl-item-price-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.pl-item-price { font-size: 12px; font-weight: 800; color: #101828; white-space: nowrap; }
.pl-fav-btn {
    background: none; border: none; cursor: pointer; padding: 0;
    color: #d0d5dd;
}
.pl-fav-btn.active { color: #2f5ede; }
.pl-fav-btn.active svg { fill: #2f5ede; }

.pl-qty-row { display: flex; align-items: center; gap: 8px; }
.pl-stepper {
    /*display: flex; */
    align-items: center;
    border: 1px solid #e4e7ec; border-radius: 8px;
    overflow: hidden;
}
.pl-stepper button {
    background: #fff; border: none; width: 24px; height: 28px;
    font-size: 14px; color: #344054; cursor: pointer;
}
.pl-qty-plus { color: #4f5fff !important; font-weight: 700; }
.pl-stepper span { font-size: 12px; font-weight: 600; padding: 0 4px; min-width: 34px; text-align: center; }

.pl-add-btn {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1.5px solid #e2571f;
    background: #fff;
    color: #e2571f;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    padding: 0;
}
.pl-add-btn svg { width: 14px; height: 14px; }
.pl-add-btn.to-add { border-color: #e2571f; color: #e2571f; background: #fff; }
.pl-add-btn.added { border-color: #4f5fff; color: #fff; background: #4f5fff; }


/* ===== Cart bar — static, sits in normal flow after the product list.
   (Uses position:sticky, not fixed — fixed would float over/cover
   product cards at every scroll position, not just the bottom of the
   page. Sticky respects document flow: it stays in its normal place
   until scrolling brings it near the bottom of the viewport, then
   holds there — never overlapping earlier content.) ===== */
.pl-cart-bar {
    position: sticky;
    bottom: 76px;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(16,24,40,0.10);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 16px;
    z-index: 50;
}
.pl-cart-left { display: flex; align-items: center; gap: 10px; }
.pl-cart-icon-wrap {
    position: relative;
    color: #e2571f;
}
.pl-cart-count {
    position: absolute; top: -6px; right: -8px;
    background: #4f5fff; color: #fff;
    font-size: 10px; font-weight: 700;
    width: 17px; height: 17px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}
.pl-cart-text-main { font-size: 12.5px; color: #344054; font-weight: 600; }
.pl-cart-text-sub { font-size: 11px; color: #e2571f; font-weight: 700; text-decoration: none; }
.pl-cart-total { text-align: right; }
.pl-cart-total-label { font-size: 10.5px; color: #98a2b3; }
.pl-cart-total-value { font-size: 14px; font-weight: 800; color: #101828; }
.pl-checkout-btn {
    background: #e2571f; color: #fff; border: none;
    border-radius: 10px; padding: 10px 18px;
    font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap;
    text-decoration: none; display: inline-flex; align-items: center; justify-content: center;
}

@media (min-width: 768px) {
    .pl-page { padding: 32px 24px; }
    .pl-container {
        background: #fff;
        border-radius: 20px;
        padding: 28px 32px;
        box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04);
    }
}

.pl-assistant-card {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 14px;
}
.pl-assistant-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 10px;
}
.pl-assistant-title {
    font-size: 14px;
    font-weight: 800;
    color: #101828;
}
.pl-assistant-sub {
    font-size: 12px;
    color: #667085;
}
.pl-assistant-toggle {
    border: none;
    background: #4f5fff;
    color: #fff;
    border-radius: 10px;
    padding: 10px 14px;
    font-weight: 700;
    cursor: pointer;
}
.pl-assistant-panel {
    display: none;
    border-top: 1px solid #eef0f3;
    padding-top: 12px;
    margin-top: 10px;
}
.pl-assistant-panel.open { display: block; }
.pl-assistant-greeting { font-size: 13px; font-weight: 700; color: #101828; margin-bottom: 10px; }
.pl-assistant-step { font-size: 12px; color: #475569; line-height: 1.6; margin-bottom: 10px; }
.pl-assistant-suggestions { display: grid; gap: 10px; margin-bottom: 12px; }
.pl-assistant-suggestion { border: 1px solid #e4e7ec; border-radius: 12px; padding: 12px; display: flex; justify-content: space-between; gap: 10px; align-items: center; }
.pl-assistant-suggestion strong { display: block; font-size: 13px; margin-bottom: 4px; }
.pl-assistant-suggestion small { color: #667085; }
.pl-assistant-suggestion button { border: none; background: #e2571f; color: #fff; border-radius: 10px; padding: 8px 10px; font-size: 12px; cursor: pointer; }
.pl-assistant-summary { border-top: 1px solid #eef0f3; padding-top: 12px; }
.pl-assistant-summary-title { font-size: 13px; font-weight: 700; color: #101828; margin-bottom: 8px; }
.pl-assistant-summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13px; color: #344054; }
.pl-assistant-action { width: 100%; border: none; background: #2f5ede; color: #fff; border-radius: 10px; padding: 12px; font-size: 13px; font-weight: 700; cursor: pointer; }

.pl-filter-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,0.5);
    z-index: 998;
    opacity: 0; pointer-events: none;
    transition: opacity .25s ease;
}
.pl-filter-overlay.open { opacity: 1; pointer-events: auto; }

.pl-filter-sheet {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    max-width: 640px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px 18px 0 0;
    z-index: 999;
    transform: translateY(100%);
    transition: transform .28s cubic-bezier(.16,1,.3,1);
    max-height: 80vh;
    overflow-y: auto;
}
.pl-filter-sheet.open { transform: translateY(0); }

.pl-filter-sheet-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px; border-bottom: 1px solid #eef0f3;
}
.pl-filter-sheet-header h3 { font-size: 16px; font-weight: 700; color: #101828; margin: 0; }
.pl-filter-close {
    width: 30px; height: 30px; border-radius: 50%; background: #f4f5f7;
    border: none; display: flex; align-items: center; justify-content: center; cursor: pointer;
    color: #64748b;
}

.pl-filter-sheet-body { padding: 18px 20px; }
.pl-filter-group { margin-bottom: 18px; }
.pl-filter-group label { display: block; font-size: 12.5px; font-weight: 700; color: #344054; margin-bottom: 8px; }
.pl-filter-group select {
    width: 100%; border: 1px solid #e4e7ec; border-radius: 10px;
    padding: 11px 12px; font-size: 13.5px; color: #344054;
}
.pl-filter-price-row { display: flex; align-items: center; gap: 10px; }
.pl-filter-price-row input {
    flex: 1; border: 1px solid #e4e7ec; border-radius: 10px;
    padding: 11px 12px; font-size: 13.5px; color: #344054; min-width: 0;
}
.pl-filter-price-row span { font-size: 13px; color: #98a2b3; flex-shrink: 0; }

.pl-filter-sheet-footer {
    display: flex; gap: 10px; padding: 16px 20px;
    border-top: 1px solid #eef0f3;
}
.pl-filter-reset-btn {
    flex: 1; border: 1px solid #e4e7ec; background: #fff; color: #344054;
    border-radius: 10px; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer;
}
.pl-filter-apply-btn {
    flex: 2; border: none; background: #e2571f; color: #fff;
    border-radius: 10px; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer;
}

.pl-sort-wrap { position: relative; }
.pl-sort-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 10px;
    box-shadow: 0 12px 32px rgba(16,24,40,0.14);
    z-index: 150;
    min-width: 170px;
    overflow: hidden;
}
.pl-sort-dropdown.open { display: block; }
.pl-sort-option {
    padding: 10px 14px;
    font-size: 12.5px;
    color: #344054;
    cursor: pointer;
    border-bottom: 1px solid #f2f3f6;
}
.pl-sort-option:last-child { border-bottom: none; }
.pl-sort-option:hover { background: #fafbfe; }
.pl-sort-option.active { color: #4f5fff; font-weight: 700; background: #eef2ff; }


</style>

<div class="pl-page">
    <div class="pl-container">

        <!-- ===== Header ===== -->
        <div class="pl-top">
            <h1 class="pl-title">Order Price List</h1>

            <div class="pl-outlet-wrap">
                <button type="button" class="pl-outlet-btn" id="outletBtn">
                    <svg class="pin" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span id="outletBtnLabel">{{ $currentOutlet->outlet_name ?? 'Select Outlet' }}</span>
                    <svg class="chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="pl-outlet-dropdown" id="outletDropdown">
                    @forelse($outlets ?? [] as $outlet)
                        @php $isCurrent = isset($currentOutlet) && $currentOutlet->id == $outlet->id; @endphp
                        <div class="pl-outlet-item {{ $isCurrent ? 'current' : '' }}" data-id="{{ $outlet->id }}" data-name="{{ $outlet->outlet_name }}">
                            <div class="pl-outlet-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v10h16V9"/><path d="M9 21v-6h6v6"/></svg>
                            </div>
                            <div>
                                <div class="pl-outlet-name">{{ $outlet->outlet_name }}</div>
                                <div class="pl-outlet-loc">{{ $outlet->location ?? '' }}</div>
                            </div>
                            @if($isCurrent)
                                <svg class="pl-outlet-check" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            @endif
                        </div>
                    @empty
                        <div class="pl-outlet-item">
                            <div class="pl-outlet-name">No outlets found</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ===== Quick Action Cards ===== -->
  <!--      <div class="pl-quick-actions">-->
  <!--          <div class="pl-qa-card">-->
  <!--              <div class="pl-qa-icon">-->
  <!--                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>-->
  <!--              </div>-->
  <!--              <div class="pl-qa-label">Quick Order</div>-->
  <!--              <div class="pl-qa-sub">Order fast</div>-->
  <!--          </div>-->

  <!--          <div class="pl-qa-card">-->
  <!--              <span class="pl-qa-badge">New</span>-->
  <!--              <div class="pl-qa-icon">-->
  <!--<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/></svg>-->
  <!--              </div>-->
  <!--              <div class="pl-qa-label">Voice Order</div>-->
  <!--              <div class="pl-qa-sub">Speak &amp; order</div>-->
  <!--          </div>-->

  <!--          <div class="pl-qa-card">-->
  <!--              <div class="pl-qa-icon">-->
  <!--                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>-->
  <!--              </div>-->
  <!--              <div class="pl-qa-label">Repeat Order</div>-->
  <!--              <div class="pl-qa-sub">Reorder items</div>-->
  <!--          </div>-->

  <!--          <div class="pl-qa-card">-->
  <!--              <div class="pl-qa-icon">-->
  <!--                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>-->
  <!--              </div>-->
  <!--              <div class="pl-qa-label">Browse</div>-->
  <!--              <div class="pl-qa-sub">All categories</div>-->
  <!--          </div>-->
  <!--      </div>-->

        <!-- ===== Filter Row ===== -->
        <div class="pl-filter-row">
            <select class="pl-select" id="categoryFilter">
                <option value="">All Categories</option>
                <option value="meat">Meat &amp; Poultry</option>
                <option value="dairy">Dairy</option>
                <option value="grains">Grains &amp; Rice</option>
                <option value="oil">Oils</option>
                <option value="vegetables">Vegetables</option>
            </select>

            <div class="pl-search-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#98a2b3" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchProducts" placeholder="Search products...">
            </div>

            <button type="button" class="pl-filter-btn" aria-label="Filter">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            </button>
        </div>

        <!-- ===== Meta Row ===== -->
        <div class="pl-meta-row">
            <div class="pl-meta-count" id="productCount">Showing {{ $products->count() ?? count($products ?? []) }} products</div>
  <!--          <div class="pl-meta-right">-->
  <!--              <div class="pl-sort">-->
  <!--                  Sort: Popular-->

  <!--<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>-->
  <!--              </div>-->
  <!--              <div class="pl-view-toggle">-->
  <!--                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>-->
  <!--              </div>-->
  <!--          </div>-->
  
   <div class="pl-sort-wrap">
                <div class="pl-sort" id="quickSortBtn">
                    Sort: <span id="quickSortLabel">Popular</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="pl-sort-dropdown" id="quickSortDropdown">
                    <div class="pl-sort-option" data-sort="popular">Popular</div>
                    <div class="pl-sort-option" data-sort="price_low">Price: Low to High</div>
                    <div class="pl-sort-option" data-sort="price_high">Price: High to Low</div>
                    <div class="pl-sort-option" data-sort="name_az">Name: A to Z</div>
                </div>
            </div>
        </div>

        <!-- ===== Product List ===== -->
        <div class="pl-list" id="productList">
            @forelse($products ?? [] as $product)
           <div class="pl-item" data-product-id="{{ $product['id'] ?? $product->id ?? '' }}" data-price="{{ $product['price'] ?? 0 }}" data-cart-id="{{ $product['cart_id'] ?? '' }}">
                <div class="pl-item-img-wrap">
                    @if(!empty($product['image']))
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] ?? 'Product' }}" class="pl-item-img-real">
                    @else
                        <span class="pl-item-emoji">📦</span>
                    @endif
                </div>

                <div class="pl-item-info">
                    <div class="pl-item-name">{{ $product['name'] ?? $product->product_name ?? 'Product' }}</div>
                    <div class="pl-item-weight">{{ $product['weight'] ?? '1 kg' }}</div>
                    <div class="pl-item-carton">Carton Size: {{ $product['carton_size'] ?? '-' }} pcs</div>
                </div>

                <div class="pl-item-right">
                    <div class="pl-item-price-row">
                        <div class="pl-item-price">₹{{ number_format($product['price'] ?? 0, 2) }}</div>
                        <button type="button" class="pl-fav-btn {{ ($product['favorited'] ?? false) ? 'active' : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                    </div>

                    @if(($product['price_change'] ?? 0) != 0)
                        <div class="pl-item-price-change {{ ($product['price_change'] ?? 0) < 0 ? 'down' : 'up' }}">
                            @if(($product['price_change'] ?? 0) < 0)
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="19 12 12 19 5 12"/><line x1="12" y1="5" x2="12" y2="19"/></svg>
                            @else
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="5 12 12 5 19 12"/><line x1="12" y1="19" x2="12" y2="5"/></svg>
                            @endif
                            ₹{{ number_format(abs($product['price_change'] ?? 0), 2) }}
                            @if(!empty($product['price_change_percent']))
                                ({{ number_format(abs($product['price_change_percent']), 2) }}%)
                            @endif
                        </div>
                    @else
                        <div class="pl-item-price-change-placeholder"></div>
                    @endif

                    <div class="pl-qty-row">
                        <div class="pl-stepper">
                            <button type="button" class="pl-qty-minus">−</button>
                            <span class="pl-qty-value">{{ $product['cart_qty'] ?? 1 }}</span>
                            <button type="button" class="pl-qty-plus">+</button>
                        </div>
                        <button type="button" class="pl-add-btn {{ ($product['in_cart'] ?? false) ? 'added' : 'to-add' }}" aria-label="Add to cart">
                            <svg class="icon-plus" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="{{ ($product['in_cart'] ?? false) ? 'display:none;' : '' }}"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            <svg class="icon-check" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="{{ ($product['in_cart'] ?? false) ? '' : 'display:none;' }}"><polyline points="20 6 9 17 4 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="pl-item">
                <div class="pl-item-info">No products found.</div>
            </div>
            @endforelse
        </div>
        
          <!-- ===== Filter Bottom Sheet ===== -->
        <div class="pl-filter-overlay" id="filterOverlay"></div>
        <div class="pl-filter-sheet" id="filterSheet">
            <div class="pl-filter-sheet-header">
                <h3>Filter & Sort</h3>
                <button type="button" class="pl-filter-close" id="filterCloseBtn" aria-label="Close">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <div class="pl-filter-sheet-body">
                <div class="pl-filter-group">
                    <label>Sort By</label>
                    <select id="filterSort">
                        <option value="popular">Popular</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="name_az">Name: A to Z</option>
                    </select>
                </div>

                <div class="pl-filter-group">
                    <label>Price Range (₹)</label>
                    <div class="pl-filter-price-row">
                        <input type="number" id="filterPriceMin" placeholder="Min" min="0">
                        <span>to</span>
                        <input type="number" id="filterPriceMax" placeholder="Max" min="0">
                    </div>
                </div>
            </div>

            <div class="pl-filter-sheet-footer">
                <button type="button" class="pl-filter-reset-btn" id="filterResetBtn">Reset</button>
                <button type="button" class="pl-filter-apply-btn" id="filterApplyBtn">Apply Filters</button>
            </div>
        </div>

        <!-- ===== Cart Bar — normal flow, sits right after the product list ===== -->
        <div class="pl-cart-bar" id="cartBar">
    <div class="pl-cart-left">
        <div class="pl-cart-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span class="pl-cart-count" id="cartCount">{{ $cartCount ?? 0 }}</span>
        </div>
        <div>
            <div class="pl-cart-text-main"><span id="cartItemsCount">{{ $cartCount ?? 0 }}</span> Items in Cart</div>
            <a href="#" class="pl-cart-text-sub">View Cart &gt;</a>
        </div>
    </div>
    <div class="pl-cart-total">
        <div class="pl-cart-total-label">Est. Total</div>
        <div class="pl-cart-total-value">₹<span id="cartTotal">{{ number_format($cartTotalAmount ?? 0, 2) }}</span></div>
    </div>
    <a href="{{ route('web.chekout') }}" class="pl-checkout-btn">Checkout</a>
</div>

  </div>
</div>

@endsection

@section('scripts')
<script>

    document.querySelectorAll('.pl-item.added, [class*="added"]').forEach(el => {
    console.log(el.closest('.pl-item')?.dataset.cartId);
});


function initializeCartLineTotals() {
    $('.pl-item').each(function () {
        const $item = $(this);
        const $addBtn = $item.find('.pl-add-btn');

        if ($addBtn.hasClass('added')) {
            const price = parseFloat($item.data('price')) || 0;
            const qty = parseInt($item.find('.pl-qty-value').text()) || 1;
            $item.data('line-total', price * qty);
        }
    });
}


$(document).ready(function () {

  
    $('#outletBtn').on('click', function (e) {
        e.stopPropagation();
        $(this).toggleClass('open');
        $('#outletDropdown').toggleClass('open');
    });

    $(document).on('click', function () {
        $('#outletBtn').removeClass('open');
        $('#outletDropdown').removeClass('open');
    });

  
    $('#outletDropdown').on('click', '.pl-outlet-item[data-id]', function (e) {
        e.stopPropagation();

        const $item = $(this);
        const outletId = $item.data('id');
        const outletName = $item.data('name');

        if ($item.hasClass('current')) {
            $('#outletBtn').removeClass('open');
            $('#outletDropdown').removeClass('open');
            return;
        }

        $('#outletBtn').removeClass('open');
        $('#outletDropdown').removeClass('open');

        const originalLabel = $('#outletBtnLabel').text();
        $('#outletBtnLabel').text('Switching...');

        $.ajax({
            url: '/outlet-selection/choose/' + outletId,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function () {
                $('#outletBtnLabel').text(outletName);

                $('.pl-outlet-item').removeClass('current');
                $('.pl-outlet-item .pl-outlet-check').remove();
                $item.addClass('current');
                $item.append('<svg class="pl-outlet-check" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>');

               
                cartTotalAmount = 0;
                $('#cartCount').text('0');
                $('#cartItemsCount').text('0');
                $('#cartTotal').text(formatCurrency(0));

                refreshProductList();
            },
            error: function () {
                $('#outletBtnLabel').text(originalLabel);
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: 'Could not switch outlet. Please try again.',
                    confirmButtonColor: '#4f5fff'
                });
            }
        });
    });

    
    $('#outletDropdown').on('click', function (e) { e.stopPropagation(); });

function refreshProductList() {
    $('#productList').css('opacity', '0.5');

    $.ajax({
        url: window.location.pathname,
        type: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (html) {
            const $fetched = $(html);

            const $newList = $fetched.find('#productList').html();
            if ($newList) {
                $('#productList').html($newList);
            }

            const newCountText = $fetched.find('#productCount').text();
            if (newCountText) {
                $('#productCount').text(newCountText);
            }

 
            const newCartCount = $fetched.find('#cartCount').text();
            const newCartTotalRaw = $fetched.find('#cartTotal').text();

            $('#cartCount').text(newCartCount);
            $('#cartItemsCount').text(newCartCount);
            $('#cartTotal').text(newCartTotalRaw);

            cartTotalAmount = parseFloat(newCartTotalRaw.replace(/,/g, '')) || 0;

             initializeCartLineTotals();

            $('#productList').css('opacity', '1');
        },
        error: function () {
            $('#productList').css('opacity', '1');
        }
    });
}

   

  
$(document).on('click', '.pl-add-btn', function () {
    const $btn = $(this);
    const $item = $btn.closest('.pl-item');
    const productId = $item.data('product-id');
    const price = parseFloat($item.data('price')) || 0;
    const qty = parseInt($item.find('.pl-qty-value').text()) || 1;

    if ($btn.hasClass('to-add')) {

        $btn.prop('disabled', true);

        $.ajax({
            url: '{{ route('cart.add') }}',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: qty,
                price: price
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                $btn.removeClass('to-add').addClass('added');
                $btn.find('.icon-plus').hide();
                $btn.find('.icon-check').show();
                $item.data('line-total', response.line_total);
                $item.data('cart-id', response.cart_id); // <-- store cart row id
                updateCartSummary(1, response.line_total);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Could not add to cart',
                    text: 'Please try again.',
                    confirmButtonColor: '#4f5fff'
                });
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });

    } else {

        const cartId = $item.data('cart-id'); // <-- read stored cart row id

        if (!cartId) {
            console.error('No cart_id found on this item — cannot remove.');
            return;
        }

        $btn.prop('disabled', true);

        $.ajax({
            url: '{{ route('cart.remove') }}',
            type: 'POST',
            data: { cart_id: cartId }, // <-- send cart_id, not product_id
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                $btn.removeClass('added').addClass('to-add');
                $btn.find('.icon-check').hide();
                $btn.find('.icon-plus').show();
                const removedLineTotal = parseFloat($item.data('line-total')) || (price * qty);
                $item.removeData('line-total');
                $item.removeData('cart-id');
                updateCartSummary(-1, -removedLineTotal);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Could not remove from cart',
                    text: 'Please try again.',
                    confirmButtonColor: '#4f5fff'
                });
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    }
});

    
$(document).on('click', '.pl-qty-minus, .pl-qty-plus', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const $item = $btn.closest('.pl-item');
    const $qty = $item.find('.pl-qty-value');

    let val = parseInt($qty.text(), 10);
    if (isNaN(val) || val < 1) val = 1;

    if ($btn.hasClass('pl-qty-minus')) {
        if (val > 1) val = val - 1;
        // if val is already 1, stays at 1 — this is intentional (min quantity)
    } else {
        val = val + 1;
    }

    $qty.text(val);

    // Only sync to server if this item is already in the cart
    const $addBtn = $item.find('.pl-add-btn');
    if (!$addBtn.hasClass('added')) return;

    const productId = $item.data('product-id');
    const previousLineTotal = parseFloat($item.data('line-total')) || 0;

    $.ajax({
        url: '{{ route('cart.update-quantity') }}',
        type: 'POST',
        data: { product_id: productId, quantity: val },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            $item.data('line-total', response.line_total);
            cartTotalAmount = Math.max(0, cartTotalAmount - previousLineTotal + response.line_total);
            $('#cartTotal').text(formatCurrency(cartTotalAmount));
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Could not update quantity',
                text: 'Please try again.',
                confirmButtonColor: '#4f5fff'
            });
        }
    });
});


    let cartTotalAmount = {{ $cartTotalAmount ?? 0 }};
    initializeCartLineTotals(); 

    function formatCurrency(amount) {
        return amount.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateCartSummary(countDelta, amountDelta) {
        const $count = $('#cartCount');
        const $itemsCount = $('#cartItemsCount');
        let current = parseInt($count.text()) || 0;
        current = Math.max(0, current + countDelta);
        $count.text(current);
        $itemsCount.text(current);

        cartTotalAmount = Math.max(0, cartTotalAmount + amountDelta);
        $('#cartTotal').text(formatCurrency(cartTotalAmount));
    }


    function recalculateTotalForItem($item) {
        const price = parseFloat($item.data('price')) || 0;
        const newQty = parseInt($item.find('.pl-qty-value').text()) || 1;
        const previousLineTotal = parseFloat($item.data('line-total')) || price; 
        const newLineTotal = price * newQty;

        cartTotalAmount = Math.max(0, cartTotalAmount - previousLineTotal + newLineTotal);
        $item.data('line-total', newLineTotal);

        $('#cartTotal').text(formatCurrency(cartTotalAmount));
    }

});


let searchDebounceTimer;

$('#searchProducts').on('input', function () {
    clearTimeout(searchDebounceTimer);
    const searchTerm = $(this).val();

    searchDebounceTimer = setTimeout(function () {
        refreshProductListWithFilters({ search: searchTerm });
    }, 400); 
});


$('#categoryFilter').on('change', function () {
    refreshProductListWithFilters({ category: $(this).val() });
});

function refreshProductListWithFilters(params) {
    $('#productList').css('opacity', '0.5');

    $.ajax({
        url: window.location.pathname,
        type: 'GET',
        data: params,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (html) {
            const $fetched = $(html);

            const $newList = $fetched.find('#productList').html();
            if ($newList) {
                $('#productList').html($newList);
            }

            const newCountText = $fetched.find('#productCount').text();
            if (newCountText) {
                $('#productCount').text(newCountText);
            }

            initializeCartLineTotals();

            $('#productList').css('opacity', '1');
        },
        error: function () {
            $('#productList').css('opacity', '1');
        }
    });
}

$('.pl-filter-btn').on('click', function () {
    $('#filterOverlay').addClass('open');
    $('#filterSheet').addClass('open');
});

$('#filterCloseBtn, #filterOverlay').on('click', function () {
    $('#filterOverlay').removeClass('open');
    $('#filterSheet').removeClass('open');
});

$('#filterApplyBtn').on('click', function () {
    $('#filterOverlay').removeClass('open');
    $('#filterSheet').removeClass('open');

    refreshProductListWithFilters({
        search: $('#searchProducts').val(),
        category: $('#categoryFilter').val(),
        sort: $('#filterSort').val(),
        price_min: $('#filterPriceMin').val(),
        price_max: $('#filterPriceMax').val()
    });
});

$('#filterResetBtn').on('click', function () {
    $('#filterSort').val('popular');
    $('#filterPriceMin').val('');
    $('#filterPriceMax').val('');

    refreshProductListWithFilters({
        search: $('#searchProducts').val(),
        category: $('#categoryFilter').val()
    });
});

$('#quickSortBtn').on('click', function (e) {
    e.stopPropagation();
    $('#quickSortDropdown').toggleClass('open');
});

$(document).on('click', function () {
    $('#quickSortDropdown').removeClass('open');
});

$('#quickSortDropdown').on('click', function (e) { e.stopPropagation(); });

$('#quickSortDropdown').on('click', '.pl-sort-option', function () {
    const sortValue = $(this).data('sort');
    const sortLabel = $(this).text();

    $('#quickSortLabel').text(sortLabel);
    $('.pl-sort-option').removeClass('active');
    $(this).addClass('active');

   
    $('#filterSort').val(sortValue);

    $('#quickSortDropdown').removeClass('open');

    refreshProductListWithFilters({
        search: $('#searchProducts').val(),
        category: $('#categoryFilter').val(),
        sort: sortValue,
        price_min: $('#filterPriceMin').val(),
        price_max: $('#filterPriceMax').val()
    });
});

$(document).on('click', '.pl-fav-btn', function () {
    const $btn = $(this);
    const $item = $btn.closest('.pl-item');
    const productId = $item.data('product-id');

    $btn.prop('disabled', true);

    $.ajax({
        url: '{{ route('favorite.toggle') }}',
        type: 'POST',
        data: { product_id: productId },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            $btn.toggleClass('active', response.favorited);

            if (response.favorited) {
                favOrderCounter++;
                $item.data('fav-order', favOrderCounter);
            } else {
                $item.removeData('fav-order');
            }

            resortFavoritesToTop();
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Could not update favorite',
                text: 'Please try again.',
                confirmButtonColor: '#4f5fff'
            });
        },
        complete: function () {
            $btn.prop('disabled', false);
        }
    });
});

let favOrderCounter = 0;

function resortFavoritesToTop() {
    const $list = $('#productList');
    const $allItems = $list.find('.pl-item').get();

    const favorited = [];
    const rest = [];

    $allItems.forEach(function (el) {
        const $el = $(el);
        if ($el.data('fav-order') !== undefined) {
            favorited.push($el);
        } else {
            rest.push($el);
        }
    });

    favorited.sort(function (a, b) {
        return $(a).data('fav-order') - $(b).data('fav-order');
    });

    favorited.concat(rest).forEach(function ($el) {
        $list.append($el);
    });
}

</script>
@endsection
