@extends('mobile.mobile-app')
@section('content')

<style>
.home-page {
    background: #f7f8fa;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.home-container { max-width: 1180px; margin: 0 auto; }

/* ===== Weather alert banner ===== */
.weather-alert {
    background: #eef2ff;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 20px;
    margin-bottom: 4px;
}
.weather-alert-icon { font-size: 32px; flex-shrink: 0; line-height: 1; }
.weather-alert-text { font-size: 12px; color: #101828; line-height: 1.45; font-weight: 500; }

/* ===== Delivery info ===== */
.delivery-info {
    text-align: center;
    padding: 20px 20px 0;
}
.delivery-info-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 6px;
}
.delivery-icon {
    width: 24px; height: 24px;
    color: #1d9e75;
    flex-shrink: 0;
}
.delivery-title { font-size: 19px; font-weight: 800; color: #101828; }
.delivery-subtitle { font-size: 13.5px; color: #98a2b3; }

/* ===== Search + Settings ===== */
.search-settings-row {
    display: flex;
    gap: 10px;
    padding: 20px;
}
.home-search-box {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 14px 16px;
    min-width: 0;
}
.home-search-box svg { color: #e0442e; flex-shrink: 0; }
.home-search-box input {
    border: none;
    outline: none;
    flex: 1;
    font-size: 14px;
    color: #344054;
    min-width: 0;
    background: transparent;
}
.home-search-box input::placeholder { color: #98a2b3; }

.settings-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 10px 18px;
    color: #344054;
    text-decoration: none;
    flex-shrink: 0;
}
.settings-btn svg { width: 20px; height: 20px; }
.settings-btn span { font-size: 11px; font-weight: 600; }

/* ===== Feature cards grid ===== */
.feature-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    padding: 0 20px 24px;
}
.feature-card {
    border-radius: 18px;
    padding: 13px 18px;
    text-decoration: none;
    display: block;
    position: relative;
    min-height: 190px;
}
.feature-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
}
.feature-icon-wrap svg { width: 26px; height: 26px; color: #fff; }
.feature-title {
    font-size: 15px;
    font-weight: 800;
    color: #101828;
    margin-bottom: 6px;
}
.feature-desc {
    font-size: 11.5px;
    color: #667085;
    line-height: 1.5;
    /*margin-bottom: 14px;*/
    padding-right: 40px;
}
.feature-arrow {
    position: absolute;
    bottom: 20px;
    right: 18px;
    width: 38px; height: 38px;
    border-radius: 50%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(16,24,40,0.08);
}
.feature-arrow svg { width: 17px; height: 17px; }

.feature-card.catalogue { background: linear-gradient(135deg, #fde8e8, #fdf3f3); }
.feature-card.catalogue .feature-icon-wrap { background: #e0442e; }
.feature-card.catalogue .feature-arrow svg { color: #e0442e; }

.feature-card.pricelist { background: linear-gradient(135deg, #e3f8ec, #f0fbf5); }
.feature-card.pricelist .feature-icon-wrap { background: #1d9e75; }
.feature-card.pricelist .feature-arrow svg { color: #1d9e75; }

.feature-card.quotes { background: linear-gradient(135deg, #ede7fb, #f5f1fd); }
.feature-card.quotes .feature-icon-wrap { background: #7c4fd6; }
.feature-card.quotes .feature-arrow svg { color: #7c4fd6; }

.feature-card.statement { background: linear-gradient(135deg, #e3ecfd, #eef4fe); }
.feature-card.statement .feature-icon-wrap { background: #2f5ede; }
.feature-card.statement .feature-arrow svg { color: #2f5ede; }

.feature-card.outstanding { background: linear-gradient(135deg, #fdecd6, #fdf4e7); }
.feature-card.outstanding .feature-icon-wrap { background: #e2711d; }
.feature-card.outstanding .feature-arrow svg { color: #e2711d; }

.feature-card.orders { background: linear-gradient(135deg, #daf1ec, #eaf7f4); }
.feature-card.orders .feature-icon-wrap { background: #11919c; }
.feature-card.orders .feature-arrow svg { color: #11919c; }

@media (min-width: 768px) {
    .home-container {
        background: #fff;
        border-radius: 20px;
        padding-bottom: 20px;
        box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04);
        margin-top: 16px;
        overflow: hidden;
    }
    .weather-alert { border-radius: 20px 20px 0 0; }
    .feature-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .feature-card { min-height: 180px; }
}
@media (max-width: 420px) {
    .feature-grid { gap: 10px; padding-inline: 14px; }
    .feature-card { padding: 14px; min-height: 176px; }
    .feature-desc { padding-right: 24px; }
    .search-settings-row { padding: 14px; }
}
</style>

<div class="home-page">
    <div class="home-container">

        <!-- ===== Weather Alert Banner ===== -->
        @if($showWeatherAlert ?? true)
        <div class="weather-alert">
            <div class="weather-alert-icon">🌧️</div>
            <div class="weather-alert-text">
                Deliveries may be impacted due to heavy rain.<br>
                Thank you for your patience and co-operation!
            </div>
        </div>
        @endif

        <!-- ===== Delivery Info ===== -->
        <!--<div class="delivery-info">-->
        <!--    <div class="delivery-info-row">-->
        <!--        <svg class="delivery-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>-->
        <!--        <div class="delivery-title">Delivery {{ $deliveryLabel ?? 'tomorrow' }}</div>-->
        <!--    </div>-->
        <!--    <div class="delivery-subtitle">{{ $currentOutlet->outlet_name ?? 'Select Outlet' }}: {{ $currentOutlet->location ?? '-' }}</div>-->
        <!--</div>-->

        <!-- ===== Search + Settings ===== -->
        <div class="search-settings-row">
            <div class="home-search-box">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="homeSearch" placeholder="Search documents">
            </div>
            <!--<a href="#" class="settings-btn">-->
            <!--    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>-->
            <!--    <span>Settings</span>-->
            <!--</a>-->
        </div>

        <!-- ===== Feature Cards ===== -->
        <div class="feature-grid">

            <a href="{{ route('web.price.list') }}" class="feature-card catalogue">
                <div class="feature-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <div class="feature-title">Catalogue</div>
                <div class="feature-desc">Browse products with details and specifications</div>
                <div class="feature-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
            </a>

            <a href="{{ route('web.price.list') }}" class="feature-card pricelist">
                <div class="feature-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41L13.42 20.58a2 2 0 0 1-2.83 0L2.59 12.6a2 2 0 0 1-.59-1.41V4a2 2 0 0 1 2-2h7.17a2 2 0 0 1 1.41.59l8.01 8.01a2 2 0 0 1 0 2.81z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                </div>
                <div class="feature-title">Order Price List</div>
                <div class="feature-desc">View prices to place your orders</div>
                <div class="feature-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
            </a>

            <a href="{{ route('quoteslist') }}" class="feature-card quotes">
                <div class="feature-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="13" y2="17"/></svg>
                </div>
                <div class="feature-title">Quotes</div>
                <div class="feature-desc">View and manage your quotations</div>
                <div class="feature-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
            </a>

            <a href="{{ route('web.account-statement') }}" class="feature-card statement">
                <div class="feature-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="15" y2="17"/></svg>
                </div>
                <div class="feature-title">Statement</div>
                <div class="feature-desc">Account summary of your transactions</div>
                <div class="feature-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
            </a>

            <a href="{{ route('web.payments-outstanding') }}" class="feature-card outstanding">
                <div class="feature-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                </div>
                <div class="feature-title">Outstanding</div>
                <div class="feature-desc">View unpaid invoices and pending balances</div>
                <div class="feature-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
            </a>

            <a href="{{ route('web.order.tracker') }}" class="feature-card orders">
                <div class="feature-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12l2 2 4-4"/></svg>
                </div>
                <div class="feature-title">Orders</div>
                <div class="feature-desc">View and track your purchase orders</div>
                <div class="feature-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
            </a>

        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('homeSearch');
    search?.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            const query = search.value.trim();
            if (query) {
                window.location.href = '{{ route('web.price.list') }}?search=' + encodeURIComponent(query);
            }
        }
    });
});
</script>
@endsection
