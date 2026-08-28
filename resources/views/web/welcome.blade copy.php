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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link href="{{ asset('frontweb/assests/css/style.css') }}" rel="stylesheet">
    <title>Zonik</title>
</head>
<style>
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

.mb-65 {
    margin-bottom: 65px !important;
}
</style>

<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <a href="https://api.whatsapp.com/send?phone=+91 74000 29048&text=Hello" class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>
    <!-- Header Started -->
    <section class="header-section">
        <div class="container">
            <div class="row g-0">
                <div class="col-md-6 col-4">
                    <img src="{{ asset('frontweb/assests/images/logo-name.png') }}" class="logo mob-none">
                    <img src="{{ asset('frontweb/assests/images/zonik.png') }}" class="logo desk-none">
                </div>
                <div class="col-md-6 text-end col-8">
                    {{-- <div class="middle-box"> --}}
                    {{-- <div class="location-box"> --}}
                    <button type="submit" data-bs-toggle="modal" data-bs-target="#locationModal"
                        class="btn location-button add-button addcart-button btn buy-button">
                        <div class="shadow-inner-btn ">LOGIN / SIGNUP</div>
                    </button>
                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
            </div>
            <div class="modal  location-modal fade theme-modal" id="locationModal" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-lg modal-dialog-centered modal-full screen-sm-down modal-dialogg">
                    <div class="modal-content modal-cust" id="mobileBox">
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

                                    <div class="row d-flex">
                                        <div class="col-md-6 ">
                                            <input oninput="checkNumber()" type="number" name="mobile" id="mobile"
                                                class="form-control mb-3" placeholder="Enter Your Mobile Number"
                                                required />
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" name="name" id="name" class="form-control mb-3 "
                                                placeholder="Enter Your Name" required />
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="designation" id="designation"
                                                class="form-control mb-3" placeholder="Enter Your Designation"
                                                required />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="outlet_name" id="outlet_name"
                                                class="form-control mb-3" placeholder="Enter Your Outlet Name"
                                                required />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="location" id="location" class="form-control mb-3"
                                                placeholder="Enter Your Location Name" required />
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" oninput="checkPincode()" name="pincode" id="pincode"
                                                class="form-control mb-3 " placeholder="Enter Your Pincode" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="messageBox" class="mb-3 error-message"></div>
                            <button type="button" onclick="validateForm()" class="btn red-btn SendOtpBtn">Send
                                OTP</button>
                        </div>
                    </div>

                    <div class="modal-content modal-cust1 d-none" id="otpBox">
                        <div class="modal-header">
                            <h5 class="modal-title indexh5" id="exampleModalLabel">Enter Verification
                                Code
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <!-- <i class="fa-solid fa-xmark"></i> -->
                            </button>
                        </div>

                        <div class="modal-body ">

                            <div class="location-list">
                                <p class="mt-1 text-content mb-4"> 4 digit OTP has
                                    been sent to +91 <span id="mobile_number">
                                </p>
                                <div class="search-input">
                                    <input type="number" name="otp" id="otp" class="form-control mb-4"
                                        placeholder="Enter Your OTP">
                                </div>
                            </div>
                            <button type="button " onclick="verifyOtp()" class="btn red-btn ">Verify
                                OTP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Header Ended -->

    <!-- Banner Started -->
    <section class="banner-section">

        <div class="row">
            <div class="col-md-6">
                <div class="banner-left">
                    <h1><span class="" style="color:#121212;">B2B Food Tech Platform to buy your Critical Food Supplies
                            : Faster , Cheaper & Organized manner</h1>
                    <button type="submit" data-bs-toggle="modal" data-bs-target="#locationModal" class="td-btn mt-4">Buy
                        Now &nbsp;&nbsp; <i class="fa-solid fa-angle-right rih"></i></button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="banner-img">
                    <img src="{{ asset('frontweb/assests/images/banner-left-side.png') }}" class="img-fluid">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                    <h2 class="text-center heading"><span class="text-primary"></span><span class="fw-600">
                            Advanced Marketplace Platform</span></h2>
                </div>
                <div class="col-md-10 m-auto">
                    <p class="paragraph text-center mb-5">Our platform ensures B2B pricing confidentiality via KYC,
                        offering a vast selection of groceries at optimal margins.</p>
                </div>
                <div class="col-md-5">
                    <img src="{{ asset('frontweb/assests/images/circle-rotate.png') }}" class="img-fluid image">
                    <div class="text-center mob-div">
                        <img src="{{ asset('frontweb/assests/images/aa.png') }}" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="points">
                        <ul>
                            <li><span class="num">01</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Log in &
                                    create account</span></li>
                            <li><span class="num">02</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">From catalouge
                                    select product with your buying pattern</span></li>
                            <li><span class="num">03</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Add to enquiry
                                    cart & submit enquiry</span>
                            </li>
                            <li><span class="num">04</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Get offer
                                    price which you can accept or negotiate</span></li>
                            <li><span class="num">05</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Accepted price
                                    items will only be added to my price list</span></li>
                            <li><span class="num">06</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Place order
                                    from my price list to order cart</span></li>
                            <li><span class="num">07</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Select
                                    delivery & payment term</span></li>
                            <li><span class="num">08</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="steps-li">Track order &
                                    get delivery</span></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Steps Ended -->
    <!-- Products Started -->






    <section class="category-section-3 padding-100 ">
        <div class="container mb-65">
            <h2 class="text-center mb-5 heading">
                <span class="text-primary"></span><span class="fw-600">What You Can
                    Buy</span>
            </h2>
            <h2 class="text-center mb-5 heading">
                <span class="text-primary"></span><span class="fw-600">
                    @foreach ($categories as $category)
                </span>
            </h2>
            <div class="title mb-3">
                <h3>{{ $category->category_name }}</h3>
            </div>
            @if ($category->subcategories->count() > 0)
            <div class="row ">
                <div class="owl-carousel carousel-main mb-65">
                    @foreach ($category->subcategories as $subcategory)
                    <div class="col-md-2 col-w text-center ">
                        <div class="product-div p-1 text-center">
                            <a
                                href="{{ route('subcateg') }}?category_id={{ $subcategory->category_id }}&sub_id={{ $subcategory->id }}">
                                <img src="/uploads/{{ $subcategory->image }}" class="product-img mx-auto"
                                    style="width:75px;">
                                <h5 class="pt-4" style="color: #942525; font-size: 15px; height: 50px">
                                    {{ $subcategory->name }}
                                </h5>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p>No subcategories available for this category.</p>
                @endif
                @endforeach
            </div>
    </section>







    <!-- Products Ended -->
    <!-- Client Logo Started -->
    <!--<section class="padding-100">-->
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
    <section class="padding-100">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
