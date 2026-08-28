<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Zonik Admin Panel</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('vendors/adminnew/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('vendors/adminnew/images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">  --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
</head>
<style>
    .horizontal-menu .bottom-navbar .page-navigation>.nav-item:not(.mega-menu) .submenu{
        top: 60px;
    }
    
     /* CSS for loader */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgb(255, 255, 255); /* semi-transparent white background */
            z-index: 9999; /* ensure loader appears above all other elements */
            display: block; /* initially hidden */
        }
        
        .table td{
            font-weight: 700;
        }

        .loader img {
            position: absolute;
            top: 50%;
            left: 50%;
            /* width: 150px; */
            /* height: 150px; */
            mix-blend-mode: multiply;
            transform: translate(-50%, -50%);
        }

.header-wishlist span {
    width: 14px;
    height: 15px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    background-color: #a558c8;
    font-size: 9px;
    padding: 0;
    border-radius: 2px;
}

.start-100 {
    left: 81.6rem !important;
}
.top-0 {
    top: 32px !important;
}
.offcanvas-header {
    border-bottom: 2px solid #eceff7 !important;
}

.offcanvas {
    position: fixed;
    bottom: 0;
    z-index: 1080;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    max-width: 100%;
    visibility: hidden;
    background-color: #fff;
    background-clip: padding-box;
    outline: 0;
    -webkit-box-shadow: -1px 0 10px 0 rgba(34, 34, 34, 0.07),
        5px 20px 40px 0 rgba(34, 34, 34, 0.04);
    box-shadow: -1px 0 10px 0 rgba(34, 34, 34, 0.07),
        5px 20px 40px 0 rgba(34, 34, 34, 0.04);
    -webkit-transition: -webkit-transform 0.3s ease-in-out;
    transition: -webkit-transform 0.3s ease-in-out;
    transition: transform 0.3s ease-in-out;
    transition: transform 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out;
    will-change: transform, box-shadow;
    -webkit-transition: -webkit-transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1),
        -webkit-box-shadow 0.3s ease;
    transition: -webkit-transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1),
        -webkit-box-shadow 0.3s ease;
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1),
        box-shadow 0.3s ease;
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1),
        box-shadow 0.3s ease,
        -webkit-transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1),
        -webkit-box-shadow 0.3s ease;
    visibility: visible !important;
}

.offcanvas:not(.offcanvas-end):not(.offcanvas-bottom) {
    top: 0;
    left: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
}

@media (max-width: 1199px) {
    .offcanvas:not(.offcanvas-end):not(.offcanvas-bottom) {
        width: calc(300px + (320 - 300) * ((100vw - 1200px) / (1920 - 1200)));
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
        z-index: 7;
    }
}

.offcanvas-header {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    padding: 20px 24px;
    z-index: 1;
}

.offcanvas-header .btn-close {
    padding: 0;
    margin: -10px 0;
    font-size: 16px;
}

.offcanvas-header h5 {
    color: var(--theme-color);
    font-weight: 600;
}

.offcanvas-title {
    margin-bottom: 0;
    line-height: initial;
}

.offcanvas-body {
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    /* padding: 20px 24px; */
    overflow-y: auto;
    height: 100%;
}

.offcanvas-body::-webkit-scrollbar {
    width: 0;
    background-color: transparent;
    opacity: 0;
}

.offcanvas-body::-webkit-scrollbar-thumb {
    border-radius: 4px;
}

.offcanvas-body>.simplebar-track {
    display: block;
    background-color: transparent;
}

.offcanvas-body .simplebar-vertical {
    margin-right: 3px;
}

.offcanvas-start {
    top: 0;
    left: 0;
    width: 352px;
    border-right: 0 solid transparent;
    -webkit-transform: translateX(-100%);
    transform: translateX(-100%);
}

.offcanvas-end {
    top: 0;
    right: 0;
    width: 422px;
    border-left: 0 solid transparent;
    -webkit-transform: translateX(100%);
    transform: translateX(100%);
}

.offcanvas-top {
    top: 0;
    right: 0;
    left: 0;
    height: 30vh;
    max-height: 100%;
    border-bottom: 0 solid transparent;
    -webkit-transform: translateY(-100%);
    transform: translateY(-100%);
}

.offcanvas-bottom {
    right: 0;
    left: 0;
    height: 30vh;
    max-height: 100%;
    border-top: 0 solid transparent;
    -webkit-transform: translateY(100%);
    transform: translateY(100%);
}

.offcanvas.show {
    -webkit-transform: none !important;
    transform: none !important;
    -webkit-box-shadow: -1px 0 10px 0 rgba(34, 34, 34, 0.07),
        5px 20px 40px 0 rgba(34, 34, 34, 0.04) !important;
    box-shadow: -1px 0 10px 0 rgba(34, 34, 34, 0.07),
        5px 20px 40px 0 rgba(34, 34, 34, 0.04) !important;
}

.offcanvas-footer {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    padding: 20px 24px;
}
h3 {
    font-size: calc(16px + (20 - 16) * ((100vw - 320px) / (1920 - 320)));
    font-weight: 500;
    line-height: 1.2;
    margin: 0;
    color : black;
}

