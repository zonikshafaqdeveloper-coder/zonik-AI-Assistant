<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">
  <link rel="shortcut icon" href="{{ asset('frontweb/assests/images/favicon.png') }}" />
<title>Zonik </title>

<!-- Google font -->

<link rel="preconnect" href="https://fonts.gstatic.com/">
<link
href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&amp;display=swap" rel="stylesheet">

<!-- bootstrap css -->
<link id="rtl-link" rel="stylesheet" type="text/css"
href="{{ asset('frontweb/assets/css/vendors/bootstrap.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<!-- wow css -->
<link rel="stylesheet" href="{{ asset('frontweb/assets/css/animate.min.css') }}" />

<!-- font-awesome css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- feather icon css -->
<link rel="stylesheet" type="text/css" href="{{ asset('frontweb/assets/css/vendors/feather-icon.css') }}">
<!-- Plugin CSS file with desired skin css -->
<link rel="stylesheet" href="{{ asset('assets/css/vendors/ion.rangeSlider.min.css') }}">
<!-- slick css -->
<link rel="stylesheet" type="text/css" href="{{ asset('frontweb/assets/css/vendors/slick/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontweb/assets/css/vendors/slick/slick-theme.css') }}">
<!-- animation css -->
<link rel="stylesheet" type="text/css" href="{{ asset('frontweb/assets/css/font-style.css') }}">
<!-- Template css -->
<link rel="stylesheet" href="https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.css">

<link id="color-link" rel="stylesheet" type="text/css" href="{{ asset('frontweb/assets/css/style.css') }}?v={{ time() }}">

   <link href="{{ asset('frontweb/assests/css/style.css') }}?v={{ time() }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    
    
</head>
<style>

#rotating-placeholder {
width: 100%;
padding: 10px;
font-size: 16px;
border: 1px solid #ccc;
border-radius: 4px;
}

.w-95{
width: 90% !important;
}

.active {
font-weight: bold;
}

.d-flex {
gap: 10px;
}

