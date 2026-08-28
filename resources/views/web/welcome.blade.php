<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <!--<link href="{{ asset('frontweb/assests/css/style.css') }}" rel="stylesheet">-->
    <!--<link href="{{ asset('frontweb/assests/css/style.css?v=2.4') }}" rel="stylesheet">-->
    
    <link href="{{ asset('frontweb/assests/css/style.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('frontweb/assests/css/variable.css') }}?v={{ time() }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('frontweb/assests/images/favicon.png') }}" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
    <title>Zonik</title>
    
</head>

<script
  type="text/javascript"
  src="https://d3mkw6s8thqya7.cloudfront.net/integration-plugin.js"
  id="aisensy-wa-widget"
  widget-id="aaanxb"
></script>

<script>
(function(){
  const cfg={hide:'ais-hide',poll:8,interval:2000,bm:'80px',bd:'80px',r:'30px',z:'1040'};
  document.head.insertAdjacentHTML("beforeend",
    `<style>.${cfg.hide}{opacity:0!important;visibility:hidden!important;transition:none!important}</style>`);

  const bottom=()=>window.innerWidth<=768?cfg.bm:cfg.bd;
  const fix=el=>{
    if(!el)return;
    el.classList.add(cfg.hide);
    ['position','bottom','right','z-index','transform','transition'].forEach((p,i)=>{
      const v=['fixed',bottom(),cfg.r,cfg.z,'none','none'][i];
      el.style.setProperty(p,v,'important');
    });
    requestAnimationFrame(()=>{el.classList.remove(cfg.hide);el.style.opacity='1';el.style.visibility='visible';});
  };

  const findNodes=()=>[
    ...document.querySelectorAll('.df-btn,iframe')
  ].filter(e=>{
    const s=e.src||'';return s.includes('cloudfront.net')||s.includes('aisensy')||e.classList.contains('df-btn');
  });

  const reposition=()=>findNodes().forEach(fix);
  reposition();

  let t=0;const iv=setInterval(()=>{reposition();if(++t>=cfg.poll)clearInterval(iv);},cfg.interval);

  new MutationObserver(m=>m.forEach(x=>x.addedNodes.forEach(n=>{
    if(n.nodeType!=1)return;
    if(n.matches('.df-btn,iframe'))fix(n);
    n.querySelectorAll&&n.querySelectorAll('.df-btn,iframe').forEach(fix);
  }))).observe(document.body,{childList:true,subtree:true});

  window.addEventListener('resize',()=>findNodes().forEach(e=>e.style.bottom=bottom()),{passive:true});
})();

</script>

<script>
(function(){
  const WIDGET_Z = '1038'; // ensure below backdrop (1040) and modal (1050)
  const SELECTOR = '.df-btn, iframe, [id^="aisensy"], [data-widgetid], [widget-id]';

  // Helper to force important style even if widget reapplies inline styles
  function forceZ(el, z = WIDGET_Z) {
    if(!el || !(el instanceof HTMLElement)) return;
    try {
      el.style.setProperty('z-index', z, 'important');
      // If widget is wrapped, also set on parent container
      if(el.parentElement) el.parentElement.style.setProperty('z-index', z, 'important');
    } catch(e){ /* ignore */ }
  }

  // Find likely nodes (broad selector) and force z-index
  function enforceAll() {
    document.querySelectorAll(SELECTOR).forEach(el=>{
      // narrow to ones that look like the widget (src contains aisensy/cloudfront or class df-btn)
      const src = el.getAttribute && (el.getAttribute('src') || '');
      const cls = el.className || '';
      if (src.includes('cloudfront.net') || src.includes('aisensy') || cls.includes('df-btn') || el.id && el.id.toLowerCase().includes('ais')) {
        forceZ(el);
      } else {
        // still apply to any df-btn-like element even if src mismatch
        if(cls.includes('df-btn')) forceZ(el);
      }
    });
  }

  // Observe additions/attribute changes and re-apply immediately
  const obs = new MutationObserver(mutations=>{
    for(const mut of mutations){
      if(mut.type === 'childList'){
        mut.addedNodes.forEach(n=>{
          if(n.nodeType !== 1) return;
          // check node and its descendants quickly
          if(n.matches && n.matches(SELECTOR)) forceZ(n);
          n.querySelectorAll && n.querySelectorAll(SELECTOR).forEach(forceZ);
        });
      } else if(mut.type === 'attributes'){
        const target = mut.target;
        if(target && target.matches && target.matches(SELECTOR)) forceZ(target);
      }
    }
  });

  // Start observing body for additions and attribute changes
  obs.observe(document.documentElement || document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style','class','src','id'] });

  // Run initial enforcement a few times to beat widget's own setup
  enforceAll();
  // repeat a couple times (widget sometimes initializes a bit later)
  setTimeout(enforceAll, 300);
  setTimeout(enforceAll, 1000);
  setTimeout(enforceAll, 2500);

  // Keep enforcing on window resize (if bottom value changes etc.)
  window.addEventListener('resize', enforceAll, { passive: true });

  // When any bootstrap modal is shown, re-apply widget z-index (so modal stays above)
  document.addEventListener('shown.bs.modal', function() {
    enforceAll();
  });

  // Optional: hide the widget while any modal is open (uncomment if you prefer hide instead of z-index)
  
  document.addEventListener('shown.bs.modal', function() {
    document.querySelectorAll(SELECTOR).forEach(el => { el.style.setProperty('display','none','important'); });
  });
  document.addEventListener('hidden.bs.modal', function() {
    document.querySelectorAll(SELECTOR).forEach(el => { el.style.removeProperty('display'); enforceAll(); });
  });
  

  // Extra fallback CSS (applied programmatically to ensure it has weight)
  const css = `.df-btn, iframe[src*="cloudfront.net"], iframe[src*="aisensy"], [id^="aisensy"] { z-index: ${WIDGET_Z} !important; }`;
  const styleEl = document.createElement('style');
  styleEl.appendChild(document.createTextNode(css));
  document.head.appendChild(styleEl);

})();
</script>


<style>

.video-frame {
    width: 330px !important;
    height: 630px !important;
    object-fit: cover;
    border-radius: 10px;
}

@media (max-width: 767px) {
    .features-section {
        padding: 0px !important;
    }
    
      #loginModal .modal-content {
    padding: 0 !important;
  }
}