.active {
        font-weight: bold;
    }

    .d-flex {
        gap: 10px;
    }

    .bell-icon {
        position: relative;
        font-size: 22px;
    }

    .bell-icon .badge {
    position: absolute;
    top: 5px; /* Adjust vertically (negative to move up) */
    right: -0px; /* Adjust horizontally (negative to move left) */
    background-color: red; /* Background color for the badge */
    color: white; /* Text color */
    border-radius: 50%; /* Make the badge circular */
    padding: 3px 6px; /* Adjust padding for size */
    font-size: 10px; /* Font size for the number */
    font-weight: bold; /* Bold text */
    transform: translate(50%, -50%); /* Ensures proper alignment */
    z-index: 10; /* Ensure the badge stays on top */
    display: flex;
    align-items: center;
    justify-content: center;
}

      #button {
        display: inline-block;
        background-color: #a558c8;
        width: 50px;
        height: 50px;
        text-align: center;
        border-radius: 4px;
        position: fixed;
        bottom: 20px;
        right: 12px;
        transition: background-color .3s, opacity .5s, visibility .5s;
        opacity: 0;
        visibility: hidden;
        z-index: 1000;
    }

    /* Mobile styles */
    @media (max-width: 600px) {
        #button {
            width: 50px;   /* Adjust width for mobile */
            height: 50px;  /* Adjust height for mobile */
            bottom: 70px;  /* Adjust bottom position for mobile */
            right: 10px;   /* Adjust right position for mobile */
        }
    }

.search-box {
    position: relative;
    display: inline-block;
}

.mbo-count {
    position: absolute;
    top: -10px; /* Adjust this value as needed */
    right: -10px; /* Adjust this value as needed */
    background-color: red; /* Background color for the count badge */
    color: white; /* Text color for the count badge */
    border-radius: 50%; /* Make the badge circular */
    padding: 5px 10px; /* Adjust padding for size */
    font-size: 12px; /* Adjust font size as needed */
    font-weight: bold; /* Make the text bold */
}


    .notifications-ul {
    list-style-type: none;
    padding: 0;
}

.notification-item {
    display: flex;
    align-items: center;
    padding: 5px;
    border-bottom: 1px solid #ddd;
}

.notification-icon {
    margin-right: 12px;
}

.icon-circle {
    width: 40px;
    height: 40px;
    background-color: #11af6d; /* Circle background color */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-circle i {
    color: #fff; /* Icon color */
    font-size: 20px; /* Icon size */
}

.notification-content {
    flex: 1;
}

.notification-date {
    font-size: 12px;
    color: #999;
    margin-bottom: 4px;
}

.notification-text {
    font-size: 12px;
}


    #button::after {
        content: "\f077";
        font-family: FontAwesome;
        font-weight: normal;
        font-style: normal;
        font-size: 2em;
        line-height: 50px;
        color: #fff;
    }

    #button:hover {
        cursor: pointer;
        background-color: #a558c8;
    }

    #button:active {
        background-color: #555;
    }

    #button.show {
        opacity: 1;
        visibility: visible;
    }

    .border-red {
        font-weight: 500 !important;
        margin-top: 10px;
        border: 1px solid #a558c8;
        padding: 5px;
        text-align: center;
        border-radius: 5px;
        font-size: 12px;
    }

    .notifications-ul li {
        background-color: aliceblue;
        margin-bottom: 20px;
        width: 100%;
        border-radius: 5px;
        background-color: aliceblue;
        border: 1px solid #abd8ff;
    }

    /* Keep notification drawers independent from the generic offcanvas and
       sidebar styles above. The old 100% body height pushed notification
       cards below the viewport, so only a thin, clipped strip was visible. */
    .admin-notification-offcanvas {
        width: min(422px, 100vw);
        height: 100vh;
        overflow: hidden;
    }

    .admin-notification-offcanvas .offcanvas-header {
        min-height: 62px;
        padding: 18px 22px;
        border-bottom: 1px solid #e6ebf2 !important;
    }

    .admin-notification-offcanvas .offcanvas-header h3 {
        color: #111827;
        font-size: 20px;
        font-weight: 700;
    }

    .admin-notification-offcanvas .offcanvas-body {
        height: auto;
        min-height: 0;
        padding: 12px;
        overflow-y: auto;
        overscroll-behavior: contain;
        background: #f8fafc;
    }

    .admin-notification-offcanvas .offcanvas-body::-webkit-scrollbar {
        width: 7px;
    }

    .admin-notification-offcanvas .offcanvas-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .admin-notification-offcanvas .notifications-ul {
        display: grid;
        gap: 10px;
        margin: 0;
        padding: 0;
    }

    .admin-notification-offcanvas .notifications-ul li {
        margin: 0;
        overflow: hidden;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        background: #ffffff;
    }

    .admin-notification-offcanvas .notification-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .admin-notification-offcanvas .notification-link:hover {
        background: #f0f7ff;
    }

    .admin-notification-offcanvas .notification-item {
        min-height: 72px;
        padding: 12px;
        border: 0;
    }

    .admin-notification-offcanvas .icon-circle {
        width: 38px;
        height: 38px;
        background: #e8f5ee;
    }

    .admin-notification-offcanvas .icon-circle i {
        color: #119563;
        font-size: 17px;
    }

    .admin-notification-offcanvas .notification-text {
        color: #1f2937;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.35;
    }

    .admin-notification-offcanvas .notification-date {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 12px;
    }

    @media (max-width: 575px) {
        .admin-notification-offcanvas { width: 100vw; }
    }

    .slide-drawer-a {
        text-decoration: none !important;
        /* padding:10px !important;
        margin-bottom: 15px !important;  */
        border-radius: 5px !important;
        font-weight: 500 !important;
        color: inherit !important;
    }

    .slide-drawer-i {
        margin-right: 10px !important;
        font-size: 20px !important;
    }
    @keyframes shake {
  0% {
    transform: rotate(35deg);
  }

  12.5% {
    transform: rotate(-30deg);
  }

  25% {
    transform: rotate(25deg);
  }

  37.5% {
    transform: rotate(-20deg);
  }

  50% {
    transform: rotate(15deg);
  }

  62.5% {
    transform: rotate(-10deg);
  }

  75% {
    transform: rotate(5deg);
  }

  100% {
    transform: rotate(0deg);
  }
}


