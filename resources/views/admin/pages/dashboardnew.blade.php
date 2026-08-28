@extends('admin.layouts.appnew')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap');

body,
.container-scroller,
.page-body-wrapper,
.main-panel,
.content-wrapper {
    background: #f0f2f8 !important;
}

:root {
  --bg:        #f0f2f8;
  --surface:   #ffffff;
  --surface2:  #f7f8fc;
  --border:    rgba(0,0,0,0.07);
  --borderh:   rgba(0,0,0,0.14);
  --text:      #1a1f36;
  --text2:     #4a5568;
  --muted:     #8a93a8;

  --blue:      #3b82f6;
  --cyan:      #06b6d4;
  --green:     #10b981;
  --violet:    #8b5cf6;
  --amber:     #f59e0b;
  --rose:      #f43f5e;
  --indigo:    #6366f1;
  --teal:      #14b8a6;
  --orange:    #f97316;

  --g-blue:    rgba(59,130,246,0.08);
  --g-cyan:    rgba(6,182,212,0.08);
  --g-green:   rgba(16,185,129,0.08);
  --g-violet:  rgba(139,92,246,0.08);
  --g-amber:   rgba(245,158,11,0.08);
  --g-rose:    rgba(244,63,94,0.08);
  --g-indigo:  rgba(99,102,241,0.08);
  --g-teal:    rgba(20,184,166,0.08);
}

.dash {
  font-family: 'DM Sans', sans-serif;
  color: var(--text);
  padding: 24px 28px 48px;
  background: var(--bg);
}

.d-topbar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 24px; padding-bottom: 18px;
  border-bottom: 2px solid var(--border);
}
.d-page-sup {
  font-size: 10px; font-weight: 700; letter-spacing: 2px;
  text-transform: uppercase; color: var(--muted); margin-bottom: 4px;
}
.d-page-h1 {
  font-family: sans-serif; font-weight: 800;
  font-size: 26px; letter-spacing: -0.6px; margin: 0; color: var(--text);
}
.d-topbar-right { display: flex; align-items: center; gap: 12px; }
.d-date {
  font-size: 12px; color: var(--muted); font-weight: 500;
}
.d-live-pill {
  display: flex; align-items: center; gap: 7px;
  padding: 6px 16px;
  background: rgba(16,185,129,0.1);
  border: 1.5px solid rgba(16,185,129,0.3);
  border-radius: 100px;
  font-size: 11px; font-weight: 700; letter-spacing: 1px;
  color: #059669;
}
.d-live-dot {
  width: 7px; height: 7px;
  background: #10b981; border-radius: 50%;
  box-shadow: 0 0 6px #10b981;
  animation: livePulse 1.6s ease infinite;
}
@keyframes livePulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.35;transform:scale(.65)} }