.btn-round {
  border-radius: 3rem;
  background-color: #e97457 !important;
    padding: 10px 30px !important;
    color: #fff !important;
    /* border-radius: 5px !important; */
    font-size: 16px !important;
    letter-spacing: 0.5px !important;
    font-weight: 500 !important;
    font-family: var(--secondary-font) !important;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px !important;
}


.field-icon {
    cursor: pointer;
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    font-size: 18px;
    color: #6c757d;
}

    @media (min-width: 769px) {
    .hide-desktop {
        display: none;
    }
}
@media (min-width: 320px) and (max-width: 767px) {
    .hide-mobile {
        display: none;
    }    
}

    .error-message {
    color: red;
}

.col-w {
    width: 100% !important;
}

.owl-carousel .owl-nav button.owl-prev:hover,
.owl-carousel .owl-nav button.owl-next:hover {
    background-color: #a558c8;
}

.owl-theme .owl-nav [class*="owl-"]:hover {
    background: #869791;
    background-color: rgb(134, 151, 145);
    color: #fff;
    text-decoration: none;
}
.owl-carousel .owl-nav button.owl-next {
    right: 0;
}

.product-img-box {
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-img {
    max-width: 100%;
    max-height: 100%;
}
.accordion-flush .accordion-item .accordion-button, .banner-left{
    text-transform: capitalize;
}

.title{
        text-transform: capitalize;
}


.shadow-inner-btn{
    cursor: pointer;
}

.owl-carousel .owl-nav button.owl-prev,
.owl-carousel .owl-nav button.owl-next {
    z-index: 1;
    width: 35px;
    height: 35px;
    background-color: #fff;
    border-radius: 50%;
    position: absolute;
    top: 43%;
    transform: translatey(-50%);
}

.owl-carousel .owl-nav button.owl-prev {
    left: 0;
}

.owl-img {
    width: 135px;
}

.mb-65
{
    margin-bottom:65px !important;
    }

    .mb-50
{
    margin-bottom:50px !important;
    }

    .popup__overlay {
  display: none;
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background:#6c757d36;
  text-align: center;
  z-index: 100;
}

.popup__overlay:after {
  display: inline-block;
  height: 100%;
  width: 0;
  vertical-align: middle;
  content: "";
}

.popup {
  display: inline-block;
  position: relative;
  width: 100%;
  height: 100%;
  max-width: 640px;
  max-height: 480px;
  padding: 2rem;
  border: 2px solid black;
  background: #332941;
  color: white;
  vertical-align: middle;
}

.popup-form__row {
  margin: 1em 0;
}

.popup__close {
  display: flex;
  position: absolute;
  top: 1px;
  right: 1px;
  font-size: 1rem;
  font-weight: bold;
  padding: 0.1rem 0.3rem;
}

iframe {
  width: 100%;
  height: 100%;
  outline: 2px solid #e97457;
}

button {
  border: 1;
  padding: 0.5em;
  background-color: #e97457;
  font-size: 1.4em;
}


/* Responsive design for smaller screens */
@media (max-width: 768px) {
  .popup {
    width: 95%; /* Full width for small screens */
    max-width: none; /* Remove max-width to prevent restriction */
    height: auto; /* Adjust height dynamically */
    max-height: 90%; /* Ensure popup fits in viewport */
    padding: 1rem; /* Reduce padding */
  }

  iframe {
    height: 60%; /* Adjust iframe height for smaller screens */
  }

  .popup__close {
    font-size: 1rem; /* Reduce close button size */
    width: 25px;
    height: 25px;
  }

  button {
    font-size: 1em; /* Adjust button size */
  }
}

@media (max-width: 480px) {
  .popup {
    width: 90%; /* Full width for very small screens */
    height: auto; /* Allow dynamic height */
    padding: 0.8rem; /* Further reduce padding */
  }

  iframe {
    height: 50%; /* Further reduce iframe height */
  }

  .popup__close {
    font-size: 0.9rem; /* Smaller close button */
  }

  button {
    font-size: 0.9em; /* Smaller button text */
  }
}

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #fff;
    background-color: #e97457;
    border-color: #dee2e6 #dee2e6 #fff;
}