.icon-left-right {
    animation: shake 2s infinite ease;
}


.nav-order-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Reserve space whether button exists or not */
/*.notification-slot {*/
/*    height: 32px;          */
/*    display: flex;*/
/*    align-items: center;*/
/*    justify-content: center;*/
/*    margin-top: -33px;*/
/*}*/

</style>
<body>
    <!-- <div class="loader" id="loader">
    <img src="/frontweb/assets/images/loader.png" alt="Loading...">
</div> -->
    <div class="container-scroller">
        <div class="row p-0 m-0 proBanner" id="proBanner">
            <div class="col-md-12 p-0 m-0">
                <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
                    <div class="ps-lg-1">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 font-weight-medium me-3 buy-now-text">Free 24/7 customer support, updates,
                                and more with this template!</p>
                            <a href="https://www.bootstrapdash.com/product/kapella-admin-pro/?utm_source=organic&utm_medium=banner&utm_campaign=buynow_demo"
                                target="_blank" class="btn me-2 buy-now-btn border-0">Get Pro</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="https://www.bootstrapdash.com/product/kapella-admin-pro/"><i
                                class="mdi mdi-home me-3 text-white"></i></a>
                        <button id="bannerClose" class="btn border-0 p-0">
                            <i class="mdi mdi-close text-white me-0"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
 @php
$userRole = optional(Auth::guard('admin')->user())->role_id;
@endphp
        <!-- partial:partials/_horizontal-navbar.html -->
        <div class="horizontal-menu">
            <nav class="navbar top-navbar col-lg-12 col-12 p-0">
                <div class="container">
                    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">

                        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                            <a class="navbar-brand brand-logo" href="/adminnew"><img
                                    src="https://zonik.in/frontweb/assests/images/Adobe Express - file.png" alt="logo" /></a>
                            <a class="navbar-brand brand-logo-mini" href="/adminnew"><img
                                    src="https://zonik.in/frontweb/assests/images/Adobe Express - file.png" alt="logo" /></a>
                        </div>
                        
                         <div class="d-flex align-items-center justify-content-center flex-grow-1 notification-center">
                            @include('admin.includes.notifications')
                        </div>
                        
                        <ul class="navbar-nav navbar-nav-right">

                       

                        <!-- Sale Management Notification -->

                
                            <div class="offcanvas offcanvas-end admin-notification-offcanvas" tabindex="-1" id="offcanvasRight"
                                aria-labelledby="offcanvasRightLabel">
                                <div class="offcanvas-header">
                                    <h3 id="offcanvasRightLabel">Sales Notifications</h3>
                                    <button type="button" class="btn-close text-reset"
                                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                <ul class="notifications-ul">
                                @forelse($adminNotifications as $notification)
                                <li>
                                <a href="{{ route('notification.read',$notification->id) }}" class="notification-link">
                                    <div class="notification-item">
                                            <div class="notification-icon">
                                                <div class="icon-circle">
                                                    <i class="far fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                            <div class="notification-content">
                                            <div class="notification-text">{{ __($notification->title) }}</div>
                                            <div class="notification-date">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                                        </div>
                                        </div>
                                    </a>
                                </li>
                                @empty
                                    <li>
                                        <div class="notification-item">
                                            <div class="notification-content">
                                                <div class="notification-text">No new notifications</div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforelse
                            </ul>
                                </div>
                            </div>
                          

                                    
                            <!-- User Management Notification Offcanvas -->
                    
                        <div class="offcanvas offcanvas-end admin-notification-offcanvas" tabindex="-1" id="userOffcanvasRight" aria-labelledby="userOffcanvasRightLabel">
                            <div class="offcanvas-header">
                                <h3 id="userOffcanvasRightLabel">User Notifications</h3>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="notifications-ul">
                                    @forelse($NewUsernotifications as $notification)
                                    <li>
                                        <a href="{{ route('usernotification.read', $notification->id) }}" class="notification-link">
                                            <div class="notification-item">
                                                <div class="notification-icon">
                                                    <div class="icon-circle">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                </div>
                                                <div class="notification-content">
                                                    <div class="notification-text">{{ __($notification->title) }}</div>
                                                    <div class="notification-date">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    @empty
                                    <li>
                                        <div class="notification-item">
                                            <div class="notification-content">
                                                <div class="notification-text">No new notifications</div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                     

                        <!-- Order Management Notification Offcanvas -->
                      
                        <div class="offcanvas offcanvas-end admin-notification-offcanvas" tabindex="-1" id="orderOffcanvasRight" aria-labelledby="orderOffcanvasRightLabel">
                            <div class="offcanvas-header">
                                <h3 id="orderOffcanvasRightLabel">Order Notifications</h3>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="notifications-ul">
                                    @forelse($oredrnotifications as $notification)
                                    <li>
                                        <a href="{{ route('ordernotification.read', $notification->id) }}" class="notification-link">
                                            <div class="notification-item">
                                                <div class="notification-icon">
                                                    <div class="icon-circle">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                </div>
                                                <div class="notification-content">
                                                    <div class="notification-text">{{ __($notification->title) }}</div>
                                                    <div class="notification-date">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    @empty
                                    <li>
                                        <div class="notification-item">
                                            <div class="notification-content">
                                                <div class="notification-text">No new notifications</div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                     

                                    

                            <li class="nav-item dropdown d-lg-flex d-none">
                                <a class="dropdown-toggle show-dropdown-arrow btn btn-inverse-primary btn-sm"
                                    id="nreportDropdown" href="#" data-bs-toggle="dropdown">
                                    Reports
                                </a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                    aria-labelledby="nreportDropdown">
                                    <p class="mb-0 font-weight-medium float-left dropdown-header">Reports</p>
                                    <a class="dropdown-item">
                                        <i class="mdi mdi-file-pdf text-primary"></i>
                                        Pdf
                                    </a>
                                    <a class="dropdown-item">
                                        <i class="mdi mdi-file-excel text-primary"></i>
                                        Exel
                                    </a>
                                </div>
                            </li>

                            <li class="nav-item nav-profile dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                    id="profileDropdown">
                                    <span class="nav-profile-name">Zonik</span>
                                    <span class="online-status"></span>

                                </a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                                    aria-labelledby="profileDropdown">

                                    <a href="{{ route('admin.newlogout') }}" class="dropdown-item">
                                        <i class="mdi mdi-logout text-primary"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                            data-toggle="horizontal-menu-toggle">
                            <span class="mdi mdi-menu"></span>
                        </button>
                    </div>
                </div>
 


               <nav class="bottom-navbar">
