<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="icon" href="../assets/images/favicon/5.png" type="image/x-icon">
    <title>Dizcover Business</title>

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&amp;display=swap" rel="stylesheet">

    <!-- bootstrap css -->
    <link id="rtl-link" rel="stylesheet" type="text/css" href="../assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- wow css -->
    <link rel="stylesheet" href="../assets/css/animate.min.css" />

    <!-- font-awesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- feather icon css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/feather-icon.css">

    <!-- Plugin CSS file with desired skin css -->
    <link rel="stylesheet" href="../assets/css/vendors/ion.rangeSlider.min.css">

    <!-- slick css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick/slick-theme.css">

    <!-- animation css -->
    <link rel="stylesheet" type="text/css" href="../assets/css/font-style.css">

    <!-- Template css -->
    <link id="color-link" rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body class="theme-color-3 dark ">

    <!-- Loader Start -->
    <!-- <div class="fullpage-loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div> -->
    <!-- Loader End -->

    <!-- Header Start -->
    <header class=" pb-0">
        <div class="top-nav top-header sticky-header">
            <div class="container-fluid-lg">
                <div class="row">
                    <div class="col-12">
                        <div class="navbar-top">
                            <button class="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                                <span class="navbar-toggler-icon">
                                    <i class="fa-solid fa-bars"></i>
                                </span>
                            </button>
                            <a href="index.html" class="web-logo nav-logo">
                                <img src="assests/images/logo.png" class="img-fluid blur-up lazyload" alt="">
                            </a>

                            <div class="middle-box">
                                <div class="location-box">
                                    <button class="btn location-button" data-bs-toggle="modal"
                                        data-bs-target="#locationModal">
                                        <span class="location-arrow">
                                            <i data-feather="map-pin"></i>
                                        </span>
                                        <span class="locat-name">Your Location</span>
                                        <i class="fa-solid fa-angle-down"></i>
                                    </button>
                                </div>

                                <div class="search-box">
                                    <div class="input-group">
                                        <input type="search" class="form-control"
                                            placeholder="Search Your Products ...." aria-label="Recipient's username"
                                            aria-describedby="button-addon2">
                                        <button class="btn" type="button" id="button-addon2">
                                            <i data-feather="search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="rightside-box">
                                <div class="search-full">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i data-feather="search" class="font-light"></i>
                                        </span>
                                        <input type="text" class="form-control search-type"
                                            placeholder="Search here..">
                                        <span class="input-group-text close-search">
                                            <i data-feather="x" class="font-light"></i>
                                        </span>
                                    </div>
                                </div>
                                <ul class="right-side-menu">
                                    <li class="right-side">
                                        <div class="delivery-login-box">
                                            <div class="delivery-icon">
                                                <div class="search-box">
                                                    <i data-feather="search"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="right-side">
                                        <a href="contact-us.html" class="delivery-login-box">
                                            <div class="delivery-icon">
                                                <i data-feather="phone-call"></i>
                                            </div>
                                            <div class="delivery-detail">
                                                <h6>24/7 Delivery</h6>
                                                <h5>+91 888 104 2340</h5>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="right-side">
                                        <a href="{{ route('quoteslist') }}"
                                            class="btn p-0 position-relative header-wishlist">
                                            <i data-feather="heart"></i>
                                            <span class="position-absolute top-0 start-100 translate-middle badge">
                                                {{ $quote_Items_list->count() }}
                                                <span class="visually-hidden">unread messages</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="right-side">
                                        <div class="onhover-dropdown header-badge">
                                            <a href="/cart" type="button"
                                                class="btn p-0 position-relative header-wishlist">
                                                <i data-feather="shopping-cart"></i>
                                                <span class="position-absolute top-0 start-100 translate-middle badge">
                                                    {{ $cart->count() }}
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </a>

                                            {{-- <div class="onhover-div">
                                            <ul class="cart-list">
                                              @foreach ($cart as $cartProducts)
                                                <li class="product-box-contain">
                                                    <div class="drop-cart">
                                                        <a href="#" class="drop-image">
                                                            <img src="../assets/images/vegetable/product/1.png"
                                                                class="blur-up lazyload" alt="">
                                                        </a>

                                                        <div class="drop-contain">
                                                            <a href="#">
                                                                <h5>{{$cartProducts->product->product_name}}</h5>
                                                            </a>
                                                            <h6><span>1 x</span> $80.58</h6>
                                                            <button class="close-button close_button">
                                                                <i class="fa-solid fa-xmark"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                    
                                                </li>

                                            </ul>

                                            <div class="price-box">
                                                <h5>Total :</h5>
                                                <h4 class="theme-color fw-bold">$106.58</h4>
                                            </div>

                                            <div class="button-group">
                                                <a href="/cart" class="btn btn-sm cart-button">View Cart</a>
                                                <a href="checkout.html" class="btn btn-sm cart-button theme-bg-color
                                                text-white">Checkout</a>
                                            </div>
                                        </div> --}}
                                        </div>
                                    </li>
                                    <li class="right-side onhover-dropdown">
                                        <div class="delivery-login-box">
                                            <div class="delivery-icon" data-bs-toggle="offcanvas"
                                                href="#offcanvasExample" role="button"
                                                aria-controls="offcanvasExample">
                                                <i class="fa-solid fa-bars"></i>
                                            </div>
                                            <div class="delivery-detail">
                                                <h6>Hello,</h6>
                                                <h5>My Account</h5>
                                            </div>
                                        </div>


                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div>
                    Some text as placeholder. In real life you can have the elements you have chosen. Like, text,
                    images, lists, etc.
                </div>
                <div class="dropdown mt-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown">
                        Dropdown button
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </header>

    <section class="order-management pt-100 ">
        <div class="container-fluid  ">
            <div class="row">
                <div class="col-md-12">
                    <div class="title">
                        <h3 class="heading"><span class="text-primary">Order</span><span class="fw-600"> &nbsp;
                                Management</span></h3>
                        <section class="py-5 header">
                            <div class="container-fluid py-4">
                                <div class="d-flex align-items-start">
                                    <div class="left-sidebar">

                                        <div class="img-div">
                                            <img src="{{ asset('assets/images/empty-profile.webp')}}" class="empty-profile">
                                            <h4 class="companyname mt-4">ABC COMPANY</h4>
                                            <h5 class="mt-2">abc@gmail.com</h5>
                                        </div>

                                        <!-- Tabs nav -->
                                        <div class="nav flex-column nav-pills orders-m" id="v-pills-tab"
                                            role="tablist" aria-orientation="vertical">
                                            <button class="nav-link active" id="v-pills-enquiry-tab" data-bs-toggle="pill" data-bs-target="#v-pills-enquiry" type="button" role="tab" aria-controls="v-pills-enquiry " aria-selected="false"><i class="fa-solid fa-cart-shopping left-icon"></i> Enquiry  <i class="fa-solid fa-angle-right profile-arrow left-icon"></i></button>

                                            <button class="nav-link" id="v-pills-offer-tab" data-bs-toggle="pill" data-bs-target="#v-pills-offer" type="button" role="tab" aria-controls="v-pills-offer" aria-selected="false"><i class="fa-solid fa-scroll left-icon"></i> Offer List <i class="fa-solid fa-angle-right profile-arrow left-icon"></i></button>