.bell-icon {
font-size: 22px;
}

 .error-message {
    color: red;
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
/*border-bottom: 1px solid #ddd;*/
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
font-size: 14px;
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
border: 1px solid #e97457;
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


.btn-round {
  border-radius: 3rem;
  background-color: var(--primary) !important;
    padding: 10px 30px !important;
    color: #fff !important;
    /* border-radius: 5px !important; */
    font-size: 16px !important;
    letter-spacing: 0.5px !important;
    font-weight: 500 !important;
    font-family: var(--secondary-font) !important;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px !important;
}

</style>

<body>
    
<script
  type="text/javascript"
  src="https://d3mkw6s8thqya7.cloudfront.net/integration-plugin.js"
  id="aisensy-wa-widget"
  widget-id="aaanxb"
></script>

<style>

body .df-btn,
body iframe[id^="aisensy-wa-widget"],
body iframe[src*="cloudfront.net"],
body iframe[src*="aisensy"] {
    z-index: 100 !important;
    position: fixed !important;
    bottom: 80px !important;
    right: 25px !important;
    transform: none !important;
}
</style>





<!--<a href="https://api.whatsapp.com/send?phone=+91 8850268043&text=Hello" class="float" target="_blank">-->
<!--<i class="fab fa-whatsapp my-float"></i>-->
<!--</a>-->
{{-- {{ $selectedSubCategoryId }} --}}
<!-- Header Started -->

<header class="pb-0">
<div class="top-nav top-header sticky-header">
<div class="container-fluid">

<div class="navbar-top">
<div class="row sm-justify-content-between">

<div class="col-md-2 d-sm-max">
<a href="{{ route('homepage') }}" class="web-logo nav-logo">
<!--<img src="{{ asset('frontweb/assests/images/new_logo.png') }}" class="img-fluid blur-up lazyloaded" alt="">-->
<img src="{{ asset('frontweb/assests/images/Adobe Express - file.png') }}" class="img-fluid blur-up lazyloaded" alt="">
</a>
</div>

<div class="col-md-7 d-sm-none">
<div class="row">

<div class="col-md-3">
<div class="out-let">
@if(auth()->check() && auth()->user()->location)
<?php
$location = auth()->user()->location;
$limited_location = strlen($location) > 15 ? substr($location, 0, 25) . '...' : $location;
?>
<span style="font-size: 18px;color: #121286;" class="mt-4"><b>{{ auth()->user()->name }}</b></span><br>
<span class="locat-name">
{{ $limited_location }}{{ strlen($location) <= 15 ? ' - ' . auth()->user()->pincode : '' }}
</span>
@endif
</div>
</div>


<div class="col-md-8 d-sm-none">
<div class="search-box {{ auth()->user() ? 'w-95' : 'w-75' }}">
<div class="input-group">
<span class="input-group-text" id="button-addon2" style="border-radius: 24px 0 0 24px;">
<i class="fa fa-search"></i>
</span>

<div class="location-list" style="width: calc(100% - 50px)">
<input type="search" class="form-control search" style="background-color: #f8f9fc; border-radius: 0 24px 24px 0;" placeholder="Search Your Products" id="rotating-placeholder">
<ul id="search_list" class="location-select custom-height search_list"></ul>
</div>
</div>
</div>
<div id="searchPopup" class="search-popup">
<div id="searchPopupContent" class="search-popup-content"></div>
</div>
</div>
</div>
</div>


<div class="col-md-3 d-sm-max d-flex align-items-center justify-content-end p-right">
<div class="rightside-box">
<ul class="right-side-menu">
@if(auth()->user())
<li class="right-side  d-sm-none">
<a onclick="redirectToQuote('quoteCounts', 'true')"
class="btn p-0 position-relative header-wishlist" data-bs-toggle="tooltip" 
     data-bs-placement="bottom" 
     title="Enquiry">
<i class="fa-solid fa-bag-shopping" style="font-size:22px;color: rgb(248, 158, 12);"></i>
<span id="quoteCountNew"
class="position-absolute top-0 start-100 translate-middle badge">
{{ $quoteCounts }}
</span>
</a>
</li>

<li class="right-side d-sm-none">
<a onclick="redirectToQuote('{{ $offerListCount > 0 ? 'offerListCount' : ($reofferListCount > 0 ? 'offerList' : 'offerListCount') }}', 'true')"
class="btn p-0 position-relative header-wishlist" data-bs-toggle="tooltip" 
     data-bs-placement="bottom" 
     title="Offers List">
<i class="fa fa-tag" style="font-size:22px;color: rgb(47, 9, 237);"></i>
<span id="quoteCountNewofferDesktop" class="position-absolute top-0 start-100 translate-middle badge">
{{ $offerListCount > 0 ? $offerListCount : ($reofferListCount > 0 ? $reofferListCount : $offerListCount) }}
</span>
</a>
</li>


<li class="right-side  d-sm-none">
<div class="onhover-dropdown header-badge">
<a onclick="redirectToQuotesList('true')" data-bs-toggle="tooltip" 
     data-bs-placement="bottom" 
     title="Order Cart">
<button type="button" class="btn p-0 position-relative header-wishlist">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
viewBox="0 0 24 24" fill="none" stroke="currentColor"
stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
class="feather feather-shopping-cart">
<circle cx="9" cy="21" r="1"></circle>
<circle cx="20" cy="21" r="1"></circle>
<path
d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
</path>
</svg>
<span id="cartCountDesktop"
class="position-absolute top-0 start-100 translate-middle badge">
{{ $cart }}
</span>
</button>
</a>
</div>
</li>



<li class="right-side  d-sm-none">
<div class="onhover-dropdown header-badge">
<a onclick="redirectToPriceCount('test', 'true')" data-bs-toggle="tooltip" 
     data-bs-placement="bottom" 
     title="Price List">
<button type="button" class="btn p-0 position-relative header-wishlist">
<i class="fa-solid fa-clipboard-check" style="font-size: 26px;color: #1acb1ad4;"></i>
<span id="myPricelistDesktop"
class="position-absolute top-0 start-100 translate-middle badge">
{{ $mypricelist }}
</span>
</button>
</a>
</div>
</li>

<li class="right-side">
<div class="onhover-dropdown header-badge">
<button class="btn p-0 bell-icon text-dark header-wishlist notification-update" type="button"
data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
aria-controls="offcanvasRight">
<i class="fa-solid fa-bell text-dark"></i>
<span id="notificationCount"
class="position-absolute top-0 start-100 translate-middle badge">
{{ $notification ?? '0' }}
</span>

</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
aria-labelledby="offcanvasRightLabel">
<div class="offcanvas-header">
<h3 id="offcanvasRightLabel">Notifications</h3>

<button type="button" class="btn-close text-reset"
data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
<ul class="notifications-ul">
@if (auth()->user()?->unreadNotifications)
@foreach (auth()->user()->unreadNotifications as $key => $notification)
@if (isset($notification->data['tag']) && $notification->data['tag'] == 'Customer')
<li>
    <div class="notification-item">
        <div class="notification-icon">
            <div class="icon-circle">
                <i class="far fa-calendar-alt"></i> <!-- Assuming you're using Font Awesome for icons -->
            </div>
        </div>
        <div class="notification-content">
            <div class="notification-text">{{ $notification->data['data'] }}</div>
            <div class="notification-date">{{ $notification->created_at->format('M d, Y') }}</div>
        </div>
    </div>
</li>
@endif
@endforeach
@endif
</ul>


</div>
</div>

</div>
</li>

@else
<li class="right-side">
<div class="onhover-dropdown header-badge d-flex" style="gap: 10px">
<!-- <a href="/signup" class="btn signup-btn header-wishlist">Signup</a>
<a href="/login" class="btn signup-btn header-wishlist">Login</a> -->

 <div class="shadow-inner-btn " data-bs-toggle="modal" data-bs-target="#loginModal" style="max-width: fit-content; height: max-content;">
                            <a >Login</a>
                        </div>
                        <div data-bs-toggle="modal" data-bs-target="#locationModal" class="shadow-inner-btn" style="max-width: fit-content; height: max-content;">
                           <a  >Signup</a>
                        </div>


</div>


 <div class="modal  location-modal fade theme-modal" id="locationModal" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-full screen-sm-down " style="max-width: 500px !important;">
                    <div class="modal-content modal-cust mobileBox" id="mobileBox" style="padding: 1rem;">
                        <div class="modal-header">
                            <h5 class="modal-title indexh5 mb-2" id="exampleModalLabel">sign Up
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="location-list">
                            <p class="mt-1 mb-4 text-content">
                                OTP will be sent
                                to this number for verification</p>
                                <div class="search-input">
                                <div id="messageBox" class="mb-3 error-message"></div>
                                    <div class="row">
                                    <div class="col-md-6 ">
                                    <input type="number" name="mobile" id="mobile"
                                    class="form-control mb-3 mobile_number2" placeholder=" Mobile Number" required />
                                    </div>

                                         <div class="col-md-6">
                                       <input type="text" name="name" id="name" class="form-control mb-3 "
                                        placeholder="User Name" required />
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                        <input type="text" name="designation" id="designation" class="form-control mb-3"
                                        placeholder=" Designation" required />
                                        </div>
                                        <div class="col-md-6">
                                       <input type="text" name="outlet_name" id="outlet_name" class="form-control mb-3"
                                        placeholder="Company Name" required />
                                        </div>
                                    </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                        <input type="text" name="location" id="location" class="form-control mb-3"
                                        placeholder=" Location Name" required />
                                            </div>

                                            <div class="col-md-6">
                                        <input type="text" name="pincode" id="pincode" class="form-control mb-3 "
                                        placeholder=" Pincode" required />
                                        </div>
                                        </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Email" required />
                                    </div>
                                    <div class="col-md-6 position-relative">
                                        <input id="password-field" type="password" name="password" class="form-control mb-3" placeholder="Password"  required />
                                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password position-absolute" style="top: 37%; right: 16px; transform: translateY(-50%); cursor: pointer;"></span>
                                    </div>
                                </div>


                                </div>
                            </div>

                            <div class="d-grid gap-2">
                            <button type="button" onclick="validateForm()"
                                class="btn btn-round ">Send OTP</button>
                            </div>

                        </div>


                        <div class="modal-footer d-flex justify-content-center">
        <div class="signup-section">already a member? <a href="#a" id="newlogin" style="color:#a661c6;"> Login</a>.</div>
      </div>

                        </div>





                    <div class="modal-content modal-cust1 d-none otp-box" id="otpBox" style="padding: 1rem; border-radius: 24px;">
                        <div class="modal-header">
                            <h5 class="modal-title indexh5" id="exampleModalLabel">Enter Verification
                                Code
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="modal-body ">

                            <div class="location-list">
                            <p class="mt-1 text-content mb-4"> 4 digit OTP has
                                been sent to +91 <span class="mobile_number_display">
                            </p>
                               <div class="search-input">
                                    <input type="number" name="otp" id="otp" class="form-control mb-4 otp" placeholder="OTP" maxlength="4" oninput="checkOtpLength(this)">
                                </div>

                            </div>
                            <button type="button " onclick="verifyOtp()" class="btn red-btn ">Verify
                                OTP</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>



<div class="modal location-modal fade theme-modal" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px !important;">
        <div class="modal-content modal-cust mobileBox" id="mobileBox" style="padding: 1rem; border-radius: 24px;">
            <div class="modal-header">
                <h5 class="modal-title indexh5 mb-2" id="exampleModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa-solid fa-xmark"></i>
                </button>
                
            </div>

            <div class="modal-body">
        
        <ul class="nav nav-tabs mb-4" id="loginTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="email-login-tab" data-bs-toggle="tab" data-bs-target="#email-login" type="button" role="tab" aria-controls="email-login" aria-selected="true">Email Login</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="otp-login-tab" data-bs-toggle="tab" data-bs-target="#otp-login" type="button" role="tab" aria-controls="otp-login" aria-selected="false">Login via OTP</button>
          </li>
        </ul>

        <div class="tab-content" id="loginTabContent">
        <div class="tab-pane fade show active" id="email-login" role="tabpanel" aria-labelledby="email-login-tab">

            <div class="form-group mb-3">
              <label for="loginemail" class="form-label">Email</label>
              <input type="email" id="loginemail" class="form-control" placeholder="Enter your email" required />
            </div>
            
            <div class="form-group mb-3 position-relative">
              <label for="password" class="form-label">Password</label>
              <input id="toggle-password_login" type="password" name="password" class="form-control mb-3" placeholder="Password" required />
              <span toggle="#toggle-password_login" class="fa fa-fw fa-eye toggle-password_login position-absolute" style="top: 70%; right: 9px; transform: translateY(-50%); cursor: pointer;"></span>
            </div>
            
            <div class="mb-4">
              <a href="#" id="forgotPasswordLink" style="color: #a661c6; text-decoration: underline;">Forgot Password?</a>
            </div>
            
            <div class="d-grid gap-2">
              <button type="button" onclick="loginUser()" class="btn btn-round" style="border-radius: 3rem;">Login</button>
            </div>


            
            </div>
    

            <div class="tab-pane fade" id="otp-login" role="tabpanel" aria-labelledby="otp-login-tab">

                <div class="location-list">
                    <p class="mt-1 mb-4 text-content">OTP will be sent to this number for verification</p>
                    <div class="search-input">
                        <div id="messageBox" class="mb-3 error-message"></div>
                        <div class="row d-flex">
                            <div class="mb-4">
                            <input oninput="checkNumber(this)" type="number" name="mobile_number3" id="mobile_number3" class="form-control mb-3 mobile_number3" placeholder="Mobile Number" required maxlength="10" />

                                <span id="error-message" style="color: red; display: none;">Invalid mobile number. Must be 10 digits and start with 9, 8, 7, or 6.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                <button type="button" onclick="sendOtp()" class="btn btn-round">Send OTP</button>
            </div>
            </div>
        </div>

        
        </div>
        
              <div class="modal-footer d-flex justify-content-center">
        <div class="signup-section">
          Not a member yet? <a href="#a" id="signupLink" style="color:#a661c6;"> Sign Up</a>.
        </div>
      </div>
      </div>

            

        
        <div class="modal-content modal-cust1 otp-box d-none" id="otpBox" style="padding: 1rem; border-radius: 24px; width: -webkit-fill-available;">
            <div class="modal-header">
                <h5 class="modal-title indexh5" id="exampleModalLabel">Enter Verification
                    Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-body ">

            <div class="location-list">
                    <p class="mt-1 text-content mb-4"> 4 digit OTP has
                        been sent to +91 <span class="mobile_number_display">
                    </p>
                    <div class="search-input">
                        <input type="number" name="otp" id="otp" class="form-control mb-4 otp2" oninput="checkOtpLength(this)"
                            placeholder=" OTP">
                    </div>
                </div>
                <button type="button " onclick="verifyloginOtp()" class="btn red-btn ">Verify
                    OTP</button>
            </div>
        </div>
    </div>
</div>



<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px !important;">
        <div class="modal-content" style="padding: 1rem; border-radius: 24px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="resetEmail" class="form-label">Enter your email address</label>
                    <input type="email" id="resetEmail" class="form-control" placeholder="Enter your email" required />
                </div>
                <div class="form-group mb-3">
                    <label for="resetMobile" class="form-label">Enter your mobile number</label>
                    <input type="text" id="resetMobile" class="form-control" placeholder="Enter your mobile number" required />
                </div>
                <div class="d-grid gap-2">
                    <button type="button" onclick="resetPassword()" class="btn btn-round" style="border-radius: 3rem;">Submit </button>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="setNewPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px !important;">
        <div class="modal-content" style="padding: 1rem; border-radius: 24px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Set New Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="form-group mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input id="toggle-password_newpass" type="password" name="password" class="form-control mb-3" placeholder="Password"  required />
            <span toggle="#toggle-password_newpass" class="fa fa-fw fa-eye toggle-password_newpass position-absolute" style="top: 27%; right: 19px; transform: translateY(-50%); cursor: pointer;"></span>
        </div>

                <div class="form-group mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input id="toggle-password_confirmPassword" type="password" name="password" class="form-control mb-3" placeholder="Password"  required />
                    <span toggle="#toggle-password_confirmPassword" class="fa fa-fw fa-eye toggle-password_confirmPassword position-absolute" style="top: 62%; right: 19px; transform: translateY(-50%); cursor: pointer;"></span>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" onclick="submitNewPassword()" class="btn btn-round" style="border-radius: 3rem;">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>







</li>

@endif


<li class="right-side d-sm-none">
<div class="onhover-dropdown header-badge">
<a href="tel:+919136411489" type="button"
class="btn p-0 position-relative header-wishlist" data-toggle="tooltip"
data-placement="bottom" title="Call +918850268043">
<i class="fa-solid fa-headset"></i>
</a>
</div>

</li>

@if(auth()->check() && auth()->user()->location)
<li class="right-side   onhover-dropdown">
<div class="delivery-login-box">
<div class="delivery-detail">

<div class="delivery-icon" data-bs-toggle="offcanvas"
href="#offcanvasExample" role="button"
aria-controls="offcanvasExample">
<i class="fa-solid fa-bars"></i>
</div>

<div class="delivery-detail">

</div>
</div>
</div>
</li>
@endif

</ul>
</div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
aria-labelledby="offcanvasExampleLabel">
<div class="offcanvas-header">
<div class="row">

<div class="position-relative d-inline-block" style="width: 92px; height: 61px;">
    <img 
    src="{{ auth()->user()?->profile_image 
        ? asset('uploads/profile/' . auth()->user()->profile_image) 
        : 'https://zonik.in/assets/images/user.svg' }}" 
    class="rounded-circle border shadow-sm"
    style="
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        border: 2px solid #e6e6e6; 
        box-shadow: 0 1px 4px rgba(0,0,0,0.1); 
        cursor: pointer;
    "
    data-bs-toggle="modal"
    data-bs-target="#uploadProfileModal"
    alt="User Profile">


    <!-- Small Edit Icon (Perfectly Stuck to Bottom-Right) -->
    <button 
        type="button" 
        class="btn btn-light rounded-circle position-absolute p-0 border"
        style="
            width: 16px; 
            height: 16px; 
            right: 0px; 
            bottom: 0px; 
            background-color: white; 
            border: 1px solid #ccc; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        "
        data-bs-toggle="modal"
        data-bs-target="#uploadProfileModal"
        title="Change Profile Picture">
        <i class="fa-solid fa-pen text-primary" style="font-size:8px;"></i>
    </button>