.d-section {
  margin-bottom: 30px;
}
.d-section-head {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 14px;
}
.d-section-tag {
  font-size: 10px; font-weight: 700; letter-spacing: 1.5px;
  text-transform: uppercase; padding: 4px 12px;
  border-radius: 100px;
}
.d-section-tag.front  { background: var(--g-blue);  color: #185fa5; }
.d-section-tag.back   { background: var(--g-rose);  color: #99351d; }
.d-section-tag.core   { background: var(--g-green); color: #27500a; }
.d-section-tag.process { background: var(--g-indigo); color: #3730a3; }
.d-section-title {
  font-family:'Syne',sans-serif; font-weight:700; font-size:16px;
  color: var(--text);
}
.d-section-line {
  flex: 1; height: 1.5px; background: var(--border);
}

.d-stats {
  display: grid;
  grid-template-columns: repeat(4,1fr);
  gap: 14px;
}

.sc {
  background: var(--surface);
  border: 1.5px solid var(--border);
  border-radius: 16px;
  padding: 20px 22px;
  position: relative; overflow: hidden;
  opacity: 0; transform: translateY(24px);
  animation: fadeUp .55s cubic-bezier(.16,1,.3,1) forwards;
  transition: transform .3s cubic-bezier(.34,1.56,.64,1), border-color .25s, box-shadow .3s;
  cursor: default;
}
.sc:hover {
  transform: translateY(-5px) scale(1.012);
  border-color: var(--borderh);
  box-shadow: 0 12px 40px rgba(0,0,0,0.09);
}

.sc:nth-child(1){animation-delay:.04s} .sc:nth-child(2){animation-delay:.09s}
.sc:nth-child(3){animation-delay:.13s} .sc:nth-child(4){animation-delay:.17s}
.sc:nth-child(5){animation-delay:.21s} .sc:nth-child(6){animation-delay:.25s}
.sc:nth-child(7){animation-delay:.29s} .sc:nth-child(8){animation-delay:.33s}

@keyframes fadeUp { to { opacity:1; transform:translateY(0); } }

.sc::after {
  content:''; position:absolute; top:0; left:0;
  width:4px; height:100%; border-radius:16px 0 0 16px;
}
.sc.s1::after { background: var(--blue); }
.sc.s2::after { background: var(--cyan); }
.sc.s3::after { background: var(--violet); }
.sc.s4::after { background: var(--amber); }
.sc.s5::after { background: var(--green); }
.sc.s6::after { background: var(--teal); }
.sc.s7::after { background: var(--indigo); }
.sc.s8::after { background: var(--rose); }
.sc.s9::after { background: var(--orange); }
.sc.s10::after { background: var(--rose); }

.sc.s1:hover { box-shadow:0 12px 40px rgba(59,130,246,0.15); }
.sc.s2:hover { box-shadow:0 12px 40px rgba(6,182,212,0.15); }
.sc.s3:hover { box-shadow:0 12px 40px rgba(139,92,246,0.15); }
.sc.s4:hover { box-shadow:0 12px 40px rgba(245,158,11,0.15); }
.sc.s5:hover { box-shadow:0 12px 40px rgba(16,185,129,0.15); }
.sc.s6:hover { box-shadow:0 12px 40px rgba(20,184,166,0.15); }
.sc.s7:hover { box-shadow:0 12px 40px rgba(99,102,241,0.15); }
.sc.s8:hover { box-shadow:0 12px 40px rgba(244,63,94,0.15); }
.sc.s9:hover { box-shadow:0 12px 40px rgba(249,115,22,0.15); }
.sc.s10:hover { box-shadow:0 12px 40px rgba(244,63,94,0.15); }

.sc-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }

.sc-ico {
  width:44px; height:44px; border-radius:12px;
  display:grid; place-items:center; font-size:20px; flex-shrink:0;
}
.sc-ico.s1{background:var(--g-blue)}
.sc-ico.s2{background:var(--g-cyan)}
.sc-ico.s3{background:var(--g-violet)}
.sc-ico.s4{background:var(--g-amber)}
.sc-ico.s5{background:var(--g-green)}
.sc-ico.s6{background:var(--g-teal)}
.sc-ico.s7{background:var(--g-indigo)}
.sc-ico.s8{background:var(--g-rose)}
.sc-ico.s9{background:var(--g-amber)}
.sc-ico.s10{background:var(--g-rose)}

.sc-tag {
  font-size:10px; font-weight:700; padding:3px 10px;
  border-radius:100px; letter-spacing:.3px; white-space:nowrap;
}
.sc-tag.up { background:rgba(16,185,129,0.1); color:#059669; }
.sc-tag.dn { background:rgba(244,63,94,0.1);  color:#e11d48; }
.sc-tag.nt { background:rgba(100,116,139,0.1); color:#64748b; }

.sc-val {
  font-family:sans-serif; font-weight:800;
  font-size:30px; letter-spacing:-1px; line-height:1;
  margin-bottom:5px; color: var(--text);
}
.sc-lbl {
  font-size:11px; color:var(--muted); font-weight:500;
  letter-spacing:.4px; text-transform:uppercase; line-height:1.5;
}
.sc-bar { margin-top:14px; height:4px; background:var(--bg); border-radius:100px; overflow:hidden; }
.sc-bar-f { height:100%; border-radius:100px; width:0%; transition:width 1.8s cubic-bezier(.16,1,.3,1); }

.sc-dual-row {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}
.sc-dual-item {
  flex: 1;
  background: var(--surface2);
  border-radius: 10px;
  padding: 8px 10px;
}
.sc-dual-label {
  font-size: 9px;
  color: var(--muted);
  font-weight: 600;
  letter-spacing: .3px;
  text-transform: uppercase;
  margin-bottom: 2px;
}
.sc-dual-val {
  font-family: sans-serif;
  font-weight: 800;
  font-size: 18px;
  color: var(--text);
  line-height: 1;
}

.d-row2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

.cc {
  background:var(--surface);
  border:1.5px solid var(--border);
  border-radius:16px; overflow:hidden;
  opacity:0;
  animation:fadeUp .65s cubic-bezier(.16,1,.3,1) .45s forwards;
  transition:border-color .25s, box-shadow .3s;
}
.cc:hover {
  border-color:var(--borderh);
  box-shadow:0 8px 32px rgba(0,0,0,0.08);
}
.cc-head {
  padding:14px 18px 12px;
  border-bottom:1.5px solid var(--border);
  display:flex; align-items:center; justify-content:space-between; gap:8px;
  background: var(--surface2);
}
.cc-title {
  font-family:'Syne',sans-serif; font-weight:700;
  font-size:13px; color:var(--text); flex:1; min-width:0;
}
.cc-type {
  font-size:9px; font-weight:700; padding:2px 9px;
  border-radius:100px; background:var(--bg);
  color:var(--muted); border:1.5px solid var(--border);
  letter-spacing:.8px; text-transform:uppercase; white-space:nowrap; flex-shrink:0;
}
.cc-body { padding:16px 18px 18px; background:var(--surface); }

@media(max-width:1300px){ .d-stats{grid-template-columns:repeat(2,1fr)} }
@media(max-width:992px) { .d-row2{grid-template-columns:1fr} }
@media(max-width:576px) {
  .d-stats{grid-template-columns:1fr 1fr}
  .dash{padding:14px 14px 32px}
  .sc-val{font-size:24px}
}

.d-stats a { color: inherit; text-decoration: none; display: block; }
.d-stats a .sc { cursor: pointer; }
</style>

<div class="dash">

  <div class="d-topbar">
    <div>
      <div class="d-page-sup">Admin · Real-time Overview</div>
      <h2 class="d-page-h1">Performance Dashboard</h2>
    </div>
    <div class="d-topbar-right">
      <div class="d-date" id="dashDate"></div>
      <div class="d-live-pill">
        <div class="d-live-dot"></div>
        LIVE
      </div>
    </div>
  </div>

@if(in_array('products', $allowedSections))
  <div class="d-section">
    <div class="d-section-head">
      <span class="d-section-tag core">Products</span>
      <div class="d-section-line"></div>
    </div>

    <div class="d-stats">

      <div class="sc s1">
        <div class="sc-top">
          <div class="sc-ico s1">✅</div>
          <span class="sc-tag up">↑ Active</span>
        </div>
        <div class="sc-val dc" data-val="{{ $activeProductsCount }}">0</div>
        <div class="sc-lbl">No. of Active Products</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--blue);width:{{ $productsCount > 0 ? round(($activeProductsCount / $productsCount) * 100) : 0 }}%"></div></div>
      </div>

      <div class="sc s2">
        <div class="sc-top">
          <div class="sc-ico s2">⛔</div>
          <span class="sc-tag dn">↓ Inactive</span>
        </div>
        <div class="sc-val dc" data-val="{{ $inactiveProductsCount }}">0</div>
        <div class="sc-lbl">No. of Inactive Products</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--cyan);width:{{ $productsCount > 0 ? round(($inactiveProductsCount / $productsCount) * 100) : 0 }}%"></div></div>
      </div>

    </div>
  </div>
@endif  
  
 @if(in_array('sales', $allowedSections))
    <div class="d-section">
  <div class="d-section-head">
    <span class="d-section-tag core">Sales</span>
    <div class="d-section-line"></div>
  </div>

  <div class="d-stats">

<a href="{{ route('admin.sales.today') }}" style="text-decoration:none; color:inherit;">
    <div class="sc s1">
      <div class="sc-top">
        <div class="sc-ico s1">📅</div>
        <span class="sc-tag up">↑ Today</span>
      </div>
      <div class="sc-lbl">Today's Sales</div>
      <div class="sc-dual-row">
        <div class="sc-dual-item">
          <div class="sc-dual-label">Orders</div>
          <div class="sc-dual-val dc" data-val="{{ $salesTodayCount }}">0</div>
        </div>
        <div class="sc-dual-item">
          <div class="sc-dual-label">Amount</div>
          <div class="sc-dual-val dm" data-val="{{ $salesTodayAmount }}">₹0</div>
        </div>
      </div>
    </div>
    </a>

<a href="{{ route('admin.sales.previous') }}" style="text-decoration:none; color:inherit;">
    <div class="sc s2">
      <div class="sc-top">
        <div class="sc-ico s2">🗓️</div>
        <span class="sc-tag nt">→ Yesterday</span>
      </div>
      <div class="sc-lbl">Previous Day Sales</div>
      <div class="sc-dual-row">
        <div class="sc-dual-item">
          <div class="sc-dual-label">Orders</div>
          <div class="sc-dual-val dc" data-val="{{ $salesYesterdayCount }}">0</div>
        </div>
        <div class="sc-dual-item">
          <div class="sc-dual-label">Amount</div>
          <div class="sc-dual-val dm" data-val="{{ $salesYesterdayAmount }}">₹0</div>
        </div>
      </div>
    </div>
    </a>
    
<a href="{{ route('admin.sales.month') }}" style="text-decoration:none; color:inherit;">
    <div class="sc s3">
      <div class="sc-top">
        <div class="sc-ico s3">📊</div>
        <span class="sc-tag up">↑ Month</span>
      </div>
      <div class="sc-lbl">This Month's Sales</div>
      <div class="sc-dual-row">
        <div class="sc-dual-item">
          <div class="sc-dual-label">Orders</div>
          <div class="sc-dual-val dc" data-val="{{ $salesMonthCount }}">0</div>
        </div>
        <div class="sc-dual-item">
          <div class="sc-dual-label">Amount</div>
          <div class="sc-dual-val dm" data-val="{{ $salesMonthAmount }}">₹0</div>
        </div>
      </div>
    </div>
    </a>

    <div class="sc s4">
      <div class="sc-top">
        <div class="sc-ico s4">📈</div>
        <span class="sc-tag up">↑ FY Total</span>
      </div>
      <div class="sc-lbl">Till Date (Financial Year)</div>
      <div class="sc-dual-row">
        <div class="sc-dual-item">
          <div class="sc-dual-label">Orders</div>
          <div class="sc-dual-val dc" data-val="{{ $salesFYCount }}">0</div>
        </div>
        <div class="sc-dual-item">
          <div class="sc-dual-label">Amount</div>
          <div class="sc-dual-val dm" data-val="{{ $salesFYAmount }}">₹0</div>
        </div>
      </div>
    </div>

  </div>
</div>
@endif
  
  @if(in_array('frontend', $allowedSections))
  <div class="d-section">
    <div class="d-section-head">
      <span class="d-section-tag front">Front End</span>
      <div class="d-section-line"></div>
    </div>

    <div class="d-stats">

      <div class="sc s3">
        <div class="sc-top">
          <div class="sc-ico s3">📥</div>
          <span class="sc-tag nt">→ Pending</span>
        </div>
        <div class="sc-val dc" data-val="{{ $enquiriesReceivedCount }}">0</div>
        <div class="sc-lbl">Enquiry Received</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--violet);width:55%"></div></div>
      </div>

      <div class="sc s4">
        <div class="sc-top">
          <div class="sc-ico s4">📤</div>
          <span class="sc-tag up">↑ Submitted</span>
        </div>
        <div class="sc-val dc" data-val="{{ $enquiriesSubmittedCount }}">0</div>
        <div class="sc-lbl">Enquiry Submitted</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--green);width:65%"></div></div>
      </div>

      <!--<div class="sc s5">-->
      <!--  <div class="sc-top">-->
      <!--    <div class="sc-ico s5">🗒️</div>-->
      <!--    <span class="sc-tag up">↑ Quoted</span>-->
      <!--  </div>-->
      <!--  <div class="sc-val dc" data-val="{{ $totalItemsQuotedCount }}">0</div>-->
      <!--  <div class="sc-lbl">Total Items Quoted</div>-->
      <!--  <div class="sc-bar"><div class="sc-bar-f" style="background:var(--green);width:82%"></div></div>-->
      <!--</div>-->
      
      <div class="sc s5">
          <div class="sc-top">
            <div class="sc-ico s5">📩</div>
            <span class="sc-tag up">↑ Received</span>
          </div>
          <div class="sc-val dc" data-val="{{ $customerResponseReceivedCount }}">0</div>
          <div class="sc-lbl">Customer Response Received</div>
          <div class="sc-bar"><div class="sc-bar-f" style="background:var(--blue);width:82%"></div></div>
        </div>

      <div class="sc s6">
        <div class="sc-top">
          <div class="sc-ico s6">✔️</div>
          <span class="sc-tag up">↑ Approved</span>
        </div>
        <div class="sc-val dc" data-val="{{ $totalItemsApprovedCount }}">0</div>
        <div class="sc-lbl">Total Items Approved</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--teal);width:61%"></div></div>
      </div>

      <div class="sc s7">
        <div class="sc-top">
          <div class="sc-ico s7">❌</div>
          <span class="sc-tag dn">↓ Rejected</span>
        </div>
        <div class="sc-val dc" data-val="{{ $totalItemsRejectedCount }}">0</div>
        <div class="sc-lbl">Total Items Rejected</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--indigo);width:49%"></div></div>
      </div>

      <div class="sc s8">
        <div class="sc-top">
          <div class="sc-ico s8">🏬</div>
          <span class="sc-tag up">↑ Active</span>
        </div>
        <div class="sc-val dc" data-val="{{ $totalActiveOutletsCount }}">0</div>
        <div class="sc-lbl">Total Active Outlets</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--rose);width:66%"></div></div>
      </div>
      
      <!-- Total Inactive Outlets -->
       <div class="sc s8">
       <div class="sc-top">
       <div class="sc-ico s8">🏬</div>
       <span class="sc-tag dn">↓ Inactive</span>
       </div>
       <div class="sc-val dc" data-val="{{ $totalInactiveOutletsCount ?? 0 }}">0</div>
       <div class="sc-lbl">Total Inactive Outlets</div>
       <div class="sc-bar"><div class="sc-bar-f" style="background:var(--rose);width:66%"></div></div>
       </div>

      <div class="sc s9">
        <div class="sc-top">
          <div class="sc-ico s9">🏷️</div>
          <span class="sc-tag up">↑ On Sale</span>
        </div>
        <div class="sc-val dc" data-val="{{ $productsOnSaleCount }}">0</div>
        <div class="sc-lbl">Products On Sale</div>
        <div class="sc-bar"><div class="sc-bar-f" style="background:var(--orange);width:58%"></div></div>
      </div>

    </div>
  </div>
