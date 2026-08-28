<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link href="assests/css/style.css" rel="stylesheet">
    <title>Dizcover Businessss</title>
  </head>
  <body>

    <!-- Header Started -->
   <header class=" pb-0">
        <div class="top-nav top-header sticky-header">
            <div class="container-fluid">
                <div class="">
                    <div class="">
                        <div class="navbar-top">
                            <button class="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                                <span class="navbar-toggler-icon">
                                    <i class="fa-solid fa-bars"></i>
                                </span>
                            </button>
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="" class="web-logo nav-logo">
                                        <img src="https://zonik.in/frontweb/assests/images/logo-name.png" class="img-fluid blur-up lazyloaded" alt="">
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <div class="middle-box">
                                        <div class="location-box">
                                            <button class="btn location-button" data-bs-toggle="modal" data-bs-target="#locationModal">
                                                <span class="location-arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                                </span>
                                                <span class="locat-name">Your Location</span>
                                                <i class="fa-solid fa-angle-down"></i>
                                            </button>
                                        </div>

                                        <div class="search-box">
                                            <div class="input-group" data-bs-toggle="modal" data-bs-target="#ProductsModal">
                                                <input style="background-color:#ffffff;" readonly="" type="search" class="form-control" placeholder="Search Your Products...." aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <button class="btn" type="button" id="button-addon2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="rightside-box">
                                        <div class="search-full">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search font-light"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                </span>
                                                <input type="text" class="form-control search-type" placeholder="Search here..">
                                                <span class="input-group-text close-search">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x font-light"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                        <ul class="right-side-menu">  
                                            <li class="right-side">
                                                <a href="https://zonik.in/quoteslist" class="btn p-0 position-relative header-wishlist">
                                                    <i class="fa-solid fa-basket-shopping" style=" font-size:22px;"></i>
                                                    <span class="position-absolute top-0 start-100 translate-middle badge">
                                                        0
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="right-side">
                                                <div class="onhover-dropdown header-badge">
                                                    <button type="button" class="btn p-0 position-relative header-wishlist">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>

                                                    </button>
                                                </div>
                                            </li>
                                            <li class="right-side">
                                                <div class="onhover-dropdown header-badge">
                                                    <button type="button" class="btn p-0 position-relative header-wishlist ">
                                                        
                                                        </button><button class="btn p-0 bell-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa-solid fa-bell"></i></button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h3 id="offcanvasRightLabel">Notifications</h3>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="notifications-ul">
        <li>Your item is added to cart</li>
        <li>Get ₹100 off on FIRST ORDER</li>
        
        
    </ul>
  </div>