<div class="container-fluid">
<ul class="nav page-navigation">

{{-- ================= MANAGEMENT ================= --}}
@if(
    hasPermission('management.dashboard.view') ||
    hasPermission('management.approved_po.view') ||
    hasPermission('management.user_assignment.manage') ||
    hasPermission('management.dashboard_assignment.manage')
)
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
        <span class="menu-title">Management</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>
            @if(hasPermission('management.dashboard.view'))
            <li><a class="nav-link" href="/adminnew">Dashboard</a></li>
            @endif

            @if(hasPermission('management.approved_po.view'))
            <li><a class="nav-link" href="{{ route('admin.purchase-orders.approval') }}">Approved PO</a></li>
            @endif

            @if(hasPermission('management.user_assignment.manage'))
            <li><a class="nav-link" href="{{ route('roles.index') }}">User Assignment</a></li>
            @endif
            
            @if(hasPermission('management.dashboard_assignment.manage'))
            <li><a class="nav-link" href="{{ route('dashboard-assignment.index') }}">Dashboard Assignment</a></li>
            @endif
            
        </ul>
    </div>
</li>
@endif


{{-- ================= DATA MANAGEMENT ================= --}}
@if(
    hasPermission('data.category.manage') ||
    hasPermission('data.subcategory.manage') ||
    hasPermission('data.product.manage') ||
    hasPermission('data.stock_opening.manage') ||
    hasPermission('data.stock_adjustment.manage') ||
    hasPermission('data.stock_transfer.manage') ||
    hasPermission('data.zone.manage')
)
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-database menu-icon"></i>
        <span class="menu-title">Data Management</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>
            @if(hasPermission('data.category.manage'))
            <li><a class="nav-link" href="{{ route('categoriess.index') }}">Category</a></li>
            @endif

            @if(hasPermission('data.subcategory.manage'))
            <li><a class="nav-link" href="{{ route('subcategoriess.index') }}">Subcategory</a></li>
            @endif

            @if(hasPermission('data.product.manage'))
            <li><a class="nav-link" href="{{ route('productss.index') }}">Product</a></li>
            @endif

            @if(hasPermission('data.stock_opening.manage'))
            <li><a class="nav-link" href="{{ route('admin.stock-opening') }}">Stock Opening</a></li>
            @endif

            @if(hasPermission('data.stock_adjustment.manage'))
            <li><a class="nav-link" href="{{ route('admin.stock-adjustment.index') }}">Stock Adjustment</a></li>
            @endif
            
             @if(hasPermission('data.stock_transfer.manage'))
            <li><a class="nav-link" href="{{ route('admin.stock-transfer.index') }}">Stock Transfer</a></li>
            @endif

            @if(hasPermission('data.zone.manage'))
            <li><a class="nav-link" href="/zoneprocessings">Zone Manage</a></li>
            @endif
        </ul>
    </div>
</li>
@endif