@endif  


@if(in_array('collection', $allowedSections))
<div class="d-section">
  <div class="d-section-head">
    <span class="d-section-tag collection">Collection</span>
    <div class="d-section-line"></div>
  </div>

  <div class="d-stats">

    <a href="{{ route('admin.reports.overdue-details', 'overdue_till_date') }}">
      <div class="sc s7">
        <div class="sc-top">
          <div class="sc-ico s7">🧾</div>
          <span class="sc-tag dn">↓ Overdue</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers (Till Date)</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdueCustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdueTotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'not_overdue_till_date') }}">
      <div class="sc s8">
        <div class="sc-top">
          <div class="sc-ico s8">💵</div>
          <span class="sc-tag up">↑ Not Overdue</span>
        </div>
        <div class="sc-lbl">Total Billed but Not Overdue (Till Date)</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $notOverdueCustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $notOverdueTotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'due_soon') }}">
      <div class="sc s9">
        <div class="sc-top">
          <div class="sc-ico s9">⏱️</div>
          <span class="sc-tag nt">→ Due Soon</span>
        </div>
        <div class="sc-lbl">To Be Overdue After 3 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $dueSoonCustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $dueSoonTotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>
    
      <a href="{{ route('admin.reports.overdue-details', 'due_soon_7') }}">
      <div class="sc s10">
        <div class="sc-top">
          <div class="sc-ico s10">📅</div>
          <span class="sc-tag nt">→ Due in 7 Days</span>
        </div>
        <div class="sc-lbl">To Be Overdue in the Next 7 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $dueSoon7CustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $dueSoon7TotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'overdue_90_plus') }}">
      <div class="sc s1">
        <div class="sc-top">
          <div class="sc-ico s1">🔴</div>
          <span class="sc-tag dn">↓ 90+ Days</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers More Than 90 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdueOver90Count }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdueOver90Amount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'overdue_60_90') }}">
      <div class="sc s2">
        <div class="sc-top">
          <div class="sc-ico s2">🟠</div>
          <span class="sc-tag dn">↓ 60-90 Days</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers 60 to 90 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdue60to90Count }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdue60to90Amount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'overdue_30_60') }}">
      <div class="sc s3">
        <div class="sc-top">
          <div class="sc-ico s3">🟡</div>
          <span class="sc-tag nt">→ 30-60 Days</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers 30 to 60 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdue30to60Count }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdue30to60Amount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

  </div>