</div>

<!--<div class="col-md-3">-->
<!--<img src="https://zonik.in/assets/images/user.svg" class="img-fluid" style="height:50px;">-->
<!--</div>-->
<div class="col-md-9">
<h4 class="offcanvas-title" id="offcanvasExampleLabel"> {{ auth()->user()?->outlet_name }}
</h4>
<h6 class="mt-1">Cust. ID : {{ auth()->user()?->customer_id }}</h6>
</div>
</div>

<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
aria-label="Close"></button>

</div>
<div class="offcanvas-body">
<div>
<div class="drawer-t mb-3">ORDERS & STATEMENTS</div>
<ul class="slide-ul mb-4">

<!--<li style="display: flex; align-items: center;">-->
<!--<a href="{{ route('orders') }}" class="slide-drawer-a">-->
<!--<i class="fa-solid fa-file-lines" style="margin-right: 10px; font-size: 20px;"></i>-->
<!--Your Orders-->
<!--</a>-->
<!--<span style="flex-grow: 1;"></span>-->
<!--<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i>-->
<!--</a>-->
<!--</li>-->


<li >
<a href="{{ route('orders') }}" class="slide-drawer-a" style="display: flex; align-items: center;">
<i class="fa-solid fa-file-lines" style="margin-right: 10px; font-size: 20px;"></i>
Your Orders