{{-- ================= DIGITAL MARKETING ================= --}}
@if(
    hasPermission('marketing.banners.manage') ||
    hasPermission('marketing.offers.manage') ||
    hasPermission('marketing.brands.manage') ||
    hasPermission('marketing.clients.manage')
)
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-bullhorn-outline menu-icon"></i>
        <span class="menu-title">Digital Marketing</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>
            @if(hasPermission('marketing.banners.manage'))
            <li><a class="nav-link" href="{{ route('banners.index') }}">Main Banners</a></li>
            @endif

            @if(hasPermission('marketing.offers.manage'))
            <li><a class="nav-link" href="{{ route('festivalandoffers.index') }}">Offers Banner</a></li>
            @endif

            @if(hasPermission('marketing.brands.manage'))
            <li><a class="nav-link" href="{{ route('brandsassoc.index') }}">Brand Logos</a></li>
            @endif

            @if(hasPermission('marketing.clients.manage'))
            <li><a class="nav-link" href="{{ route('clientsserve.index') }}">Clients Logos</a></li>
            @endif
        </ul>
    </div>
</li>
@endif


{{-- ================= FRONT END SALES ================= --}}
@if(
    hasPermission('frontend.enquiry.manage') ||
    hasPermission('frontend.approved_price_list.view') ||
    hasPermission('frontend.customer_group.manage') ||
    hasPermission('frontend.customer_outlet.manage') ||
    hasPermission('frontend.customer_payment.manage') ||
    hasPermission('frontend.customer_price_list.manage') ||
    hasPermission('frontend.sales_report.view') ||
    hasPermission('frontend.product_request.manage') ||
    hasPermission('frontend.logistics.view') ||
    hasPermission('frontend.urgent_sale.view')
)
<li class="nav-item nav-order-wrapper">



    <a href="#" class="nav-link">
        <i class="mdi mdi-cart-outline menu-icon"></i>
        <span class="menu-title">Front End Sales</span>
        <i class="menu-arrow"></i>
    </a>

    <div class="submenu">
        <ul>

            @if(hasPermission('frontend.enquiry.manage'))
            <li><a class="nav-link" href="{{ route('enquiry.indexx') }}">Quotations & Enquiry</a></li>
            @endif

            @if(hasPermission('frontend.approved_price_list.view'))
            <li><a class="nav-link" href="{{ route('approved.list') }}">Online Price List</a></li>
            @endif

            @if(hasPermission('frontend.customer_group.manage'))
            <li><a class="nav-link" href="{{ route('customer.indexx') }}">Customer Group</a></li>
            @endif

            @if(hasPermission('frontend.customer_outlet.manage'))
            <li><a class="nav-link" href="{{ route('customer.indexx',['type'=>'outlet']) }}">Customer Outlet</a></li>
            @endif

            @if(hasPermission('frontend.customer_payment.manage'))
            <li><a class="nav-link" href="{{ route('payments.update_payments') }}">Customer Payment</a></li>
            @endif

            @if(hasPermission('frontend.customer_price_list.manage'))
            <li><a class="nav-link" href="{{ route('admin.customer.price') }}">Backend Price List</a></li>
            @endif

            @if(hasPermission('frontend.sales_report.view'))
            <li><a class="nav-link" href="{{route('customer.sales.report')}}">Customer Sales Report</a></li>
            @endif

            @if(hasPermission('frontend.product_request.manage'))
            <li><a class="nav-link" href="{{ route('admin.requestedproduct') }}">New Product Requests</a></li>
            @endif

            @if(hasPermission('frontend.logistics.view'))
            <li><a class="nav-link" href="{{ route('admin.logistics.index') }}">Logistics</a></li>
           @endif
            
             @if(hasPermission('frontend.urgent_sale.view'))
            <li><a class="nav-link" href="{{ route('admin.urgent-sale-stock') }}">Products For Urgent Sale</a></li>
              @endif

        </ul>
    </div>
</li>
@endif


{{-- ================= BACK END SALES ================= --}}
@if(
    hasPermission('backend.sales_order.manage') ||
    hasPermission('backend.payment_update.manage') ||
    hasPermission('backend.invoice_list.view') ||
    hasPermission('backend.customer_approved_price_list.view') ||
    hasPermission('backend.credit_note.manage') ||
    hasPermission('backend.logistics.view') ||
    hasPermission('backend.price_logs.view') 
)
<li class="nav-item nav-order-wrapper">

  

    <a href="#" class="nav-link">
        <i class="mdi mdi-cash-register menu-icon"></i>
        <span class="menu-title">Back End Sales</span>
        <i class="menu-arrow"></i>
    </a>

    <div class="submenu">
        <ul>

            @if(hasPermission('backend.sales_order.manage'))
            <li><a class="nav-link" href="{{ route('admin.invoice') }}">Customer Sales Order</a></li>
            @endif

            @if(hasPermission('backend.payment_update.manage'))
            <li><a class="nav-link" href="{{ route('payments.update_payments') }}">Customer Payment Update</a></li>
            @endif

            @if(hasPermission('backend.invoice_list.view'))
            <li><a class="nav-link" href="{{ route('invoice.list') }}">Invoice List</a></li>
            @endif
            
            @if(hasPermission('backend.customer_approved_price_list.view'))
            <li><a class="nav-link" href="{{ route('admin.customer.price') }}">Customer Approved Price List</a></li>
            @endif

            @if(hasPermission('backend.credit_note.manage'))
            <li><a class="nav-link" href="{{ route('creditnote.index') }}">Customer Returns Credit Note</a></li>
            @endif

            @if(hasPermission('backend.logistics.view'))
            <li><a class="nav-link" href="{{ route('admin.logistics.index') }}">Logistics</a></li>
            @endif
            
             @if(hasPermission('backend.price_logs.view'))
            <li>
                <a class="nav-link" href="{{ route('admin.price.logs') }}">
                    Price Change Logs
                </a>
            </li>
            @endif

        </ul>
    </div>
