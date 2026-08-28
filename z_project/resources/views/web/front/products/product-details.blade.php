@extends('web.layouts.app')

@section('content')
<!-- mobile fix menu end -->
<!-- Breadcrumb Section Start -->
@php
$productnameparts = explode(',', $product->product_name);
@endphp
<style>

.p1-img{
    height: 90%;
}
.product-section .product-left-box {
    position: sticky;
    top: 0;
    width: fit-content;
}


.buy-buttons{
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    height: 30px;
    /* position: absolute; */
    /* right: -3px; */
    padding: 7px 11px;
    background-color: #e97457;
    width: 30px;
    font-size: 25px;
    color: #fff;
    border-radius: 50px;
    }
    
    @media (max-width: 768px) {
        
          .product-section .product-left-box {
              margin-top: 2rem;
        }
        
        
          .border-red {
        font-weight: 500 !important;
        margin-top: 2px;
        margin-bottom: -37px;
        border: 1px solid #e97457;
        padding: 5px;
        text-align: center;
        border-radius: 5px;
        font-size: 11px;
        height: 22px;
        position: absolute;
    }
    
        .product-box-4 .product-detail .buy-button{
        position: relative;
        bottom: unset;
        right: unset;
        width: 25px;
        /* margin-top: 12px; */
        /*color: var(--theme-color);*/
        color: #fff;
        border-radius: 5px;
        /*border: 1px solid var(--theme-color);*/
        margin-left: 125px;
        height: 24px;
        font-size: 23px;
        margin-top: -2rem;
        border-radius: 50%;
    }
        
     .boxbtncss{
            font-size: 10px;
            width: 45px;
        }
        .loosbtncss {
        font-size: 10px;
        width: 57px;
        margin-left: 3.5rem;
        margin-top: -1.90rem;
    }
    }
    
    @media (min-width: 320px) and (max-width: 359px) {

    .boxbtncss {
        font-size: 8px;
        width: 38px;
    }

    .loosbtncss {
        font-size: 8px;
        width: 49px;
        margin-left: 2.75rem;
        margin-top: -1.7rem;
    
    }
    .product-box-4 .product-detail .buy-button{
        margin-left: 6.4rem;
    }
}
    
    
</style>