</div>
@endif


@if(in_array('backend', $allowedSections))
<div class="d-section">
  <div class="d-section-head">
    <span class="d-section-tag back">Back End</span>
    <div class="d-section-line"></div>
  </div>

  <div class="d-stats">

    <div class="sc s10">
      <div class="sc-top">
        <div class="sc-ico s10">⏳</div>
        <span class="sc-tag dn">↓ Pending</span>
      </div>
      <div class="sc-val dc" data-val="{{ $salesOrderPendingCount }}">0</div>
      <div class="sc-lbl">Sales Orders Pending (3+ Days)</div>
      <div class="sc-bar">
        <div class="sc-bar-f" style="background:var(--rose);width:40%"></div>
      </div>
    </div>

    <a href="{{ route('admin.reports.overdue-details', 'overdue_till_date') }}">
      <div class="sc s7">
        <div class="sc-top">
          <div class="sc-ico s7">🧾</div>
          <span class="sc-tag dn">↓ Overdue</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers (Till Date)</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdueCustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdueTotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'not_overdue_till_date') }}">
      <div class="sc s8">
        <div class="sc-top">
          <div class="sc-ico s8">💵</div>
          <span class="sc-tag up">↑ Not Overdue</span>
        </div>
        <div class="sc-lbl">Total Billed but Not Overdue (Till Date)</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $notOverdueCustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $notOverdueTotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'due_soon') }}">
      <div class="sc s9">
        <div class="sc-top">
          <div class="sc-ico s9">⏱️</div>
          <span class="sc-tag nt">→ Due Soon</span>
        </div>
        <div class="sc-lbl">To Be Overdue After 3 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $dueSoonCustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $dueSoonTotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>
    
      <a href="{{ route('admin.reports.overdue-details', 'due_soon_7') }}">
      <div class="sc s10">
        <div class="sc-top">
          <div class="sc-ico s10">📅</div>
          <span class="sc-tag nt">→ Due in 7 Days</span>
        </div>
        <div class="sc-lbl">To Be Overdue in the Next 7 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $dueSoon7CustomerCount }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $dueSoon7TotalAmount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>
    

  </div>
