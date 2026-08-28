@extends('web.layouts.app')
@section('content')
<title>Zonik – Order Management</title>
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"/> -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/> -->
  <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet"/> -->
<style>
/* ═══════════════════════════════════════
   ZONIK DESIGN SYSTEM – ORDER MANAGEMENT
   ═══════════════════════════════════════ */
:root {
  --zonik-orange:  #f26522;
  --zonik-blue:    #1e9de0;
  --zonik-dark:    #1a1a2e;
  --zonik-bg:      #f4f6fb;
  --zonik-card:    #ffffff;
  --zonik-border:  #e8ecf4;
  --zonik-green:   #22c55e;
  --zonik-red:     #ef4444;
  --zonik-text:    #2d3a4a;
  --zonik-muted:   #8493a6;
  --shadow-sm:     0 2px 8px  rgba(30,157,224,.07);
  --shadow-md:     0 4px 20px rgba(30,157,224,.12);
}
*, *::before, *::after { box-sizing: border-box; }
body { font-family: 'Nunito', sans-serif; background: var(--zonik-bg); color: var(--zonik-text); margin: 0; }

/* ── PAGE WRAPPER ── */
.order-management { 
    padding: 28px 24px 60px; 
    /* max-width: 1300px;  */
    margin: 0 auto; 
}

.page-title {
  font-size: 1.7rem; font-weight: 900; letter-spacing: -.5px;
  color: var(--zonik-dark); margin-bottom: 24px;
}
.page-title span { color: var(--zonik-orange); }