<button class="nav-link" id="v-pills-price-tab" 
  data-bs-toggle="pill" data-bs-target="#v-pills-price" 
  type="button" role="tab" aria-controls="v-pills-price" 
  aria-selected="false"><i class="fa-solid fa-receipt left-icon"></i> My Accept List 
   <i class="fa-solid fa-angle-right profile-arrow left-icon"></i></button>

   <button class="nav-link" id="v-pills-rejected-tab" 
   data-bs-toggle="pill" data-bs-target="#v-pills-rejected" 
   type="button" role="tab" aria-controls="v-pills-rejected" 
   aria-selected="false"><i class="fa-solid fa-square-xmark left-icon"></i> My Rejected List 
    <i class="fa-solid fa-angle-right profile-arrow left-icon"></i></button>
    <button class="nav-link " id="v-pills-orders-tab" data-bs-toggle="pill" data-bs-target="#v-pills-orders" type="button" role="tab" aria-controls="v-pills-orders" aria-selected="false"><i class="fa-solid fa-list left-icon"></i> Orders <i class="fa-solid fa-angle-right profile-arrow left-icon"></i></button>

                                           
                                        </div>
                                    </div>







                                    <div class="tab-content right-side1" id="v-pills-tabContent">

                                        <div class="tab-pane fade" id="v-pills-orders" role="tabpanel"
                                            aria-labelledby="v-pills-orders-tab">
                                            <div class="orders-tab orders-tab-margin">
                                                <h4>Requested <span class="color-primary"> Enquiry </span></h4>
                                                <table class="table all-package theme-table" id="table_id">
                                                    <tbody>
                                                        <tr>
                                                            <td class="w1">Sr.No.</td>
                                                            <td class="w2">Image</td>
                                                            <td class="w3">Product Name</td>
                                                            <td class="w4">Pattern</td>
                                                            <td class="w5">Monthly Consumption</td>
                                                            <td class="w6">Action</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="w1">01 </td>
                                                            <td class="w2"><img src="assests/images/img.png"
                                                                    class="enquiry-img"></td>
                                                            <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                                    : 1 kg</span></td>
                                                            <td class="w4"><select class="form-select"
                                                                    aria-label="Default select example">
                                                                    <option selected>Select</option>
                                                                    <option value="1">Loose Box</option>
                                                                    <option value="2">Cartoon box</option>
                                                                </select></td>
                                                            <td class="w5">
                                                                <div class="quantity">
                                                                    <a href="#"
                                                                        class="quantity__minus"><span>-</span></a>
                                                                    <input name="quantity" type="text"
                                                                        class="quantity__input" value="1">
                                                                    <a href="#"
                                                                        class="quantity__plus"><span>+</span></a>
                                                                </div>
                                                                <input type="text form-control" class="optional mt-3">
                                                            </td>
                                                            <td class="w6"><img src="assests/images/close.svg">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w1">02 </td>
                                                            <td class="w2"><img src="assests/images/img.png"
                                                                    class="enquiry-img"></td>
                                                            <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                                    : 1 kg</span></td>
                                                            <td class="w4"><select class="form-select"
                                                                    aria-label="Default select example">
                                                                    <option selected>Select</option>
                                                                    <option value="1">Loose Box</option>
                                                                    <option value="2">Cartoon box</option>
                                                                </select></td>
                                                            <td class="w5">
                                                                <div class="quantity">
                                                                    <a href="#"
                                                                        class="quantity__minus"><span>-</span></a>
                                                                    <input name="quantity" type="text"
                                                                        class="quantity__input" value="1">
                                                                    <a href="#"
                                                                        class="quantity__plus"><span>+</span></a>
                                                                </div>
                                                                <input type="text form-control" class="optional mt-3">
                                                            </td>
                                                            <td class="w6"><img src="assests/images/close.svg">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w1">03 </td>
                                                            <td class="w2"><img src="assests/images/img.png"
                                                                    class="enquiry-img"></td>
                                                            <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                                    : 1 kg</span></td>
                                                            <td class="w4"><select class="form-select"
                                                                    aria-label="Default select example">
                                                                    <option selected>Select</option>
                                                                    <option value="1">Loose Box</option>
                                                                    <option value="2">Cartoon box</option>
                                                                </select></td>
                                                            <td class="w5">
                                                                <div class="quantity">
                                                                    <a href="#"
                                                                        class="quantity__minus"><span>-</span></a>
                                                                    <input name="quantity" type="text"
                                                                        class="quantity__input" value="1">
                                                                    <a href="#"
                                                                        class="quantity__plus"><span>+</span></a>
                                                                </div>
                                                                <input type="text form-control" class="optional mt-3">
                                                            </td>
                                                            <td class="w6"><img src="assests/images/close.svg">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w1">04 </td>
                                                            <td class="w2"><img src="assests/images/img.png"
                                                                    class="enquiry-img"></td>
                                                            <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                                    : 1 kg</span></td>
                                                            <td class="w4"><select class="form-select"
                                                                    aria-label="Default select example">
                                                                    <option selected>Select</option>
                                                                    <option value="1">Loose Box</option>
                                                                    <option value="2">Cartoon box</option>
                                                                </select></td>
                                                            <td class="w5">
                                                                <div class="quantity">
                                                                    <a href="#"
                                                                        class="quantity__minus"><span>-</span></a>
                                                                    <input name="quantity" type="text"
                                                                        class="quantity__input" value="1">
                                                                    <a href="#"
                                                                        class="quantity__plus"><span>+</span></a>
                                                                </div>
                                                                <input type="text form-control" class="optional mt-3">
                                                            </td>
                                                            <td class="w6"><img src="assests/images/close.svg">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w1">05 </td>
                                                            <td class="w2"><img src="assests/images/img.png"
                                                                    class="enquiry-img"></td>
                                                            <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                                    : 1 kg</span></td>
                                                            <td class="w4"><select class="form-select"
                                                                    aria-label="Default select example">
                                                                    <option selected>Select</option>
                                                                    <option value="1">Loose Box</option>
                                                                    <option value="2">Cartoon box</option>
                                                                </select></td>
                                                            <td class="w5">
                                                                <div class="quantity">
                                                                    <a href="#"
                                                                        class="quantity__minus"><span>-</span></a>
                                                                    <input name="quantity" type="text"
                                                                        class="quantity__input" value="1">
                                                                    <a href="#"
                                                                        class="quantity__plus"><span>+</span></a>
                                                                </div>
                                                                <input type="text form-control" class="optional mt-3">
                                                            </td>
                                                            <td class="w6"><img src="assests/images/close.svg">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w1">06 </td>
                                                            <td class="w2"><img src="assests/images/img.png"
                                                                    class="enquiry-img"></td>
                                                            <td class="w3">Govind - Dahi, 1 Kg Pouch<br><span>Unit
                                                                    : 1 kg</span></td>
                                                            <td class="w4"><select class="form-select"
                                                                    aria-label="Default select example">
                                                                    <option selected>Select</option>
                                                                    <option value="1">Loose Box</option>
                                                                    <option value="2">Cartoon box</option>
                                                                </select></td>
                                                            <td class="w5">
                                                                <div class="quantity">
                                                                    <a href="#"
                                                                        class="quantity__minus"><span>-</span></a>
                                                                    <input name="quantity" type="text"
                                                                        class="quantity__input" value="1">
                                                                    <a href="#"
                                                                        class="quantity__plus"><span>+</span></a>
                                                                </div>
                                                                <input type="text form-control" class="optional mt-3">
                                                            </td>
                                                            <td class="w6"><img src="assests/images/close.svg">
                                                            </td>
                                                        </tr>



                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                        <div class="tab-pane fade" id="v-pills-enquiry" role="tabpanel"
                                            aria-labelledby="v-pills-enquiry-tab">
                                            <div class="orders-tab orders-tab-margin">
                                                <h4>Enquiry </h4>
                                                <form action="{{ route('enquiry.store') }}" method="POST">
                                                    @csrf
                                                    <table class="table mt-5 requested-enquiry w-100" cellpadding="15">
                                                        <thead>
                                                       
                                                            <tr>
                                                            <th scope="col" >Sr.No.</th>
                                                            <th scope="col">Image</th>
                                                            <th scope="col" >Product Name</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">Total</th>
                                                            <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                            <tbody>
                                                            @foreach ($quote_Items_list as $key => $quote_Items)
                                                                <tr>
                                                                    <td scope="row"> {{ $key + 1 }} </td>
                                                                    <td ><img
                                                                            src="uploads/{{ $quote_Items->product->image }}"
                                                                            class="enquiry-img"></td>
                                                                    <td >
                                                                        {{ $quote_Items->product->product_name }}</td>

                                                                    <td>
                                                                        <select class="form-select"
                                                                            name="product_types{{ $key + 1 }}"
                                                                            aria-label="Default select example">
                                                                            <option value="">Select</option>
                                                                            <option value="1">Loose Box</option>
                                                                            <option value="2">Cartoon box</option>
                                                                        </select>
                                                                        <div class="quantity mt-3">
                                                                            <span 
                                                                                onclick="quantityMinus('quantity__input{{ $key + 1 }}','total{{ $key + 1 }}')"
                                                                                class="quantity__minus"><span>-</span></span>
                                                                            <input type="hidden"
                                                                                name="product_id{{ $key + 1 }}"
                                                                                value="{{ $quote_Items->product->id }}">
                                                                            <input type="number"
                                                                                name="quantity{{ $key + 1 }}"
                                                                                id="quantity__input{{ $key + 1 }}"
                                                                                class="quantity__input"
                                                                                value="1">
                                                                            <span 
                                                                                onclick="quantityPlus('quantity__input{{ $key + 1 }}','total{{ $key + 1 }}')"
                                                                                class="quantity__plus"><span>+</span></span>
                                                                        </div>
                                                                    </td>
                                                                    <td><span id="total{{ $key + 1 }}"></span></td>
                                                                    <td><img
                                                                            src="{{ asset('frontweb/assests/images/del.png') }}"
                                                                            class="del-svg"></td>
                                                                </tr>
                                                            @endforeach
                                                            <tr colspan="5" class="tr">
                                                                <td class="d-flex align-items-center justify-content-center">
                                                            <button type="submit"
                                                        class="add-button addcart-button btn buy-button text-light red-btn">Submit 
                                                            </button></td>
                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    
                                                </form>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-offer" role="tabpanel"
                                            aria-labelledby="v-pills-offer-tab">
                                            <div class="orders-tab orders-tab-margin">
                                                <h4>My <span class="color-primary">Offer List</span></h4>
                                                <table class="table mt-5 requested-enquiry w-100" cellpadding="15">
                                                    <thead>
                                                    <tr>
                                                            <th scope="col" >Sr.No.</th>
                                                            <th   scope="col" >Image</th>
                                                            <th scope="col" >Product Name</th>
                                                            <th  scope="col" >Order Qty (Pattern)</th>
                                                            <th scope="col" >Offer Price</th>
                                                            <th  scope="col" >MRP</th>
                                                            <th scope="col" >Discount</th>
                                                            <th scope="col" >Action</th>
                                                        </tr>
                                            </thead>
                                                    <tbody>
                                                        

                                                        {{-- {{$enquiries}} --}}
                                                        @foreach ($enquiriesForOfferList as $key => $offer_list)
                                                            <tr>
                                                                <td scope="row">{{ $key + 1 }}</td>
                                                                <td><img
                                                                        src="uploads/{{ $offer_list->product->image }}"
                                                                                          class="enquiry-img"></td>
                                                                <td>
                                                                    {{ $offer_list->product->product_name }}</td>
                                                                <td class="w3-10">{{ $offer_list->quantity }}</td>
                                                               
                                                               
                                                                <td>
                                                                    <select class="form-select" name="product_types{{ $key + 1 }}"   aria-label="Default select example">
                                                                        <option value=""> Select</option>
                                                                        <option value="1" 
                                                                            {{ $offer_list->product_types == 1 ? 'selected' : '' }}>
                                                                            Loose Box</option>
                                                                        <option value="2"
                                                                            {{ $offer_list->product_types == 2 ? 'selected' : '' }}>
                                                                            Carton Box</option>

                                                                    </select>



                                                                    <h6 class="color-grey">₹
                                                                        {{ $offer_list->offer_price }}</h6>
                                                                </td>

                                                                <td>
                                                                    <h6 class="color-grey">₹ {{ $offer_list->mrp }}
                                                                    </h6>
                                                                </td>
                                                                <td>₹ {{ $offer_list->discount }}</td>
                                                                <td class="w3-13">
                                                                    <div class="d-flex">
                                                                    <form
                                                                        action="{{ route('offer.request', $offer_list->id) }}"
                                                                        method="POST" rel="noopener noreferrer">
                                                                        @csrf
                                                                        <input type="hidden" name="status"
                                                                            value="accept">
                                                                        <button type="submit"
                                                                            class=" btn-primary bg-primary1"><i class="fa-solid fa-check text-light"></i></button>
                                                                    </form>
                                                                    <form
                                                                        action="{{ route('offer.request', $offer_list->id) }}"
                                                                        method="POST" rel="noopener noreferrer">
                                                                        @csrf
                                                                        <input type="hidden" name="status"
                                                                            value="rejected">
                                                                        <button type="submit" value="Click" id="button"
                                                                            class=" btn-primary bg-primary2 text-light"><i class="fa-solid fa-x"></i></button>
                                            
                                                                    </form></div>

                                                                 