<!-- Product Left Sidebar Start -->
<section class="product-section padding-50">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInUp">
                <div class="row g-4">
                    <div class="col-xl-5 wow fadeInUp">
                        <div class="product-left-box details-p flex-fill">
                            <div class="row g-2">
                                <div class="col-xxl-10 col-lg-12 col-md-10 order-xxl-2 order-lg-1 order-md-2">
                                    <div class="product-main-2 no-arrow">
                                        <div>

                                            <div class="slider-image">
                                                <img src="/uploads/{{ $product->image }}" id="img-1" data-zoom-image="../assets/images/product/category/1.jpg" class="img-fluid image_zoom_cls-0 blur-up p1-img lazyload" alt=""
                                               style=margin-left:-1px; >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 wow fadeInUp pb-100 d-flex" data-wow-delay="0.1s">
                        <div class="right-box-contain flex-fill">
                            <h1 class="name product_name_details"> {{ $product->product_name }} </h1>
                            <!-- <div class="price-rating">

                                    <h3 class="theme-color price">1 kg</h3>
                                      <div class="row d-flex mt-2">
                                        <div class="col">
                                             <div class="product-title mb-2">
                                            <h4>Select Unit</h4>
                                        </div>
                                             <p class="theme-color price mb-2">Select Unit</p>
                                            <form action="">
                                                <div class="form-group" style="width:168px;border-radius:5px;">
                                                    <select class="form-control" id="exampleFormControlSelect1">
                                                        <option>5 kg</option>
                                                        <option>250 gm</option>
                                                        <option>500 gm</option>
                                                        <option>1 kg</option>
                                                    </select>

                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    <div class="product-packege">
                                        <div class="product-title">
                                            <h4>Quantity</h4>
                                        </div>
                                        <div class="row">
                                            <div class="row g-3 mb-3">
                                                <div class="col">
                                                    <div class="box-btn mb-2" onclick="updateQuantity('BOX', {{ $product->id }})">BOX</div>
                                                </div>
                                                  <div class="col">
                                                 <div class="loose-btn mb-2" onclick="updateQuantity('LOOSE', {{ $product->id }})">LOOSE</div>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>

                                </div> -->


                            <div class="product-box-4 price-rating d-flex flex-wrap justify-content-between mt-2 mb-4">
                                <div class="col-md-6 ">
                                    <div class="product-title text-start">
                                        <h4>Quantity</h4>
                                    </div>
                                    <div class="row g-3 mb-3 product-{{ $product->id }}">
                                        <div class="col">
                                            <div class="box-btn mb-2" onclick="updateQuantity('BOX', {{ $product->id }})">BOX</div>
                                           <div class="border-red pcs-box pcs-{{ $product->id }}" style="display:none;">{{ $product->carton_size ?? 0 }} PCS </div>
                                        </div>
                                        <div class="col">
                                            <div class="loose-btn mb-2" onclick="updateQuantity('LOOSE', {{ $product->id }})">LOOSE</div>
                                        </div>
                                        
                                                                                <div class="col">
                            <button type="button" onclick="submit({{ $product->id }})" class="btn mb-2 add-button addcart-button btn buy-buttons" @if(!Auth::user()) disabled="true" @endif>
                                +
                            </button>
                                        </div>
                                        
                                    </div>

                                </div>
                            </div>




                            <!-- <div class="product-packege">
                                        <div class="product-title">
                                            <h4>Quantity</h4>
                                        </div>
                                        <div class="row">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <div class="box-btn mb-2" onclick="updateQuantity('BOX', {{ $product->id }})">BOX</div>
                                                </div>
                                                  <div class="col-md-6">
                                                 <div class="loose-btn mb-2" onclick="updateQuantity('LOOSE', {{ $product->id }})">LOOSE</div>
                                                 </div>
                                            </div>
                                        </div>
                                    </div> -->


                            <!-- <button type="button" onclick="submit()" class="btn btn-md bg-dark cart-button text-white w-100">
                                        Add To Quotes List
                                    </button> -->
                            <!--<button type="button" onclick="submit()" class="btn red-btn mb-4" @if(!Auth::user()) disabled="true" @endif>-->
                            <!--    <i class="fa-solid fa-bag-shopping mx-2"></i>Add To Enquiry-->
                            <!--</button>-->


                            </form>

                            <div class="pickup-box ">
                                <div class="product-title mt-4">
                                    <h4>Description</h4>
                                </div>

                                <div class="pickup-detail">
                                    <h4 class="text-content">{{ $product->description }}</h4>
                                </div>

                                <!--<div class="product-info">-->
                                <!--    <ul class="product-info-list product-info-list-2">-->
                                <!--        <li>Type : <a href="javascript:void(0)">Black Forest</a></li>-->
                                <!--        <li>SKU : <a href="javascript:void(0)">SDFVW65467</a></li>-->
                                <!--        <li>MFG : <a href="javascript:void(0)">Jun 4, 2022</a></li>-->
                                <!--        <li>Stock : <a href="javascript:void(0)">2 Items Left</a></li>-->
                                <!--        <li>Tags : <a href="javascript:void(0)">Cake,</a> <a href="javascript:void(0)">Backery</a></li>-->
                                <!--    </ul>-->
                                <!--</div>-->
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- @if ($relatedProducts->isNotEmpty())
        <h3>Related Products</h3>
        <div class="row">
            @foreach ($relatedProducts as $relatedProduct)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        <p class="card-text">{{ $relatedProduct->description }}</p>
                        <a href="{{ route('product-details', $relatedProduct->id) }}" class="btn btn-primary">View
                            Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif -->

        <div class="row my-5">
            <div class="title">
                <h4 class="heading"><span class="text-primary">Related</span><span class="fw-600"> &nbsp;
                        Products</span>
                </h4>
            </div>
            <div class="owl-carousel owl-theme owl-carousel7">
                @if ($relatedProducts->isNotEmpty())
                @foreach ($relatedProducts as $relatedProduct)
                @if ($relatedProduct->image !== '1718876959.jpg')

                <div class="item ">
                    <div class="product-box-4 wow fadeInUp">
                        <div class="product-image product-image-2">
                            <a @if (Auth::user()) href="{{ route('product-details',  $relatedProduct->id) }}" @endif>
                                <img src="/uploads/{{  $relatedProduct->image }}" class="img-fluid blur-up lazyload" alt="">
                            </a>
                        </div>


                        <div class="product-detail">

                            <a href="{{ route('product-details',  $relatedProduct->id) }}">
                                <h5 class="name text-title">{{ $relatedProduct->product_name }} </h5>
                            </a>

                            <!-- <h5 class="price price-p"> Pack of {{ $relatedProduct->product_quantity }}</h5> -->
                            @if (Auth::user())
                            <div>

                                <input type="hidden" name="quantity" id="quantityInput">
                                <div class="quantity product-{{ $relatedProduct->id }}"">
                                    <div class="row g-1 mt-2  mb-3  ">
                                        <div class="col-md-5">
                                            <div class="box-btn boxbtncss" onclick="updateQuantity('BOX', {{  $relatedProduct->id }})">BOX
                                            </div>

                                            <div class="border-red pcs-box pcs-{{ $relatedProduct->id }}" style="display:none;">
                                                                        {{ $relatedProduct->carton_size ?? 0 }} PCS
                                                                    </div>
                                            <!-- <div class="border-red">24 PCS</div> -->
                                        </div>
                                        <div class="col-md-5">
                                            <div class="loose-btn loosbtncss" onclick="updateQuantity('LOOSE', {{  $relatedProduct->id }})">
                                                LOOSE</div>
                                        </div>
                                        <div class="col-md-2">
                                            <button onclick="submit({{ $relatedProduct->id }})" type="button" class="add-button addcart-button btn buy-button">+
                                            </button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            @endif
                        </div>

                    </div>
                </div>
                @endif
                @endforeach
                @endif
            </div>

        </div>

    </div>
    </div>