body.loading {
    overflow: hidden !important;
    padding-right: var(--scrollbar-width, 0px) !important;
}

html.loading {
    overflow: hidden !important;
}

.loader {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background-color: #ffffff;
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.loader img {
    max-width: 230px;
    max-height: 330px;
    mix-blend-mode: multiply;
    display: block;
}


</style>

<body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<!--<a href="https://api.whatsapp.com/send?phone=918850268043&text={{ urlencode('Hello, how can I help you?') }}" class="float" target="_blank">-->
<!--<i class="fa fa-whatsapp my-float"></i>-->
<!--</a>-->
    <!-- Header Started -->


<div class="loader" id="loader">
    <!-- <img src="/frontweb/assets/images/loader.gif" alt="Loading..."> -->
    <img src="/frontweb/assets/images/loader.png" alt="Loading...">
</div>



<!--<div class="popup__overlay">-->
<!--  <div class="popup">-->
<!--    <button href="#" class="popup__close">X</button>-->
<!--<iframe id="existing-iframe-example" -->
<!--        type="text/html" -->
<!--        src="https://www.youtube.com/embed/2-SFHBGSnog?si=VkrvrYq7Dg-Ag813" -->
<!--        frameborder="0" -->
<!--        allow="encrypted-media; fullscreen" -->
<!--        allowfullscreen>-->
<!--</iframe>-->


<!--  </div>-->
<!--</div>-->

    <section class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-3 p-0">
                    <!--<img src="{{ asset('frontweb/assests/images/logo-name.png') }}" class="logo mob-none">-->
                    <img src="{{ asset('frontweb/assests/images/Adobe Express - file.png') }}" class="logo mob-none">
                     <!--<img src="{{ asset('https://zonik.in/frontweb/assests/images/new_logo.png') }}" class="logo desk-none">-->
                     <img src="{{ asset('https://zonik.in/frontweb/assests/images/Adobe Express - file.png') }}" class="logo desk-none">
                </div>
                <div class="col-md-6 text-end col-8 row p-0 new-css" style="justify-content: end; gap: 10px">
                    {{-- <div class="middle-box"> --}}
                    {{-- <div class="location-box"> --}}


                        <div class="shadow-inner-btn " data-bs-toggle="modal" data-bs-target="#loginModal" style="max-width: fit-content; height: max-content;">
                            <a >Login</a>
                        </div>
                        <div data-bs-toggle="modal" data-bs-target="#locationModal" class="shadow-inner-btn" style="max-width: fit-content; height: max-content;">
                           <a  >Signup</a>
                        </div>


                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
            </div>


                     <div class="modal  location-modal fade theme-modal" id="locationModal" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-full screen-sm-down ">
                    <div class="modal-content modal-cust mobileBox" id="mobileBox" style="padding: 1rem;">
                        <div class="modal-header">
                            <h5 class="modal-title indexh5 mb-2" id="exampleModalLabel">sign Up
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <!-- <i class="fa-solid fa-xmark"></i> -->
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="location-list">
                            <p class="mt-1 mb-4 text-content">
                                OTP will be sent
                                to this number for verification</p>
                                <div class="search-input">
                                <div id="messageBox" class="mb-3 error-message"></div>
                                    <div class="row d-flex">
                                    <div class="col-md-6 ">
                                    <input type="number" name="mobile" id="mobile"
                                    class="form-control mb-3 mobile_number2" placeholder=" Mobile Number" required />
                                    </div>

                                         <div class="col-md-6">
                                       <input type="text" name="name" id="name" class="form-control mb-3 "
                                        placeholder=" User Name" required />
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
        <div class="signup-section">already a member? <a href="#a" id="newlogin" style="color:#121286;"> Login</a>.</div>
      </div>

                        </div>





                    <div class="modal-content modal-cust1 d-none otp-box" id="otpBox" style="padding: 1rem; border-radius: 24px;">
                        <div class="modal-header">
                            <h5 class="modal-title indexh5" id="exampleModalLabel">Enter Whatsapp Verification
                                Code
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <!-- <i class="fa-solid fa-xmark"></i> -->
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
<div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-cust mobileBox" id="mobileBox" style="padding: 1rem; border-radius: 24px;">
            <div class="modal-header">
                <h5 class="modal-title indexh5 mb-2" id="exampleModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
              <a href="#" id="forgotPasswordLink" style="color: #121286; text-decoration: underline;">Forgot Password?</a>
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
          Not a member yet? <a href="#a" id="signupLink" style="color:#121286;"> Sign Up</a>.
        </div>
      </div>
      </div>

            

        
        <div class="modal-content modal-cust1 otp-box d-none" id="otpBox" style="padding: 1rem; border-radius: 24px;">
            <div class="modal-header">
                <h5 class="modal-title indexh5" id="exampleModalLabel">Enter Whatsapp Verification
                    Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <i class="fa-solid fa-xmark"></i> -->
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
    
<div class="modal-dialog modal-dialog-centered" role="document">
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
    <div class="modal-dialog modal-dialog-centered" role="document">
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











    </section>
    <!-- Header Ended -->




    <!-- Banner Started -->
 <section class="banner-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="banner-left">
                    <!--<img src="{{ asset('frontweb/assests/images/new.jpg') }}" alt="Banner Image" class="d-none d-md-block">-->
                    <img src="{{ asset('frontweb/assests/images/banner_area (1).png') }}" alt="Banner Image" class="d-none d-md-block">
                    <h1><span class="d-block d-md-none" style="color:#121212;">B2B Food Tech Platform to buy your Critical Food Supplies : Faster , Cheaper & Organized manner</h1>
                    
                    <button type="submit" data-bs-toggle="modal" data-bs-target="#locationModal" class="td-btn buy-btn mt-4">
                        Buy Now &nbsp;&nbsp; <i class="fa-solid fa-angle-right rih"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="banner-img">
                    <img src="{{ asset('frontweb/assests/images/finalbanner.jpg') }}" class="img-fluid" alt="Side Banner">
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Banner Ended -->
    <!-- Features Started -->
    <section class="features-section" style="padding: 140px  0px;">
        <div class="container">
            <div class="row gx-5">
                <div class="col-md-3 col-sm-6">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-2 mob-c">
                                <img src="{{ asset('frontweb/assests/images/h1.png') }}">
                            </div>
                            <div class="col-md-10">
                                <h6 class="del-d">DELIVERY</h6>
                                <p class="text-grey text-14">Same day & Next <br>Day</p>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-2 mob-c">
                                <img src="{{ asset('frontweb/assests/images/h2.png') }}">
                            </div>
                            <div class="col-md-10">
                                <h6 class="del-d">CUSTOMIZED PRICING</h6>
                                <p class="text-grey text-14">Get Customized Prices as per your Buying Pattern</p>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-2 mob-c">
                                <img src="{{ asset('frontweb/assests/images/h3.png') }}">
                            </div>
                            <div class="col-md-10">
                                <h6 class="del-d">FULFILMENT</h6>
                                <p class="text-grey text-14">Assured Fulfillment, Avoid Stock outs</p>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-2 mob-c">
                                <img src="{{ asset('frontweb/assests/images/h4.png') }}">
                            </div>
                            <div class="col-md-10">
                                <h6 class="del-d">PAYMENT TERM</h6>
                                <p class="text-grey text-14">Credit applicable based on Credit rating</p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Ended -->
    <!-- Steps Started -->
    <section class="step-section">
        <div class="">
            <div class="row align-items-center">
                <div class="col-md-12 mb-4">
                    <h2 class="text-center heading"><span class="text-primary"></span>
                    <span class="fw-600 fw-size">Advanced Marketplace Platform</span></h2>
                </div>
                <div class="col-md-10 m-auto">
                    <p class="paragraph text-center mb-5 p-1">Our platform ensures B2B pricing confidentiality via KYC, offering a vast selection of groceries at optimal margins.</p>
                </div>
                <div class="col-md-5">
                    <!--<img src="{{ asset('frontweb/assests/images/circle-rotate.png') }}" class="img-fluid image back-css">-->
                     <div class="text-center mob-div">
        <video 
            width="311" 
            height="630" 
            class="video-frame" 
            autoplay 
            muted 
            loop 
            playsinline>
            <source src="{{ asset('frontweb/assests/videos/banner-area.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
                </div>
                <div class="col-md-7">
                    <div class="points">
                        <ul>
                            <li><span class="num">01</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Log in & create account</span></li>
                            <li><span class="num">02</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">From catalouge select product with your buying pattern</span></li>
                            <li><span class="num">03</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Add to enquiry cart & submit enquiry</span>
                            </li>
                            <li><span class="num">04</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Get offer price which you can accept or negotiate</span></li>
                            <li><span class="num">05</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Accepted price items will only be added to my price list</span></li>
                             <li><span class="num">06</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Place order from my price list to order cart</span></li>
                             <li><span class="num">07</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Select delivery  & payment term</span></li>
                             <li><span class="num">08</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Track order & get delivery</span></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Steps Ended -->
    <!-- Products Started -->




    <section class="category-section-3 ">
    <div class="container mb-50">
        <h2 class="text-center mb-5 heading">
            <span class="text-primary"></span><span class="fw-600">What You Can Buy</span>
        </h2>

        @foreach ($categories as $category)
            @php
                // Filter subcategories where the image is not '1718876959.jpg', and ensure the image exists in the folder
                $validSubcategories = $category->subcategories->filter(function ($subcategory) {
                    $imagePath = public_path('uploads/' . $subcategory->image);
                    return !empty($subcategory->image) && $subcategory->image !== '1718876959.jpg' && file_exists($imagePath);
                });
            @endphp

            {{-- Only display the category if it has valid subcategories --}}
            @if ($validSubcategories->count() > 0)
                <div class="title mb-3">
                    <h3>{{ $category->category_name }}</h3>
                </div>
                <div class="row">
                    <div class="owl-carousel carousel-main mb-50">
                        @foreach ($validSubcategories as $subcategory)
                            <div class="col-md-2 col-w text-center">
                                <div class="product-div p-1 text-center">
                                    <a href="{{ route('subcateg') }}?category_id={{ $subcategory->category_id }}&sub_id={{ $subcategory->id }}">
                                        <div class="product-img-box">
                                            {{-- Only display the image if it exists in the folder --}}
                                            @if (file_exists(public_path('uploads/' . $subcategory->image)))
                                                <img src="/uploads/{{ $subcategory->image }}" class="product-img mx-auto" style="width:75px;">
                                            @else
                                                {{-- Optionally, display a placeholder image or leave it blank --}}
                                                <img src="/uploads/placeholder.png" class="product-img mx-auto" style="width:75px;">
                                            @endif
                                        </div>
                                        <h5 class="pt-2" style="color: #942525; font-size: 15px; height: 45px">
                                            {{ $subcategory->name }}
                                        </h5>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>








    <!-- Products Ended -->
    <!-- Client Logo Started -->
    <!--<section class="padding-50">-->
    <!--    <div class="container">-->
    <!--        <section class="customer-logos slider">-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png') }}"></div>-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c4.png') }}"></div>-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png') }}"></div>-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png') }}"></div>-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>-->
    <!--            <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>-->


    <!--        </section>-->
    <!--    </div>-->
    <!--</section>-->
    <!-- Client Logo Ended -->
    <!-- Testimonial Started -->
    <section class="padding-50">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center mb-4 heading"><span class="text-primary"></span><span
                            class="fw-600">Brands Associated With Us</span></h2>
                     <section class="customer-logos owl-carousel carousel-main mb-50 owl-loaded owl-drag">
                            @foreach ($brandsassoc as $key => $category)  
                           <div class="slide"><img src="/uploads/{{ $category->image }}"></div>
                            @endforeach
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png') }}"></div>-->



                    </section>
                </div>
            </div>
        </div>
    </section>

      <section class="">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center mb-4 heading"><span class="text-primary"></span><span
                            class="fw-600">Clients We Serve</span></h2>
                    
                     <section class="customer-logos owl-carousel carousel-main mb-50 owl-loaded owl-drag">
                          @foreach ($clientserve as $key => $category)
                        <div class="slide"><img src="/uploads/{{ $category->image }}"></div>
                         @endforeach
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c4.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>-->
                        <!--<div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>-->


                    </section>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Ended -->
    <!-- FAQ Started -->
    <section class="faq-section padding-50 ">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center mb-4 heading"><span class="text-primary"></span><span
                            class="fw-600">Frequently Asked Questions</span></h2>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    What is This Zonik Platform ?
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Zonik is a B2B Wholesale Food& Beverage platform , crafted for Food Service Industry Clients like Hotels, Restaurants , Cafes, Resorts, Food & Beverage Manufacturers , Cloud Kitchens ,Caterers, QSR and others Who wants to source Crticial food products with door step delivered as per their negotiated prices & payment terms.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                  Why should I order from Zonik and not any other Online Platforms available in B2B Space?
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">This platform is uniquely crafted after understanding the unorganized working pattern of Food Service industry where all the pratical features are kept as it is but Technology Simplied version like any offline working pattern of Enquiry being sent, Buying patterns based customized pricing is set by authorized person of customer with right payment term & Delivery types set by us. Removing the traditional or Flat Online Ecommerce style working which is not how the actual Industry works. InShort Coverting the Offline Unorganized working style with Tech Based solution without changing or compromising the working style of the industry but bettering it.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                  How to add products to my Enquiry Cart ?
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Select products listed after your Basic Log in is done, and select your buying pattern based on which price will be quoted to you. If you buy full box or Carton select the same and everytime order will be placed is carton based ordering only or you can select Loose where you order products in loose not in box so that prices will be given based on the same. Keep adding and finally go to enquiry section and submit your enquiry</div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFour" aria-expanded="false"
                                    aria-controls="flush-collapseFour">
                                How do I approve offered price and negotiate ?
                                </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Once our price is submitted to you , you will get notification from where you can either accept by clicking tick mark , or reoffer with your expected price. If Accepted it will be added to your Approved My Price List, If reoffer is selected. Prices will be requoted to you and again you can do your selection or cancel it.</div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFive" aria-expanded="false"
                                    aria-controls="flush-collapseFive">
                                  How do I place order ?
                                </button>
                            </h2>
                            <div id="flush-collapseFive" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">From the approved price list where you have accepted the prices from Offer sent, you can select products to add in Order Cart where you can see your approved price and select qty and place order from there</div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseSix" aria-expanded="false"
                                    aria-controls="flush-collapseSix">
                                Whats your delivery System ?
                                </button>
                            </h2>
                            <div id="flush-collapseSix" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Delivery is done Next Day before 6 pm, for any urgency you can connect to our customer care, who will ensure faster service.</div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingSeven">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseSeven" aria-expanded="false"
                                    aria-controls="flush-collapseSeven">
                                  Will I get Credit term for payment?
                                </button>
                            </h2>
                            <div id="flush-collapseSeven" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Yes this platform accepts credit to selected customers but only after verification clearance by our customer care team on raising a request us for the same. Payment term Credit period will be solely dependent on your timely payment credit rating. Poor ontime payment will result in permanent cancellation of credit term payment.</div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingEight">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseEight" aria-expanded="false"
                                    aria-controls="flush-collapseEight">
                                 Can I return the item once Purchased?
                                </button>
                            </h2>
                            <div id="flush-collapseEight" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingEight" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Items can only be returned During Delivery period only, Once the delivery is done No Items can be returned. Also only those Items can be returned which are damaged , expired or wrong items given by us  will be considered for returns. Post Delivery No items will be considered for any returns.</div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingNine">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseNine" aria-expanded="false"
                                    aria-controls="flush-collapseNine">
                                When will be  the Refund of payment in conditions of cancellation ?
                                </button>
                            </h2>
                            <div id="flush-collapseNine" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Refund of any amount to be done will be done within 5 working days after acceptance by our customer care team and confirmation in writing given.</div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTen">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTen" aria-expanded="false"
                                    aria-controls="flush-collapseTen">
                               Can I cancel my order once placed ?
                                </button>
                            </h2>
                            <div id="flush-collapseTen" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTen" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Yes you can cancel the item within 3 hours from the order placement is done. Post that order will be processed without any notice or cancellation.</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Ended -->
    <!-- Footer Started -->
   <!-- Footer Started -->