</div>
@endif

@if(in_array('report', $allowedSections))
<div class="d-section">
  <div class="d-section-head">
    <span class="d-section-tag back">Report</span>
    <div class="d-section-line"></div>
  </div>

  <div class="d-stats">
      
    <a href="{{ route('admin.reports.overdue-details', 'overdue_0_30') }}">
      <div class="sc s4">
        <div class="sc-top">
          <div class="sc-ico s4">🟢</div>
          <span class="sc-tag nt">→ 0-30 Days</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers 0 to 30 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdue0to30Count }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdue0to30Amount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'overdue_90_plus') }}">
      <div class="sc s1">
        <div class="sc-top">
          <div class="sc-ico s1">🔴</div>
          <span class="sc-tag dn">↓ 90+ Days</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers More Than 90 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdueOver90Count }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdueOver90Amount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'overdue_60_90') }}">
      <div class="sc s2">
        <div class="sc-top">
          <div class="sc-ico s2">🟠</div>
          <span class="sc-tag dn">↓ 60-90 Days</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers 60 to 90 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdue60to90Count }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdue60to90Amount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.reports.overdue-details', 'overdue_30_60') }}">
      <div class="sc s3">
        <div class="sc-top">
          <div class="sc-ico s3">🟡</div>
          <span class="sc-tag nt">→ 30-60 Days</span>
        </div>
        <div class="sc-lbl">Total Overdue Customers 30 to 60 Days</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Customers</div>
            <div class="sc-dual-val dc" data-val="{{ $overdue30to60Count }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">Amount</div>
            <div class="sc-dual-val dm" data-val="{{ $overdue30to60Amount }}">₹0</div>
          </div>
        </div>
      </div>
    </a>

  </div>