<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i> </a>
</li>



<li class="open-modal"  data-toggle="modal" data-target="#statementModal">
<a href="#" class="slide-drawer-a" style="display: flex; align-items: center;" >
<i class="fa-solid fa-receipt" style="margin-right: 10px; font-size: 20px;"></i>
Account Statements

<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i>
</a>

</a>
</li>
<li>
<a target="_blank" style="display: flex; align-items: center;" class="slide-drawer-a"
href="https://api.whatsapp.com/send?phone=919082133646&text=Hello">
<i class="fa-solid fa-message" style="margin-right:10px; font-size:20px;"></i>Your Need help
<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i>
</a>
</li>

</ul>

<div class="drawer-t mb-3">MORE</div>
<ul class="slide-ul">

@if(auth()->user())
@if(auth()->user()->type == 'group')
<li >
<a href="{{ route('profile') }}" class="slide-drawer-a" style="display: flex; align-items: center;">
<i class="fa-solid fa-user" style="margin-right: 10px; font-size: 20px;"></i>
Profile Settings

<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i> </a>
</li>
@endif
@endif

<li >
<a style="display: flex; align-items: center;" class="slide-drawer-a" href="javascript(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
aria-controls="offcanvasRight">
<i class="fa-solid fa-bell text-dark" style="margin-right:10px; font-size:20px;"></i>
Notifications

<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i>

</a>

</li>
<li >
<a style="display: flex; align-items: center;" href="requestproduct" class="slide-drawer-a">
<i class="fa fa-cart-plus" style="margin-right:10px; font-size:20px;"></i>
Request New Products
<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i>
</a>
</li>


<li>
    <a href="{{ route('productlist') }}" class="slide-drawer-a" style="display: flex; align-items: center;">
        <i class="fa fa-box" style="margin-right:10px; font-size:20px;"></i>
        Product List
        <span style="flex-grow: 1;"></span>
        <i class="fa-solid fa-angle-right" style="font-size: 14px;"></i>
    </a>
</li>



<li >
<a style="display: flex; align-items: center;" href="tel:+918850268043" class="slide-drawer-a">
<i class="fa fa-phone" style="margin-right:10px; font-size:20px;"></i>
Call Us
<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i>
</a>
</li>


<li class="logout-btn">
<a href="/logout" class="slide-drawer-a" style="display: flex; align-items: center;">
<i class="fa-solid fa-right-to-bracket" style="margin-right: 10px; font-size: 20px;"></i>
Logout

<span style="flex-grow: 1;"></span>
<i class="fa-solid fa-angle-right" style="font-size: 14px;"></i></a>
</li>
</ul>
</div>

</div>
</div>
</div>

<div class="modal location-modal fade theme-modal" id="statementModal" tabindex="-1"
aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog centered-div modal-dialog-centered modal-fullscreen-sm-down">
<div class="modal-content modal-cust1 modal-w">
<div class="modal-header">
<h5 class="modal-title text-dark" id="exampleModalLabel">Choose accounting period</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
<i class="fa-solid fa-xmark"></i>
</button>
<hr>
</div>
<div class="modal-body">
<div class="location-list">

<div class="tabs nav-justified">
<button class="tablinks active" onclick="openTab('custom')">Custom</button>
<button class="tablinks" onclick="openTab('last-month')">Last Month</button>
<button class="tablinks" onclick="openTab('current-month')">Current Month</button>
<button class="tablinks" onclick="openTab('last-quarter')">Last Quarter</button>
<button class="tablinks mt-2" onclick="openTab('current-quarter')">Current
Quarter</button>
</div>
<div id="custom" class="tabcontent mb-4">
<div class="container">
<div class="row">
<div class="col-md-6">
<label for="startDate" class="date-title">Start Date:</label>
<input type="date" class="form-control date-input startDateCustom"
id="startDateCustom" placeholder="Select start date">
</div>
<div class="col-md-6">
<label for="endDate" class="date-title">End Date:</label>
<input type="date" class="form-control date-input endDateCustom"
id="endDateCustom" placeholder="Select end date">
</div>
</div>
</div>
</div>