</div>
                                                    
                                                </div>
                                            </li>
                                              <li class="right-side">
                                                <div class="onhover-dropdown header-badge">
                                                    <button type="button" class="btn p-0 position-relative header-wishlist">
                                                        <i class="fa-solid fa-headset"></i>
                                                      
                                                        +91 9874563210
                                                    </button>
                                                </div>
                                            </li>
                                           <li class="right-side onhover-dropdown">
                                                <div class="delivery-login-box">
                                                                                                            <div class="delivery-detail">

                                                        </div>
                                            </div></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <div class="row">
                    <div class="col-md-4">
                       <img src="https://zonik.in/assets/images/man.png" style="height:50px;"> 
                    </div>
                    <div class="col-md-8">
                      <h4 class="offcanvas-title" id="offcanvasExampleLabel">Guest Outlet</h4>  
                      <h6 class="mt-1">Cust. ID : #NA</h6>
                    </div>
                </div>
                
                
                
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    
            </div>
            <div class="offcanvas-body">
                <div>
                 <ul class="slide-ul">
                     <li> <i class="fa-solid fa-user" style="margin-right:10px; font-size:20px;"></i>Account Details</li>
                     <li><i class="fa-solid fa-file-lines" style="margin-right:10px; font-size:20px;"></i>Your Orders</li>
                     <li><i class="fa-solid fa-bell" style="margin-right:10px; font-size:20px;"></i>Notifications</li>
                     <li><i class="fa-solid fa-question" style="margin-right:10px; font-size:20px;"></i>FAQs</li>
                     <li><i class="fa-solid fa-phone" style="margin-right:10px; font-size:20px;"></i>Contact Us</li>
                     <li class="logout-btn"><i class="fa-solid fa-right-to-bracket" style="margin-right:10px; font-size:20px;"></i>
                        <a class="" style="color: white" href="/logout">Logout</a>
                    </li>
                 </ul>
                </div>
                
            </div>
        </div>

    </header>
    <!-- Header Ended -->

    <!-- Banner Started -->
    <section class="banner-section">
        
            <div class="row">
                <div class="col-md-6">
                    <div class="banner-left">
                        <h1><span class="text-primary">We connect the most 
                            prestigious leading brands 
                            through</span><span class="fw-600">  our smart supply 
                            chain network</span></h1>
                            <a href="#" class="td-btn mt-4">Buy Now &nbsp;&nbsp; <i class="fa-solid fa-angle-right rih"></i></a>
                    </div>
                </div>
        <div class="col-md-6">
            <div class="banner-img">
            <img src="assests/images/banner-left-side.png" class="img-fluid">
        </div>
        </div>
            </div>
        
        
    </section>
    <!-- Banner Ended -->
    <!-- Features Started -->
    <section class="features-section ">
        <div class="container">
            <div class="row gx-5">
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="assests/images/h1.png">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Fast Delivery</h6>
                                <p class="text-grey">Same day Delivery</p>
                            </div>
                        </div>
                       
                       
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="assests/images/h2.png">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Secure Payment</h6>
                                <p class="text-grey">Totally secured</p>
                            </div>
                        </div>
                       
                       
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="assests/images/h3.png">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Support</h6>
                                <p class="text-grey">Full support</p>
                            </div>
                        </div>
                       
                       
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="assests/images/h4.png">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Offers</h6>
                                <p class="text-grey">Get offer in Bulk</p>
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
                    <h1 class="text-center"><span class="text-primary">Advanced</span><span class="fw-600">  Marketplace Platform</span></h1>
                </div>
                <div class="col-md-8 m-auto">
                    <p class="paragraph text-center mb-5">Our platforms provide a vast selection of grocery products with complete B2B pricing confidentiality through our KYC process at the best margin price.</p>
                </div>
                <div class="col-md-5">
                    <img src="assests/images/circle-rotate.png" class="img-fluid image">
                    <div class="text-center mob-div">
                    <img src="assests/images/aa.png" class="img-fluid">
                </div>
                </div>
                <div class="col-md-7">
                    <div class="points">
                        <ul>
                            <li><span class="num">01</span>&nbsp;&nbsp;&nbsp;&nbsp;Create an account</li>
                            <li><span class="num">02</span>&nbsp;&nbsp;&nbsp;&nbsp;Add items in order list and submit for the prices</li>
                            <li><span class="num">03</span>&nbsp;&nbsp;&nbsp;&nbsp;Get best prices within 24 hours</li>
                            <li><span class="num">04</span>&nbsp;&nbsp;&nbsp;&nbsp;Now , place your order and pay via Net-banking or UPI</li>
                            <li><span class="num">05</span>&nbsp;&nbsp;&nbsp;&nbsp;Get you order at your time and place</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Steps Ended -->
    <!-- Products Started -->
    <section class="products-section">
        <div class="container">
            <h1 class="text-center mb-5"><span class="text-primary">What You</span><span class="fw-600">  Can Buy</span></h1>
            <ul class="nav nav-pills mb-3 btn-bar" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><img src="assests/images/b1.png" class="beverages-img">Beverages</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><img src="assests/images/b2.png" class="beverages-img">Frozen</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"><img src="assests/images/b3.png" class="beverages-img">Diary</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-contact-tab1" data-bs-toggle="pill" data-bs-target="#pills-contact1" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"><img src="assests/images/b4.webp" class="beverages-img">Baking</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-contact-tab2" data-bs-toggle="pill" data-bs-target="#pills-contact2" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"><img src="assests/images/b6.png" class="beverages-img">Ready To Cook & Eat</button>
                  </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row product-main-div gy-4">
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="row product-main-div gy-4">
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="row product-main-div gy-4">
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-contact1" role="tabpanel" aria-labelledby="pills-contact-tab1">
                    <div class="row product-main-div gy-4">
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="product-div text-center">
                                <img src="assests/images/pp1.png" class="product-img">
                                <h5 class="pt-3">Juices</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-contact2" role="tabpanel" aria-labelledby="pills-contact-tab2">  <div class="row product-main-div gy-4">
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="product-div text-center">
                            <img src="assests/images/pp1.png" class="product-img">
                            <h5 class="pt-3">Juices</h5>
                        </div>
                    </div>
                </div></div>
              </div>
            
        </div>
    </section>
    <!-- Products Ended -->
    <!-- Client Logo Started -->
    <section class="padding-100">
    <div class="container">
         <section class="customer-logos slider">
            <div class="slide"><img src="assests/images/c1.png"></div>
            <div class="slide"><img src="assests/images/c2.png"></div>
            <div class="slide"><img src="assests/images/c3.png"></div>
            <div class="slide"><img src="assests/images/c4.png"></div>
            <div class="slide"><img src="assests/images/c5.png"></div>
            <div class="slide"><img src="assests/images/c6.png"></div>
            <div class="slide"><img src="assests/images/c1.png"></div>
            <div class="slide"><img src="assests/images/c2.png"></div>
            
           
         </section>
      </div>
    </section>
    <!-- Client Logo Ended -->
    <!-- Testimonial Started -->
    <section class="padding-100 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center mb-4"><span class="text-primary">Clients</span><span class="fw-600">  Feedback</span></h1>
                    <div class="testimonial">
                        <div class="owl-carousel owl-theme">
                          <div class="item">
                           <div class="testimonial-div">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="assests/images/t1.png" class="img-fluid">
                                </div>
                                <div class="col-md-8">
                                    <h5>Mr John Deo</h5>
                                    <h6 class="text-primary">SS Cafe</h6>

                                </div>
                                <div class="col-md-2">
                                    <div class="quote">
                                    <img src="assests/images/white-quote.png" class="">
                                </div>
                                </div>
                                <div class="col-md-12">
                                    <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                </div>
                            </div>
                           </div>
                          </div>
                          <div class="item">
                            <div class="testimonial-div-red">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="assests/images/t1.png" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <h5>Mr John Deo</h5>
                                        <h6 class="text-primary">SS Cafe</h6>
    
                                    </div>
                                    <div class="col-md-2">
                                        <div class="quote">
                                        <img src="assests/images/bquote.png" class="">
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                    </div>
                                </div>
                               </div>
                          </div>
                          <div class="item">
                            <div class="testimonial-div">
                             <div class="row">
                                 <div class="col-md-2">
                                     <img src="assests/images/t1.png" class="img-fluid">
                                 </div>
                                 <div class="col-md-8">
                                     <h5>Mr John Deo</h5>
                                     <h6 class="text-primary">SS Cafe</h6>
 
                                 </div>
                                 <div class="col-md-2">
                                     <div class="quote">
                                     <img src="assests/images/white-quote.png" class="">
                                 </div>
                                 </div>
                                 <div class="col-md-12">
                                     <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                 </div>
                             </div>
                            </div>
                           </div>
                           <div class="item">
                             <div class="testimonial-div-red">
                                 <div class="row">
                                     <div class="col-md-2">
                                         <img src="assests/images/t1.png" class="img-fluid">
                                     </div>
                                     <div class="col-md-8">
                                         <h5>Mr John Deo</h5>
                                         <h6 class="text-primary">SS Cafe</h6>
     
                                     </div>
                                     <div class="col-md-2">
                                         <div class="quote">
                                         <img src="assests/images/bquote.png" class="">
                                     </div>
                                     </div>
                                     <div class="col-md-12">
                                         <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                     </div>
                                 </div>
                                </div>
                           </div>
                         
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Ended -->
    <!-- FAQ Started -->
    <section class="faq-section padding-100 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center mb-4"><span class="text-primary">Frequently Asked</span><span class="fw-600">  Questions</span></h1>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Why is Dizcover good for my restaurant operations ?
                            </button>
                          </h2>
                          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                How can I sign up for a Dizcover account ?
                            </button>
                          </h2>
                          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                I run a restaurant. Can I purchase from Dizcover ?
                            </button>
                          </h2>
                          <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                          </div>
                        </div>
                   
                      </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Ended -->
    <!-- Footer Started -->
    <section class="footer-section ">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="footer-heading mb-2">GET IN TOUCH</h4>
                    <div class="hr"></div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-phone"></i>
                        <p class="paragraph">+91 8597412036 / +91 9857410236</p>
                    </div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-envelope"></i>
                        <p class="paragraph">support@dizcoverbusiness.com</p>
                    </div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-location-dot"></i>
                        <p class="paragraph">Mulund , Maharashtra</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-heading mb-2">QUICK LINKS</h4>
                    <div class="hr"></div>
                    <div class="links mt-4">
                        <ul class="footer-flex">
                            <li ><a href="#" class="text-light">
                                <i class="fa-solid fa-angle-right text-center lir"></i>
                                Login / Signup</a></li>
                            <li class="mt-3"><a href="#" class="text-light">
                                <i class="fa-solid fa-angle-right text-center lir"></i>
                                Catalogue</a></li>
                            <li class="mt-3"><a href="#" class="text-light">
                                <i class="fa-solid fa-angle-right text-center lir "></i>
                                Terms & Conditions</a></li>
                            <li class="mt-3"><a href="#" class="text-light">
                                <i class="fa-solid fa-angle-right text-center lir"></i>
                                Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <h4 class="footer-heading mb-2">FOLLOW US</h4>
                    <div class="hr"></div>
                    <div class="social-links mt-4">
                        <ul>
                            <li class="text-light "><i class="fa-brands fa-instagram color-primary so-l"></i> Instagram</li>
                            <li class="text-light mt-3"><i class="fa-brands fa-square-facebook color-primary so-l"></i>Facebook</li>
                            <li class="text-light mt-3"><i class="fa-brands fa-linkedin color-primary so-l"></i> Lindekin</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-heading mb-2">NEWSLETTER</h4>
                    <div class="hr"></div>
                    <p class="paragraph text-light mt-4">You will be notified when somthing new will be appear.</p>
                    <form class="mt-4">
                        <div class="mb-3">
                          <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email">
                        </div>
                </div>



            </div>
        </div>
        <div class="copyright">
            <div class="div-hr mb-5"></div>
            <div class="col-md-12">
                <div class="copyright-sec d-flex align-items-center justify-content-center">
                    <p class="text-light mr-2" >Copyright @2023</p> <img src="assests/images/white-logo.png"><p class="color-primary ml-2"></p>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Ended -->





    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script>
        $(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 3
            }
        }]
    });
});
</script>
<script>
$(function() {
    // Owl Carousel
    var owl = $(".owl-carousel");
    owl.owlCarousel({
      items: 2,
      margin: 30,
     autoplay:true,
      loop: true,
      nav: false,
    });
  });
</script>
  </body>
</html>