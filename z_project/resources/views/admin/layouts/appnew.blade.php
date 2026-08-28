<!DOCTYPE html>
<html>
<head>
    <title>Grocery App</title>
    <!-- Include your CSS and other head elements -->
     <!-- Add for price log sheet -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <script>
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
      <!-- Add for price log sheet -->
</head>
<body>

    @php
    $headerView = app('App\Http\Controllers\LayoutAdminController')->header();
    $footerView = app('App\Http\Controllers\LayoutAdminController')->footer();
    @endphp
    {{--  @include('admin.includes.headernew')

    <div class="content">
        @yield('content')
           @yield('js')
    </div>

    @include('admin.includes.footernew')  --}}


{!! $headerView !!}

<style>
:root{--zn-sidebar:272px;--zn-ink:#172033;--zn-muted:#667085;--zn-primary:#3157d5;--zn-bg:#f4f6fb;--zn-line:#e5e9f2}
body{background:var(--zn-bg)!important;color:var(--zn-ink);overflow-x:hidden}
#proBanner{display:none!important}
.horizontal-menu .top-navbar{position:fixed!important;inset:0 0 auto 0;z-index:1040;height:72px;background:rgba(255,255,255,.96)!important;border-bottom:1px solid var(--zn-line);box-shadow:0 4px 24px rgba(22,34,66,.06);backdrop-filter:blur(12px)}
.horizontal-menu .top-navbar .container{max-width:none!important;padding:0 24px 0 calc(var(--zn-sidebar) + 24px)!important}
.horizontal-menu .navbar-menu-wrapper{height:72px!important;padding:0!important}
.navbar-brand-wrapper{position:fixed;left:0;top:0;width:var(--zn-sidebar)!important;height:72px;background:#fff;border-right:1px solid var(--zn-line);z-index:2}
.navbar-brand img{max-height:48px!important;width:auto!important;object-fit:contain}
.horizontal-menu .bottom-navbar{position:fixed!important;display:block!important;left:0;top:72px;bottom:0;width:var(--zn-sidebar);height:calc(100dvh - 72px)!important;min-height:0!important;background:#fff!important;border-right:1px solid var(--zn-line);box-shadow:none!important;overflow-y:auto;overflow-x:hidden;overscroll-behavior:contain;z-index:1035;padding:16px 12px 32px;filter:none!important;-webkit-filter:none!important;backdrop-filter:none!important;-webkit-backdrop-filter:none!important;opacity:1!important;visibility:visible!important;isolation:isolate}
.horizontal-menu .bottom-navbar,.horizontal-menu .bottom-navbar *{text-shadow:none!important}
.horizontal-menu .bottom-navbar .page-navigation,.horizontal-menu .bottom-navbar .nav-item,.horizontal-menu .bottom-navbar .nav-link,.horizontal-menu .bottom-navbar .menu-title,.horizontal-menu .bottom-navbar .menu-icon{filter:none!important;-webkit-filter:none!important;opacity:1!important;visibility:visible!important}
.horizontal-menu .bottom-navbar .container-fluid{padding:0!important}
.horizontal-menu .bottom-navbar .page-navigation{display:flex!important;flex-direction:column!important;width:100%;gap:5px}
.horizontal-menu .bottom-navbar .page-navigation>.nav-item{width:100%!important;margin:0!important}
.horizontal-menu .bottom-navbar .page-navigation>.nav-item>.nav-link{min-height:46px!important;padding:11px 13px!important;border-radius:11px!important;color:#344054!important;display:flex!important;align-items:center!important;gap:11px;font-weight:700!important;transition:.18s ease}
.horizontal-menu .bottom-navbar .page-navigation>.nav-item>.nav-link:hover,.horizontal-menu .bottom-navbar .page-navigation>.nav-item.active>.nav-link{background:#eef2ff!important;color:var(--zn-primary)!important}
.horizontal-menu .bottom-navbar .menu-icon{font-size:21px!important;margin:0!important;color:inherit!important}
.horizontal-menu .bottom-navbar .menu-title{flex:1;text-align:left;white-space:normal;line-height:1.25;color:inherit!important;font-size:14px!important;-webkit-font-smoothing:antialiased}
.horizontal-menu .bottom-navbar .menu-arrow{margin-left:auto!important}
.horizontal-menu .bottom-navbar .submenu{position:static!important;display:none;width:100%!important;min-width:0!important;box-shadow:none!important;border:0!important;background:#f8f9fc!important;border-radius:10px!important;margin:4px 0 8px!important;padding:6px!important;transform:none!important}
.horizontal-menu .bottom-navbar .nav-item.zn-open>.submenu{display:block!important}
.horizontal-menu .bottom-navbar .submenu ul{padding:0!important;display:block!important}
.horizontal-menu .bottom-navbar .submenu li{width:100%!important}
.horizontal-menu .bottom-navbar .submenu .nav-link{padding:9px 11px!important;border-radius:8px;color:#667085!important;font-size:13px!important}
.horizontal-menu .bottom-navbar .submenu .nav-link:hover{background:#fff!important;color:var(--zn-primary)!important}
.horizontal-menu .bottom-navbar .submenu .nav-link.active{background:#fff!important;color:var(--zn-primary)!important;font-weight:700!important}
.horizontal-menu .bottom-navbar .nav-item.active>.nav-link .menu-arrow,.horizontal-menu .bottom-navbar .nav-item.zn-open>.nav-link .menu-arrow{transform:rotate(180deg)}
.content{margin-left:var(--zn-sidebar);padding:96px 24px 32px;min-height:100vh;transition:margin .22s ease}
.content>.container,.content>.container-fluid{max-width:1600px}
.footer{margin-left:var(--zn-sidebar)!important;background:transparent!important;border:0!important;padding:18px 24px 28px!important}
.zn-sidebar-toggle{position:fixed;top:17px;left:calc(var(--zn-sidebar) - 54px);z-index:1050;width:38px;height:38px;border:1px solid var(--zn-line);border-radius:11px;background:#fff;color:#344054;display:grid;place-items:center;cursor:pointer;box-shadow:0 4px 12px rgba(16,24,40,.06)}
.zn-sidebar-toggle .mdi{font-size:23px}
.zn-sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.48);z-index:1030;backdrop-filter:none!important;-webkit-backdrop-filter:none!important}
.zn-mobile-nav{display:none}
body.zn-sidebar-collapsed{--zn-sidebar:82px}
body.zn-sidebar-collapsed .bottom-navbar .menu-title,body.zn-sidebar-collapsed .bottom-navbar .menu-arrow{display:none!important}
body.zn-sidebar-collapsed .bottom-navbar .submenu{display:none!important}
body.zn-sidebar-collapsed .bottom-navbar .nav-link{justify-content:center!important}
body.zn-sidebar-collapsed .navbar-brand .brand-logo{display:none!important}
body.zn-sidebar-collapsed .navbar-brand .brand-logo-mini{display:block!important}
.card{border:1px solid #e8ecf3!important;border-radius:16px!important;box-shadow:0 7px 24px rgba(16,24,40,.05)!important}
.table-responsive{border-radius:12px;-webkit-overflow-scrolling:touch}
@media(max-width:991.98px){
 body{padding-bottom:70px}.horizontal-menu .top-navbar{height:62px}.horizontal-menu .top-navbar .container{padding:0 12px 0 66px!important}.horizontal-menu .navbar-menu-wrapper{height:62px!important}.navbar-brand-wrapper{position:fixed;width:58px!important;height:62px;border-right:0}.navbar-brand-wrapper .brand-logo{display:none!important}.navbar-brand-wrapper .brand-logo-mini{display:block!important}.navbar-brand img{max-width:42px!important;max-height:42px!important}.notification-center{display:none!important}
 .horizontal-menu .bottom-navbar{top:0;bottom:0;left:0;width:min(84vw,310px);height:100dvh!important;min-height:100dvh!important;padding:78px 12px 90px;transform:translateX(-105%);transition:transform .24s ease;z-index:1060;background:#fff!important;box-shadow:16px 0 40px rgba(15,23,42,.16)!important}
 body.zn-mobile-menu-open .horizontal-menu .bottom-navbar{transform:translateX(0)}body.zn-mobile-menu-open .zn-sidebar-overlay{display:block}
 .zn-sidebar-toggle{top:12px;left:12px;width:38px;height:38px}.content{margin-left:0;padding:78px 12px 88px}.footer{margin-left:0!important;padding-bottom:84px!important}.navbar-toggler[data-toggle="horizontal-menu-toggle"]{display:none!important}
 .zn-mobile-nav{position:fixed;display:grid;grid-template-columns:repeat(4,1fr);left:10px;right:10px;bottom:calc(8px + env(safe-area-inset-bottom,0px));height:58px;background:rgba(255,255,255,.97);border:1px solid var(--zn-line);border-radius:17px;box-shadow:0 12px 34px rgba(15,23,42,.18);z-index:1050;padding:5px;backdrop-filter:blur(14px)}
 .zn-mobile-nav a,.zn-mobile-nav button{border:0;background:transparent;color:#667085;text-decoration:none;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;font-size:10px;font-weight:700;border-radius:12px}.zn-mobile-nav .mdi{font-size:21px}.zn-mobile-nav a.active{background:#eef2ff;color:var(--zn-primary)}
 .dataTables_wrapper .dt-buttons,.dataTables_wrapper .dataTables_length,.dataTables_wrapper .dataTables_filter{float:none!important;width:100%;margin:5px 0!important}.dataTables_wrapper .dataTables_filter input{max-width:100%!important;width:calc(100% - 20px)!important}.card-body{padding:14px!important}
}
</style>
<button class="zn-sidebar-toggle" id="znSidebarToggle" type="button" aria-label="Toggle navigation"><i class="mdi mdi-menu"></i></button>
<div class="zn-sidebar-overlay" id="znSidebarOverlay"></div>

<div class="content" id="znAdminContent">
    @yield('content')
    @yield('js')
</div>

<nav class="zn-mobile-nav" aria-label="Admin quick navigation">
    <a href="/adminnew" class="{{ request()->is('adminnew') ? 'active' : '' }}"><i class="mdi mdi-view-dashboard-outline"></i><span>Home</span></a>
    <a href="javascript:void(0)" id="znMobileSearch"><i class="mdi mdi-magnify"></i><span>Search</span></a>
    <a href="javascript:void(0)" id="znMobileNotifications"><i class="mdi mdi-bell-outline"></i><span>Alerts</span></a>
    <button type="button" id="znMobileMore"><i class="mdi mdi-menu"></i><span>Menu</span></button>
</nav>

{!! $footerView !!}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const toggle = document.getElementById('znSidebarToggle');
    const overlay = document.getElementById('znSidebarOverlay');
    const mobileMore = document.getElementById('znMobileMore');
    const closeMobile = () => body.classList.remove('zn-mobile-menu-open');
    const toggleNavigation = () => {
        if (window.innerWidth < 992) body.classList.toggle('zn-mobile-menu-open');
        else {
            body.classList.toggle('zn-sidebar-collapsed');
            localStorage.setItem('znSidebarCollapsed', body.classList.contains('zn-sidebar-collapsed') ? '1' : '0');
        }
    };
    if (window.innerWidth >= 992 && localStorage.getItem('znSidebarCollapsed') === '1') body.classList.add('zn-sidebar-collapsed');
    toggle?.addEventListener('click', toggleNavigation);
    mobileMore?.addEventListener('click', toggleNavigation);
    overlay?.addEventListener('click', closeMobile);
    document.querySelectorAll('.bottom-navbar .page-navigation > .nav-item > .nav-link').forEach(function (link) {
        const item = link.closest('.nav-item');
        const submenu = item?.querySelector(':scope > .submenu');
        if (!submenu) return;
        link.addEventListener('click', function (event) {
            event.preventDefault();
            document.querySelectorAll('.bottom-navbar .nav-item.zn-open').forEach(function (openItem) { if (openItem !== item) openItem.classList.remove('zn-open'); });
            item.classList.toggle('zn-open');
        });
        const currentPath = window.location.pathname.replace(/\/$/, '') || '/';
        const currentLink = Array.from(submenu.querySelectorAll('a[href]')).find(function (submenuLink) {
            try { return new URL(submenuLink.href, window.location.origin).pathname.replace(/\/$/, '') === currentPath; }
            catch (error) { return false; }
        });
        if (currentLink) {
            currentLink.classList.add('active');
            item.classList.add('zn-open', 'active');
        }
    });
    document.querySelectorAll('.bottom-navbar .submenu a').forEach(function (link) {
        link.addEventListener('click', closeMobile);
    });
    document.getElementById('znMobileSearch')?.addEventListener('click', function () {
        const search = document.querySelector('.dataTables_filter input, input[type="search"], .form-control[placeholder*="Search" i]');
        if (search) { search.scrollIntoView({behavior:'smooth', block:'center'}); setTimeout(() => search.focus(), 350); }
        else toggleNavigation();
    });
    document.getElementById('znMobileNotifications')?.addEventListener('click', function () {
        const trigger = document.querySelector('[data-bs-target*="offcanvas"], .notification-center a, .notification-center button');
        if (trigger) trigger.click(); else toggleNavigation();
    });
});
    let inactivityTimeout;

function resetInactivityTimer() {
  
    clearTimeout(inactivityTimeout);

   
    inactivityTimeout = setTimeout(function() {
        location.reload();
    }, 1800000);
}

// Reset the inactivity timer whenever there is user interaction
document.addEventListener('mousemove', resetInactivityTimer); // Mouse move
document.addEventListener('keydown', resetInactivityTimer);   // Key press
document.addEventListener('click', resetInactivityTimer);     // Click event
document.addEventListener('scroll', resetInactivityTimer);    // Scroll event

// Initialize the inactivity timer when the page loads
resetInactivityTimer();

</script>
</body>
</html>