<div id="last-month" class="tabcontent mb-4">
<div class="container">
<div class="row">
<div class="col-md-6">
<label for="startDate" class="date-title">Start Date:</label>
<input type="date" class="form-control date-input startDate" id="startDate"
placeholder="Select start date">
</div>
<div class="col-md-6">
<label for="endDate" class="date-title">End Date:</label>
<input type="date" class="form-control date-input endDate" id="endDate"
placeholder="Select end date">
</div>
</div>
</div>
</div>

<div id="current-month" class="tabcontent mb-4">
<div class="container">
<div class="row">
<div class="col-md-6">
<label for="startDate" class="date-title">Start Date:</label>
<input type="date" class="form-control date-input startDate" id="startDate"
placeholder="Select start date ">
</div>
<div class="col-md-6">
<label for="endDate" class="date-title">End Date:</label>
<input type="date" class="form-control date-input endDate" id="endDate"
placeholder="Select end date">
</div>
</div>
</div>
</div>

<div id="last-quarter" class="tabcontent mb-4">
<div class="container">
<div class="row">
<div class="col-md-6">
<label for="startDate" class="date-title">Start Date:</label>
<input type="date" class="form-control date-input startDate" id="startDate"
placeholder="Select start date">
</div>
<div class="col-md-6">
<label for="endDate" class="date-title">End Date:</label>
<input type="date" class="form-control date-input endDate" id="endDate"
placeholder="Select end date">
</div>
</div>
</div>
</div>

<div id="current-quarter" class="tabcontent mb-4">
<div class="container">
<div class="row">
<div class="col-md-6">
<label for="startDate" class="date-title">Start Date:</label>
<input type="date" class="form-control date-input startDate" id="startDate"
placeholder="Select start date">
</div>
<div class="col-md-6">
<label for="endDate" class="date-title">End Date:</label>
<input type="date" class="form-control date-input endDate" id="endDate"
placeholder="Select end date">
</div>
</div>
</div>
</div>

<hr>

<div class="mt-4">
<dl class="text-secondary">
<dt class="mt-2"> NOTE:</dt>
<dd class="mt-2"> 1. Account statement includes details of all your transactions on
Zonik.</dd>
</dl>
</div>
<div class="modal-footer state-modal-btn">

<form action="{{ route('exportaccount.excel') }}" method="post"
enctype="multipart/form-data">
@csrf

<input type="hidden" class="startDate" name="startDate" id="startDate">
<input type="hidden" class="endDate" name="endDate" id="endDate">
<button type="submit" class="btn red-btn1 exportAccountData">Download</button>
</form>
</div>
</form>
</div>
</div>
</div>
</div>
</div>





</header>


<!-- Profile Upload Modal -->
<div class="modal fade" id="uploadProfileModal" tabindex="-1" aria-labelledby="uploadProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadProfileModalLabel">Upload Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="profileUploadForm" action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body text-center">
        
          
          <img 
    id="previewImage"
    src="{{ auth()->user()?->profile_image 
        ? asset('uploads/profile/' . auth()->user()->profile_image) 
        : 'https://zonik.in/assets/images/user.svg' }}" 
    class="rounded-circle border mb-3"
    style="height:120px; width:120px; object-fit:cover;">

          
          <input type="file" name="profile_image" id="profileImageInput" class="form-control" accept="image/*">
          <small class="text-muted d-block mt-2">Accepted formats: JPG, PNG, GIF (max 2MB)</small>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- mobile fix menu start -->
<div class="mobile-menu d-md-none d-block mobile-cart">
<ul>
@if(auth()->user())
<li class=" ">
<a href="{{ route('homepage') }}">
<i class="fa fa-home" aria-hidden="true" style="font-size:17px;"></i>
<span>Home</span>
</a>
</li>

<li class="mobile-category">
<a onclick="redirectToQuote('quoteCounts', 'true')"
class="btn p-0 position-relative header-wishlist">
<i class="fa-solid fa-bag-shopping" style="color:rgb(248, 158, 12); font-size:17px;"></i>

<span id="quoteCount"
class="position-absolute top-0 start-100 translate-middle badge" style="margin-left: -1.5rem;
background-color: a558c8;
background-color: #000;
color: #fff;">
{{ $quoteCounts }}

</span>
<span>Enquiry</span>
</a>
</li>

<li class="mobile-category">
<a onclick="redirectToQuote('{{ $offerListCount > 0 ? 'offerListCount' : ($reofferListCount > 0 ? 'offerList' : 'offerListCount') }}', 'true')"
class="btn p-0 position-relative header-wishlist">
<i class="fa fa-tag" style="color:rgb(78, 7, 244); font-size:17px;"></i>

<span id="quoteCountNewofferMobile"
class="position-absolute top-0 start-100 translate-middle badge" style="margin-left: -1.5rem;
background-color: a558c8;
background-color: #000;
color: #fff;">
{{ $offerListCount > 0 ? $offerListCount : ($reofferListCount > 0 ? $reofferListCount : $offerListCount) }}

</span>
<span>Offer's</span>
</a>
</li>


<!--             <li>-->
<!--                <a onclick="redirectToPriceCount('test', 'true')">-->
<!--                <i class="fa-solid fa-clipboard-check" style="color: #1acb1ad4; font-size:17px;"></i>-->
<!--                <span-->
<!--                    class="position-absolute top-0 start-100 translate-middle badge" -->
<!--style="margin-left: -9.5rem;-->
<!--    background-color: a558c8;-->
<!--    background-color: #000;-->
<!--    color: #fff;-->
<!--    margin-top: 9px;">-->
<!--                    {{ $mypricelist }}-->
<!--                </span>  -->
<!--                <span>Price List</span>-->
<!--                </a>-->
<!--            </li>-->

<li>
<a onclick="redirectToPriceCount('test', 'true')" class="search-box">
<i class="fa-solid fa-clipboard-check" aria-hidden="true" style="color: #1acb1ad4; font-size:19px;"></i>
<span id="myPricelistMobile"
class="position-absolute top-0 start-100 translate-middle badge" style="margin-left: -1.5rem;
background-color: a558c8;
background-color: #000;
color: #fff;">
{{ $mypricelist }}
</span>
<span>Price List</span>
</a>