</li>
@endif


{{-- ================= ORDER PROCESS ================= --}}
@if(
    hasPermission('order.review.manage') ||
    hasPermission('order.pick_list.view') ||
    hasPermission('order.delivery.manage') ||
    hasPermission('order.logistics.view') ||
    hasPermission('order.pre_short_material_log.view') ||
    hasPermission('order.post_short_material_log.view')
)
<li class="nav-item nav-order-wrapper">

   

    <a href="#" class="nav-link">
        <i class="mdi mdi-truck-delivery menu-icon"></i>
        <span class="menu-title">Order Process</span>
        <i class="menu-arrow"></i>
    </a>

    <div class="submenu">
        <ul>

            @if(hasPermission('order.review.manage'))
            <li><a class="nav-link" href="{{ route('order.backend.details') }}">Backend Order Review / Accept Order</a></li>
            <li><a class="nav-link" href="{{ route('order.details') }}">Online Order Review / Accept Order</a></li>
            @endif

            @if(hasPermission('order.pick_list.view'))
            <li><a class="nav-link" href="{{ route('pick.list') }}">Pick List</a></li>
            @endif

            @if(hasPermission('order.delivery.manage'))
            <li><a class="nav-link" href="{{ route('admin.delivery.new_index') }}">Delivery Management</a></li>
            @endif

            @if(hasPermission('order.logistics.view'))
            <li><a class="nav-link" href="{{ route('admin.logistics.index') }}">Logistics</a></li>
            @endif
            
            @if(hasPermission('order.pre_short_material_log.view'))
            <li><a class="nav-link" href="{{route('pre.short.material.log')}}">Pre Shot Material Log</a></li>
           @endif
           
            @if(hasPermission('order.post_short_material_log.view'))
            <li><a class="nav-link" href="{{route('short.material.log')}}">Post Short Material Log</a></li>
            @endif

        </ul>
    </div>
</li>
@endif


{{-- ================= LOGISTICS ================= --}}
@if(
    hasPermission('logistics.planning.manage') ||
    hasPermission('logistics.delivery.manage') ||
    hasPermission('logistics.pick_list.view')
)
<li class="nav-item">
    <a href="#" class="nav-link">
      <i class="mdi mdi-truck-fast menu-icon"></i>
        <span class="menu-title">Logistics</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>

            @if(hasPermission('logistics.planning.manage'))
            <li><a class="nav-link" href="{{ route('admin.logistics.index') }}">Logistic Planning & Update</a></li>
            @endif

            @if(hasPermission('logistics.delivery.manage'))
            <li><a class="nav-link" href="{{ route('admin.delivery.new_index') }}">Delivery Management</a></li>
            @endif

            @if(hasPermission('logistics.pick_list.view'))
            <li><a class="nav-link" href="{{ route('pick.list') }}">Pick List</a></li>
            @endif

        </ul>
    </div>
</li>
@endif


{{-- ================= PURCHASE ================= --}}
@if(
    hasPermission('purchase.vendor.manage') ||
    hasPermission('purchase.vendor_price_list.manage') ||
    hasPermission('purchase.po.create') ||
    hasPermission('purchase.vendor_po_status.view') ||
    hasPermission('purchase.debit_note.manage') ||
    hasPermission('purchase.short_material_log.view') ||
    hasPermission('purchase.near_expiry.view') ||
    hasPermission('purchase.expired_products.view') ||
    hasPermission('purchase.damaged_report.view') ||
    hasPermission('purchase.return_report.view')
)
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-cart-plus menu-icon"></i>
        <span class="menu-title">Purchase</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>

            @if(hasPermission('purchase.vendor.manage'))
            <li><a class="nav-link" href="{{ route('vendors.index') }}">Create Vendor</a></li>
            @endif

            @if(hasPermission('purchase.vendor_price_list.manage'))
            <li><a class="nav-link" href="{{ route('vendor.price.index') }}">Vendors Price List</a></li>
            @endif

            @if(hasPermission('purchase.po.create'))
            <li><a class="nav-link" href="{{ route('admin.purchase-orders.index') }}">Create PO</a></li>
            @endif

            @if(hasPermission('purchase.vendor_po_status.view'))
            <li><a class="nav-link" href="{{ route('admin.purchase-orders.approval') }}">Vendor PO & Status</a></li>
            @endif

            @if(hasPermission('purchase.debit_note.manage'))
            <li><a class="nav-link" href="{{ route('debitnote.index') }}">Debit Note</a></li>
            @endif
           
            @if(hasPermission('purchase.short_material_log.view'))
            <li><a class="nav-link" href="{{route('short.material.log')}}">Short Material Log</a></li>
             @endif

            @if(hasPermission('purchase.near_expiry.view'))
            <li><a class="nav-link" href="{{route('admin.near-expiry-stock')}}">Near To Expiry Report</a></li>
            @endif

            @if(hasPermission('purchase.expired_products.view'))
            <li><a class="nav-link" href="{{route('admin.expired-products')}}">Expired Products Report</a></li>
            @endif

            @if(hasPermission('purchase.damaged_report.view'))
            <li><a class="nav-link" href="{{route('admin.disposals.index')}}">Damaged Report</a></li>
            @endif

            @if(hasPermission('purchase.return_report.view'))
            <li><a class="nav-link" href="{{route('admin.return.report')}}">Return & Pending Return Report</a></li>
            @endif
        </ul>
    </div>