<section class="footer-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 my-2 ">
                <h4 class="footer-heading mb-2 ul-padding">Zonik (Infigourmet networks pvt ltd)</h4>
                <div class="hr"></div>
                <p class=" text-light mt-3">Zonik is a premier supplier specializing in providing top-quality products to restaurants and outlets. We understand the unique needs of the food service industry and are committed to delivering the freshest and most reliable products to our clients. Whether you run a small cafe or a large restaurant chain, Zonik ensures that you have everything you need to satisfy your customers and enhance their dining experience. Trust Zonik to be your dedicated partner in success, offering exceptional service and dependable supply solutions tailored to your specific requirements.

                </p>

            </div>

            <div class="col-md-2 my-2 " >
                <h4 class="footer-heading mb-2 ul-padding">QUICK LINKS</h4>
                <div class="hr"></div>
                <div class="links mt-3">
                    <ul class="footer-flex">
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir"></i>Login / Signup</a></li>
                        <!--<li class="mt-3"><a href="{{ route('subcateg', ['category_id' => 15]) }}" class="text-light"><i-->
                        <!--            class="fa-solid fa-angle-right text-center lir"></i>Catalogue</a></li>-->
                         <li class="mt-3"><a href="https://zonik.in/shipping-policy" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir "></i>Shipping Policy</a>
                        </li>
                        
                        <li class="mt-3"><a href="https://zonik.in/terms-condition" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir "></i>Terms & Conditions</a>
                        </li>
                        <li class="mt-3"><a href="https://zonik.in/privacy_policy" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir"></i>Privacy Policy</a></li>
                    
                    <li class="mt-3"><a href="https://zonik.in/payments-refunds" class="text-light"><i
                        class="fa-solid fa-angle-right text-center lir"></i>Payments Refunds</a></li>
                        <li class="mt-3"><a href="https://zonik.in/return-replacement" class="text-light"><i
                        class="fa-solid fa-angle-right text-center lir"></i>Return Replacement</a></li>
                    
                    </ul>
                </div>
            </div>
            <div class="col-md-2 my-2 ">
                <h4 class="footer-heading mb-2 ul-padding">FOLLOW US</h4>
                <div class="hr"></div>
                <div class="social-links mt-3">
                   <ul class="footer-flex">
                        <li class="text-light">
                            <a href="https://www.instagram.com/zonik.live" target="_blank" class="text-light"><i class="fa-brands fa-instagram color-primary so-l"></i> Instagram</a>
                        </li>
                        <li class="text-light mt-3">
                            <a href="https://www.facebook.com/people/Zonik-Live/pfbid0YTQN455T4TYfwCzS1uXnTj9njR8Fp9ucL5DxeS3BcGbB8ZnSyPiJ3LVa9Z2miY3bl/"  target="_blank" class="text-light"><i class="fa-brands fa-square-facebook color-primary so-l"></i> Facebook</a>
                        </li>
                        <!--<li class="text-light mt-3">-->
                        <!--    <a href="https://www.linkedin.com" target="_blank"  class="text-light"><i class="fa-brands fa-linkedin color-primary so-l"></i> LinkedIn</a>-->
                        <!--</li>-->
                    </ul>

                </div>
            </div>

            <div class="col-md-4 my-2">
                <h4 class="footer-heading mb-2">GET IN TOUCH</h4>
                <div class="hr"></div>
                <div class="d-flex mt-3 social align-items-center">
                    <i class="fa-solid fa-phone"></i>
                    <p class="paragraph">+91 8850268043</p>
                </div>
                <div class="d-flex mt-3 social align-items-center">
                    <i class="fa-solid fa-envelope"></i>
                    <p class="paragraph">connect@zonik.in</p>
                </div>
                <div class="d-flex mt-3 social align-items-center">
                    <i class="fa-solid fa-location-dot"></i>
                    <p class="paragraph hide-mobile">Unit B-45 ,Shanti Industrial Estate, Tambe Nagar,Mulund West,Mumbai 400080,India <br>
                    Fssai No. 11525009000305</p>
                    <p class="paragraph hide-desktop">Mulund, Mumbai, Maharashtra</p>

                </div>
                <!-- <div class="d-flex mt-3">
                         <div class="col">
                             <a href="">
                             <img src="{{ asset('frontweb/assets/images/app-store.png') }}"  class="play-store-m play-store">
                             </a>
                         </div>
                         <div class="col">
                             <a href="https://play.google.com/store/apps/details?id=com.infipara.dizcoverapp&pli=1" target='blank'>
                             <img src="{{ asset('frontweb/assets/images/google_play.png') }}"  class="play-store-m play-store">
                             </a>
                         </div>
                     </div> -->
            </div>




        </div>
    </div>
    <div class="copyright">
         <div class="div-hr mb-3"></div>
         <div class="col-md-12">
             <div class="copyright-sec d-flex align-items-center justify-content-center fs-12">
                 <p class="text-light mr-2 text-center">Copyright @2025  Infigourmet networks pvt ltd</p>
                 <!-- <img src="assests/images/white-logo.png"> -->
             </div>
         </div>
     </div>
     <a id="button"></a>