<<<<<<< Updated upstream
                    <h2 class="text-center mb-4 heading"><span class="text-primary"></span><span class="fw-600">Brands
                            Associated With Us</span></h2>
                    <section class="customer-logos slider">
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c4.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
=======
                    <h2 class="text-center mb-4 heading"><span class="text-primary"></span><span
                            class="fw-600">Brands Associated With Us</span></h2>
                     <!-- <section class="customer-logos slider">
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c4.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
            </section> -->
>>>>>>> Stashed changes

                <div class="owl-carousel owl-theme mt-4" id="owl-carousel">
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c1.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img ">
                    <img src="{{ asset('frontweb/assests/images/c2.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img ">
                    <img src="{{ asset('frontweb/assests/images/c3.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img ">
                    <img src="{{ asset('frontweb/assests/images/c4.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img ">
                    <img src="{{ asset('frontweb/assests/images/c6.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img ">
                    <img src="{{ asset('frontweb/assests/images/c5.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img ">
                    <img src="{{ asset('frontweb/assests/images/c1.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c2.png') }}" class="img-fluid">
                </div>

            </div>

<<<<<<< Updated upstream
                    </section>
=======
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </section>

    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
<<<<<<< Updated upstream
                    <h2 class="text-center mb-4 heading"><span class="text-primary"></span><span class="fw-600">Clients
                            We Serve</span></h2>
                    <section class="customer-logos slider">
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c4.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                        <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
=======
                    <h2 class="text-center mb-4 heading"><span class="text-primary"></span><span
                            class="fw-600">Clients We Serve</span></h2>
                     <!-- <section class="customer-logos slider">
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c4.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png') }}"></div>
                <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png') }}"></div>
            </section> -->
