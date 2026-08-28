<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Zonik - B2B Food Supply')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f7f8fa;
            margin: 0;
            padding: 0;
        }
        .mobile-main {
            min-height: calc(100vh - 130px);
            padding-bottom: 80px;
        }
    </style>

    @yield('styles')
</head>
<body>

    @include('mobile.header')

    <main class="mobile-main">
        @yield('content')
    </main>

    @include('mobile.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')
    
<div class="drawer-overlay" id="accountDrawerOverlay"></div>
<div class="account-drawer" id="accountDrawer">
    <div class="drawer-handle"></div>
    <button type="button" class="drawer-close" id="accountDrawerClose" aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    <div class="drawer-profile">
        <div class="drawer-avatar-wrap">
            <div class="drawer-avatar">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.4c-3.3 0-9.8 1.6-9.8 4.9v2.5h19.6v-2.5c0-3.3-6.5-4.9-9.8-4.9z"/></svg>
            </div>
            <div class="drawer-avatar-edit">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
            </div>
        </div>
        <div>
            <div class="drawer-company-name">{{ auth()->user()->name ?? 'Your Company' }}</div>
            <div class="drawer-cust-id">Cust. ID : {{ auth()->user()->id ?? '-' }}</div>
        </div>
    </div>

    <div class="drawer-divider"></div>

    <div class="drawer-section-label">Account</div>
    <div class="drawer-item-list">
        <a href="{{ route('profile') }}" class="drawer-item">
            <div class="drawer-item-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
            </div>
            <div class="drawer-item-body">
                <div class="drawer-item-title">Profile Settings</div>
                <div class="drawer-item-sub">Manage your profile &amp; preferences</div>
            </div>
            <svg class="drawer-item-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

        <a href="{{ route('web.outlet.select') }}" class="drawer-item">
            <div class="drawer-item-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v10h16V9"/><path d="M9 21v-6h6v6"/></svg>
            </div>
            <div class="drawer-item-body">
                <div class="drawer-item-title">Outlet Settings</div>
                <div class="drawer-item-sub">Manage your outlets &amp; delivery details</div>
            </div>
            <svg class="drawer-item-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <div class="drawer-section-label">Support</div>
    <div class="drawer-item-list">
         <a href="https://wa.me/918850268043" target="_blank" class="drawer-item">
    <div class="drawer-item-icon whatsapp">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.48 1.32 4.99L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.878 9.878 0 0 0 12.04 2zm0 18.16h-.01c-1.48 0-2.94-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.13 8.13 0 0 1-1.25-4.37c0-4.51 3.67-8.18 8.19-8.18a8.13 8.13 0 0 1 5.79 2.4 8.13 8.13 0 0 1 2.4 5.79c0 4.51-3.67 8.18-8.19 8.18zm4.48-6.13c-.24-.12-1.45-.72-1.68-.8-.22-.08-.39-.12-.55.12-.16.24-.63.8-.78.96-.14.16-.29.18-.53.06-.24-.12-1.02-.38-1.94-1.2-.72-.64-1.2-1.43-1.34-1.67-.14-.24-.02-.37.11-.49.11-.11.24-.29.36-.43.12-.14.16-.24.24-.4.08-.16.04-.31-.02-.43-.06-.12-.55-1.33-.76-1.82-.2-.48-.4-.42-.55-.42-.14 0-.31-.02-.47-.02-.16 0-.43.06-.65.31-.22.24-.86.84-.86 2.05 0 1.2.88 2.37 1 2.53.12.16 1.72 2.63 4.17 3.69.58.25 1.04.4 1.39.51.59.19 1.12.16 1.54.1.47-.07 1.45-.59 1.65-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z"/>
        </svg>
    </div>
    <div class="drawer-item-body">
        <div class="drawer-item-title">Chat on WhatsApp</div>
        <div class="drawer-item-sub">Chat with our support team</div>
    </div>
    <svg class="drawer-item-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
</a>

        <a href="tel:+918850268043" class="drawer-item">
            <div class="drawer-item-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.32 1.85.55 2.81.68A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div class="drawer-item-body">
                <div class="drawer-item-title">Call Us</div>
                <div class="drawer-item-sub">Speak with our support team</div>
            </div>
            <svg class="drawer-item-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <div class="drawer-section-label">More</div>
    <div class="drawer-item-list">
        <a href="{{ route('requestproduct') }}" class="drawer-item">
            <div class="drawer-item-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/><line x1="16" y1="10" x2="16" y2="16"/><line x1="13" y1="13" x2="19" y2="13"/></svg>
            </div>
            <div class="drawer-item-body">
                <div class="drawer-item-title">Request New Products</div>
                <div class="drawer-item-sub">Suggest products for your business</div>
            </div>
            <svg class="drawer-item-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

        <a href="{{ route('web.payments-outstanding') }}" class="drawer-item">
            <div class="drawer-item-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
            </div>
            <div class="drawer-item-body">
                <div class="drawer-item-title">View Credit Status</div>
                <div class="drawer-item-sub">Check your credit limit &amp; outstanding</div>
            </div>
            <svg class="drawer-item-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

       <a href="{{ route('customer.logout') }}" class="drawer-item">
            <div class="drawer-item-icon logout">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </div>
            <div class="drawer-item-body">
                <div class="drawer-item-title logout-text">Logout</div>
                <div class="drawer-item-sub">Sign out from your account</div>
            </div>
            <svg class="drawer-item-chevron logout-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <div class="drawer-footer">
        <div class="drawer-footer-logo">
            <span class="z1">z</span><span class="o1">o</span><span class="rest">nik</span>
        </div>
        <div class="drawer-footer-sub">B2B Food Supply</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('sidebarToggle');
    const drawer = document.getElementById('accountDrawer');
    const overlay = document.getElementById('accountDrawerOverlay');
    const closeButton = document.getElementById('accountDrawerClose');
    if (!toggle || !drawer || !overlay) return;

    function setDrawer(open) {
        drawer.classList.toggle('open', open);
        overlay.classList.toggle('open', open);
        document.body.classList.toggle('drawer-open', open);
        drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) closeButton?.focus();
        else toggle.focus();
    }

    drawer.setAttribute('aria-hidden', 'true');
    toggle.setAttribute('aria-controls', 'accountDrawer');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.addEventListener('click', function () { setDrawer(true); });
    closeButton?.addEventListener('click', function () { setDrawer(false); });
    overlay.addEventListener('click', function () { setDrawer(false); });
    drawer.addEventListener('click', function (event) {
        if (event.target.closest('a.drawer-item')) setDrawer(false);
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && drawer.classList.contains('open')) setDrawer(false);
    });
});
</script>


</body>
</html>