</li>
<li>
<a onclick="redirectToQuotesList('true')" class="search-box">
<i class="fa fa-cart-plus" aria-hidden="true" style="font-size:17px;"></i>
<span id="cartCountMobile"
class="position-absolute top-0 start-100 translate-middle badge" style="margin-left: -1.5rem;
background-color: a558c8;
background-color: #000;
color: #fff;">
{{ $cart }}
</span>
<span>Cart</span>
</a>

</li>
@endif
</ul>
</div>
<!-- mobile fix menu end -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
</script>
<script>
$(document).ready(function() {
$(".open-modal").click(function() {
$("#statementModal").modal('show');
});
});
</script>

<script>
function formatDateLocal(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

function openTab(tabName) {
    var today = new Date();
      console.log(today);

    $(".tabcontent").hide();
    $(".tablinks").removeClass("active");
    $("#" + tabName).show();
    $(event.currentTarget).addClass("active");

    let startDate, endDate;

    switch (tabName) {
        case 'custom':
            $(".startDate").val("").prop('readonly', false);
            $(".endDate").val("").prop('readonly', false);
            return;

        case 'last-month':
            startDate = formatDateLocal(new Date(today.getFullYear(), today.getMonth() - 1, 1));
            endDate = formatDateLocal(new Date(today.getFullYear(), today.getMonth(), 0));
            break;

        case 'current-month':
            startDate = formatDateLocal(new Date(today.getFullYear(), today.getMonth(), 1));
            endDate = formatDateLocal(new Date(today.getFullYear(), today.getMonth() + 1, 0));
            break;

        case 'last-quarter':
            let currentQuarter = Math.floor(today.getMonth() / 3);
            let lastQuarter = currentQuarter - 1;
            let year = today.getFullYear();
            if (lastQuarter < 0) {
                lastQuarter = 3;
                year--;
            }
            startDate = formatDateLocal(new Date(year, lastQuarter * 3, 1));
            endDate = formatDateLocal(new Date(year, lastQuarter * 3 + 3, 0));
            break;

        case 'current-quarter':
            let qStart = Math.floor(today.getMonth() / 3) * 3;
            startDate = formatDateLocal(new Date(today.getFullYear(), qStart, 1));
            endDate = formatDateLocal(new Date(today.getFullYear(), today.getMonth() + 1, 0));
            break;
    }

    console.log("✅ Setting dates:", startDate, endDate);
    $(".startDate").val(startDate).prop('readonly', true);
    $(".endDate").val(endDate).prop('readonly', true);
}





// function openTab(tabName) {
// var today = new Date();

// $(".tabcontent").hide();
// $(".tablinks").removeClass("active");
// $("#" + tabName).show();
// $(event.currentTarget).addClass("active");

// let startDate, endDate;

// switch (tabName) {
// case 'custom':
// $(".startDate").val("").prop('readonly', false); // Allow manual input
// $(".endDate").val("").prop('readonly', false);
// return; // Exit function to allow manual selection
// case 'last-month':
// startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1).toISOString().split('T')[0];
// endDate = new Date(today.getFullYear(), today.getMonth(), 0).toISOString().split('T')[0];
// break;
// case 'current-month':
// startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
// endDate = today.toISOString().split('T')[0];
// break;
// case 'last-quarter':
// startDate = new Date(today.getFullYear(), today.getMonth() - 3, 1).toISOString().split('T')[0];
// endDate = new Date(today.getFullYear(), today.getMonth(), 0).toISOString().split('T')[0];
// break;
// case 'current-quarter':
// startDate = new Date(today.getFullYear(), Math.floor(today.getMonth() / 3) * 3, 1).toISOString().split('T')[0];
// endDate = today.toISOString().split('T')[0];
// break;
// }

// console.log("Setting dates:", startDate, endDate); // Debugging
// $(".startDate").val(startDate).prop('readonly', true);
// $(".endDate").val(endDate).prop('readonly', true);
// }

// // ✅ Listen for manual date selection in "Custom" tab and update hidden inputs
// $("#startDateCustom, #endDateCustom").on("change", function () {
// let customStart = $("#startDateCustom").val();
// let customEnd = $("#endDateCustom").val();

// console.log("Custom selected dates:", customStart, customEnd); // Debugging

// $(".startDate").val(customStart);
// $(".endDate").val(customEnd);
// });

// // ✅ Ensure hidden inputs are updated before form submission
// $(".exportAccountData").on("click", function (e) {
// let startDate = $(".startDate").val();
// let endDate = $(".endDate").val();

// console.log("Before submission - Start Date:", startDate, "End Date:", endDate); // Debugging

// if (!startDate || !endDate) {
// e.preventDefault(); // Prevent form submission
// alert("Please select a valid date range before downloading.");
// return false;
// }
// });


/*    $('.sent_account_statement').click(function(e) {
e.preventDefault();

var csrfToken = $('meta[name="csrf-token"]').attr('content');
var startDate = $(".startDate").val();
var endDate = $(".endDate").val();

$.ajax({
url: '{{ route("exportaccount.excel") }}',
method: 'POST',
headers: {
'X-CSRF-TOKEN': csrfToken   },
data: {
startDate: startDate,
endDate: endDate
},
success: function(response) {

var blob = new Blob([response]);
var link = document.createElement('a');
link.href = window.URL.createObjectURL(blob);
link.download = 'payments.xlsx';
link.click();
},
error: function(xhr, status, error) {
console.error(xhr.responseText);
}
});
}); */
$(document).ready(function() {
$('[data-toggle="tooltip"]').tooltip();

$('.notification-update').click(function(){
// Get CSRF token from meta tag
var csrfToken = $('meta[name="csrf-token"]').attr('content');

// Make AJAX request to update notification
$.ajax({
url: '/home/updateNotification',
type: 'POST',
headers: {
'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
},
success: function(response){
// Update notification count
$('.notification-update span').text(response.notificationCount);
console.log('Notification updated');
},
error: function(xhr, status, error){
console.error('Error updating notification:', error);
}
});
});




});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
const notifications = document.querySelectorAll('.notification-date');

notifications.forEach(function (element) {
const notificationDate = new Date(element.textContent);
const currentDate = new Date();
const diffTime = Math.abs(currentDate - notificationDate);
const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) - 1;

if (diffDays === 0) {
element.textContent = 'today';
} else if (diffDays === 1) {
element.textContent = '1 day ago';
} else if (diffDays === 2) {
element.textContent = '2 days ago';
} else {
// Display the actual date in 'M d, Y' format
const formattedDate = notificationDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
element.textContent = formattedDate;
}
});
});

