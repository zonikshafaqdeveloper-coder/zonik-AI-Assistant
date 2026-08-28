@php
    $isPriceListPage = request()->is('price-list');
@endphp

<div class="mobile-header">
<div class="header-left">
    <button class="hamburger-btn" id="sidebarToggle" aria-label="Menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>

    <div class="brand">
        <img src="{{ asset('frontweb/assests/images/mobile-logo-1.png') }}" alt="Zonik" class="brand-logo">
        <!-- <div class="brand-sub">B2B Food Supply</div> -->
    </div>
</div>

    <div class="header-right">

        <!--@if($isPriceListPage)-->
        <!--    <a href="#" class="icon-btn" aria-label="Search" id="headerSearchBtn">-->
        <!--        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#14213d" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>-->
        <!--    </a>-->
        <!--@endif-->

        <a href="#" class="icon-btn" aria-label="Notifications" id="notifBellBtn">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#14213d" stroke-width="2"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="badge notif-badge">{{ ($notificationCount ?? 0) > 99 ? '99+' : ($notificationCount ?? 0) }}</span>
        </a>

        @if($isPriceListPage)
            <a href="{{route('web.chekout')}}" class="icon-btn" aria-label="Cart" id="headerCartBtn">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#14213d" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span class="badge cart-badge" id="headerCartCount">{{ $cartCount ?? 0 }}</span>
            </a>
        @else
            <!--<a href="{{ route('profile') }}" class="icon-btn" aria-label="Profile">-->
            <!--    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#14213d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
            <!--        <circle cx="12" cy="7" r="4"/>-->
            <!--        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>-->
            <!--    </svg>-->
            <!--</a>-->
        @endif

    </div>
</div>

@include('mobile.notification-panel')

<style>
.mobile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    background: #fff;
    border-bottom: 1px solid #eef0f3;
    position: sticky;
    top: 0;
    z-index: 100;
}
.header-left { display: flex; align-items: center; gap: 14px; }
.hamburger-btn { background: none; border: none; padding: 4px; cursor: pointer; color: #14213d; }
.brand-name { font-size: 20px; font-weight: 800; line-height: 1; }
.brand-sub { font-size: 10.5px; color: #98a2b3; letter-spacing: .3px; }
.header-right { display: flex; align-items: center; gap: 10px; }
.icon-btn {
    width: 38px; height: 38px; border-radius: 50%;
    border: 1px solid #eef0f3;
    display: flex; align-items: center; justify-content: center;
    position: relative; text-decoration: none;
}
.notif-badge {
    position: absolute; top: -4px; right: -4px;
    background: #f04438; color: #fff;
    font-size: 10px; font-weight: 700;
    min-width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; padding: 0 3px;
}
.cart-badge {
    position: absolute; top: -4px; right: -4px;
    background: #2f5ede; color: #fff;
    font-size: 10px; font-weight: 700;
    min-width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; padding: 0 3px;
}

/* .brand {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 2px;
} */
.brand-logo {
    height: 30px;
    width: auto;
    display: block;
}
/* .brand-sub {
    font-size: 10.5px;
    color: #98a2b3;
    letter-spacing: .3px;
} */


 /* ===== Account Drawer (bottom sheet) ===== */
.drawer-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,0.5);
    z-index: 998;
    opacity: 0; pointer-events: none;
    transition: opacity .25s ease;
}
.drawer-overlay.open { opacity: 1; pointer-events: auto; }

.account-drawer {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    max-width: 640px;
    margin: 0 auto;
    background: #fff;
    border-radius: 20px 20px 0 0;
    z-index: 999;
    transform: translateY(100%);
    transition: transform .3s cubic-bezier(.16,1,.3,1);
    max-height: 90vh;
    overflow-y: auto;
}
.account-drawer.open { transform: translateY(0); }

.drawer-handle {
    width: 40px; height: 4px;
    background: #d0d5dd;
    border-radius: 100px;
    margin: 10px auto 0;
}