</div>
@endif

@if(in_array('inventory', $allowedSections))
 <div class="d-section">
  <div class="d-section-head">
    <span class="d-section-tag back">Inventory</span>
    <div class="d-section-line"></div>
  </div>

  <div class="d-stats">

<a href="{{ route('admin.reports.inventory-details', 'expired') }}">
    <div class="sc s1">
      <div class="sc-top">
        <div class="sc-ico s1">🗑️</div>
        <span class="sc-tag dn">↓ Expired</span>
      </div>
      <div class="sc-val dc" data-val="{{ $expiredProductsCount }}">0</div>
      <div class="sc-lbl">No. of Products Expired</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--rose);width:40%"></div></div>
    </div>
</a>    

<a href="{{ route('admin.reports.inventory-details', 'near_expiry') }}">
    <div class="sc s2">
      <div class="sc-top">
        <div class="sc-ico s2">⏰</div>
        <span class="sc-tag nt">→ Near Expiry</span>
      </div>
      <div class="sc-val dc" data-val="{{ $nearExpiryProductsCount }}">0</div>
      <div class="sc-lbl">No. of Products Near To Expiry</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--amber);width:45%"></div></div>
    </div>
    </a>

<a href="{{ route('admin.reports.inventory-details', 'non_moving') }}">
    <div class="sc s3">
      <div class="sc-top">
        <div class="sc-ico s3">📉</div>
        <span class="sc-tag dn">↓ Non Moving</span>
      </div>
      <div class="sc-val dc" data-val="{{ $nonMovingProductsCount }}">0</div>
      <div class="sc-lbl">No. of Products Non Moving</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--indigo);width:35%"></div></div>
    </div>
    </a>
    
    

    <div class="sc s4">
      <div class="sc-top">
        <div class="sc-ico s4">💰</div>
        <span class="sc-tag up">↑ In Hand</span>
      </div>
      <div class="sc-val dm" data-val="{{ $totalStockValue }}">₹0</div>
      <div class="sc-lbl">Total Value Stock in Hand</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--amber);width:70%"></div></div>
    </div>


<a href="{{ route('admin.reports.inventory-details', 'careful') }}">
    <div class="sc s7">
      <div class="sc-top">
        <div class="sc-ico s7">🟢</div>
        <span class="sc-tag nt">→ Careful</span>
      </div>
      <div class="sc-val dc" data-val="{{ $carefulCount }}">0</div>
      <div class="sc-lbl">No. of Products in Careful</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--teal);width:45%"></div></div>
    </div>
</a>    

 <a href="{{ route('admin.reports.inventory-details', 'watch') }}">
    <div class="sc s5">
      <div class="sc-top">
        <div class="sc-ico s5">👁️</div>
        <span class="sc-tag nt">→ Watch</span>
      </div>
      <div class="sc-val dc" data-val="{{ $watchCount }}">0</div>
      <div class="sc-lbl">No. of Products in Watch</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--violet);width:50%"></div></div>
    </div>
</a>    


 <a href="{{ route('admin.reports.inventory-details', 'reorder') }}">
    <div class="sc s6">
      <div class="sc-top">
        <div class="sc-ico s6">🔁</div>
        <span class="sc-tag dn">↓ Reorder</span>
      </div>
      <div class="sc-val dc" data-val="{{ $reorderCount }}">0</div>
      <div class="sc-lbl">No. of Products in Reorder</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--rose);width:60%"></div></div>
    </div>
    </a>    


 <a href="{{ route('admin.reports.inventory-details', 'critical') }}">
    <div class="sc s8">
      <div class="sc-top">
        <div class="sc-ico s8">🚨</div>
        <span class="sc-tag dn">↓ Critical</span>
      </div>
      <div class="sc-val dc" data-val="{{ $criticalCount }}">0</div>
      <div class="sc-lbl">No. of Products in Critical</div>
      <div class="sc-bar"><div class="sc-bar-f" style="background:var(--rose);width:75%"></div></div>
    </div>
    </a>

  </div>
</div>
@endif