document.addEventListener("DOMContentLoaded", function () {
const input = document.getElementById("rotating-placeholder");
const placeholders = [
"Search for your favorite beverage...",
"Looking for a refreshing drink?",
"Enter a drink name or brand...",
"Find deals on beverages today!",
];

let index = 0;

// Function to rotate placeholders
function changePlaceholder() {
input.setAttribute("placeholder", placeholders[index]);
index = (index + 1) % placeholders.length; // Cycle through the array
}

// Initial placeholder setup
changePlaceholder();

// Change placeholder every 3 seconds
setInterval(changePlaceholder, 3000);
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  });
</script>

    <!-- <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script> -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"> -->
    <!-- </script> -->

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->

    <script  rel="stylesheet" type="text/css" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"></script>

    <link rel="stylesheet" type="text/css"  href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script type="text/javascript">


        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    <!--@if (session('success'))-->
    <!--    <script type="text/javascript">-->
    <!--        toastr.success("{{ session('success') }}", 'Success');-->
    <!--    </script>-->
    <!--@endif-->

    <!--@foreach ($errors->all() as $error)-->
    <!--    <script type="text/javascript">-->
            <!--// alert('okk')-->
    <!--        toastr.error("{{ $error }}", 'Error');-->
    <!--    </script>-->
    <!--@endforeach-->


     <script>
     



function sendOtp() {
    var mobile = $('.mobile_number2').val();

    if (mobile == '') {
        mobile = $('.mobile_number3').val();
    }

console.log(mobile.length);


        if (mobile.length === 10) {
            axios.get(`/customer/name/${mobile}`)
                .then((res) => {
                    if (res.data?.name) {
                        axios.post('/customer/sendOtp', {
                            mobile: mobile,
                        }).then(function(response) {
                            if (response.data) {
                                toastr.success('OTP sent successfully');
                                $('.otp-box').removeClass('d-none');
                                $('.mobileBox').addClass('d-none');
                            } else {
                                toastr.error('Failed to send OTP');
                            }
                        }).catch(function(error) {
                            console.error('Error:', error);
                            toastr.error('Failed to send OTP');
                        });
                    }else{
                        axios.post('/customer/sendOtp', {
                            mobile: mobile,
                        }).then(function(response) {
                            if (response.data) {
                                toastr.success('OTP sent successfully');
                                $('.otp-box').removeClass('d-none');
                                $('.mobileBox').addClass('d-none');
                            } else {
                                toastr.error('Failed to send OTP');
                            }
                        }).catch(function(error) {
                            console.error('Error:', error);
                            toastr.error('Failed to send OTP');
                        });
                    }
                });
        }


        $('.mobile_number_display').text(mobile);



}


function validateForm() {
    var mobile = document.getElementById('mobile').value.trim();
    var name = document.getElementById('name').value.trim();
    var designation = document.getElementById('designation').value.trim();
    var outletName = document.getElementById('outlet_name').value.trim();
    var location = document.getElementById('location').value.trim();
    var pincode = document.getElementById('pincode').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password-field').value.trim();

    var messageBox = document.getElementById('messageBox');
    messageBox.innerHTML = ''; // Clear previous error messages

    var errors = [];

    // Validate each field
    if (mobile.length !== 10 || isNaN(mobile)) {
        errors.push('Please enter a valid 10-digit mobile number.');
    }

    if (name === '') {
        errors.push('Please enter your name.');
    }

    if (designation === '') {
        errors.push('Please enter your designation.');
    }

    if (outletName === '') {
        errors.push('Please enter your outlet name.');
    }

    if (location === '') {
        errors.push('Please enter your location name.');
    }

    if (pincode === '' || isNaN(pincode)) {
        errors.push('Please enter a valid pincode.');
    }

    if (!email.match(/^\S+@\S+\.\S+$/)) {
        errors.push('Please enter a valid email address.');
    }

    if (password.length < 6) {
        errors.push('Password must be at least 6 characters long.');
    }

    // If there are errors, display them and stop the function
    if (errors.length > 0) {
        messageBox.innerHTML = errors.join('<br>');
        return; // Stop execution if validation fails
    }

    // If all fields are valid, proceed to check mobile number
    axios.get(`/customer/name/${mobile}`)
        .then((res) => {
            if (res.data?.name) {
                // User already exists
                toastr.warning("User already exists");
            } else {
                // User doesn't exist, send OTP
                sendOtp();
            }
        })
        .catch((error) => {
            console.error('Error checking mobile number:', error);
            toastr.error("Error checking mobile number. Please try again.");
        });
}






function checkNumber(input) {
       if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
    
    var mobile = $('.mobile_number3').val();
    const errorMessage = document.getElementById('error-message');
    const value = mobile.value;

        if (mobile.length === 10) {
            axios.get(`/customer/name/${mobile}`)
                .then((res) => {
                    if (res.data?.name) {
                        // sendOtp(mobile);
                    } else {
                        toastr.error('This number is not registered. Please sign up.');
                           $("#locationModal").modal('show');
                           $("#loginModal").modal('hide');

                    }
                });
        }


        $('.mobile_number_display').text(mobile);
    }





        function verifyOtp() {
            var otp = document.getElementById('otp').value;
            if (otp === '') {
                otp = $('.otp2').val();

            }

            var mobile = $('#mobile').val();
            var name = $('#name').val();
            var outlet_name = $('#outlet_name').val();
            var designation = $('#designation').val();
            var location = $('#location').val();
            var pincode = $('#pincode').val();
            var email = $('#email').val();
            var password = $('#password-field').val();

            axios.post('/customer/verifyOtp', {
                    otp: otp,
                    mobile: mobile,
                    name: name,
                    outlet_name:outlet_name,
                    designation:designation,
                    pincode:pincode,
                    email:email,
                    location:location,
                    password: password
                })
                .then(response => {
                    console.log(response.data.success);
                    if (response.data.success) {
                        toastr.success(response.data.message);
                        closeModal();
                        window.location.href = '/homepage';

                    } else {
                        toastr.error(response.data.message);
                    }


                })
                .catch(error => {
                    toastr.error('An error occurred while verifying OTP');
                });
        }

        function verifyloginOtp() {

                otp = $('.otp2').val();
                mobile =$('.mobile_number3').val();
            axios.post('/customer/verifyloginOtp', {
                    otp: otp,
                    mobile: mobile,
                })
                .then(response => {
                    console.log(response.data.success);
                    if (response.data.success) {
                        toastr.success(response.data.message);
                        closeModal();
                        window.location.href = '/homepage';

                    } else {
                        toastr.error(response.data.message);
                    }


                })
                .catch(error => {
                    toastr.error('An error occurred while verifying OTP');
                });
        }


        function closeModal() {
            // Close the modal with the ID "otpBox"
            var otpBox = document.getElementById("otpBox");
            if (otpBox) {
                otpBox.style.display = "none";
            }
        }



    function loginUser() {
    const email = document.getElementById('loginemail').value;
    const password = document.getElementById('toggle-password_login').value;

    console.log("Email:", email, "Password:", password);  // Debugging line

    // Validation
    if (!email || !password) {
        toastr.error('Please fill in all fields.');
        return;
    }

    // Axios request for login
    axios.post('/customer/login', {
        email: email,
        password: password
    })
    .then(response => {
        if (response.data.success) {
            toastr.success(response.data.message);
            // Redirect to homepage after successful login
            closeModal();
            window.location.href = '/homepage'; // Adjust to your homepage URL
        } else {
            toastr.error(response.data.message);
        }
    })
    .catch(error => {
        toastr.error('An error occurred while logging in.');
    });
}