.drawer-close {
    position: absolute; top: 16px; right: 16px;
    background: none; border: none; color: #667085;
    cursor: pointer; padding: 4px;
}

.drawer-profile { display: flex; align-items: center; gap: 14px; padding: 20px 20px 16px; }
.drawer-avatar-wrap { position: relative; flex-shrink: 0; }
.drawer-avatar {
    width: 60px; height: 60px; border-radius: 50%;
    background: #1d3a8a; color: #fff;
    display: flex; align-items: center; justify-content: center;
}
.drawer-avatar svg { width: 30px; height: 30px; }
.drawer-avatar-edit {
    position: absolute; bottom: -2px; right: -2px;
    width: 22px; height: 22px; border-radius: 50%;
    background: #fff; border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(16,24,40,0.15);
    color: #2f5ede;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
}
.drawer-company-name { font-size: 17px; font-weight: 800; color: #101828; margin-bottom: 6px; }
.drawer-cust-id {
    display: inline-block;
    background: #eef2ff; color: #2f5ede;
    font-size: 12px; font-weight: 700;
    padding: 4px 12px; border-radius: 8px;
}

.drawer-divider { height: 1px; background: #eef0f3; margin: 0 20px; }

.drawer-section-label {
    font-size: 11px; font-weight: 700; letter-spacing: 0.6px;
    color: #98a2b3; text-transform: uppercase;
    padding: 18px 20px 10px;
}

.drawer-item-list { padding: 0 20px 4px; }
.drawer-item {
    display: flex; align-items: center; gap: 14px;
    border: 1px solid #eef0f3; border-radius: 12px;
    padding: 14px; margin-bottom: 10px;
    text-decoration: none; cursor: pointer;
}
.drawer-item-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: #eef2ff; color: #2f5ede;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.drawer-item-icon.whatsapp {
    background: #25D366;
    color: #fff;
}
.drawer-item-icon.logout { background: #fdecea; color: #e2571f; }
.drawer-item-body { flex: 1; min-width: 0; }
.drawer-item-title { font-size: 14.5px; font-weight: 700; color: #101828; }
.drawer-item-title.logout-text { color: #e2571f; }
.drawer-item-sub { font-size: 12px; color: #98a2b3; margin-top: 2px; }
.drawer-item-chevron { color: #2f5ede; flex-shrink: 0; }
.drawer-item-chevron.logout-chevron { color: #e2571f; }

.drawer-footer {
    background: #f0f2ff;
    border-radius: 20px 20px 0 0;
    margin-top: 10px;
    padding: 20px;
    text-align: center;
}
.drawer-footer-logo { font-size: 20px; font-weight: 800; margin-bottom: 2px; }
.drawer-footer-logo .z1 { color: #2f5ede; }
.drawer-footer-logo .o1 { color: #e2571f; }
.drawer-footer-logo .rest { color: #2f5ede; }
.drawer-footer-sub { font-size: 11px; color: #98a2b3; }
body.drawer-open { overflow: hidden; }
.hamburger-btn:focus-visible, .drawer-close:focus-visible, .drawer-item:focus-visible, .icon-btn:focus-visible {
    outline: 3px solid rgba(47,94,222,.28);
    outline-offset: 2px;
}
.drawer-item:hover { border-color: #cfd8f7; background: #f8faff; }

@media (min-width: 768px) {
    .mobile-header { padding-inline: max(24px, calc((100vw - 1180px) / 2)); }
    .account-drawer {
        top: 0; bottom: 0; left: 0; right: auto;
        width: min(390px, 92vw); max-width: none; max-height: none;
        margin: 0; border-radius: 0 22px 22px 0;
        transform: translateX(-105%);
        box-shadow: 20px 0 60px rgba(15,23,42,.18);
    }
    .account-drawer.open { transform: translateX(0); }
    .drawer-handle { display: none; }
}

@media (prefers-reduced-motion: reduce) {
    .account-drawer, .drawer-overlay { transition: none; }
}

</style>