@if(in_array('order_process', $allowedSections))
  <div class="d-section">
    <div class="d-section-head">
      <span class="d-section-tag process">Order Process</span>
      <div class="d-section-line"></div>
    </div>

    <div class="d-stats">

      <div class="sc s1">
        <div class="sc-top">
          <div class="sc-ico s1">📋</div>
          <span class="sc-tag nt">→ Pending</span>
        </div>
        <div class="sc-lbl">Order Pending for Acceptance</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $pendingAcceptanceToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $pendingAcceptanceMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s2">
        <div class="sc-top">
          <div class="sc-ico s2">🗂️</div>
          <span class="sc-tag up">↑ Created</span>
        </div>
        <div class="sc-lbl">Pick List Created</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $pickListCreatedToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $pickListCreatedMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s3">
        <div class="sc-top">
          <div class="sc-ico s3">✅</div>
          <span class="sc-tag up">↑ Picked</span>
        </div>
        <div class="sc-lbl">Mark As Picked</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $markedPickedToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $markedPickedMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s4">
        <div class="sc-top">
          <div class="sc-ico s4">⚙️</div>
          <span class="sc-tag up">↑ In Progress</span>
        </div>
        <div class="sc-lbl">Order Accepted & In Progress</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $acceptedInProgressToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $acceptedInProgressMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s5">
        <div class="sc-top">
          <div class="sc-ico s5">📦</div>
          <span class="sc-tag up">↑ Ready</span>
        </div>
        <div class="sc-lbl">Ready for Dispatch</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $readyForDispatchToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $readyForDispatchMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s6">
        <div class="sc-top">
          <div class="sc-ico s6">🔍</div>
          <span class="sc-tag up">↑ Checked</span>
        </div>
        <div class="sc-lbl">Final Check Done</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $finalCheckDoneToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $finalCheckDoneMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s7">
        <div class="sc-top">
          <div class="sc-ico s7">🚚</div>
          <span class="sc-tag up">↑ Dispatched</span>
        </div>
        <div class="sc-lbl">Dispatched</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $dispatchedToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $dispatchedMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s8">
        <div class="sc-top">
          <div class="sc-ico s8">🏁</div>
          <span class="sc-tag up">↑ Delivered</span>
        </div>
        <div class="sc-lbl">Delivered</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $deliveredToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $deliveredMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s9">
        <div class="sc-top">
          <div class="sc-ico s9">🚫</div>
          <span class="sc-tag dn">↓ Cancelled</span>
        </div>
        <div class="sc-lbl">Cancelled</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $cancelledToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $cancelledMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s10">
        <div class="sc-top">
          <div class="sc-ico s10">⚠️</div>
          <span class="sc-tag dn">↓ Short</span>
        </div>
        <div class="sc-lbl">Pre Short Log — No. of Items</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $preShortLogToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $preShortLogMonth }}">0</div>
          </div>
        </div>
      </div>

      <div class="sc s1">
        <div class="sc-top">
          <div class="sc-ico s1">⚠️</div>
          <span class="sc-tag dn">↓ Short</span>
        </div>
        <div class="sc-lbl">Post Short Log — No. of Items</div>
        <div class="sc-dual-row">
          <div class="sc-dual-item">
            <div class="sc-dual-label">Today</div>
            <div class="sc-dual-val dc" data-val="{{ $postShortLogToday }}">0</div>
          </div>
          <div class="sc-dual-item">
            <div class="sc-dual-label">This Month</div>
            <div class="sc-dual-val dc" data-val="{{ $postShortLogMonth }}">0</div>
          </div>
        </div>
      </div>

    </div>
  </div>
 @endif 