// Function to handle the Forgot Password process
function resetPassword() {
    const email = document.getElementById('resetEmail').value;
    const mobile = document.getElementById('resetMobile').value;

    if (!email || !mobile) {
        toastr.error('Please enter both your email and mobile number.');
        return;
    }

    // Make an Axios request to validate email and mobile
    axios.post('/customer/validate-email-mobile', {
        email: email,
        mobile: mobile
    })
    .then(response => {
        if (response.data.success) {
            // Store the user_id from the response
            const userId = response.data.user_id;

            // Hide the first modal and show the second modal for password reset
            $("#forgotPasswordModal").modal('hide');
            $("#setNewPasswordModal").modal('show');

            // Pass the user_id to the submitNewPassword function
            window.user_id = userId;
        } else {
            toastr.error(response.data.message);  // Show error if email or mobile is invalid
        }
    })
    .catch(error => {
        if (error.response && error.response.data && error.response.data.errors) {
            // Extract and show validation errors
            const validationErrors = error.response.data.errors;
            Object.values(validationErrors).forEach(messages => {
                messages.forEach(message => toastr.error(message));
            });
        } else {
            toastr.error('An error occurred while checking email and mobile.');
        }
    });
}

function submitNewPassword() {
    const newPassword = document.getElementById('toggle-password_newpass').value;
    const confirmPassword = document.getElementById('toggle-password_confirmPassword').value;

    // Check if passwords match
    if (newPassword !== confirmPassword) {
        toastr.error('Passwords do not match!');
        return;
    }

    // Check if password is strong enough (optional)
    if (newPassword.length < 6) {
        toastr.error('Password must be at least 6 characters long.');
        return;
    }

    // Make an Axios request to submit the new password
    axios.post('/customer/reset-password', {
        user_id: window.user_id,  // Send user_id to the backend
        newPassword: newPassword,
        confirmPassword: confirmPassword  // Send the confirmPassword as well
    })
    .then(response => {
        if (response.data.success) {
            // Close the modal and show a success message
            $("#setNewPasswordModal").modal('hide');
            toastr.success('Password updated successfully!');

            // Reload the homepage or redirect
            window.location.href = '/homepage';  
        } else {
            toastr.error(response.data.message);  
        }
    })
    .catch(error => {
        toastr.error('An error occurred while updating the password.');
    });
}






    </script>

    <!-- <script>
        $(function() {
            // Owl Carousel
            var owl = $(".owl-carousel");
            owl.owlCarousel({
                items: 2,
                margin: 30,
                autoplay: true,
                loop: true,
                nav: false,
            });
        });
    </script> -->

<script>
  

    function checkOtpLength(input) {
    if (input.value.length > 4) {
        input.value = input.value.slice(0, 4);
    }
}
</script>


<script>

// Reset modal on show
$('#loginModal').on('shown.bs.modal', function () {
    $('#mobile_number3').val('');
    $('#error-message').hide();
    $('#messageBox').html('');
});






$(".toggle-password").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});

$(".toggle-password_login").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});

$(".toggle-password_newpass").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");

    var input = $($(this).attr("toggle"));


    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});
$(".toggle-password_confirmPassword").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");

    var input = $($(this).attr("toggle"));


    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});





</script>

<script>
  $(document).ready(function() {
    $("#signupLink").click(function() {
      // Hide the login modal
      $("#loginModal").modal('hide');
      
      // Show the location modal
      $("#locationModal").modal('show');
    });
  });
  $(document).ready(function() {
    $("#newlogin").click(function() {
      // Hide the login modal
      $("#locationModal").modal('hide');
      
      // Show the location modal
      $("#loginModal").modal('show');
    });
  });

  $(document).ready(function() {
    $("#forgotPasswordLink").click(function() {
      // Hide the login modal
      $("#loginModal").modal('hide');
      
      // Show the location modal
      $("#forgotPasswordModal").modal('show');
    });
  });
</script>


<script>
document.getElementById('profileImageInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const offcanvasEl = document.getElementById('offcanvasExample');
    const profileModalEl = document.getElementById('uploadProfileModal');
    const profileTriggers = document.querySelectorAll('[data-bs-target="#uploadProfileModal"]');

    // Bootstrap helpers
    const getOffcanvas = () => bootstrap.Offcanvas.getInstance(offcanvasEl) 
                            || new bootstrap.Offcanvas(offcanvasEl);
    const getModal = () => new bootstrap.Modal(profileModalEl);

    profileTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            
            const offcanvasInstance = getOffcanvas();

            // If offcanvas is visible, close it first
            if (offcanvasEl.classList.contains('show')) {
                offcanvasInstance.hide();

                // Wait until offcanvas is fully hidden
                offcanvasEl.addEventListener('hidden.bs.offcanvas', function handler() {
                    offcanvasEl.removeEventListener('hidden.bs.offcanvas', handler);
                    getModal().show();
                });
            } else {
                // If offcanvas isn’t open, open modal directly
                getModal().show();
            }
        });
    });
});
</script>