</section>
    <!-- Footer Ended -->





    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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

    @if (session('success'))
        <script type="text/javascript">
            toastr.success("{{ session('success') }}", 'Success');
        </script>
    @endif

    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            // alert('okk')
            toastr.error("{{ $error }}", 'Error');
        </script>
    @endforeach


     <script>
        category({{ $selectedCategory->id }});

        function category(CategoryId) {
            $.ajax({
                url: 'subcategory/pages',
                method: 'GET',
                data: {
                    CategoryId: CategoryId,
                },
                success: function(data) {
                    $('#product_id').html(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }


        function boxSelect(tagValue, id) {
            if (tagValue) {
                $("select[id='loose_value" + id + "']").prop('disabled', true);
            } else {
                $("select[id='loose_value" + id + "']").prop('disabled', false);
            }
        }

        function looseSelect(LooseValue, id) {
            if (LooseValue) {
                $("select[id='box" + id + "']").prop('disabled', true);
            } else {
                $("select[id='box" + id + "']").prop('disabled', false);
            }
        }

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
    $(document).ready(function(){

       $('.customer-logos').owlCarousel({
            loop:true,
            margin:30,
             autoplay: true,
            nav:false,
            responsive:{
                0:{
                    items:3,
                    nav:false
                },
                600:{
                    items:3,
                    nav:false
                },
                1000:{
                    items:6
                }
            }
        });
        $('.owl-carousel').owlCarousel({
            loop:false,
            margin:30,
            nav:true,
            responsive:{
                0:{
                    items:3
                },
                600:{
                    items:3
                },
                1000:{
                    items:6
                }
            }
        });

    });

    function checkOtpLength(input) {
    if (input.value.length > 4) {
        input.value = input.value.slice(0, 4);
    }
}
</script>




<script>
document.addEventListener("DOMContentLoaded", function () {

    
    document.documentElement.style.overflowY = "scroll"; 
    document.body.style.overflow = "hidden"; 

    setTimeout(() => {
        const loader = document.getElementById("loader");
        loader.style.transition = "opacity 0.7s";
        loader.style.opacity = "0";

        setTimeout(() => {
            loader.style.display = "none";

            
            document.body.style.overflow = "";
        }, 700);

    }, 200);
});

</script>

<script>

// Reset modal on show
$('#loginModal').on('shown.bs.modal', function () {
    $('#mobile_number3').val('');
    $('#error-message').hide();
    $('#messageBox').html('');
});


//  document.addEventListener("DOMContentLoaded", function(event) {
//         setTimeout(function() {
//             var loader = document.getElementById('loader');
//             loader.style.transition = 'opacity 1s';
//             loader.style.opacity = '0';
//             setTimeout(function() {
//                 loader.style.display = 'none';
//             }, 800);
//         }, 1000);
//     });

var player = null;
var tag = document.createElement("script");
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// Create the YouTube player instance
function onYouTubeIframeAPIReady() {
  player = new YT.Player("existing-iframe-example", {
    events: {
      onReady: function (event) {
        console.log("YouTube Player is ready!");
      },
    },
  });
}

// Function to close the popup and pause the video
function popupDidClose() {
  if (player && player.pauseVideo) {
    player.pauseVideo(); // Pause the video when closing
  } else {
    console.warn("Player is not ready yet, attempting to stop via src reset");
    $("#existing-iframe-example").attr("src", $("#existing-iframe-example").attr("src")); 
  }
  $(".popup__overlay").css({ display: "none" }); // Hide the popup
}

// Attach close functionality to the button
$(document).ready(function () {
  $(".popup__close").click(function () {
    popupDidClose(); // Close the popup when the button is clicked
  });

  // Show the popup when the page loads
  $(".popup__overlay").css({ display: "block" });
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


</body>

</html>