<!-- Button trigger modal -->


<button type="button" class="btn btn-primary3 mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
Reoffer
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-body">
      <form
                                                                        action="{{ route('offer.request', $offer_list->id) }}"
                                                                        method="POST" rel="noopener noreferrer">
                                                                        @csrf
                                                                        Enter Your Expected Price
                                                                        <input type="text" class="form-control mt-3"
                                                                            name="expected_price_value"
                                                                            value="">
                                                                           
                                                                       
                                                                    </form>
      </div>
     
      <form
                                                                        action="{{ route('offer.request', $offer_list->id) }}"
                                                                        method="POST" rel="noopener noreferrer">
                                                                        @csrf
<button type="button" class="btn btn-primary4 mt-3 p-4" >
Submit
</button>
                                            </form>
     
    </div>
  </div>
</div>

                                                           
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-price" role="tabpanel"
                                            aria-labelledby="v-pills-price-tab">
                                            <div class="orders-tab orders-tab-margin">
                                                <h4>My <span class="color-primary"> Accept List</span></h4>
                                                <table class="table mt-5 requested-enquiry">
<thead>
                                                        <tr>
                                                            <th  scope="col">Sr.No.</th>
                                                            <th  scope="col">Image</th>
                                                            <th  scope="col">Product Name</th>
                                                            <th  scope="col">Order Qty (Pattern)</th>
                                                            <th  scope="col">Approved Price
                                                                (Basic)</th>
                                                                <th  scope="col">MRP</th>
                                                                <th  scope="col">Discount</th>
                                                                <th  scope="col">Expected price </th>
                                                                <th  scope="col">Action</th>
                                                        </tr>
                                            </thead>
                                                        <tbody>
                                                        @foreach ($enquiriesForAccept as $key => $acceptLits)
                                                            <tr>
                                                                <td class="w3-7">{{ $key + 1 }}</td>
                                                                <td class="w3-8"><img
                                                                        src="uploads/{{  $acceptLits->product->image }}"
                                                                        class="enquiry-img"></td>
                                                                <td class="w3-9">
                                                                    {{ $acceptLits->product->product_name }}</td>
                                                                <td class="w3-9">{{ $acceptLits->quantity }}</td>
                                                                <td class="w3-10">
                                                                    <select class="form-select" name="product_types{{ $key + 1 }}"   aria-label="Default select example">
                                                                        <option value=""> Select</option>
                                                                        <option value="1" 
                                                                            {{ $acceptLits->product_types == 1 ? 'selected' : '' }}>
                                                                            Loose Box</option>
                                                                        <option value="2"
                                                                            {{ $acceptLits->product_types == 2 ? 'selected' : '' }}>
                                                                            Carton Box</option>

                                                                    </select>
                                                                    <h6 class="color-grey">₹
                                                                        {{ $acceptLits->offer_price }}</h6>
                                                                </td>
                                                                

                                                                <td class="w3-11">
                                                                    <h6 class="color-grey">₹ {{ $acceptLits->mrp }}
                                                                    </h6>
                                                                </td>
                                                                <td class="w3-12">₹ {{ $acceptLits?->discount }}</td>

                                                                <td class="w3-12">₹
                                                                    {{ $acceptLits->expected_price_value }}</td>
                                                                <td class="w3-12">
                                                                    <div class="d-flex">
                                                                    <form action="{{ route('cart.create') }}"
                                                                        method="POST" rel="noopener noreferrer">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $acceptLits->product_id }}">
                                                                        <input type="hidden" name="user_id"
                                                                            value="1">

                                                                        <button type="submit"
                                                                            class="btn btn-cart"><i class="fa-solid fa-cart-shopping"></i></button>
                                                                    </form>
                                                                    

                                                                    <form
                                                                        action="{{ route('offer.remove', $acceptLits->id) }}"
                                                                        method="POST" rel="noopener noreferrer">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $acceptLits->product_id }}">
                                                                        <input type="hidden" name="user_id"
                                                                            value="1">

                                                                        <button type="submit"
                                                                            class="btn btn-del"><i class="fa-solid fa-trash"></i></button>
                                                                    </form></div>
                                                                </td>
                                                            </tr>
                                                        @endforeach



                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade" id="v-pills-rejected" role="tabpanel"
                                            aria-labelledby="v-pills-rejected-tab">
                                            <div class="orders-tab orders-tab-margin">
                                                <h4>My <span class="color-primary"> Rejected List</span></h4>
                                                <table class="table mt-5 requested-enquiry">

                                                 <thead>
                                                        <tr>
                                                        <th  scope="col">Sr.No.</th>
                                                        <th  scope="col">Image</th>
                                                        <th  scope="col">Product Name</th>
                                                        <th  scope="col">Order Qty (Pattern)</th>
                                                        <th  scope="col">Approved Price
                                                                (Basic)</td>
                                                                <th  scope="col">MRP</th>
                                                                <th  scope="col">Discount</th>

                                                                <th  scope="col">Action</th>
                                                        </tr></thead>
                                                        <tbody>
                                                        @foreach ($enquiriesForRejected as $key => $acceptLits)
                                                            <tr>
                                                                <td class="w3-7">{{ $key + 1 }}</td>
                                                                <td class="w3-8"><img
                                                                        src="uploads/{{ $acceptLits->product->image }}"
                                                                        class="enquiry-img"></td>
                                                                <td class="w3-9">
                                                                    {{ $acceptLits->product->product_name }}</td>
                                                                <td class="w3-9">{{ $acceptLits->quantity }}</td>
                                                                <td class="w3-10">
                                                                    <select class="form-select" name="product_types{{ $key + 1 }}"   aria-label="Default select example">
                                                                        <option value=""> Select</option>
                                                                        <option value="1" 
                                                                            {{ $acceptLits->product_types == 1 ? 'selected' : '' }}>
                                                                            Loose Box</option>
                                                                        <option value="2"
                                                                            {{ $acceptLits->product_types == 2 ? 'selected' : '' }}>
                                                                            Carton Box</option>

                                                                    </select>
                                                                    <h6 class="color-grey">₹
                                                                        {{ $acceptLits->offer_price }}</h6>
                                                                </td>
                                                                <td class="w3-11">
                                                                    <h6 class="color-grey">₹ {{ $acceptLits->mrp }}
                                                                    </h6>
                                                                </td>
                                                                <td class="w3-12">₹ {{ $acceptLits->discount }}</td>

                                                                <td class="w3-12">
                                                                    <form
                                                                        action="{{ route('offer.request', $acceptLits->id) }}"
                                                                        method="POST" rel="noopener noreferrer">
                                                                        @csrf
                                                                        <input type="hidden" name="status"
                                                                            value="accept">
                                                                        <button type="submit"
                                                                            class="btn  bg-primary4"><i class="fa-solid fa-check text-light"></i></button>
                                                                    </form>


                                                                    <form action="{{ route('offer.request', $acceptLits->id) }}" method="POST" rel="noopener noreferrer">
                                                                        @csrf
                                                                        

                                                                           

                                                                         <button type="button" class="btn btn-primary3 mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