</li>
@endif


{{-- ================= INVENTORY ================= --}}
@if(
    hasPermission('inventory.issued_po.view') ||
    hasPermission('inventory.grn.manage') ||
    hasPermission('inventory.stock_transfer.manage') ||
    hasPermission('inventory.rack_storage.manage')
)
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-warehouse menu-icon"></i>
        <span class="menu-title">Inventory</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>

            @if(hasPermission('inventory.issued_po.view'))
            <li><a class="nav-link" href="{{ route('admin.stock-receivings.pending') }}">Issued PO</a></li>
            @endif

            @if(hasPermission('inventory.grn.manage'))
            <li><a class="nav-link" href="{{ route('admin.stock-receivings.index') }}">GRN</a></li>
            @endif

            @if(hasPermission('inventory.rack_storage.manage'))
            <li><a class="nav-link" href="{{ route('admin.rack.receiving.index') }}">Rack Storage</a></li>
            @endif
            
            @if(hasPermission('data.stock_transfer.manage'))
            <li><a class="nav-link" href="{{ route('admin.stock-transfer.index') }}">Stock Transfer</a></li>
            @endif
            
           

        </ul>
    </div>
</li>
@endif


{{-- ================= BILLING ================= --}}
@if(
    hasPermission('billing.bill_approval.manage') ||
    hasPermission('billing.vendor_payment.manage') ||
    hasPermission('billing.approved_po.view') ||
    hasPermission('billing.grn.view')
)
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-file-document-outline menu-icon"></i>
        <span class="menu-title">Billing</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>

            @if(hasPermission('billing.bill_approval.manage'))
            <li><a class="nav-link" href="{{ route('admin.stock-receivings.bills') }}">Bill Review & Approval</a></li>
            @endif

            @if(hasPermission('billing.vendor_payment.manage'))
            <li><a class="nav-link" href="{{ route('admin.vendor-payments.index') }}">Vendor Payments</a></li>
            @endif

            @if(hasPermission('billing.approved_po.view'))
             <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.purchase-orders.approval') }}">
                    <span class="menu-title">Approved PO</span>
                </a>
            </li>
            @endif

            @if(hasPermission('billing.grn.view'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.purchase-orders.approval') }}">
                    <span class="menu-title">Stock Receiving Record (GRN)</span>
                </a>
            </li>
            @endif

        </ul>
    </div>
</li>
@endif

{{-- ================= LEAD GENERATION ================= --}}
@if(
    hasPermission('lead_customer.view') ||
    hasPermission('quotation.view')
)

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-file-document-edit-outline menu-icon"></i>
        <span class="menu-title">Lead Generation</span>
        <i class="menu-arrow"></i>
    </a>

    <div class="submenu">
        <ul>

            @if(hasPermission('lead_customer.view'))
            <li>
                <a class="nav-link" href="{{ route('lead-customers.index') }}">
                    Lead Customer
                </a>
            </li>
            @endif

            @if(hasPermission('quotation.view'))
            <li>
                <a class="nav-link" href="{{ route('quotations.index') }}">
                    Quotation
                </a>
            </li>
            @endif

        </ul>
    </div>
</li>

@endif


{{-- ================= REVISED INVOICE ================= --}}
@if(
    hasPermission('revised_invoice.view') ||
    hasPermission('warehouse_revised_invoice.view')
)

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-file-document-edit-outline menu-icon"></i>
        <span class="menu-title">Revised Invoice</span>
        <i class="menu-arrow"></i>
    </a>

    <div class="submenu">
        <ul>

            @if(hasPermission('revised_invoice.view'))
            <li>
                <a class="nav-link" href="{{ route('stock-return.index') }}">
                    Revised Invoice
                </a>
            </li>
            @endif

            @if(hasPermission('warehouse_revised_invoice.view'))
            <li>
                <a class="nav-link" href="{{ route('warehouse.stock-return.index') }}">
                    Warehouse Revised Invoice
                </a>
            </li>
            @endif

        </ul>
    </div>
</li>

@endif




{{-- ================= REPORTS ================= --}}
@if(
    hasPermission('reports.stock_location.view') ||
    hasPermission('reports.stock_report.view') ||
    hasPermission('reports.stock_ledger.view') ||
    hasPermission('reports.notifications.view') ||
    hasPermission('reports.reorder_point.view') ||  
    hasPermission('reports.reorder_qty.view')  ||
    hasPermission('reports.reorder_combined.view') ||
    hasPermission('reports.non_running_products.view')
)

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-chart-bar menu-icon"></i>
        <span class="menu-title">Reports</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>

            @if(hasPermission('reports.stock_location.view'))
            <li><a class="nav-link" href="{{ route('admin.rack.stock-location') }}">Live Stock Location</a></li>
            @endif

            @if(hasPermission('reports.stock_report.view'))
            <li><a class="nav-link" href="{{ route('admin.rack.live-location') }}">Live Stock Report</a></li>
            @endif

            @if(hasPermission('reports.stock_ledger.view'))
            <li><a class="nav-link" href="{{ route('admin.stock-receivings.ledger') }}">Stock Ledger</a></li>
            @endif

            @if(hasPermission('reports.notifications.view'))
            <li><a class="nav-link" href="{{ route('customer.notification.detailss') }}">Notifications</a></li>
            @endif
            
           @if(hasPermission('reports.reorder_point.view'))
            <li><a class="nav-link" href="{{ route('admin.reorder.report') }}">Reorder Point Calculation</a></li>
            @endif

             @if(hasPermission('reports.reorder_qty.view'))
            <li><a class="nav-link" href="{{ route('admin.reorder.qty.report') }}">Reorder Qty Calculation</a></li>
            @endif
            
             @if(hasPermission('reports.reorder_combined.view'))
            <li><a class="nav-link" href="{{ route('admin.reorder.qty.report.point') }}">Reorder Point & Qty Report </a></li>
            @endif
            
            @if(hasPermission('reports.non_running_products.view'))
            <li><a class="nav-link" href="{{ route('admin.nonRunningProductsReport') }}">Non-Running Products Report</a></li>
            @endif

        </ul>
    </div>
</li>
@endif


{{-- ================= ACCOUNTS ================= --}}
@if(
    hasPermission('accounts.customer_payment_history.view') ||
    hasPermission('accounts.customer_outstanding.view') ||
    hasPermission('accounts.customer_invoice_list.view') ||
    hasPermission('accounts.vendor_payment_history.view') ||
    hasPermission('accounts.vendor_outstanding.view') ||
    hasPermission('accounts.vendor_po_list.view')
)
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="mdi mdi-calculator menu-icon"></i>
        <span class="menu-title">Accounts</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="submenu">
        <ul>

            @if(hasPermission('accounts.customer_payment_history.view'))
            <li><a class="nav-link" href="{{ route('payments.index') }}">Customer Payment History</a></li>
            @endif

            @if(hasPermission('accounts.customer_outstanding.view'))
            <li><a class="nav-link" href="{{ route('outstanding.details') }}">Customer Outstanding</a></li>
            @endif

            @if(hasPermission('accounts.customer_invoice_list.view'))
            <li><a class="nav-link" href="{{ route('invoice.list') }}">Customer Invoice List</a></li>
            @endif

            @if(hasPermission('accounts.vendor_payment_history.view'))
            <li><a class="nav-link" href="{{ route('admin.vendor-payments.index') }}">Vendor Payments History</a></li>
            @endif

            @if(hasPermission('accounts.vendor_outstanding.view'))
            <li><a class="nav-link" href="{{ route('vendor.outstanding.index') }}">Vendor Outstanding</a></li>
            @endif

            @if(hasPermission('accounts.vendor_po_list.view'))
            <li><a class="nav-link" href="{{ route('admin.purchase-orders.approval') }}">Vendor Approved PO List</a></li>
            @endif

        </ul>
    </div>
</li>
@endif

</ul>
</div>
</nav>




             
    </div>
 @if($NewUserCount > 0 || $orderCount > 0 || $adminNotificationCount > 0)
    <audio id="newUserSound" src="{{ asset('/sound/user.mp3') }}"></audio>
    <audio id="newOrderSound" src="{{ asset('/sound/order.mp3') }}"></audio>
    <audio id="adminNotificationSound" src="{{ asset('/sound/sale.mp3') }}"></audio>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const newUserCount = {{ $NewUserCount }};
            const orderCount = {{ $orderCount }};
            const adminNotificationCount = {{ $adminNotificationCount }};

            // Retrieve previous counts from localStorage
            const prevNewUserCount = localStorage.getItem('prevNewUserCount') || 0;
            const prevOrderCount = localStorage.getItem('prevOrderCount') || 0;
            const prevAdminNotificationCount = localStorage.getItem('prevAdminNotificationCount') || 0;

            // Convert to numbers
            const prevUser = parseInt(prevNewUserCount);
            const prevOrder = parseInt(prevOrderCount);
            const prevAdmin = parseInt(prevAdminNotificationCount);

            // Get audio elements
            const newUserSound = document.getElementById('newUserSound');
            const newOrderSound = document.getElementById('newOrderSound');
            const adminNotificationSound = document.getElementById('adminNotificationSound');

            // Function to play sound with fallback
            function playSound(sound) {
                if (sound) {
                    sound.play().catch(error => {
                        console.warn("Autoplay blocked. Playing sound on user interaction.");
                        document.addEventListener("click", function playOnClick() {
                            sound.play();
                            document.removeEventListener("click", playOnClick);
                        });
                    });
                }
            }

            // Check for new notifications and play sound if count increased
            if (newUserCount > prevUser) {
                playSound(newUserSound);
            }
            if (orderCount > prevOrder) {
                playSound(newOrderSound);
            }
            if (adminNotificationCount > prevAdmin) {
                playSound(adminNotificationSound);
            }

            // Update localStorage with current counts
            localStorage.setItem('prevNewUserCount', newUserCount);
            localStorage.setItem('prevOrderCount', orderCount);
            localStorage.setItem('prevAdminNotificationCount', adminNotificationCount);
        });
    </script>
@endif







    <script>
  document.addEventListener("DOMContentLoaded", function(event) {
        setTimeout(function() {
            var loader = document.getElementById('loader');
            loader.style.transition = 'opacity 1s';
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
            }, 800);
        }, 1000);
    });
    </script>