>>>>>>> Stashed changes

                 <div class="owl-carousel owl-theme mt-4" id="owl-carousel1">
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c1.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c2.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c3.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c4.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c6.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c5.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c1.png') }}" class="img-fluid">
                </div>
                <div class="item owl-img">
                    <img src="{{ asset('frontweb/assests/images/c2.png') }}" class="img-fluid">
                </div>

<<<<<<< Updated upstream
                    </section>
=======
            </div>
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Ended -->
    <!-- FAQ Started -->
    <section class="faq-section padding-100 ">
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
                                <div class="accordion-body">Zonik is a B2B Wholesale Food& Beverage platform , crafted
                                    for Food Service Industry Clients like Hotels, Restaurants , Cafes, Resorts, Food &
                                    Beverage Manufacturers , Cloud Kitchens ,Caterers, QSR and others Who wants to
                                    source Crticial food products with door step delivered as per their negotiated
                                    prices & payment terms.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    Why should I order from Zonik and not any other Online Platforms available in B2B
                                    Space?
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">This platform is uniquely crafted after understanding the
                                    unorganized working pattern of Food Service industry where all the pratical features
                                    are kept as it is but Technology Simplied version like any offline working pattern
                                    of Enquiry being sent, Buying patterns based customized pricing is set by authorized
                                    person of customer with right payment term & Delivery types set by us. Removing the
                                    traditional or Flat Online Ecommerce style working which is not how the actual
                                    Industry works. InShort Coverting the Offline Unorganized working style with Tech
                                    Based solution without changing or compromising the working style of the industry
                                    but bettering it.</div>
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
                                <div class="accordion-body">Select products listed after your Basic Log in is done, and
                                    select your buying pattern based on which price will be quoted to you. If you buy
                                    full box or Carton select the same and everytime order will be placed is carton
                                    based ordering only or you can select Loose where you order products in loose not in
                                    box so that prices will be given based on the same. Keep adding and finally go to
                                    enquiry section and submit your enquiry</div>
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
                                <div class="accordion-body">Once our price is submitted to you , you will get
                                    notification from where you can either accept by clicking tick mark , or reoffer
                                    with your expected price. If Accepted it will be added to your Approved My Price
                                    List, If reoffer is selected. Prices will be requoted to you and again you can do
                                    your selection or cancel it.</div>
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
                                <div class="accordion-body">From the approved price list where you have accepted the
                                    prices from Offer sent, you can select products to add in Order Cart where you can
                                    see your approved price and select qty and place order from there</div>
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
                                <div class="accordion-body">Delivery is done Next Day before 6 pm, for any urgency you
                                    can connect to our customer care, who will ensure faster service.</div>
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
                                <div class="accordion-body">Yes this platform accepts credit to selected customers but
                                    only after verification clearance by our customer care team on raising a request us
                                    for the same. Payment term Credit period will be solely dependent on your timely
                                    payment credit rating. Poor ontime payment will result in permanent cancellation of
                                    credit term payment.</div>
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
                                <div class="accordion-body">Items can only be returned During Delivery period only, Once
                                    the delivery is done No Items can be returned. Also only those Items can be returned
                                    which are damaged , expired or wrong items given by us will be considered for
                                    returns. Post Delivery No items will be considered for any returns.</div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingNine">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseNine" aria-expanded="false"
                                    aria-controls="flush-collapseNine">
                                    When will be the Refund of payment in conditions of cancellation ?
                                </button>
                            </h2>
                            <div id="flush-collapseNine" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Refund of any amount to be done will be done within 5
                                    working days after acceptance by our customer care team and confirmation in writing
                                    given.</div>
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
                                <div class="accordion-body">Yes you can cancel the item within 3 hours from the order
                                    placement is done. Post that order will be processed without any notice or
                                    cancellation.</div>
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
                        <p class="paragraph">+919136411489
                        </p>
                    </div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-envelope"></i>
                        <p class="paragraph">support@dizcover.com</p>
                    </div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-location-dot"></i>
