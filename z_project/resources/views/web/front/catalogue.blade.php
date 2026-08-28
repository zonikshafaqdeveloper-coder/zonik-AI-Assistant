<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('frontweb/assets/images/favicon/5.png') }}" type="image/x-icon">
    <title>Dizcover Businesasds</title>

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
    <link id="color-link" rel="stylesheet" type="text/css" href="{{ asset('frontweb/assets/css/style.css') }}">
</head>
<style>
.active {
    font-weight: bold;
}

.d-flex {
    gap: 10px;
}
</style>

<body class="">

    {{-- {{ $selectedSubCategoryId }} --}}
    <header class=" pb-0">
        <div class="top-nav top-header sticky-header">
            <div class="container-fluid">
                <div class="">
                    <div class="">
                        <div class="navbar-top">
                            <button class="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                                <span class="navbar-toggler-icon">
                                    <i class="fa-solid fa-bars"></i>
                                </span>
                            </button>
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="" class="web-logo nav-logo">
                                        <img src="https://zonik.in/frontweb/assests/images/logo-name.png"
                                            class="img-fluid blur-up lazyloaded" alt="">
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <div class="middle-box">
                                        <div class="location-box">
                                            <button class="btn location-button" data-bs-toggle="modal"
                                                data-bs-target="#locationModal">
                                                <span class="location-arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-map-pin">
                                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                        <circle cx="12" cy="10" r="3"></circle>
                                                    </svg>
                                                </span>
                                                <span class="locat-name">Your Location</span>
                                                <i class="fa-solid fa-angle-down"></i>
                                            </button>
                                        </div>

                                        <div class="search-box">
                                            <div class="input-group" data-bs-toggle="modal"
                                                data-bs-target="#ProductsModal">
                                                <input style="background-color:#ffffff;" readonly="" type="search"
                                                    class="form-control" placeholder="Search Your Products...."
                                                    aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <button class="btn" type="button" id="button-addon2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-search">
                                                        <circle cx="11" cy="11" r="8"></circle>
                                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                    </svg>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-search font-light">
                                                        <circle cx="11" cy="11" r="8"></circle>
                                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                    </svg>
                                                </span>
                                                <input type="text" class="form-control search-type"
                                                    placeholder="Search here..">
                                                <span class="input-group-text close-search">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-x font-light">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <ul class="right-side-menu">
                                            <li class="right-side">
                                                <a href="https://zonik.in/quoteslist"
                                                    class="btn p-0 position-relative header-wishlist">
                                                    <i class="fa-solid fa-basket-shopping" style=" font-size:22px;"></i>
                                                    <span
                                                        class="position-absolute top-0 start-100 translate-middle badge">
                                                        0
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="right-side">
                                                <div class="onhover-dropdown header-badge">
                                                    <button type="button"
                                                        class="btn p-0 position-relative header-wishlist">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-shopping-cart">
                                                            <circle cx="9" cy="21" r="1"></circle>
                                                            <circle cx="20" cy="21" r="1"></circle>
                                                            <path
                                                                d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                            </path>
                                                        </svg>

                                                    </button>
                                                </div>
                                            </li>
                                            <li class="right-side">
                                                <div class="onhover-dropdown header-badge">
                                                    <button type="button"
                                                        class="btn p-0 position-relative header-wishlist ">

                                                    </button><button class="btn p-0 bell-icon" type="button"
                                                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                                        aria-controls="offcanvasRight"><i
                                                            class="fa-solid fa-bell"></i></button>

                                                    <div class="offcanvas offcanvas-end" tabindex="-1"
                                                        id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                                        <div class="offcanvas-header">
                                                            <h3 id="offcanvasRightLabel">Notifications</h3>
                                                            <button type="button" class="btn-close text-reset"
                                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
                                                    <button type="button"
                                                        class="btn p-0 position-relative header-wishlist">
                                                        <i class="fa-solid fa-headset"></i>

                                                        +91 9874563210
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="right-side onhover-dropdown">
                                                <div class="delivery-login-box">
                                                    <div class="delivery-detail">

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
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
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



                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>

            </div>
            <div class="offcanvas-body">
                <div>
                    <ul class="slide-ul">
                        <li> <i class="fa-solid fa-user" style="margin-right:10px; font-size:20px;"></i>Account Details
                        </li>
                        <li><i class="fa-solid fa-file-lines" style="margin-right:10px; font-size:20px;"></i>Your Orders
                        </li>
                        <li><i class="fa-solid fa-bell" style="margin-right:10px; font-size:20px;"></i>Notifications
                        </li>
                        <li><i class="fa-solid fa-question" style="margin-right:10px; font-size:20px;"></i>FAQs</li>
                        <li><i class="fa-solid fa-phone" style="margin-right:10px; font-size:20px;"></i>Contact Us</li>
                        <li class="logout-btn"><i class="fa-solid fa-right-to-bracket"
                                style="margin-right:10px; font-size:20px;"></i>
                            <a class="" style="color: white" href="/logout">Logout</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </header>
    <!-- Header End -->

    <!-- mobile fix menu start -->
    <div class="mobile-menu d-md-none d-block mobile-cart">
        <ul>
            <li class="active">
                <a href="index.html">
                    <i class="iconly-Home icli"></i>
                    <span>Home</span>
                </a>
            </li>

            <li class="mobile-category">
                <a href="javascript:void(0)">
                    <i class="iconly-Category icli js-link"></i>
                    <span>Category</span>
                </a>
            </li>

            <li>
                <a href="search.html" class="search-box">
                    <i class="iconly-Search icli"></i>
                    <span>Search</span>
                </a>
            </li>

            <li>
                <a href="wishlist.html" class="notifi-wishlist">
                    <i class="iconly-Heart icli"></i>
                    <span>My Wish</span>
                </a>
            </li>

            <li>
                <a href="cart.html">
                    <i class="iconly-Bag-2 icli fly-cate"></i>
                    <span>Cart</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- mobile fix menu end -->

    <!-- Catalogue -->
    <section class="catalogue-section">
        <div class="container">

            <ul class="nav second-pills nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($categories as $categoryItem)
                @if (isset($categoryItem))
                <li class="nav-item" role="presentation">
                    <a class="nav-link active mr" href="?category_id={{ $categoryItem->id }}"
                        class="{{ $selectedCategory && $selectedCategory->id == $categoryItem->id ? 'active' : '' }}">
                        {{ $categoryItem->category_name }}
                    </a>
                </li>
                @endif
                @endforeach
            </ul>

            <!-- <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                <div class="tab-pane fade" id="pills-contact1" role="tabpanel" aria-labelledby="pills-contact-tab1">...</div>
                <div class="tab-pane fade" id="pills-contact2" role="tabpanel" aria-labelledby="pills-contact-tab2">...</div>
                <div class="tab-pane fade" id="pills-contact3" role="tabpanel" aria-labelledby="pills-contact-tab3">...</div>
              </div> -->

            <div class="row mt-5 mb-5">
                <div class="col-md-4">
                    <div class="catalogue-left-bar">
                        <ul>
                            <li>
                                    <div class="d-flex  justify-content-center">
                                        <img src="assets/images/c1.png" class="catalogue-img"></div>
                               
                                <strong style="cursor:pointe;"
                                    onclick="reload()" class="cursor-pointer"> ALL </strong></li>
                            @foreach ($subcategories as $subcategory)
                            <li>    <div class="d-flex  justify-content-center"><img src="/uploads/{{ $subcategory->image }}" class="catalogue-img"></div>

                                <a href="#" class="nav-link subcategory-link"
                                    data-subcategory="{{ $subcategory->id }}">{{ $subcategory->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="filters">
                        <div class="page-wrap">

                            <section>
                                <!-- Button trigger modal-->
                                <div>
                                    <ul class="nav mb-4" id="pills-tab" role="tablist">
                                        <li role="presentation" class="d-flex ">
                                            <div onclick="toggel('exampleModalScrollable')"
                                                class="category-filter rounded-pill fs-6 shadow-lg p-2 px-3">
                                                <span class="filter-title">Brand</span>
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </div>

                                            @foreach ($tags as $item)
                                            <div class="type-filter rounded-pill fs-6 shadow-lg p-2 px-3">
                                                <div onclick="tagValue({{ $item->id }}, {{ $selectedCategoryId }})">
                                                    {{ $item->tag_name }}</div>
                                            </div>
                                            @endforeach

                                            <div onclick="toggelTypes('exampleModalScrollableTypes')"
                                                class="category-filter rounded-pill fs-6 shadow-lg p-2 px-3">
                                                <span class="filter-title">Type</span>
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                                <!-- Modal-->
                                <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog"
                                    aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
                                        role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                                                <a class="close pointer" data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            </div>
                                            @if ($brands1)
                                            <div class="modal-body" style="height: 300px;">

                                                @foreach ($brands1 as $brand)
                                                <div class="d-flex justify-content-between border-bottom gap-4 mb-2">
                                                    <label for="{{ $brand->id }}">
                                                        {{ $brand->name }}
                                                    </label>
                                                    <input id="{{ $brand->id }}" class="form-check-input brand-checkbox"
                                                        type="checkbox" name="brand_list" value="{{ $brand->id }}"
                                                        onchange="brandList({{ $brand->id }})">
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                                    data-bs-dismiss="modal">Clear All</button>
                                                <button onclick="filter({{ $selectedCategoryId }})" type="button"
                                                    class="btn btn-primary font-weight-bold">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Types --}}

                                <div class="modal fade" id="exampleModalScrollableTypes" tabindex="-1" role="dialog"
                                    aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
                                        role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                                                <a class="close pointer" data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            </div>

                                            <div class="modal-body" style="height: 300px;">

                                                @foreach ($types as $typeData)
                                                <div class="d-flex justify-content-between border-bottom gap-4 mb-2">
                                                    <label for="{{ $typeData->id }}">
                                                        {{ $typeData->type_name }}
                                                    </label>
                                                    <input id="{{ $typeData->id }}"
                                                        class="form-check-input brand-checkbox" type="checkbox"
                                                        name="type_list" value="{{ $typeData->id }}"
                                                        onchange="typeList({{ $typeData->id }})">
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                                    data-bs-dismiss="modal">Clear All</button>
                                                <button onclick="filterTypeData({{ $selectedCategoryId }})"
                                                    type="button"
                                                    class="btn btn-primary font-weight-bold">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </section>


                            <div class="products-div">
                                <div class="row gy-4 products-container">
                                    @foreach ($products as $product)
                                    <div class="col-md-4">
                                        <div class="product-box-4 wow fadeInUp br">
                                            <div class="product-image product-image-2">
                                                <a href="#">
                                                    <img src="/uploads/{{ $product->image }}"
                                                        class="img-fluid blur-up lazyload" alt="">
                                                </a>

                                            </div>

                                            <div class="product-detail">

                                                <a href="#">
                                                    <h5 class="name text-title">{{ $product->product_name }}</h5>
                                                </a>
                                                <h5 class="price price-p"> Pack of
                                                    {{ $product->product_quantity }}
                                                </h5>

                                                <div class="addtocart_btn">


                                                    @if (Auth::user())
                                                    <form action="{{ route('quotes.add') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        {{-- <input type="hidden" name="user_id" value="1"> --}}
                                                        <div class="quantity">
                                                            <div class="row g-3 mt-2 mb-3">
                                                                <div class="col-md-6">
                                                                    <!-- <a class="box-btn">BOX</a> -->
                                                                    <div class="box-btn mb-2">BOX</div>
                                                                    <p class="mt-1 pcs 24-pcs">24 PCS</p>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="loose-btn mb-2">LOOSE</div>
                                                                    <p class="mt-1 pcs">6 Pcs +</p>
                                                                    <p class="mt-1 pcs">12 Pcs +222</p>
                                                                </div>
                                                            </div>
                                                        </div> <br>
                                                        <br>

                                                        <button type="submit"
                                                            class="add-button addcart-button btn buy-button text-light">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </button>
                                                    </form>
                                                    {{-- @else
                                                            <div class="middle-box">
                                                                <div class="location-box">
                                                                    <button type="submit" data-bs-toggle="modal"
                                                                        data-bs-target="#locationModal"
                                                                        class="btn location-button add-button addcart-button btn buy-button text-light">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </button>

                                                                </div>
                                                            </div> --}}
                                                    @endif

                                                    <div class="qty-box cart_qty">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>



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
                    <p class="text-light mr-2">Copyright @2023</p> <img
                        src="{{ asset('frontweb/assests/images/white-logo.png') }}">
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
                                        View More Details
                                    </button>
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
                <img src="{{ asset('frontweb/assets/images/cookie-bar.png') }}" class="blur-up lazyload" alt="">
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
                    <h5 class="modal-title" id="exampleModalLabel">Choose your Delivery Locationddddddddd</h5>
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
    <script src="{{ asset('frontweb/assets/js/jquery-3.6.0.min.js') }}"></script>

    <!-- jquery ui-->
    <script src="{{ asset('frontweb/assets/js/jquery-ui.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('frontweb/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/bootstrap/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/bootstrap/popper.min.js') }}"></script>

    <!-- feather icon js-->
    <script src="{{ asset('frontweb/assets/js/feather/feather.min.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/feather/feather-icon.js') }}"></script>

    <!-- Lazyload Js -->
    <script src="{{ asset('frontweb/assets/js/lazysizes.min.js') }}"></script>

    <!-- Slick js-->
    <script src="{{ asset('frontweb/assets/js/slick/slick.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/slick/slick-animation.min.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/custom-slick-animated.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/slick/custom_slick.js') }}"></script>

    <!-- Range slider js -->
    <script src="{{ asset('frontweb/assets/js/ion.rangeSlider.min.js') }}"></script>

    <!-- Auto Height Js -->
    <script src="{{ asset('frontweb/assets/js/auto-height.js') }}"></script>

    <!-- Lazyload Js -->
    <script src="{{ asset('frontweb/assets/js/lazysizes.min.js') }}"></script>

    <!-- Quantity js -->
    <script src="{{ asset('frontweb/assets/js/quantity-2.js') }}"></script>

    <!-- Fly Cart Js -->
    <script src="{{ asset('frontweb/assets/js/fly-cart.js') }}"></script>

    <!-- Timer Js -->
    <script src="{{ asset('frontweb/assets/js/timer1.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/timer2.js') }}"></script>

    <!-- Copy clipboard Js -->
    <script src="{{ asset('frontweb/assets/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/copy-clipboard.js') }}"></script>

    <!-- WOW js -->
    <script src="{{ asset('frontweb/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontweb/assets/js/custom-wow.js') }}"></script>

    <!-- script js -->
    <script src="{{ asset('frontweb/assets/js/script.js') }}"></script>

    <!-- thme setting js -->
    <script src="{{ asset('frontweb/assets/js/theme-setting.js') }}"></script>

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
    var selectedSubCategoryId = {
        {
            $selectedSubCategoryId ?? 'null'
        }
    }; // Assuming it's a Blade variable
    if (selectedSubCategoryId !== 'null') {
        loadSubcategoryProducts(selectedSubCategoryId);
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

    function checkNumber() {
        if (event.target.value.length == 10) {
            axios.get(`/customer/name/${event.target.value}`).then((res) => {
                // console.log(res.data.name);
                if (res.data?.name) {
                    document.getElementById('name').classList.add('d-none');
                } else {
                    document.getElementById('name').classList.remove('d-none');
                }
            });
        };
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
                console.log(response);
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


        axios.post('/customer/verifyOtp', {
                otp: otp,
                mobile: mobile,
                name: name,
            })
            .then(response => {
                console.log(response.data.success);
                if (response.data.success) {
                    toastr.success(response.data.message);
                    location.reload();
                } else {
                    toastr.error(response.data.message);
                }
            })
            .catch(error => {
                toastr.error('An error occurred while verifying OTP');
            });
    }

    function reload() {
        location.reload();
    }

    function toggel(id) {
        $(".brand-checkbox").prop("checked", false);
        $(`#${id}`).modal('toggle');
    }

    function toggelTypes(id) {
        $(".brand-checkbox").prop("checked", false);
        $(`#${id}`).modal('toggle');
    }

    let brand_ids = [];
    let sub_category_id = '';

    function brandList(id) {
        brand_ids.push(id);
    }

    let type_ids = [];
    let type_sub_category_id = '';

    function typeList(id) {
        type_ids.push(id);

    }

    // let tag_ids = [];
    // let tag_sub_category_id = '';
    // , selectedCategoryId, subcategoryId
    function tagValue(id, selectedCategoryId) {
        //  alert(selectedCategoryId);
        // type_ids.push(id);
        $.ajax({
            url: '/subcat/products/tag/filter',
            method: 'GET',
            data: {
                tagID: id,
                selectedCategoryId: selectedCategoryId,
                sub_category_id: sub_category_id, // Use the correct parameter name
            },
            success: function(data) {
                console.log(data);
                loadProducts(data);
                // tag_ids = [];

            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function filter(selectedCategoryId) {
        // alert(selectedCategoryId)
        $.ajax({
            url: '/subcat/products/filter',
            method: 'GET',
            data: {
                brand_ids: brand_ids,
                selectedCategoryId: selectedCategoryId,
                sub_category_id: sub_category_id,
            },
            success: function(data) {
                console.log(data);
                loadProducts(data);
                brand_ids = [];
                toggel('exampleModalScrollable')

            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function filterTypeData(selectedCategoryId) {
        // alert(selectedCategoryId)
        $.ajax({
            url: '/subcat/products/filtertype',
            method: 'GET',
            data: {
                type_ids: type_ids,
                selectedCategoryId: selectedCategoryId,
                type_sub_category_id: type_sub_category_id,
            },
            success: function(data) {
                console.log(data);
                loadProducts(data);
                type_ids = [];
                toggelTypes('exampleModalScrollableTypes')

            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function loadProducts(prohtml) {
        // Display the product HTML in the .products-container element
        $('.products-container').html(prohtml);
    }

    function loadSubcategoryProducts(subcategoryId) {
        $.ajax({
            url: '/subcategories_lists/' + subcategoryId + '/products_lists',
            method: 'GET',
            success: function(data) {
                // console.log(data);
                loadProducts(data);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function loadBrandCategoryProducts(brandCategoryId) {
        $.ajax({
            url: '/brandcategories/' + brandCategoryId + '/products',
            method: 'GET',
            success: function(data) {
                products - brands(data);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    $(document).ready(function() {
        // Load all products initially
        // loadAllProducts();
        $('.subcategory-link').on('click', function(e) {
            e.preventDefault();
            var subcategoryId = $(this).data('subcategory');
            // alert(subcategoryId)
            sub_category_id = subcategoryId;
            type_sub_category_id = subcategoryId;
            tag_sub_category_id = subcategoryId;
            // tagValue(subcategoryId)
            loadSubcategoryProducts(subcategoryId);

        });

        $('.brand-category-link').on('click', function(e) {
            e.preventDefault();
            var brandCategoryId = $(this).data('brand');
            loadBrandCategoryProducts(brandCategoryId);
        });
    });
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
    /* checkboxes */
    $(document).on("change", ".__checkbox input", function() {
        if ($(this).is(":checked")) {
            $(this).parent().addClass("__checked");
        } else {
            $(this).parent().removeClass("__checked");
        }
    });

    $(document).ready(function() {
        $(".field_wrap input").focus(function() {
            $(this).addClass("__focused");
        });
        $(".field_wrap input").blur(function() {
            $(this).removeClass("__focused");
        });
    });

    // Open and close dropdown panel and focus
    if ($(".__dropdown").length) {
        $(".dropdownButton").on("click", function() {
            var container = $(this).parent(".dropdownWrap");
            var findSiblings = $(this).parents().siblings();
            var reset_siblings = function reset_siblings() {
                $(findSiblings).children(".dropdownButton").attr("aria-expanded", false);
                $(findSiblings).children(".dropdownButton").removeClass("__active");
            };

            if ($(this).attr("aria-expanded") != "true") {
                $(this).siblings(".dropdownPanel").first("label").focus();
                $(this).attr("aria-expanded", true);
                $(this).addClass("__active");
                reset_siblings();
            } else {
                closeDropdown();
                console.log("close it");
                $(this).attr("aria-expanded", false);
                $(this).removeClass("__active");
            }
        });

        // close on click outside
        $(document).on("click", ".dropdownWindow", function() {
            closeDropdown();
        });

        // close on search input click
        $(document).on("click", "#Query", function() {
            closeDropdown();
        });

        // close on esc key
        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                closeDropdown();
            }
        });

        function closeDropdown() {
            $(".dropdownButton").attr("aria-expanded", false);
            $(".dropdownButton").removeClass("__active");
        }
    }

    // add & remove tags to list via checkboxes
    $(".dropdownWrap .dropdownPanel label").on("click", function() {
        var checkBox = $(this).find("input");
        var checkBoxValue = $(checkBox).val();
        if ($(checkBox).is(":checked")) {
            $('<button class="tag">' + checkBoxValue + "</button>").appendTo(
                ".activeTaglist"
            );
        } else {
            $('button.tag:contains("' + checkBoxValue + '")').remove();
        }
    });

    //remove individual tags on click and reset associate checkbox
    var activeButton = $(".activeTaglist button");

    $(document).on("click", ".activeTaglist button", function() {
        console.log("button clicked");
        var checkBox = $(".dropdownWrap .dropdownPanel input");
        var checkBoxValue = $(this).html();
        $(checkBox).each(function() {
            if ($(this).val() == checkBoxValue) {
                $(this).prop("checked", false);
                $(this).closest("label").removeClass("__checked");
            }
        });
        $(this).remove();
    });

    // remove all tags and reset checkboxes
    $(".clearTags").on("click", function() {
        var checkBox = $(".dropdownWrap .dropdownPanel input");

        $(checkBox).each(function() {
            $(this).prop("checked", false);
            $(this).closest("label").removeClass("__checked");
        });
        $(".tag").remove();
    });

    // hide filter options
    $(".closeFilters").on("click", function() {
        $(".listing-filters-wrap").removeClass('__active');
    });

    //show filter options
    $(".openFilters").on("click", function() {
        $(".listing-filters-wrap").addClass('__active');
    });
    </script>
</body>

</html>