@if(in_array('analytics', $allowedSections))
  <div class="d-section">
    <div class="d-section-head">
      <span class="d-section-tag core">Analytics</span>
      <div class="d-section-line"></div>
    </div>

    <div class="d-row2">

      <div class="cc">
        <div class="cc-head">
          <div class="cc-title">Monthly Sales — Orders</div>
          <div class="cc-type">Line</div>
        </div>
        <div class="cc-body">
          <canvas id="orderChart" height="200"></canvas>
        </div>
      </div>

      <div class="cc">
        <div class="cc-head">
          <div class="cc-title">Product Expenses — Selling vs Purchase</div>
          <div class="cc-type">Line</div>
        </div>
        <div class="cc-body">
          <canvas id="monthlySalesChart" height="200"></canvas>
        </div>
      </div>

    </div>
  </div>
    @endif

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
(function () {
  'use strict';

  var dEl = document.getElementById('dashDate');
  if (dEl) {
    dEl.textContent = new Date().toLocaleString('en-IN', {
      weekday:'short', month:'short', day:'numeric',
      hour:'2-digit', minute:'2-digit'
    });
  }

  function animCount(el, target, dur) {
    target = parseFloat(target) || 0;
    dur = dur || 1600;
    if (target === 0) { el.textContent = '0'; return; }
    var s = performance.now();
    (function tick(ts) {
      var p = Math.min((ts - s) / dur, 1);
      var e = 1 - Math.pow(1 - p, 4);
      el.textContent = Math.round(e * target).toLocaleString('en-IN');
      if (p < 1) requestAnimationFrame(tick);
    })(s);
  }

  document.querySelectorAll('.dc').forEach(function(el) { animCount(el, el.dataset.val, 1600); });
  document.querySelectorAll('.dm').forEach(function(el) { animCount(el, el.dataset.val, 1900); });

  setTimeout(function() {
    document.querySelectorAll('.sc-bar-f').forEach(function(el) {
      var w = el.style.width; el.style.width = '0%';
      requestAnimationFrame(function(){ requestAnimationFrame(function(){ el.style.width = w; }); });
    });
  }, 300);

  Chart.defaults.color       = '#8a93a8';
  Chart.defaults.font.family = 'DM Sans, sans-serif';
  Chart.defaults.font.size   = 11;
  Chart.defaults.plugins.legend.display = false;

  var TIP = {
    backgroundColor : '#ffffff',
    borderColor     : 'rgba(0,0,0,0.1)',
    borderWidth     : 1,
    titleColor      : '#1a1f36',
    bodyColor       : '#4a5568',
    padding         : 12,
    cornerRadius    : 10,
    displayColors   : true,
    boxWidth        : 9,
    boxHeight       : 9
  };

  var oCtx  = document.getElementById('orderChart').getContext('2d');
  var oGrad = oCtx.createLinearGradient(0, 0, 0, 300);
  oGrad.addColorStop(0, 'rgba(59,130,246,0.2)');
  oGrad.addColorStop(1, 'rgba(59,130,246,0.0)');

  new Chart(oCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode(array_keys($orderdata)) !!},
      datasets: [{
        label: 'Total Orders',
        data:  {!! json_encode(array_values($orderdata)) !!},
        borderColor: '#3b82f6', backgroundColor: oGrad,
        borderWidth: 2.5, fill: true, tension: 0.42,
        pointBackgroundColor: '#3b82f6', pointBorderColor: '#fff',
        pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7
      }]
    },
    options: {
      plugins: {
        legend: { display:true, labels:{ color:'#4a5568', usePointStyle:true, pointStyle:'circle', boxWidth:8, padding:14 } },
        tooltip: TIP
      },
      scales: {
        x: { grid:{ color:'rgba(0,0,0,0.04)' }, ticks:{ color:'#8a93a8' }, border:{ color:'rgba(0,0,0,0.06)' } },
        y: { grid:{ color:'rgba(0,0,0,0.04)' }, ticks:{ color:'#8a93a8' }, border:{ color:'rgba(0,0,0,0.06)' }, beginAtZero:true }
      },
      animation: { duration:1500 }
    }
  });

  var eCtx  = document.getElementById('monthlySalesChart').getContext('2d');
  var sGrad = eCtx.createLinearGradient(0, 0, 0, 300);
  sGrad.addColorStop(0, 'rgba(59,130,246,0.18)');
  sGrad.addColorStop(1, 'rgba(59,130,246,0)');
  var bGrad = eCtx.createLinearGradient(0, 0, 0, 300);
  bGrad.addColorStop(0, 'rgba(244,63,94,0.18)');
  bGrad.addColorStop(1, 'rgba(244,63,94,0)');

  new Chart(eCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode(array_keys($monthlyTotalPrices ?? [])) !!},
      datasets: [
        {
          label: 'Selling Price',
          data:  {!! json_encode(array_values($monthlyTotalPrices ?? [])) !!},
          borderColor:'#3b82f6', backgroundColor:sGrad,
          borderWidth:2.5, fill:true, tension:0.42,
          pointBackgroundColor:'#3b82f6', pointBorderColor:'#fff',
          pointBorderWidth:2, pointRadius:4, pointHoverRadius:6
        },
        {
          label: 'Purchase Price',
          data:  {!! json_encode(array_values($monthlyProductTotalPrices ?? [])) !!},
          borderColor:'#f43f5e', backgroundColor:bGrad,
          borderWidth:2.5, fill:true, tension:0.42,
          pointBackgroundColor:'#f43f5e', pointBorderColor:'#fff',
          pointBorderWidth:2, pointRadius:4, pointHoverRadius:6
        }
      ]
    },
    options: {
      plugins: {
        legend: { display:true, labels:{ color:'#4a5568', usePointStyle:true, pointStyle:'circle', boxWidth:8, padding:20 } },
        tooltip: TIP
      },
      scales: {
        x: { grid:{ color:'rgba(0,0,0,0.04)' }, ticks:{ color:'#8a93a8' }, border:{ color:'rgba(0,0,0,0.06)' } },
        y: {
          grid: { color:'rgba(0,0,0,0.04)' },
          ticks: { color:'#8a93a8', callback:function(v){ return v>=1000?'₹'+(v/1000).toFixed(0)+'k':'₹'+v; } },
          border: { color:'rgba(0,0,0,0.06)' }, beginAtZero:true
        }
      },
      animation: { duration:1700 }
    }
  });

})();
</script>

@endsection