Reoffer
</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach



                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-cart" role="tabpanel"
                                            aria-labelledby="v-pills-cart-tab">...</div>

                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Fruit & Vegetables Section Start -->
    <section class="product-section-3 bg-light padding-100">
        <div class="container-fluid-lg">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="title d-flex bre-img align-items-center">
                        <h2>Bestsellers</h2>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <div class=" owl-carousel owl-theme" id="owl-carousel1">
                        <div class="item">
                            <div class="product-box-4 wow fadeInUp">
                                <div class="product-image product-image-2">
                                    <a href="#">
                                        <img src="assests/images/be1.png" class="img-fluid blur-up lazyload"
                                            alt="">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#view">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="javascript:void(0)" class="notifi-wishlist">
                                                <i class="fa-regular fa-heart"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="product-detail">

                                    <a href="#">
                                        <h5 class="name text-title">Coca Cola - 250 ml </h5>
                                    </a>
                                    <h5 class="price price-p">Pack of 28</h5>

                                    <div class="addtocart_btn">
                                        <button class="add-button addcart-button btn buy-button text-light">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <div class="qty-box cart_qty">
                                            <div class="input-group">
                                                <button type="button" class="btn qty-left-minus" data-type="minus"
                                                    data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text"
                                                    name="quantity" value="1">
                                                <button type="button" class="btn qty-right-plus" data-type="plus"
                                                    data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-box-4 wow fadeInUp">
                                <div class="product-image product-image-2">
                                    <a href="#">
                                        <img src="assests/images/be1.png" class="img-fluid blur-up lazyload"
                                            alt="">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#view">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="javascript:void(0)" class="notifi-wishlist">
                                                <i class="fa-regular fa-heart"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="product-detail">

                                    <a href="#">
                                        <h5 class="name text-title">Coca Cola - 250 ml </h5>
                                    </a>
                                    <h5 class="price price-p">Pack of 28</h5>

                                    <div class="addtocart_btn">
                                        <button class="add-button addcart-button btn buy-button text-light">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <div class="qty-box cart_qty">
                                            <div class="input-group">
                                                <button type="button" class="btn qty-left-minus" data-type="minus"
                                                    data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text"
                                                    name="quantity" value="1">
                                                <button type="button" class="btn qty-right-plus" data-type="plus"
                                                    data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-box-4 wow fadeInUp">
                                <div class="product-image product-image-2">
                                    <a href="#">
                                        <img src="assests/images/be1.png" class="img-fluid blur-up lazyload"
                                            alt="">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#view">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="javascript:void(0)" class="notifi-wishlist">
                                                <i class="fa-regular fa-heart"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="product-detail">

                                    <a href="#">
                                        <h5 class="name text-title">Coca Cola - 250 ml </h5>
                                    </a>
                                    <h5 class="price price-p">Pack of 28</h5>

                                    <div class="addtocart_btn">
                                        <button class="add-button addcart-button btn buy-button text-light">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <div class="qty-box cart_qty">
                                            <div class="input-group">
                                                <button type="button" class="btn qty-left-minus" data-type="minus"
                                                    data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text"
                                                    name="quantity" value="1">
                                                <button type="button" class="btn qty-right-plus" data-type="plus"
                                                    data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="product-box-4 wow fadeInUp">
                                <div class="product-image product-image-2">
                                    <a href="#">
                                        <img src="assests/images/be1.png" class="img-fluid blur-up lazyload"
                                            alt="">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#view">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="javascript:void(0)" class="notifi-wishlist">
                                                <i class="fa-regular fa-heart"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="product-detail">

                                    <a href="#">
                                        <h5 class="name text-title">Coca Cola - 250 ml </h5>
                                    </a>
                                    <h5 class="price price-p">Pack of 28</h5>

                                    <div class="addtocart_btn">
                                        <button class="add-button addcart-button btn buy-button text-light">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <div class="qty-box cart_qty">
                                            <div class="input-group">
                                                <button type="button" class="btn qty-left-minus" data-type="minus"
                                                    data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text"
                                                    name="quantity" value="1">
                                                <button type="button" class="btn qty-right-plus" data-type="plus"
                                                    data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
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
    <!-- Product Fruit & Vegetables Section End -->


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
                        <ul>
                            <li><a href="#" class="text-light"><i
                                        class="fa-solid fa-angle-right text-center lir"></i>Login / Signup</a></li>
                            <li class="mt-3"><a href="#" class="text-light"><i
                                        class="fa-solid fa-angle-right text-center lir"></i>Catalogue</a></li>
                            <li class="mt-3"><a href="#" class="text-light"><i
                                        class="fa-solid fa-angle-right text-center lir "></i>Terms & Conditions</a>
                            </li>
                            <li class="mt-3"><a href="#" class="text-light"><i
                                        class="fa-solid fa-angle-right text-center lir"></i>Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <h4 class="footer-heading mb-2">FOLLOW US</h4>
                    <div class="hr"></div>
                    <div class="social-links mt-4">
                        <ul>
                            <li class="text-light "><i class="fa-brands fa-instagram color-primary so-l"></i>
                                Instagram</li>
                            <li class="text-light mt-3"><i
                                    class="fa-brands fa-square-facebook color-primary so-l"></i>Facebook</li>
                            <li class="text-light mt-3"><i class="fa-brands fa-linkedin color-primary so-l"></i>
                                Lindekin</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-heading mb-2">NEWSLETTER</h4>
                    <div class="hr"></div>
                    <p class="paragraph text-light mt-4">You will be notified when somthing new will be appear.</p>
                    <form class="mt-4">
                        <div class="mb-3">
                            <input type="email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Enter Email">
                        </div>
                </div>



            </div>
        </div>
        <div class="copyright">
            <div class="div-hr mb-5"></div>
            <div class="col-md-12">
                <div class="copyright-sec d-flex align-items-center justify-content-center">
                    <p class="text-light mr-2">Copyright @2023</p> <img src="assests/images/white-logo.png">
                    <p class="color-primary ml-2"></p>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Ended -->

    <!-- Quick View Modal Box Start -->
    <div class="modal fade theme-modal view-modal" id="view" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-sm-4 g-2">
                        <div class="col-lg-6">
                            <div class="slider-image">
                                <img src="../assets/images/product/category/1.jpg" class="img-fluid blur-up lazyload"
                                    alt="">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="right-sidebar-modal">
                                <h4 class="title-name">Peanut Butter Bite Premium Butter Cookies 600 g</h4>
                                <h4 class="price">$36.99</h4>
                                <div class="product-rating">
                                    <ul class="rating">
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star"></i>
                                        </li>
                                    </ul>
                                    <span class="ms-2">8 Reviews</span>
                                    <span class="ms-2 text-danger">6 sold in last 16 hours</span>
                                </div>

                                <div class="product-detail">
                                    <h4>Product Details :</h4>
                                    <p>Candy canes sugar plum tart cotton candy chupa chups sugar plum chocolate I love.
                                        Caramels marshmallow icing dessert candy canes I love soufflé I love toffee.
                                        Marshmallow pie sweet sweet roll sesame snaps tiramisu jelly bear claw. Bonbon
                                        muffin I love carrot cake sugar plum dessert bonbon.</p>
                                </div>

                                <ul class="brand-list">
                                    <li>
                                        <div class="brand-box">
                                            <h5>Brand Name:</h5>
                                            <h6>Black Forest</h6>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="brand-box">
                                            <h5>Product Code:</h5>
                                            <h6>W0690034</h6>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="brand-box">
                                            <h5>Product Type:</h5>
                                            <h6>White Cream Cake</h6>
                                        </div>
                                    </li>
                                </ul>

                                <div class="select-size">
                                    <h4>Cake Size :</h4>
                                    <select class="form-select select-form-size">
                                        <option selected>Select Size</option>
                                        <option value="1.2">1/2 KG</option>
                                        <option value="0">1 KG</option>
                                        <option value="1.5">1/5 KG</option>
                                        <option value="red">Red Roses</option>
                                        <option value="pink">With Pink Roses</option>
                                    </select>
                                </div>

                                <div class="modal-button">
                                    <button onclick="location.href = 'cart.html';"
                                        class="btn btn-md add-cart-button icon">Add
                                        To Cart</button>
                                    <button onclick="location.href = 'product-left.html';"
                                        class="btn theme-bg-color view-button icon text-white fw-bold btn-md">
                                        View More Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick View Modal Box End -->

    <!-- Cookie Bar Box Start -->
    <div class="cookie-bar-box">
        <div class="cookie-box">
            <div class="cookie-image">
                <img src="../assets/images/cookie-bar.png" class="blur-up lazyload" alt="">
                <h2>Cookies!</h2>
            </div>

            <div class="cookie-contain">
                <h5 class="text-content">We use cookies to make your experience better</h5>
            </div>
        </div>

        <div class="button-group">
            <button class="btn privacy-button">Privacy Policy</button>
            <button class="btn ok-button">OK</button>
        </div>
    </div>
    <!-- Cookie Bar Box End -->

    <!-- Location Modal Start -->
    <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Choose your Delivery Locationddd</h5>
                    <p class="mt-1 text-content">Enter your address and we will specify the offer for your area.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="location-list">
                        <div class="search-input">
                            <input type="search" class="form-control" placeholder="Search Your Area">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                        <div class="disabled-box">
                            <h6>Select a Location</h6>
                        </div>

                        <ul class="location-select custom-height">
                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Alabama</h6>
                                    <span>Min: $130</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Arizona</h6>
                                    <span>Min: $150</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>California</h6>
                                    <span>Min: $110</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Colorado</h6>
                                    <span>Min: $140</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Florida</h6>
                                    <span>Min: $160</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Georgia</h6>
                                    <span>Min: $120</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Kansas</h6>
                                    <span>Min: $170</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Minnesota</h6>
                                    <span>Min: $120</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>New York</h6>
                                    <span>Min: $110</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Washington</h6>
                                    <span>Min: $130</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Location Modal End -->

    <!-- Tap to top start -->
    <div class="theme-option">
        <div class="setting-box">
            <button class="btn setting-button">
                <i class="fa-solid fa-gear"></i>
            </button>

            <div class="theme-setting-2">
                <div class="theme-box">
                    <ul>
                        <li>
                            <div class="setting-name">
                                <h4>Color</h4>
                            </div>
                            <div class="theme-setting-button color-picker">
                                <form class="form-control">
                                    <label for="colorPick" class="form-label mb-0">Theme Color</label>
                                    <input type="color" class="form-control form-control-color" id="colorPick"
                                        value="#239698" title="Choose your color">
                                </form>
                            </div>
                        </li>

                        <li>
                            <div class="setting-name">
                                <h4>Dark</h4>
                            </div>
                            <div class="theme-setting-button">
                                <button class="btn btn-2 outline" id="darkButton">Dark</button>
                                <button class="btn btn-2 unline" id="lightButton">Light</button>
                            </div>
                        </li>

                        <li>
                            <div class="setting-name">
                                <h4>RTL</h4>
                            </div>
                            <div class="theme-setting-button rtl">
                                <button class="btn btn-2 rtl-unline">LTR</button>
                                <button class="btn btn-2 rtl-outline">RTL</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="back-to-top">
            <a id="back-to-top" href="#">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <!-- Tap to top end -->

    <!-- Bg overlay Start -->
    <div class="bg-overlay"></div>
    <!-- Bg overlay End -->

    <!-- latest jquery-->
    <script src="../assets/js/jquery-3.6.0.min.js"></script>

    <!-- jquery ui-->
    <script src="../assets/js/jquery-ui.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Bootstrap js-->
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap-notify.min.js"></script>
    <script src="../assets/js/bootstrap/popper.min.js"></script>

    <!-- feather icon js-->
    <script src="../assets/js/feather/feather.min.js"></script>
    <script src="../assets/js/feather/feather-icon.js"></script>

    <!-- Lazyload Js -->
    <script src="../assets/js/lazysizes.min.js"></script>

    <!-- Slick js-->
    <script src="../assets/js/slick/slick.js"></script>
    <script src="../assets/js/slick/slick-animation.min.js"></script>
    <script src="../assets/js/custom-slick-animated.js"></script>
    <script src="../assets/js/slick/custom_slick.js"></script>

    <!-- Range slider js -->
    <script src="../assets/js/ion.rangeSlider.min.js"></script>

    <!-- Auto Height Js -->
    <script src="../assets/js/auto-height.js"></script>

    <!-- Lazyload Js -->
    <script src="../assets/js/lazysizes.min.js"></script>

    <!-- Quantity js -->
    <script src="../assets/js/quantity-2.js"></script>

    <!-- Fly Cart Js -->
    <script src="../assets/js/fly-cart.js"></script>

    <!-- Timer Js -->
    <script src="../assets/js/timer1.js"></script>
    <script src="../assets/js/timer2.js"></script>

    <!-- Copy clipboard Js -->
    <script src="../assets/js/clipboard.min.js"></script>
    <script src="../assets/js/copy-clipboard.js"></script>

    <!-- WOW js -->
    <script src="../assets/js/wow.min.js"></script>
    <script src="../assets/js/custom-wow.js"></script>

    <!-- script js -->
    <script src="../assets/js/script.js"></script>

    <!-- thme setting js -->
    <script src="../assets/js/theme-setting.js"></script>
    <script>

        function quantityMinus(inputId,totalValue) {
            const input = $(`#${inputId}`);
            var value = input.val();
            if (value > 1) {
                value--;
            }
            input.val(value);
            document.getElementById(totalValue).innerHTML = value;


        }

        function quantityPlus(inputId,totalValue) {
            const input = $(`#${inputId}`);
            var value = input.val();
            value++;
            input.val(value);
            document.getElementById(totalValue).innerHTML = value;

        }



        //     $(document).ready(function() {
        //   const minus = $('.quantity__minus');
        //   const plus = $('.quantity__plus');
        //   const input = $('#quantity__input1');
        //   minus.click(function(e) {
        //     e.preventDefault();
        //     var value = input.val();
        //     if (value > 1) {
        //       value--;
        //     }
        //     input.val(value);
        //   });

        //   plus.click(function(e) {
        //     e.preventDefault();
        //     var value = input.val();
        //     value++;
        //     input.val(value);
        //   })
        // });
    </script>
    <script>
        $(function() {
            // Owl Carousel
            var owl = $("#owl-carousel");
            owl.owlCarousel({
                items: 4,
                margin: 20,
                autoplay: true,
                loop: true,
                nav: false,
            });
        });
    </script>
    <script>
        $(function() {
            // Owl Carousel
            var owl = $("#owl-carousel1");
            owl.owlCarousel({
                items: 5,
                margin: 20,
                autoplay: true,
                loop: true,
                nav: true,
            });
        });
    </script>

    <script>
        $(function() {
            // Owl Carousel
            var owl = $(".owl-carousel");
            owl.owlCarousel({
                items: 1,
                margin: 10,
                loop: true,
                nav: true
            });
        });
    </script>
</body>

</html>