<<<<<<< Updated upstream
                        <p class="paragraph">Mulund,Mumbai,Maharashtra
                        </p>
=======
                        <p class="paragraph">Mulund,Mumbai,Maharashtra</p>
>>>>>>> Stashed changes
                    </div>
                  <div class="d-flex mt-4">
                            <div class="col">
                                <a href="">
                                <img src="{{ asset('frontweb/assets/images/app-store.png') }}"  class="play-store-m play-store">
                                </a>
                            </div>

                            <div class="col">
                                <a href="">
                                <img src="{{ asset('frontweb/assets/images/google_play.png') }}"  class="play-store-m play-store">
                                </a>
                            </div>
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
            <div class="div-hr mb-3"></div>
            <div class="col-md-12">
                <div class="copyright-sec d-flex align-items-center justify-content-center fs-12">
                    <p class="text-light mr-2">Copyright @2023</p><p class="text-light">Zonik</p>
                    <!-- <img src="assests/images/white-logo.png"> -->
                    <p class="color-primary ml-2"></p>
                </div>
            </div>
        </div>
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

    <script rel="stylesheet" type="text/css" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    </script>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


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
    function category(CategoryId) {
        $.ajax({
            url: 'subcategory/pages',
            method: 'GET',
            data: {
                CategoryId: CategoryId,
            },
            success: function(data) {
                console.log(data);
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

    function validateForm() {
        var mobile = document.getElementById('mobile').value;
        var name = document.getElementById('name').value;
        var designation = document.getElementById('designation').value;
        var outletName = document.getElementById('outlet_name').value;
        var location = document.getElementById('location').value;
        var pincode = document.getElementById('pincode').value;

        var messageBox = document.getElementById('messageBox');
        messageBox.innerHTML = '';

        var errors = [];

        if (mobile.length !== 10) {
            errors.push('Please enter a valid 10-digit mobile number.');
        }

        if (name.trim() === '') {
            errors.push('Please enter your name.');
        }

        if (designation.trim() === '') {
            errors.push('Please enter your designation.');
        }

        if (outletName.trim() === '') {
            errors.push('Please enter your outlet name.');
        }

        if (location.trim() === '') {
            errors.push('Please enter your location name.');
        }

        if (pincode.trim() === '') {
            errors.push('Please enter your pincode.');
        }

        // Check if mobile number exists
        var mobileValue = mobile.trim();
        if (mobileValue.length === 10) {
            axios.get(`/customer/name/${mobileValue}`)
                .then((res) => {
                    if (res.data?.name) {
                        // If mobile number exists, directly send OTP
                        sendOtp();
                    } else {
                        // If mobile number doesn't exist, show validation errors
                        if (errors.length > 0) {
                            messageBox.innerHTML = errors.join('<br>');
                        } else {
                            sendOtp();
                        }
                    }
                })
                .catch((error) => {
                    console.error('Error checking mobile number:', error);
                });
        }
    }




    function sendOtp() {
        var mobile = document.getElementById('mobile').value;
        document.getElementById('mobile_number').innerHTML = mobile;

        axios.post('/customer/sendOtp', {
            mobile: mobile,
        }).then(
            response => {
                if (response.data) {
                    toastr.success('OTP sent successfully');
                    document.getElementById('otpBox').classList.remove('d-none');
                    document.getElementById('mobileBox').classList.add('d-none');
                } else {
                    toastr.error('Failed to send OTP');
                }
            }
        )
    }

    function checkPincode() {
        var pincodeInput = document.getElementById('pincode');
        var pincodeValue = pincodeInput.value.trim();

        if (pincodeValue.length === 6) {
            axios.get(`/pincode/${pincodeValue}`)
                .then((res) => {
                    if (res.data.pincode) {
                        $('.error-message').text('');
                        $('.SendOtpBtn').prop('disabled', false);
                    } else {
                        $('.error-message').text('We are not available to deliver at this location.');
                        $('.SendOtpBtn').prop('disabled', true);
                    }
                })
                .catch((error) => {
                    console.error('Error checking pincode:', error);
                });
        } else {
            // Handle case where pincode length is not 6
        }
    }


    function checkNumber() {
        var mobileInput = document.getElementById('mobile');
        var mobileValue = mobileInput.value.trim();

        if (mobileValue.length === 10) {
            axios.get(`/customer/name/${mobileValue}`)
                .then((res) => {
                    if (res.data?.name) {
                        // If mobile number exists, hide fields
                        document.getElementById('name').classList.add('d-none');
                        document.getElementById('outlet_name').classList.add('d-none');
                        document.getElementById('designation').classList.add('d-none');
                        document.getElementById('location').classList.add('d-none');
                        document.getElementById('pincode').classList.add('d-none');
                    } else {
                        // If mobile number doesn't exist, show fields
                        document.getElementById('name').classList.remove('d-none');
                        document.getElementById('outlet_name').classList.remove('d-none');
                        document.getElementById('designation').classList.remove('d-none');
                        document.getElementById('location').classList.remove('d-none');
                        document.getElementById('pincode').classList.remove('d-none');
                    }
                })
                .catch((error) => {
                    console.error('Error checking mobile number:', error);
                });
        } else {
            // If mobile number length is not 10 digits, show fields
            document.getElementById('name').classList.remove('d-none');
            document.getElementById('outlet_name').classList.remove('d-none');
            document.getElementById('designation').classList.remove('d-none');
            document.getElementById('location').classList.remove('d-none');
            document.getElementById('pincode').classList.remove('d-none');
        }
    }






    function sendOtp() {
        var mobile = document.getElementById('mobile').value;
        // console.log(mobile);
        document.getElementById('mobile_number').innerHTML = mobile;
        axios.post('/customer/sendOtp', {
            mobile: mobile,
        }).then(
            response => {
                // console.log('okk');
                if (response.data) {
                    toastr.success('OTP sent successfully');
                    document.getElementById('otpBox').classList.remove('d-none');
                    document.getElementById('mobileBox').classList.add('d-none');

                } else {
                    toastr.error('Failed to send OTP');
                }
            }
        )
    }

    function verifyOtp() {
        var otp = document.getElementById('otp').value;
        var mobile = document.getElementById('mobile').value;
        var name = document.getElementById('name').value;
        var outlet_name = document.getElementById('outlet_name').value;

        var designation = document.getElementById('designation').value;
        var location = document.getElementById('location').value;
        var pincode = document.getElementById('pincode').value;

        axios.post('/customer/verifyOtp', {
                otp: otp,
                mobile: mobile,
                name: name,
                outlet_name: outlet_name,
                designation: designation,
                pincode: pincode,
                location: location
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

    $(document).ready(function() {
        $('.customer-logos').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 1500,
            arrows: false,
            dots: false,
            infinite: true,
            loop: true,
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

<<<<<<< Updated upstream
    <!-- <script>
=======
    <script>
        $(document).ready(function(){
            var owl = $('#owl-carousel');
            owl.owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                navText: [
                    '<i class="fa fa-angle-left"></i>',
                    '<i class="fa fa-angle-right"></i>'
                ],
                autoplay: false,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 6
                    }
                }
            });
        });
</script>


            <script>
        $(document).ready(function(){
            var owl = $('#owl-carousel1');
            owl.owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                navText: [
                    '<i class="fa fa-angle-left"></i>',
                    '<i class="fa fa-angle-right"></i>'
                ],
                autoplay: false,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 6
                    }
                }
            });
        });
</script>


    <script>
>>>>>>> Stashed changes
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
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        });
    });
    </script>
</body>

</html>