.home-i {
  font-size: 1rem; background: #fff; border: 1.5px solid var(--zonik-border);
  padding: 10px 14px; border-radius: 10px; color: var(--zonik-muted);
  text-decoration: none; transition: all .18s;
}
.home-i:hover { background: var(--zonik-orange); color: #fff; border-color: var(--zonik-orange); }

/* ══════════════════════════════════
   TOP-LEVEL TAB NAV
   ══════════════════════════════════ */
.order-tabs { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 28px; }
.order-tab {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 22px; border-radius: 10px;
  border: 1.5px solid var(--zonik-border); background: #fff;
  font-weight: 700; font-size: .87rem; color: var(--zonik-muted);
  cursor: pointer; position: relative; transition: all .18s;
  text-decoration: none;
}
.order-tab:hover { border-color: var(--zonik-orange); color: var(--zonik-orange); }
.order-tab.active {
  background: var(--zonik-orange); border-color: var(--zonik-orange);
  color: #fff; box-shadow: 0 4px 14px rgba(242,101,34,.3);
}
.tab-badge {
  position: absolute; top: -8px; right: -8px;
  background: var(--zonik-orange); color: #fff;
  font-size: .6rem; font-weight: 800; width: 18px; height: 18px;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  border: 2px solid #fff;
}
.order-tab.active .tab-badge { background: var(--zonik-dark); }

/* ══════════════════════════════════
   INNER TABS
   ══════════════════════════════════ */
.inner-tabs { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 20px; }
.inner-tab {
  display: flex; align-items: center; gap: 7px; padding: 8px 20px;
  border-radius: 8px; border: 1.5px solid var(--zonik-border); background: #fff;
  font-size: .83rem; font-weight: 700; color: var(--zonik-muted);
  cursor: pointer; transition: all .18s;
}
.inner-tab:hover { border-color: var(--zonik-blue); color: var(--zonik-blue); }
.inner-tab.active-quotes   { background: var(--zonik-orange); border-color: var(--zonik-orange); color: #fff; box-shadow: 0 4px 12px rgba(242,101,34,.25); }
.inner-tab.active-pricelist { background: var(--zonik-blue);   border-color: var(--zonik-blue);   color: #fff; box-shadow: 0 4px 12px rgba(30,157,224,.25); }

/* ══════════════════════════════════
   SECTION HEADER
   ══════════════════════════════════ */
.section-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  gap: 16px; margin-bottom: 20px; flex-wrap: wrap;
}
.section-title { font-size: 1.25rem; font-weight: 900; color: var(--zonik-dark); }
.section-subtitle { font-size: .8rem; color: var(--zonik-muted); font-weight: 600; margin-top: 2px; }

/* ══════════════════════════════════
   STATS ROW
   ══════════════════════════════════ */
.stats-row { display: flex; gap: 12px; margin-bottom: 22px; flex-wrap: wrap; }
.stat-card {
  flex: 1; min-width: 120px; background: #fff; border-radius: 14px;
  border: 1.5px solid var(--zonik-border); padding: 14px 18px;
  display: flex; align-items: center; gap: 12px; box-shadow: var(--shadow-sm);
}
.stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
.stat-icon.total   { background: #eef4ff; color: var(--zonik-blue); }
.stat-icon.approve { background: #f0fdf4; color: var(--zonik-green); }
.stat-icon.reject  { background: #fff1f1; color: var(--zonik-red); }
.stat-label { font-size: .72rem; color: var(--zonik-muted); font-weight: 600; }
.stat-value { font-size: 1.35rem; font-weight: 900; color: var(--zonik-dark); line-height: 1.1; }

/* ══════════════════════════════════
   PRODUCT TABLE
   ══════════════════════════════════ */
.product-table-new-wrap {
  background: #fff; border-radius: 16px; border: 1.5px solid var(--zonik-border);
  overflow: hidden; box-shadow: var(--shadow-sm); margin-bottom: 28px;
}
.product-table-new { width: 100%; border-collapse: collapse; }
.product-table-new thead tr { background: var(--zonik-bg); border-bottom: 1.5px solid var(--zonik-border); }
.product-table-new thead th {
  font-size: .72rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .8px; color: var(--zonik-muted); padding: 12px 16px; vertical-align: middle;
}
.product-table-new tbody tr { border-bottom: 1px solid var(--zonik-border); transition: background .12s; }
.product-table-new tbody tr:last-child { border-bottom: none; }
.product-table-new tbody tr:hover { background: #f8fbff; }
.product-table-new td { padding: 14px 16px; vertical-align: middle; font-size: .85rem; font-weight: 600; }

.prod-cell { display: flex; align-items: center; gap: 12px; }
.prod-img {
  width: 52px; height: 52px; border-radius: 10px; object-fit: contain;
  background: var(--zonik-bg); border: 1px solid var(--zonik-border); padding: 4px; flex-shrink: 0;
}
.prod-img-placeholder {
  width: 52px; height: 52px; border-radius: 10px;
  background: linear-gradient(135deg, #eef4ff 0%, #f4f6fb 100%);
  border: 1px solid var(--zonik-border);
  display: flex; align-items: center; justify-content: center;
  color: var(--zonik-blue); font-size: 1.2rem; flex-shrink: 0;
}
.prod-name  { font-size: .87rem; font-weight: 800; color: var(--zonik-dark); line-height: 1.3; }
.prod-pattern { font-size: .72rem; color: var(--zonik-muted); font-weight: 600; margin-top: 2px; }

.price-main { font-size: 1rem; font-weight: 900; color: var(--zonik-dark); }
.price-approved { color: var(--zonik-green) !important; }
.price-mrp  { font-size: 0.73rem; color: var(--zonik-muted); font-weight: 600; }
.price-quoted-label { font-size: 0.68rem; color: var(--zonik-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.price-quoted-val { font-size: 0.85rem; font-weight: 800; color: var(--zonik-text); text-decoration: line-through; }

/* change indicators */
.change-up   { color: var(--zonik-red);   font-size: .8rem; font-weight: 800; }
.change-down { color: var(--zonik-green); font-size: .8rem; font-weight: 800; }
.change-nil  { color: var(--zonik-muted); font-size: .8rem; font-weight: 700; }
.change-pct  { font-size: .72rem; font-weight: 700; display: block; margin-top: 1px; }
.change-pct.up   { color: var(--zonik-red); }
.change-pct.down { color: var(--zonik-green); }

.badge-box   { background: #fff1e8; color: var(--zonik-orange); border: 1px solid #fbd0b4; }
.badge-loose { background: #fff1f1; color: var(--zonik-red);    border: 1px solid #fac8c8; }
.badge-other { background: #eef4ff; color: var(--zonik-blue);   border: 1px solid #b8d9f5; }
.pattern-badge {
  display: inline-block; padding: 2px 8px; border-radius: 20px;
  font-size: .68rem; font-weight: 800;
}

/* action buttons */
.action-btns { display: flex; align-items: center; gap: 6px; }
.z-btn {
  width: 34px; height: 34px; border-radius: 8px; border: 1.5px solid;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; font-size: .85rem; transition: all .15s; background: none; padding: 0;
}
.z-btn-approve { border-color: var(--zonik-green); color: var(--zonik-green); background: #f0fdf4; }
.z-btn-approve:hover { background: var(--zonik-green); color: #fff; }
.z-btn-reject  { border-color: var(--zonik-red);   color: var(--zonik-red);   background: #fff1f1; }
.z-btn-reject:hover  { background: var(--zonik-red);   color: #fff; }
.z-btn-cart    { border-color: var(--zonik-blue);  color: var(--zonik-blue);  background: #eef4ff; }
.z-btn-cart:hover    { background: var(--zonik-blue);  color: #fff; }
.z-btn-remove  { border-color: var(--zonik-red);   color: var(--zonik-red);   background: #fff1f1; }
.z-btn-remove:hover  { background: var(--zonik-red);   color: #fff; }

.approved-tick {
  width: 34px; height: 34px; border-radius: 50%;
  border: 2.5px solid var(--zonik-green); background: #f0fdf4;
  display: flex; align-items: center; justify-content: center;
  color: var(--zonik-green); font-size: 1rem;
}

/* ══════════════════════════════════
   MOBILE CARDS
   ══════════════════════════════════ */
.z-mobile-card {
  background: #fff; border-radius: 16px; border: 1.5px solid var(--zonik-border);
  box-shadow: var(--shadow-sm); margin-bottom: 12px; overflow: hidden; position: relative;
}
.z-card-img {
  width: 72px; height: 72px; object-fit: contain; border-radius: 10px;
  background: var(--zonik-bg); border: 1px solid var(--zonik-border); padding: 4px; flex-shrink: 0;
}
.z-card-img-placeholder {
  width: 72px; height: 72px; border-radius: 10px;
  background: linear-gradient(135deg, #eef4ff 0%, #f4f6fb 100%);
  border: 1px solid var(--zonik-border);
  display: flex; align-items: center; justify-content: center;
  color: var(--zonik-blue); font-size: 1.5rem; flex-shrink: 0;
}
.z-ribbon {
  --f: 8px; --r: 10px;
  position: absolute; inset: 0 calc(-1*var(--f)) auto auto;
  padding: 0 10px var(--f) calc(6px + var(--r));
  clip-path: polygon(0 0,100% 0,100% calc(100% - var(--f)),calc(100% - var(--f)) 100%,calc(100% - var(--f)) calc(100% - var(--f)),0 calc(100% - var(--f)),var(--r) calc(50% - var(--f)/2));
  background: var(--zonik-orange); font-size: .7rem; color: #fff; font-weight: 800;
  height: 28px; line-height: 20px;
}
.z-price-tag { font-size: .78rem; color: var(--zonik-muted); font-weight: 600; }
.z-price-val { font-size: .95rem; font-weight: 900; color: var(--zonik-dark); }

/* ══════════════════════════════════
   CART / ORDER SUMMARY
   ══════════════════════════════════ */
.cart-summary {
  background: #fff; border-radius: 16px; border: 1.5px solid var(--zonik-border);
  padding: 20px; box-shadow: var(--shadow-sm);
}
.cart-summary h5 { font-weight: 900; color: var(--zonik-dark); font-size: 1rem; margin-bottom: 16px; }
.summary-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 8px 0; border-bottom: 1px solid var(--zonik-border); font-size: .88rem; font-weight: 700;
}
.summary-row:last-of-type { border-bottom: none; }
.summary-row .lbl { color: var(--zonik-muted); }
.summary-row .val { color: var(--zonik-dark); }
.summary-row.grand .val { color: var(--zonik-orange); font-size: 1.1rem; }

/* ══════════════════════════════════
   SEARCH
   ══════════════════════════════════ */
.z-search-wrap { position: relative; }
.z-search-wrap input {
  padding: 9px 18px 9px 40px; border-radius: 50px;
  border: 1.5px solid var(--zonik-border); background: var(--zonik-bg);
  font-family: 'Nunito', sans-serif; font-size: .85rem; color: var(--zonik-text);
  width: 100%; outline: none; transition: border-color .2s;
}
.z-search-wrap input:focus { border-color: var(--zonik-blue); }
.z-search-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--zonik-muted); }

/* ══════════════════════════════════
   EMPTY STATE
   ══════════════════════════════════ */
.empty-state {
  background: #f0f7ff; border-radius: 12px; border: 1.5px dashed var(--zonik-blue);
  padding: 28px 20px; text-align: center; color: var(--zonik-blue);
  font-size: .88rem; font-weight: 700; margin-bottom: 28px;
}

/* ══════════════════════════════════
   QUANTITY CONTROL
   ══════════════════════════════════ */
.z-qty { display: flex; align-items: center; border: 1.5px solid var(--zonik-border); border-radius: 8px; overflow: hidden; width: fit-content; }
.z-qty button {
  width: 28px; height: 28px; background: var(--zonik-bg); border: none;
  font-size: 1rem; font-weight: 800; color: var(--zonik-dark); cursor: pointer; transition: background .15s;
}
.z-qty button:hover { background: var(--zonik-orange); color: #fff; }
.z-qty input {
  width: 36px; height: 28px; border: none; text-align: center;
  font-size: .85rem; font-weight: 800; font-family: 'Nunito', sans-serif;
  background: #fff; color: var(--zonik-dark);
  -moz-appearance: textfield;
}
.z-qty input::-webkit-outer-spin-button,
.z-qty input::-webkit-inner-spin-button { -webkit-appearance: none; }

.empty-state {
  background: #f0f7ff;
  border-radius: 12px;
  border: 1.5px dashed var(--zonik-blue);
  padding: 28px 20px;
  text-align: center;
  color: var(--zonik-blue);
  font-size: 0.88rem;
  font-weight: 700;
  margin-bottom: 28px;
}
/* ══════════════════════════════════
   MODAL
   ══════════════════════════════════ */
.z-modal .modal-content { border-radius: 20px !important; border: 1.5px solid var(--zonik-border) !important; box-shadow: var(--shadow-md) !important; }
.z-modal .modal-header { border-bottom: 1px solid var(--zonik-border); padding: 16px 20px; }
.z-modal .modal-title { font-weight: 900; font-size: 1rem; color: var(--zonik-dark); }
.z-modal .modal-body  { padding: 20px; }
.z-modal .modal-footer{ border-top: 1px solid var(--zonik-border); padding: 14px 20px; }

/* ══════════════════════════════════
   BUTTONS
   ══════════════════════════════════ */
.red-btn {
  background: var(--zonik-orange); color: #fff; border: none;
  padding: 9px 22px; border-radius: 9px; font-weight: 700; font-size: .85rem;
  font-family: 'Nunito', sans-serif; cursor: pointer; transition: background .18s;
}
.red-btn:hover { background: #d9561b; }
.red-btn.outline {
  background: none; color: var(--zonik-dark); border: 1.5px solid var(--zonik-border);
}
.red-btn.outline:hover { background: var(--zonik-bg); }

/* ══════════════════════════════════
   PANE VISIBILITY
   ══════════════════════════════════ */
.z-tab-pane { display: none; }
.z-tab-pane.active { display: block; }

/* ══════════════════════════════════
   COUPON LIST
   ══════════════════════════════════ */
.couponList { list-style: none; padding: 0; margin: 0; }
.couponList li {
  padding: 10px 12px; border: 1px solid var(--zonik-border);
  border-radius: 8px; margin-bottom: 8px; font-size: .82rem; font-weight: 600;
}
.coupon-apply {
  background: var(--zonik-green); color: #fff; border: none;
  border-radius: 20px; font-size: .75rem; padding: 3px 12px; cursor: pointer;
  font-family: 'Nunito', sans-serif; font-weight: 700;
}

/* ── OUTLET TABLE ── */
#outletTable tbody tr { cursor: pointer; transition: background .12s; }
#outletTable tbody tr:hover { background: #f0f7ff; }

/* ══════════════════════════════════
   SCROLLBAR
   ══════════════════════════════════ */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-thumb { border-radius: 10px; background: rgba(0,0,0,.15); }

/* ══════════════════════════════════
   RESPONSIVE
   ══════════════════════════════════ */
@media (max-width: 767px) {
  .order-management { padding: 16px 12px 48px; }
  .order-tab { padding: 8px 14px; font-size: .78rem; }
  .page-title { font-size: 1.3rem; }
  .stat-card { min-width: 90px; }
  .d-none-mob { display: none !important; }
  .d-block-mob { display: block !important; }
}
@media (min-width: 768px) {
  .d-none-desk { display: none !important; }
  .d-block-desk { display: block !important; }
}
</style>
</head>
<body>

                    @php
                    // Initialize arrays to group products by reoffer status
                    $groupedProductsYes = []; // Products where reoffer is 'yes'
                    $groupedProductsNo = [];  // Products where reoffer is 'no'

                    // Group products based on the value of reoffer
                    foreach ($enquiriesForOfferList as $key => $offer_list) {
                        // Check if reoffer is 'yes'
                        if ($offer_list->reoffer == 'yes') {
                            // Calculate GST rate and selling price with GST
                            $gstRate = $offer_list->product->cgst + $offer_list->product->sgst;
                            $sellingPriceWithGst = $offer_list->offer_price * (1 + ($gstRate / 100));
                            
                             $mrp = $offer_list->mrp; // Store MRP in a variable
                            $discount = 0; // Initialize discount with a default value
                            
                            // Ensure MRP is greater than zero before calculating the discount
                            if ($mrp > 0) {
                                $discount = (($mrp - $sellingPriceWithGst) / $mrp) * 100;
                            }
                            
                            // Add discount to product data
                            $offer_list->discount = number_format($discount, 2);


                            // Add discount to product data
                            $offer_list->discount = number_format($discount, 2);

                            // Add to the 'yes' group
                            $groupedProductsYes[] = $offer_list;
                        } else {
                            // For 'no', simply add to the 'no' group
                            $groupedProductsNo[] = $offer_list;
                        }
                    }

                    // Count the number of products in each group
                    $yesCount = count($groupedProductsYes);
                    $noCount = count($groupedProductsNo);
                @endphp
<section class="order-management">

  <!-- PAGE HEADING -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="page-title mb-0">ORDER <span>MANAGEMENT</span></h1>
    <a href="#" class="home-i"><i class="fa fa-home"></i></a>
  </div>

  <!-- TOP-LEVEL TABS (Desktop) -->
  <div class="order-tabs d-none d-md-flex">
    <a href="#" class="order-tab active" id="tab-enquiry" onclick="switchTab(event,'enquiry')">
      <i class="fa-solid fa-cart-shopping"></i> Enquiry
      <span class="tab-badge">{{$quoteCounts}}</span>
    </a>
    <a href="#" class="order-tab " id="tab-pricelist" onclick="switchTab(event,'pricelist')">
      <i class="fa-solid fa-receipt"></i> My Price List
      <span class="tab-badge">{{$totalPriceListCount}}</span>
    </a>
    <a href="#" class="order-tab" id="tab-ordercart" onclick="switchTab(event,'ordercart')">
      <i class="fa-solid fa-cart-plus"></i> Order Cart
      <span class="tab-badge">{{$cartsCount}}</span>
    </a>
  </div>

  <!-- ════════════════════════════════════
       PANE: ENQUIRY CART
       ════════════════════════════════════ -->
<div class="z-tab-pane active" id="pane-enquiry">

    <div class="section-header">
        <div>
            <div class="section-title">
                Enquiry <span style="color:var(--zonik-orange)">Cart</span>
            </div>
            <div class="section-subtitle">
                Products you've added for price enquiry.
            </div>
        </div>
    </div>

    @if (isset($quote_Items_list) && $quote_Items_list->count() > 0)

        <form id="enquiryForm">
            @csrf

            @php $counter = 1; @endphp

            <!-- Desktop Table -->
            <div class="d-none d-md-block">
                <div class="product-table-new-wrap">
                    <table class="product-table-new">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Order Type</th>
                                <th>Monthly Consumption</th>
                                <th>Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($quote_Items_list as $quote_Items)
                            <tr>
                                <td>{{ $counter }}</td>

                                <td>
                                    <div class="prod-cell">
                                        <div class="prod-img-placeholder">
                                            @if($quote_Items->product && $quote_Items->product->image)
                                                <img src="uploads/{{ $quote_Items->product->image }}" class="enquiry-img">
                                            @else
                                                📦
                                            @endif
                                        </div>

                                        <div>
                                            <div class="prod-name">
                                                {{ $quote_Items->product->product_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if ($quote_Items->product_type == '1')
                                        <span class="pattern-badge badge-box">
                                            Carton Box : {{ $quote_Items->product->carton_size }}
                                        </span>    
                                            <input type="hidden" name="discount{{ $counter }}" id="discount{{ $counter }}" value="{{ $quote_Items->product->carton_discount_basic }}">
                                            <input type="hidden" name="offer_price{{ $counter }}" id="offer_price{{ $counter }}" value="{{ $quote_Items->product->sale_price_carton }}">
                                            <input type="hidden" class="form-select" name="product_types{{ $counter }}" value="{{ $quote_Items->product_type }}">
                                            <input type="hidden" class="form-select" name="quantity{{ $counter }}" value="{{ $quote_Items->quantity }}">
                                    @elseif ($quote_Items->product_type == '2')
                                        <span class="pattern-badge badge-loose">
                                            Loose (pcs.)
                                        </span>    
                                            <input type="hidden" name="discount{{ $counter }}" id="discount{{ $counter }}" value="{{ $quote_Items->product->loose_discount_basic }}">
                                            <input type="hidden" name="offer_price{{ $counter }}" id="offer_price{{ $counter }}" value="{{ $quote_Items->product->sale_price_loose_pcs }}">
                                            <input type="hidden" class="form-select" name="quantity{{ $counter }}" value="{{ $quote_Items->quantity }}">
                                            <input type="hidden" class="form-select" name="product_types{{ $counter }}" value="{{ $quote_Items->product_type }}">
                                        
                                    @else
                                        <span class="pattern-badge badge-other">Not Set</span>
                                    @endif
                                     <input type="hidden" name="product_id{{ $counter }}" value="{{ $quote_Items->product->id }}">
                                </td>

                                <td>
                                          <input type="text" name="monthlyconsumption{{ $counter }}" id="monthlyconsumption{{ $counter }}" class="form-control"
                                           style="width:120px"
                                           placeholder="Qty">
                                           
                                          <input type="hidden" name="mrp{{ $counter }}" id="mrp{{ $counter }}" value="{{ $quote_Items->product->product_mrp }}">
                                </td>

                               <td>
                                    <button type="button"
                                    class="z-btn z-btn-remove"
                                    onclick="removeRow(this, '{{ route('removequote', $quote_Items->id) }}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                                </td>
                                    </tr>

                            @php $counter++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <button id="submitEnquiryButton" class="red-btn mt-2 submitEnquiry">Submit Enquiry</button>

        </form>

    @else

        <div class="empty-state">
            <i class="bi bi-cart-plus me-2"></i>
            Add new product to your enquiry cart to get offer.
        </div>

    @endif

</div>


  <!-- ════════════════════════════════════
       PANE: MY PRICE LIST (inner tabs: Quotes / Price List)
       ════════════════════════════════════ -->
  <div class="z-tab-pane" id="pane-pricelist">

    <div class="section-header">
      <div>
        <div class="section-title" id="inner-section-ttle">Quotes</div>
        <div class="section-subtitle" id="inner-section-sub">Review and take action on quoted prices.</div>
      </div>
      <div class="inner-tabs">
        <button class="inner-tab active-quotes" id="inner-tab-quotes" onclick="switchInnerTab('quotes')">
          <i class="fa-solid fa-file-invoice"></i> Quotes
          <span style="background:#fff3ee;color:var(--zonik-orange);padding:1px 7px;border-radius:20px;font-size:.7rem;margin-left:2px;">5</span>
        </button>
        <button class="inner-tab" id="inner-tab-pricelist" onclick="switchInnerTab('pricelist')">
          <i class="fa-solid fa-shield-halved"></i> Price List
          <span style="background:#eef4ff;color:var(--zonik-blue);padding:1px 7px;border-radius:20px;font-size:.7rem;margin-left:2px;">5</span>
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon total"><i class="fa-solid fa-tag"></i></div>
        <div><div class="stat-label">Total Items</div><div class="stat-value" id="stat-total">7</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon approve"><i class="fa-solid fa-circle-check"></i></div>
        <div><div class="stat-label">Approved</div><div class="stat-value" id="stat-approved">5</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon reject"><i class="fa-solid fa-circle-xmark"></i></div>
        <div><div class="stat-label">Rejected</div><div class="stat-value" id="stat-rejected">2</div></div>
      </div>
    </div>

    <!-- ── INNER PANE: QUOTES ── -->
    <div id="inner-pane-quotes">

      <!-- Desktop Table -->
      <div class="d-none d-md-block">
        <div class="product-table-new-wrap">
          <table class="product-table-new">
            <thead>
              <tr>
                <th>#</th><th>Product</th><th>Order Type</th>
                <th>Quoted Price</th><th>Final Price</th><th>Change</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🍪</div><div><div class="prod-name">Britannia NutriChoice Biscuit 100g</div></div></div></td>
                <td><span class="pattern-badge badge-loose">Loose</span></td>
                <td>
                  <div class="price-quoted-label">Quoted</div>
                  <div class="price-quoted-val">₹26.00</div>
                </td>
                <td><span class="price-main">₹24.00</span></td>
                <td>
                  <span class="change-up"><i class="fa-solid fa-arrow-up"></i> ₹2.00</span>
                  <span class="change-pct up">+9.09%</span>
                </td>
                <td>
                  <div class="action-btns">
                    <button class="z-btn z-btn-approve" title="Accept" onclick="acceptOffer(this)"><i class="fa-solid fa-check"></i></button>
                    <button class="z-btn z-btn-reject" title="Reject" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                    <button class="z-btn z-btn-cart" title="Add to Cart" onclick="addToCart(this,'Britannia NutriChoice Biscuit 100g','₹24.00','Loose')"><i class="fa-solid fa-cart-plus"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🥤</div><div><div class="prod-name">Double Pagoda Rice Wine Vinegar 750ml</div></div></div></td>
                <td><span class="pattern-badge badge-box">Box: 6</span></td>
                <td>
                  <div class="price-quoted-label">Quoted</div>
                  <div class="price-quoted-val">₹220.00</div>
                </td>
                <td><span class="price-main">₹118.00</span></td>
                <td>
                  <span class="change-down"><i class="fa-solid fa-arrow-down"></i> ₹102.00</span>
                  <span class="change-pct down">−46.36%</span>
                </td>
                <td>
                  <div class="action-btns">
                    <button class="z-btn z-btn-approve" title="Accept" onclick="acceptOffer(this)"><i class="fa-solid fa-check"></i></button>
                    <button class="z-btn z-btn-reject" title="Reject" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                    <button class="z-btn z-btn-cart" title="Add to Cart" onclick="addToCart(this,'Double Pagoda Rice Wine Vinegar 750ml','₹118.00','Box: 6')"><i class="fa-solid fa-cart-plus"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🌻</div><div><div class="prod-name">Freedom Refined Sunflower Oil 1L Pouch</div></div></div></td>
                <td><span class="pattern-badge badge-other">Pouch</span></td>
                <td>
                  <div class="price-quoted-label">Quoted</div>
                  <div class="price-quoted-val">₹152.00</div>
                </td>
                <td><span class="price-main">₹145.00</span></td>
                <td>
                  <span class="change-up"><i class="fa-solid fa-arrow-up"></i> ₹7.00</span>
                  <span class="change-pct up">+4.61%</span>
                </td>
                <td>
                  <div class="action-btns">
                    <button class="z-btn z-btn-approve" title="Accept" onclick="acceptOffer(this)"><i class="fa-solid fa-check"></i></button>
                    <button class="z-btn z-btn-reject" title="Reject" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                    <button class="z-btn z-btn-cart" title="Add to Cart" onclick="addToCart(this,'Freedom Sunflower Oil 1L','₹145.00','Pouch')"><i class="fa-solid fa-cart-plus"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>4</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🍟</div><div><div class="prod-name">Haldiram's Aloo Bhujia 200g</div></div></div></td>
                <td><span class="pattern-badge badge-loose">Loose</span></td>
                <td>
                  <div class="price-quoted-label">Quoted</div>
                  <div class="price-quoted-val">₹52.00</div>
                </td>
                <td><span class="price-main">₹48.00</span></td>
                <td>
                  <span class="change-up"><i class="fa-solid fa-arrow-up"></i> ₹4.00</span>
                  <span class="change-pct up">+8.33%</span>
                </td>
                <td>
                  <div class="action-btns">
                    <button class="z-btn z-btn-approve" title="Accept" onclick="acceptOffer(this)"><i class="fa-solid fa-check"></i></button>
                    <button class="z-btn z-btn-reject" title="Reject" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                    <button class="z-btn z-btn-cart" title="Add to Cart" onclick="addToCart(this,'Haldiram\'s Aloo Bhujia 200g','₹48.00','Loose')"><i class="fa-solid fa-cart-plus"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>5</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🍵</div><div><div class="prod-name">Tata Tea Premium 1kg</div></div></div></td>
                <td><span class="pattern-badge badge-box">Box: 12</span></td>
                <td>
                  <div class="price-quoted-label">Quoted</div>
                  <div class="price-quoted-val">₹250.00</div>
                </td>
                <td><span class="price-main">₹235.00</span></td>
                <td>
                  <span class="change-down"><i class="fa-solid fa-arrow-down"></i> ₹15.00</span>
                  <span class="change-pct down">−6.00%</span>
                </td>
                <td>
                  <div class="action-btns">
                    <button class="z-btn z-btn-approve" title="Accept" onclick="acceptOffer(this)"><i class="fa-solid fa-check"></i></button>
                    <button class="z-btn z-btn-reject" title="Reject" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                    <button class="z-btn z-btn-cart" title="Add to Cart" onclick="addToCart(this,'Tata Tea Premium 1kg','₹235.00','Box: 12')"><i class="fa-solid fa-cart-plus"></i></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Mobile Cards -->
      <div class="d-md-none">
        <div class="z-mobile-card p-3">
          <div class="d-flex gap-3 align-items-start">
            <div class="z-card-img-placeholder">🍪</div>
            <div class="flex-grow-1">
              <div class="prod-name mb-1">Britannia NutriChoice Biscuit 100g</div>
              <div class="prod-pattern mb-2">Loose</div>
              <div class="d-flex gap-3 mb-2">
                <div><span class="z-price-tag">Quoted</span><div class="z-price-val" style="text-decoration:line-through;color:var(--zonik-muted)">₹26.00</div></div>
                <div><span class="z-price-tag">Final</span><div class="z-price-val">₹24.00</div></div>
                <div><span class="z-price-tag">Change</span><div class="z-price-val change-up">+9.09%</div></div>
              </div>
              <div class="action-btns">
                <button class="z-btn z-btn-approve" onclick="acceptOffer(this)"><i class="fa-solid fa-check"></i></button>
                <button class="z-btn z-btn-reject" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                <button class="z-btn z-btn-cart" onclick="addToCart(this,'Britannia NutriChoice Biscuit 100g','₹24.00','Loose')"><i class="fa-solid fa-cart-plus"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="z-mobile-card p-3">
          <div class="d-flex gap-3 align-items-start">
            <div class="z-card-img-placeholder">🍵</div>
            <div class="flex-grow-1">
              <div class="prod-name mb-1">Tata Tea Premium 1kg</div>
              <div class="prod-pattern mb-2">Box: 12</div>
              <div class="d-flex gap-3 mb-2">
                <div><span class="z-price-tag">Quoted</span><div class="z-price-val" style="text-decoration:line-through;color:var(--zonik-muted)">₹250.00</div></div>
                <div><span class="z-price-tag">Final</span><div class="z-price-val">₹235.00</div></div>
                <div><span class="z-price-tag">Change</span><div class="z-price-val change-down">−6.00%</div></div>
              </div>
              <div class="action-btns">
                <button class="z-btn z-btn-approve" onclick="acceptOffer(this)"><i class="fa-solid fa-check"></i></button>
                <button class="z-btn z-btn-reject" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                <button class="z-btn z-btn-cart" onclick="addToCart(this,'Tata Tea Premium 1kg','₹235.00','Box: 12')"><i class="fa-solid fa-cart-plus"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /inner-pane-quotes -->

    <!-- ── INNER PANE: PRICE LIST ── -->
    <div id="inner-pane-pricelist" style="display:none;">

      <div class="section-header mt-n2 mb-3">
        <div class="z-search-wrap" style="width:260px;">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" placeholder="Search product…" oninput="filterTable()">
        </div>
      </div>

      <!-- Desktop Table -->
      <div class="d-none d-md-block">
        <div class="product-table-new-wrap">
          <table class="product-table-new" id="priceTable">
            <thead>
              <tr>
                <th>#</th><th>Product</th><th>Order Type</th>
                <th>MRP</th><th>Approved Price</th><th>Discount</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🍝</div><div><div class="prod-name">Reggia Penne Rigate 500g</div></div></div></td>
                <td><span class="pattern-badge badge-loose">Loose</span></td>
                <td>₹160.00</td>
                <td><span class="price-main price-approved">₹140.00</span></td>
                <td><span style="color:var(--zonik-green);font-weight:800;">12.50%</span></td>
                <td>
                  <div class="action-btns">
                    <div class="approved-tick"><i class="fa-solid fa-check"></i></div>
                    <button class="z-btn z-btn-cart" onclick="addToCart(this,'Reggia Penne Rigate 500g','₹140.00','Loose')"><i class="fa-solid fa-cart-plus"></i></button>
                    <button class="z-btn z-btn-remove" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🌻</div><div><div class="prod-name">Freedom Sunflower Oil 1L Pouch</div></div></div></td>
                <td><span class="pattern-badge badge-other">Pouch</span></td>
                <td>₹162.00</td>
                <td><span class="price-main price-approved">₹145.00</span></td>
                <td><span style="color:var(--zonik-green);font-weight:800;">10.49%</span></td>
                <td>
                  <div class="action-btns">
                    <div class="approved-tick"><i class="fa-solid fa-check"></i></div>
                    <button class="z-btn z-btn-cart" onclick="addToCart(this,'Freedom Sunflower Oil 1L','₹145.00','Pouch')"><i class="fa-solid fa-cart-plus"></i></button>
                    <button class="z-btn z-btn-remove" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🍚</div><div><div class="prod-name">India Gate Classic Basmati Rice 1kg</div></div></div></td>
                <td><span class="pattern-badge badge-box">Box: 6</span></td>
                <td>₹150.00</td>
                <td><span class="price-main price-approved">₹118.00</span></td>
                <td><span style="color:var(--zonik-green);font-weight:800;">21.33%</span></td>
                <td>
                  <div class="action-btns">
                    <div class="approved-tick"><i class="fa-solid fa-check"></i></div>
                    <button class="z-btn z-btn-cart" onclick="addToCart(this,'India Gate Basmati Rice 1kg','₹118.00','Box: 6')"><i class="fa-solid fa-cart-plus"></i></button>
                    <button class="z-btn z-btn-remove" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>4</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🍅</div><div><div class="prod-name">Kissan Fresh Tomato Ketchup 1kg</div></div></div></td>
                <td><span class="pattern-badge badge-other">Bottle</span></td>
                <td>₹115.00</td>
                <td><span class="price-main price-approved">₹98.00</span></td>
                <td><span style="color:var(--zonik-green);font-weight:800;">14.78%</span></td>
                <td>
                  <div class="action-btns">
                    <div class="approved-tick"><i class="fa-solid fa-check"></i></div>
                    <button class="z-btn z-btn-cart" onclick="addToCart(this,'Kissan Tomato Ketchup 1kg','₹98.00','Bottle')"><i class="fa-solid fa-cart-plus"></i></button>
                    <button class="z-btn z-btn-remove" onclick="removeRow(this)"><i class="fa-solid fa-xmark"></i></button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>5</td>
                <td><div class="prod-cell"><div class="prod-img-placeholder">🧂</div><div><div class="prod-name">Tata Iodized Salt 1kg</div></div></div></td>
                <td><span class="pattern-badge badge-box">Pack: 12</span></td>
                <td>₹24.00</td>
                <td><span class="price-main price-approved">₹20.00</span></td>
                <td><span style="color:var(--zonik-green);font-weight:800;">16.67%</span></td>
                <td>
                  <span class="text-danger fw-bold" style="font-size:.8rem;">Unavailable</span>
                </td>
              </tr>
            </tbody>
          </table>
          <div id="noResultsMessage" class="text-center py-4 text-danger" style="display:none;">
            <p class="fw-bold mb-0">Product not found.</p>
          </div>
        </div>
      </div>

      <!-- Mobile Cards -->
      <div class="d-md-none" id="priceListMobile">
        <div class="z-mobile-card p-3 mb-2">
          <div class="z-ribbon">12% OFF</div>
          <div class="d-flex gap-3 align-items-start">
            <div class="z-card-img-placeholder">🍝</div>
            <div class="flex-grow-1">
              <div class="prod-name mb-1">Reggia Penne Rigate 500g</div>
              <div class="prod-pattern mb-2">Loose</div>
              <div class="d-flex gap-3 mb-2">
                <div><span class="z-price-tag">Price</span><div class="z-price-val price-approved">₹140.00</div></div>
                <div><span class="z-price-tag">MRP</span><div class="z-price-val">₹160.00</div></div>
              </div>
              <div class="action-btns">
                <div class="approved-tick"><i class="fa-solid fa-check"></i></div>
                <button class="z-btn z-btn-cart" onclick="addToCart(this,'Reggia Penne Rigate 500g','₹140.00','Loose')"><i class="fa-solid fa-cart-plus"></i></button>
                <button class="z-btn z-btn-remove" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="z-mobile-card p-3 mb-2">
          <div class="z-ribbon">10% OFF</div>
          <div class="d-flex gap-3 align-items-start">
            <div class="z-card-img-placeholder">🌻</div>
            <div class="flex-grow-1">
              <div class="prod-name mb-1">Freedom Sunflower Oil 1L Pouch</div>
              <div class="prod-pattern mb-2">Pouch</div>
              <div class="d-flex gap-3 mb-2">
                <div><span class="z-price-tag">Price</span><div class="z-price-val price-approved">₹145.00</div></div>
                <div><span class="z-price-tag">MRP</span><div class="z-price-val">₹162.00</div></div>
              </div>
              <div class="action-btns">
                <div class="approved-tick"><i class="fa-solid fa-check"></i></div>
                <button class="z-btn z-btn-cart" onclick="addToCart(this,'Freedom Sunflower Oil 1L','₹145.00','Pouch')"><i class="fa-solid fa-cart-plus"></i></button>
                <button class="z-btn z-btn-remove" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="z-mobile-card p-3 mb-2">
          <div class="z-ribbon">21% OFF</div>
          <div class="d-flex gap-3 align-items-start">
            <div class="z-card-img-placeholder">🍚</div>
            <div class="flex-grow-1">
              <div class="prod-name mb-1">India Gate Basmati Rice 1kg</div>
              <div class="prod-pattern mb-2">Box: 6</div>
              <div class="d-flex gap-3 mb-2">
                <div><span class="z-price-tag">Price</span><div class="z-price-val price-approved">₹118.00</div></div>
                <div><span class="z-price-tag">MRP</span><div class="z-price-val">₹150.00</div></div>
              </div>
              <div class="action-btns">
                <div class="approved-tick"><i class="fa-solid fa-check"></i></div>
                <button class="z-btn z-btn-cart" onclick="addToCart(this,'India Gate Basmati Rice 1kg','₹118.00','Box: 6')"><i class="fa-solid fa-cart-plus"></i></button>
                <button class="z-btn z-btn-remove" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /inner-pane-pricelist -->

  </div><!-- /pane-pricelist -->


  <!-- ════════════════════════════════════
       PANE: ORDER CART
       ════════════════════════════════════ -->
  <div class="z-tab-pane" id="pane-ordercart">

    <div class="section-header">
      <div>
        <div class="section-title">Order <span style="color:var(--zonik-orange)">Cart</span></div>
        <div class="section-subtitle">Review quantities and checkout.</div>
      </div>
    </div>

    <div id="emptyCartMsg" style="display:none;">
      <div class="empty-state">
        <i class="fa-solid fa-bag-shopping me-2"></i>
        Your order cart is empty. Add items from your price list.
      </div>
    </div>

    <div class="row" id="cartContentRow">
      <!-- Left: Cart Items -->
      <div class="col-lg-8 mb-3">
        <!-- Desktop -->
        <div class="d-none d-md-block">
          <div class="product-table-new-wrap">
            <table class="product-table-new" id="cartTable">
              <thead>
                <tr><th>#</th><th>Product</th><th>Order Qty</th><th>Total Qty</th><th>Total (Basic)</th><th></th></tr>
              </thead>
              <tbody id="cartTableBody">
                <tr id="cartRow1">
                  <td>1</td>
                  <td>
                    <div class="prod-cell">
                      <div class="prod-img-placeholder">🍝</div>
                      <div><div class="prod-name">Reggia Penne Rigate 500g</div><div class="prod-pattern">₹140.00 · Loose</div></div>
                    </div>
                  </td>
                  <td>
                    <div class="z-qty">
                      <button onclick="qtyChange('cartRow1',-1)">−</button>
                      <input type="number" id="qty1" class="cart-qty" value="1" min="1">
                      <button onclick="qtyChange('cartRow1',1)">+</button>
                    </div>
                  </td>
                  <td class="text-center" id="tqty1">1</td>
                  <td class="text-center" id="tamt1">₹140.00</td>
                  <td><button class="z-btn z-btn-remove" onclick="removeCartRow('cartRow1',140)"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
                <tr id="cartRow2">
                  <td>2</td>
                  <td>
                    <div class="prod-cell">
                      <div class="prod-img-placeholder">🌻</div>
                      <div><div class="prod-name">Freedom Sunflower Oil 1L Pouch</div><div class="prod-pattern">₹145.00 · Pouch</div></div>
                    </div>
                  </td>
                  <td>
                    <div class="z-qty">
                      <button onclick="qtyChange('cartRow2',-1)">−</button>
                      <input type="number" id="qty2" class="cart-qty" value="2" min="1">
                      <button onclick="qtyChange('cartRow2',1)">+</button>
                    </div>
                  </td>
                  <td class="text-center" id="tqty2">2</td>
                  <td class="text-center" id="tamt2">₹290.00</td>
                  <td><button class="z-btn z-btn-remove" onclick="removeCartRow('cartRow2',290)"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
                <tr id="cartRow3">
                  <td>3</td>
                  <td>
                    <div class="prod-cell">
                      <div class="prod-img-placeholder">🍚</div>
                      <div><div class="prod-name">India Gate Basmati Rice 1kg</div><div class="prod-pattern">₹118.00 · Box: 6</div></div>
                    </div>
                  </td>
                  <td>
                    <div class="z-qty">
                      <button onclick="qtyChange('cartRow3',-1)">−</button>
                      <input type="number" id="qty3" class="cart-qty" value="1" min="1">
                      <button onclick="qtyChange('cartRow3',1)">+</button>
                    </div>
                  </td>
                  <td class="text-center" id="tqty3">6</td>
                  <td class="text-center" id="tamt3">₹708.00</td>
                  <td><button class="z-btn z-btn-remove" onclick="removeCartRow('cartRow3',708)"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Mobile -->
        <div class="d-md-none" id="cartMobileList">
          <div class="z-mobile-card p-3 mb-3" id="cartCard1">
            <div class="d-flex gap-3 align-items-start">
              <div class="z-card-img-placeholder">🍝</div>
              <div class="flex-grow-1">
                <div class="prod-name mb-1">Reggia Penne Rigate 500g</div>
                <div class="prod-pattern mb-2">₹140.00 · Loose</div>
                <div class="z-qty mb-2">
                  <button onclick="qtyChange('cartCard1',-1,true)">−</button>
                  <input type="number" class="cart-qty-mob" value="1" min="1">
                  <button onclick="qtyChange('cartCard1',1,true)">+</button>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <span class="z-price-tag">Total: <strong>₹140.00</strong></span>
                  <button class="z-btn z-btn-remove ms-auto" onclick="removeCartRow('cartCard1',140,true)"><i class="fa-solid fa-trash"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Order Summary -->
      <div class="col-lg-4 mb-3">
        <div class="cart-summary">
          <h5>Order Summary</h5>
          <div class="summary-row">
            <span class="lbl">Subtotal (Basic)</span>
            <span class="val" id="summSubtotal">₹1,138.00</span>
          </div>
          <div class="summary-row">
            <span class="lbl">Product Discount</span>
            <span class="val" style="color:var(--zonik-green)">− ₹0.00</span>
          </div>
          <div class="summary-row">
            <span class="lbl">GST + Cess</span>
            <span class="val">+ ₹<span id="summGst">204.84</span></span>
          </div>
          <div class="summary-row grand mt-2">
            <span class="lbl fw-bold">Grand Total</span>
            <span class="val" id="summGrand">₹1,342.84</span>
          </div>
          <div class="mt-1 mb-3" style="font-size:.72rem;color:var(--zonik-muted);text-align:right;">(inclusive all taxes)</div>

          <!-- Coupon -->
          <div class="mb-3">
            <div class="fw-800 mb-2" style="font-size:.85rem;">PROMO CODE</div>
            <input class="form-control mb-2" style="font-size:.85rem;" placeholder="Coupon code…" id="couponInput">
            <ul class="couponList">
              <li class="d-flex justify-content-between align-items-center">
                <div>
                  <strong>SAVE50</strong> — Up to ₹50 Off
                  <br><span style="font-size:.75rem;color:var(--zonik-muted);">Min order ₹500 · Valid till Dec 2025</span>
                </div>
                <button class="coupon-apply" onclick="applyCoupon('SAVE50')">Apply</button>
              </li>
              <li class="d-flex justify-content-between align-items-center">
                <div>
                  <strong>FIRST100</strong> — Up to ₹100 Off
                  <br><span style="font-size:.75rem;color:var(--zonik-muted);">Min order ₹1000 · Valid till Dec 2025</span>
                </div>
                <button class="coupon-apply" onclick="applyCoupon('FIRST100')">Apply</button>
              </li>
            </ul>
          </div>

          <button class="red-btn w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
            <i class="fa-solid fa-bag-shopping me-2"></i>Checkout
          </button>
        </div>
      </div>
    </div>

  </div><!-- /pane-ordercart -->

</section>


<!-- ════════════════════════════════════
     MODALS
     ════════════════════════════════════ -->

<!-- Checkout / Select Outlet Modal -->
<div class="modal fade z-modal" id="checkoutModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Outlet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="product-table-new" id="outletTable">
            <thead>
              <tr>
                <th></th><th class="d-none d-md-table-cell">User Name</th>
                <th>Outlet Name</th><th>Location</th><th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr onclick="selectOutlet(1)">
                <td><input type="radio" name="outlet" id="rowData1"></td>
                <td class="d-none d-md-table-cell">Rahul Sharma</td>
                <td>Sharma General Store</td>
                <td>Andheri West, Mumbai</td>
                <td><span style="color:var(--zonik-green);font-weight:700;font-size:.82rem;"><i class="fa-solid fa-circle-check me-1"></i>Verified</span></td>
              </tr>
              <tr onclick="selectOutlet(2)">
                <td><input type="radio" name="outlet" id="rowData2"></td>
                <td class="d-none d-md-table-cell">Priya Mehta</td>
                <td>Mehta Kirana &amp; Co.</td>
                <td>Borivali East, Mumbai</td>
                <td><span style="color:var(--zonik-red);font-weight:700;font-size:.82rem;"><i class="fa-solid fa-circle-xmark me-1"></i>Unverified</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button class="red-btn outline" onclick="openAddOutlet()">
          <i class="fa-solid fa-plus me-1"></i>Add Outlet
        </button>
        <button id="checkoutProceedBtn" class="red-btn" disabled>
          <i class="fa-solid fa-money-bill me-1"></i>Checkout
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Add Outlet Modal -->
<div class="modal fade z-modal" id="addOutletModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Outlet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6"><input type="text" class="form-control" placeholder="Your Name" required></div>
          <div class="col-md-6"><input type="text" class="form-control" placeholder="Outlet Name" required></div>
          <div class="col-md-6"><input type="tel" class="form-control" placeholder="Mobile Number" maxlength="10" required></div>
          <div class="col-md-6"><input type="email" class="form-control" placeholder="abc@gmail.com" required></div>
          <div class="col-md-6"><input type="text" class="form-control" placeholder="Location" required></div>
          <div class="col-md-6"><input type="text" class="form-control" placeholder="Pincode" required></div>
        </div>
        <button class="red-btn w-100 mt-4" data-bs-dismiss="modal">Add Outlet</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── TAB SWITCHING ── */
function switchTab(e, tab) {
  if (e) e.preventDefault();
  document.querySelectorAll('.order-tab').forEach(t => t.classList.remove('active'));
  if (e) {
    const id = e.currentTarget.id.replace('mob-','');
    document.getElementById(id)?.classList.add('active');
    document.getElementById('mob-' + id)?.classList.add('active');
    e.currentTarget.classList.add('active');
  }
  document.querySelectorAll('.z-tab-pane').forEach(p => p.classList.remove('active'));
  document.getElementById('pane-' + tab)?.classList.add('active');
}

/* ── INNER TABS (Quotes / Price List) ── */
function switchInnerTab(tab) {
  const btnQuotes    = document.getElementById('inner-tab-quotes');
  const btnPricelist = document.getElementById('inner-tab-pricelist');
  const paneQuotes   = document.getElementById('inner-pane-quotes');
  const panePL       = document.getElementById('inner-pane-pricelist');
  const title        = document.getElementById('inner-section-title');
  const sub          = document.getElementById('inner-section-sub');
  const statTotal    = document.getElementById('stat-total');
  const statApproved = document.getElementById('stat-approved');
  const statRejected = document.getElementById('stat-rejected');

  if (tab === 'quotes') {
    btnQuotes.className    = 'inner-tab active-quotes';
    btnPricelist.className = 'inner-tab';
    paneQuotes.style.display = ''; panePL.style.display = 'none';
    title.innerHTML = 'Quotes';
    sub.textContent = 'Review and take action on quoted prices.';
    statTotal.textContent = '7'; statApproved.textContent = '5'; statRejected.textContent = '2';
  } else {
    btnPricelist.className = 'inner-tab active-pricelist';
    btnQuotes.className    = 'inner-tab';
    panePL.style.display = ''; paneQuotes.style.display = 'none';
    title.innerHTML = 'Price <span style="color:var(--zonik-blue)">List</span>';
    sub.textContent = 'Approved prices — add to cart or remove.';
    statTotal.textContent = '5'; statApproved.textContent = '5'; statRejected.textContent = '0';
  }
}


function animateRemove(el) {
  el.style.transition = 'opacity .25s';
  el.style.opacity = '0';
  setTimeout(() => el.remove(), 280);
}

/* ── REMOVE ROW ── */
function removeRow(btn, url = null) {

  const el = btn.closest('tr') || btn.closest('.z-mobile-card') || btn.closest('.col-12');
  if (!el) return;

  // 👉 No URL → only UI remove
  if (!url) {
    animateRemove(el);
    return;
  }

  if (!confirm("Remove this item?")) return;

  fetch(url, {
    method: "DELETE",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Accept": "application/json"
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      animateRemove(el);
    }
  });
}

/* ── ACCEPT OFFER (replace buttons with green tick, then fade row) ── */
function acceptOffer(btn) {
  const cell = btn.closest('td') || btn.closest('.action-btns');
  cell.innerHTML = `<div class="approved-tick"><i class="fa-solid fa-check"></i></div>`;
  const row = btn.closest('tr');
  if (row) {
    setTimeout(() => {
      row.style.transition = 'opacity .3s';
      row.style.opacity = '0';
      setTimeout(() => row.remove(), 320);
    }, 600);
  }
}

/* ── ADD TO CART (from quotes or price list) ── */
let cartSubtotal = 1138;
function addToCart(btn, name, price, type) {
  const priceNum = parseFloat(price.replace('₹',''));
  cartSubtotal += priceNum;
  updateSummary();

  // Visual feedback
  btn.innerHTML = '<i class="fa-solid fa-check"></i>';
  btn.classList.remove('z-btn-cart');
  btn.classList.add('z-btn-approve');
  btn.style.pointerEvents = 'none';
  setTimeout(() => {
    btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i>';
    btn.classList.remove('z-btn-approve');
    btn.classList.add('z-btn-cart');
    btn.style.pointerEvents = '';
  }, 1200);

  // Update cart badge
  document.querySelectorAll('#tab-ordercart .tab-badge, #mob-tab-ordercart .tab-badge').forEach(badge => {
    badge.textContent = parseInt(badge.textContent || 0) + 1;
  });
}

/* ── CART QTY ── */
const cartPrices = { cartRow1: 140, cartRow2: 145, cartRow3: 118 };
function qtyChange(rowId, delta, isMob) {
  const inp = isMob
    ? document.querySelector('#' + rowId + ' .cart-qty-mob')
    : document.querySelector('#' + rowId + ' .cart-qty');
  if (!inp) return;
  let v = Math.max(1, parseInt(inp.value) + delta);
  inp.value = v;
  const price = cartPrices[rowId] || 0;
  const totalEl = document.getElementById('tamt' + rowId.replace('cartRow',''));
  if (totalEl) totalEl.textContent = '₹' + (price * v).toFixed(2);
  recalcSubtotal();
}

function recalcSubtotal() {
  let sub = 0;
  document.querySelectorAll('#cartTableBody tr').forEach(row => {
    const amtEl = row.querySelector('[id^="tamt"]');
    if (amtEl) {
      const txt = amtEl.textContent.replace('₹','').replace(',','');
      sub += parseFloat(txt) || 0;
    }
  });
  cartSubtotal = sub;
  updateSummary();
}

function updateSummary() {
  const gst = cartSubtotal * 0.18;
  const grand = cartSubtotal + gst;
  document.getElementById('summSubtotal').textContent = '₹' + cartSubtotal.toLocaleString('en-IN', {minimumFractionDigits:2});
  document.getElementById('summGst').textContent = gst.toFixed(2);
  document.getElementById('summGrand').textContent = '₹' + grand.toLocaleString('en-IN', {minimumFractionDigits:2});
}

function removeCartRow(rowId, amt, isMob) {
  const el = document.getElementById(rowId);
  if (!el) return;
  el.style.transition = 'opacity .25s';
  el.style.opacity = '0';
  setTimeout(() => { el.remove(); recalcSubtotal(); }, 280);
}

/* ── SEARCH / FILTER (Price List tab) ── */
function filterTable() {
  const val = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
  let found = false;
  document.querySelectorAll('#priceTable tbody tr').forEach(row => {
    const match = row.textContent.toLowerCase().includes(val);
    row.style.display = match ? '' : 'none';
    if (match) found = true;
  });
  document.getElementById('noResultsMessage').style.display = (val && !found) ? 'block' : 'none';
}

/* ── COUPON ── */
function applyCoupon(code) {
  document.getElementById('couponInput').value = code;
}

/* ── OUTLET SELECTION ── */
function selectOutlet(id) {
  const radio = document.getElementById('rowData' + id);
  if (radio && !radio.disabled) {
    radio.checked = true;
    document.getElementById('checkoutProceedBtn').removeAttribute('disabled');
  }
}

function openAddOutlet() {
  bootstrap.Modal.getInstance(document.getElementById('checkoutModal'))?.hide();
  setTimeout(() => new bootstrap.Modal(document.getElementById('addOutletModal')).show(), 400);
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('tab-enquiry')?.classList.add('active');
  
});
</script>

<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>


<script>


function syncMonthlyConsumption() {

    $('[id^="monthlyconsumption"]').each(function () {
        let id = $(this).attr('id').replace('monthlyconsumption', '');
        
        let desktop = $('#monthlyconsumption' + id + ':visible');
        let mobile  = $('[id="monthlyconsumption' + id + '"]:hidden');

       
        if (desktop.length && desktop.val().trim() !== "") {
            mobile.val(desktop.val().trim());
        }

       
        if (mobile.length && mobile.val().trim() !== "") {
            desktop.val(mobile.val().trim());
        }
    });
}


$('#submitEnquiryButton').on('click', function() {
 
    if (!$(this).attr('disabled')) {
     
        submitEnquiry();

     
        $(this).attr('disabled', true).css('cursor', 'not-allowed');

       
        $(this).text('Processing...');
    }
});

function submitEnquiry() {
    
  syncMonthlyConsumption();
  
  var formData = $('#enquiryForm').serialize();
    $.ajax({
        url: '/enquiry/store',
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response && response.success) {
                Swal.fire({
                    title: "Your enquiry has been submitted!",
                    text: "We'll get back to you as soon as possible.",
                    icon: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Okay",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/homepage';
                    }
                });

            } else {
                Swal.fire({
                    title: "Oops!",
                    text: "Something went wrong. Please try again later.",
                    icon: "error"
                });
            }
        },
        error: function(xhr, status, error) {
            var errorMessage = xhr.responseText || 'Internal Server Error';
            Swal.fire({
                title: "Error",
                text: errorMessage,
                icon: "error"
            });
        }
    });
}


$(document).ready(function() {
    $('.checkout_btn_datewise').click(function() {
        var datewisecheckout = $('#datewisecheckout').val();
        localStorage.setItem('datewisecheckout', datewisecheckout);
        $('#checkout').modal('show');
        $('#checkout1').modal('hide');
    });
});


    var noCount = {{ $noCount }};
    var yesCount = {{ $yesCount }};
    var activeSection = null; // Track the active section (Offer or Reoffer)
</script>

@endsection