</section>
<!-- Product Left Sidebar End -->

<!-- Footer Started -->
<!-- <section class="footer-section ">
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
                    <p class="color-primary ml-2">Powered By BrandBucketSofttech</p>
                </div>
            </div>
        </div>
    </section> -->
<!-- Footer Ended -->

<!-- Quick View Modal Box Start -->
<div class="modal fade theme-modal view-modal" id="view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <img src="../assets/images/product/category/1.jpg" class="img-fluid blur-up lazyload" alt="">
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
                                <button onclick="location.href = 'cart.html';" class="btn btn-md add-cart-button icon">Add
                                    To Cart</button>
                                <button onclick="location.href = 'product-left.html';" class="btn theme-bg-color view-button icon text-white fw-bold btn-md">
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
<div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choose your Delivery Location</h5>
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
                                <input type="color" class="form-control form-control-color" id="colorPick" value="#239698" title="Choose your color">
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
<script>
    $(document).ready(function() {
        $('.search_list').hide();
        $('.search').on('keyup', function() {
            var query = $(this).val().trim();
            searchQuery(query);
        });

        function searchQuery(query) {
            if (query.length <= 0) {
                $('.search_list').hide();
                return;
            }

            switch (true) {
                case query.length >= 3:
                    executeSearch(query.slice(-3));
                    break;
                case query.length == 2:
                    executeSearch(query.slice(-2));
                    break;
                case query.length == 1:
                    executeSearch(query);
                    break;
                default:
                    $('.search_list').hide();
            }
        }

        function executeSearch(searchQuery) {
            $.ajax({
                url: "../search"
                , type: "GET"
                , data: {
                    'search': searchQuery
                }
                , success: function(data) {
                    if (data == 'No results') {
                        searchFallback(searchQuery);
                    } else {
                        $('.search_list').show();
                        $('.search_list').html(data);
                    }
                }
            });
        }

        function searchFallback(query) {
            var fallbackQuery = query.slice(0, -1);
            if (fallbackQuery.length > 0) {
                executeSearch(fallbackQuery);
            } else {
                $('.search_list').html('No results');
                $('.search_list').show();
            }
        }
    });

</script>
<!-- Bg overlay Start -->
<div class="bg-overlay"></div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
</script>

<script>
    function updateQuantity(productType, Id) {
        quantityData = productType;
        productId = Id;

        // Remove "active" class from all buttons
        $('.box-btn, .loose-btn').removeClass('active');
        $(`.${productType.toLowerCase()}-btn`).addClass('active').css('background-color', '#652A80');

    }

    function updateQuoteCount() {
        axios.get('/quotes/count')
            .then(response => {
                const quoteCountElement = document.getElementById('quoteCount');
                if (quoteCountElement) {
                    quoteCountElement.innerText = response.data.count;
                }
            })
            .catch(error => {
                console.error('Error fetching quote count:', error);
            });
    }

    function submit() {
        axios.post('/quotes/add', {
            productType: quantityData
            , product_id: productId
        }).then(response => {

            if (response.data.success) {
                toastr.success('Quotation added successfully');
                updateQuoteCount();

            } else if (response.data.error) {
                toastr.error(response.data.error);
            } else {
                toastr.error('Quatation already added in list');
            }
        }).catch(error => {
            console.error('Error:', error);
            toastr.error('Quatation already added in list');
        });

        $('#submitBtn').css('background-color', '#652A80');
    }




    $(document).ready(function() {
        $('.product-box-4').find('.24-pcs').hide(); // Hide all .24-pcs initially

        $('.box-btn').on('click', function() {
            $('.box-btn, .loose-btn').removeClass('active').css({
                'background-color': ''
                , 'color': '#000'
            });
            $(this).addClass('active').css({
                'background-color': '#652A80'
                , 'color': '#ffff'
            });

            // Show .24-pcs only if its parent product-box-4 has .active box-btn
            $(this).closest('.product-box-4').find('.24-pcs').show();
            $('.product-box-4').not($(this).closest('.product-box-4')).find('.24-pcs').hide();
        });

        $('.loose-btn').on('click', function() {
            $('.box-btn, .loose-btn').removeClass('active').css({
                'background-color': ''
                , 'color': '#000'
            });
            $(this).addClass('active').css({
                'background-color': '#652A80'
                , 'color': '#ffff'
            });

            // Hide all .24-pcs when loose-btn is clicked
            $('.product-box-4').find('.24-pcs').hide();
        });
    });



    // Adding an "active" class to an element with the ID 'submitBtn' and changing its color
    $('#submitBtn').on('click', function() {
        $(this).addClass('active').css('background-color', '#652A80');
    });

</script>
