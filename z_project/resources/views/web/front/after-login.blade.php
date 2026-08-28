@extends('web.layouts.app')
@section('content')


<style>

/* 🔍 Search dropdown list container */
.search_list {
  position: absolute;
  top: 100%; /* aligns just below the input */
  left: 0;
  width: 100%;
  max-height: 350px;
  overflow-y: auto;
  background: #fff;
  border: 1px solid #e4e7ec;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  z-index: 3000; /* ensures it's above all other sections/modals */
  padding: 0;
  margin-top: 4px;
  list-style: none;
  display: none;
  font-family: "Lexend", "Helvetica", sans-serif;
  animation: dropdownFade 0.15s ease-in-out;
}

/* 🧩 Each list item */
.search_list li {
  display: flex;
  align-items: center;
  padding: 10px 14px;
  transition: background-color 0.1s ease-in-out;
  cursor: pointer;
}

/* 💬 Item content */
.search_list li h6 {
  font-weight: 600;
  color: #262626;
  font-size: 15px;
  text-transform: capitalize;
  margin: 0;
}

.search_list li span {
  font-size: 13px;
  color: #666;
  display: block;
}

.search_list li img {
  margin-right: 12px;
  border-radius: 50%;
  border: 1px solid #ddd;
  width: 40px;
  height: 40px;
  object-fit: cover;
  background: #f8f8f8;
}

/* 💡 Hover effect */
.search_list li:hover {
  background-color: #f5f6fa;
}

/* 📘 Text for "No results" or messages */
.search_list li b {
  font-weight: 600;
  width: 100%;
  font-size: 15px;
  color: #767c8f;
  padding: 10px 0;
  /*text-align: center;*/
  border-top: 1px solid #f2f4f7;
}

/* 🎨 Small fade-in effect */
@keyframes dropdownFade {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

/* 🧠 Mobile adjustments */
@media (max-width: 768px) {
  .search_list {
    position: fixed;
    top: 156px; /* below navbar area */
    left: 41px;
    right: 10px;
    width: auto;
    max-height: 60vh;
    border-radius: 10px;
    z-index: 5000;
  }

  .search_list li {
    padding: 12px 18px;
  }
}



#product-search-input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}


/* Initially hide the carousel */
#owl-carousel-mobile {
    visibility: hidden; /* Prevent flicker */
}

/* Carousel items styling */
#owl-carousel-mobile .owl-item {
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    transition: height 0.3s ease-in-out;
}

/* Images styling */
#owl-carousel-mobile .owl-item img {
    object-fit: contain;
    width: 100%;
    height: 180%;
}


/* Initially hide the carousel until fully loaded */


/* Default height for inactive items */
#owl-carousel-mobile .owl-item:not(.active) {
    height: 150px; /* Non-active item height */
}


/* Ensure consistent stage height */
#owl-carousel-mobile .owl-stage-outer {
    height: 160px; /* Match the tallest item's height initially */
    transition: height 0.3s ease-in-out;
}


.newimagecss {
    width: 100%;
    height: 350px;
    border-radius: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}


.owl-carousel .item:hover img {
    transform: scale(1.0); /* Slight zoom effect on hover */
}

/* Default font size for all screen sizes */
    .f-content h6 {
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
    }
    .padscss{
            padding: 100px 0px;
        }

    /* Font size adjustment for small screens (max-width 576px) */
    @media (max-width: 768px) {
        .f-content h6 {
            font-size: 12px;
        }
        .padscss{
            padding: 25px 0px;
        }
        .product-box-4 .product-detail .buy-button{
        width: 25px;
        font-size: x-large;
        margin-left: 7.25rem;
        margin-top: -28px;
        height: 24px;    
        position: absolute;
        border-radius: 50%;
        right: 0;
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
        
        .newimagecss {
        width: 300%;
        height: 142px;
        border-radius: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

     .boxbtncss{
            font-size: 10px;
            width: 45px;
        }
        .loosbtncss{
            font-size: 10px;
        width: 57px;
        margin-left: -1.45rem;
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
        margin-left: -1.26rem;
    }

    .product-box-4 .product-detail .buy-button {
        width: 15px;
        font-size: 20px;
        margin-top: -26px;
        height: 22px;
        position: absolute;
        border-radius: 50%;
        right: 0;
    }
}


    @media (max-width: 767px) {
    .category-section-3 .category-box-list {
        padding: 11px;
        /* border-radius: 15px;
        background-color: #f1f1f3;
        position: relative;
        overflow: hidden;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        z-index: 0; */
    }
}

/* Mobile View Adjustments */
/* Mobile View Adjustments */
@media (max-width: 767px) {
    .category-box-view img {
        width: 100%;
        height: auto;
        object-fit: cover;
        mix-blend-mode: multiply;

    }
    
     .red-btn {
    padding: 10px 18px !important;
}

    .textcssfor {
        font-size: 10px;
        font-weight: 600;
        text-transform: capitalize;
        color: #000;
        white-space: normal; 
        /* word-wrap: break-word; */
        /* text-decoration: none; */
        /* word-wrap: break-word; */
        /* inline-size: max-content; */
        /* overflow: hidden; */
    /* text-overflow: ellipsis; */
    /* white-space: nowrap; */
    }

    /* Ensure images are responsive and do not overflow */
    .col-3 {
        padding-left: 5px;
        padding-right: 5px;
    }

    /* Control the grid for mobile - 4 items per row */
    .row > .col-3 {
        flex: 0 0 25%; /* 4 items per row */
        max-width: 25%;
    }
}


</style>

<!-- <section class="home-section-2 home-section-bg pt-0 overflow-hidden">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="slider-animate">
                    <div>
                        <div class="home-contain rounded-0 p-0">
                            <img src="{{ asset('frontweb/assets/images/grocery/banner/1.jpg') }}"
                                class="img-fluid bg-img blur-up lazyload" alt="">
                            <div class="home-detail home-big-space p-center-left home-overlay position-relative">
                                <div class="container-fluid-lg">
                                    <div>
                                        <h6 class="ls-expanded theme-color text-uppercase">Weekend Special offer
                                        </h6>
                                        <h1 class="heding-2">Premium Quality Dry Fruits</h1>
                                        <h2 class="content-2">Dryfruits shopping made Easy</h2>
                                        <h5 class="text-content">Fresh & Top Quality Dry Fruits are available here!
                                        </h5>
                                        <button
                                            class="btn theme-bg-color btn-md text-white fw-bold mt-md-4 mt-2 mend-auto"
                                            onclick="location.href = '#';">Shop Now <i
                                                class="fa-solid fa-arrow-right icon"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<div class="modal fade" id="quickNoteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#091B9E; color:white; padding:25px; border-radius:20px;">

      <div class="modal-header" style="border:none;">
        <h2 style="font-size:38px; font-weight:700;">Kindly Note</h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" style="font-size:17px; line-height:1.7;">
        Quick service Slot deliveries only available current in Mulund, Thane,
        Bhandup, Airoli region of Mumbai. Outside customers can still order
        from regular delivery time during checkout. “We will be soon starting
        quick service in your area” – Hang On!
      </div>

    </div>
  </div>
</div>

<section class="home-section-2 home-section-bg pt-0 overflow-hidden">
 <div class="row mt-4 mb-4 d-md-none d-block justify-content-center">
    <div class="col-12">
        @if(auth()->user())
            <div class="search-box w-100">
        @else
            <div class="search-box w-100">
        @endif
            <div class="input-group w-100 justify-content-center">
                <span class="input-group-text" id="button-addon2" style="border-radius: 24px 0px 0px 24px; width: 45px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-search" style="margin-left:7px; width: 21px;">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <div class="location-list" style="width: calc(100% - 50px)">
                    <div class="search-input">
                        <input type="search" id="product-search-input" class="form-control search"
                            style="background-color: #f8f9fc; border-radius: 0px 24px 24px 0px;"
                            placeholder="Search Your Products">
                    </div>
                    
                </div>
            </div>
        </div>
        <div id="searchPopup" class="search-popup">
            <div id="searchPopupContent" class="search-popup-content">
            </div>
        </div>
    </div>
</div>
</section>

<ul id="search_list" class="location-select custom-height search_list">
                    </ul>

<section class="home-section-2 home-section-bg pt-0 overflow-hidden">
    <div class="container p-0">
        <div class="row">
            <div class="col-12">
                
                
            <div class="d-none d-md-block">
                 <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="4500">
                    <div class="carousel-inner">
                        @foreach ($bannersImage as $index =>$item)

                        <div class="carousel-item  {{ ($index === 0) ? 'active' : ''  }}">
                            <a href="{{ route('subcateg', ['category_id' => $item->category_id]) }}">
                                <img src="uploads/{{ $item->banner_image }}"
                                    class="d-block w-100 banner-size" alt=""></a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>




    <div class="d-md-none">
    <div class="owl-theme owl-carousel owl-carousel-mobile mt-4" id="owl-carousel-mobile">
        @foreach ($bannersImage as $item)
            <div class="item">
                <a href="{{ route('subcateg', ['category_id' => $item->category_id]) }}">
                    <img src="uploads/{{ $item->banner_image }}" class="" style="height: 148px; border-radius: 7%; object-fit: fill;" alt="{{ $item->banner_image }}">
                </a>
            </div>
        @endforeach
    </div>
</div>

            </div>
            <!-- <div class="slider-animate">
                    <div>
                        <div class="home-contain rounded-0 p-0">
                            <img src="{{ asset('frontweb/assets/images/grocery/banner/1.jpg') }}"
                                class="img-fluid bg-img blur-up lazyload" alt="">
                            <div class="home-detail home-big-space p-center-left home-overlay position-relative">
                                <div class="container-fluid-lg">
                                    <div>
                                        <h6 class="ls-expanded theme-color text-uppercase">Weekend Special offer
                                        </h6>
                                        <h1 class="heding-2">Premium Quality Dry Fruits</h1>
                                        <h2 class="content-2">Dryfruits shopping made Easy</h2>
                                        <h5 class="text-content">Fresh & Top Quality Dry Fruits are available here!
                                        </h5>
                                        <button
                                            class="btn theme-bg-color btn-md text-white fw-bold mt-md-4 mt-2 mend-auto"
                                            onclick="location.href = '#';">Shop Now <i
                                                class="fa-solid fa-arrow-right icon"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
        </div>
    </div>
</section>



<!-- Category Section Start -->
<section class="category-section-3 padding-50">
    <div class="container-fluid-lg">
        <div class="title">
            <h3 class="heading">
                <span class="text-primary">Shop By</span><span class="fw-600">&nbsp; Category</span>
            </h3>
        </div>
        
        <!-- Desktop Layout (Hides on Mobile) -->
        <div class="row d-none d-md-flex">
            <div class="col-12">
                <div class="row gy-3">
                @foreach ($categories as $category)
                        {{-- Only display if the category image is valid --}}
                        @if ($category->image !== '1718876959.jpg')
                            {{-- For large screens --}}
                            <div class="col-md-2 d-sm-none gy-4">
                                <a href="{{ route('subcateg', ['category_id' => $category->id]) }}" class="category-name">
                                    <div class="category-box-list">
                                        <h4>{{ $category->category_name }}</h4>
                                        <div class="category-box-view">
                                            <a href="{{ route('subcateg', ['category_id' => $category->id]) }}">
                                                <img src="/uploads/{{ $category->image }}" 
                                                    class="img-fluid blur-up lazyload" 
                                                    alt="{{ $category->category_name }}" 
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mobile Layout (Only Visible on Mobile) -->
        <div class="row d-block d-md-none">
            <div class="col-12">
                <div class="row gy-3">
                    @foreach ($categories as $category)
                        {{-- Only display if the category image is valid --}}
                        @if ($category->image !== '1718876959.jpg')
                            {{-- For mobile view --}}
                            <div class="col-3 text-center gy-3">


                                        
                                <a href="{{ route('subcateg', ['category_id' => $category->id]) }}" class="category-name d-block">
                                    <div class="category-box-list">
                                        <div class="category-box-view">
                                            <img src="/uploads/{{ $category->image }}"
                                                 class="blur-up lazyload mx-auto"
                                                 alt="{{ $category->category_name }}"
                                                 >
                                        </div>
                                       
                                    </div>

                                    <h4 class="my-2 textcssfor">
                                            {{ $category->category_name }}
                                        </h4>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Section End -->

<!-- features started -->
<section class="before-features-section padding-25 bg-light">
    <div class="container">
        <div class="row gy-4"> <!-- Added vertical spacing -->
            <div class="col-md-3 col-6 d-flex justify-content-center">
                <div class="f-div text-center">
                    <div class="f-img mb-2">
                        <img src="{{ asset('frontweb/assests/images/f1.png') }}" alt="Feature 1" style="width: 80px; height: auto;">
                    </div>
                    <div class="f-content">
                        <h6>
                            Select Desired Product based on Buying Pattern Loose OR Box
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 d-flex justify-content-center">
                <div class="f-div text-center">
                    <div class="f-img mb-2">
                        <img src="{{ asset('frontweb/assests/images/f2.png') }}" alt="Feature 2" style="width: 80px; height: auto;">
                    </div>
                    <div class="f-content">
                        <h6 style="font-size: 14px; line-height: 1.4; word-wrap: break-word;">
                            Add to Enquiry Cart To share your list
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 d-flex justify-content-center">
                <div class="f-div text-center">
                    <div class="f-img mb-2">
                        <img src="{{ asset('frontweb/assests/images/f3.png') }}" alt="Feature 3" style="width: 80px; height: auto;">
                    </div>
                    <div class="f-content">
                        <h6 style="font-size: 14px; line-height: 1.4; word-wrap: break-word;">
                            Confirm your price or Negotiate from quoted price
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 d-flex justify-content-center">
                <div class="f-div text-center">
                    <div class="f-img mb-2">
                        <img src="{{ asset('frontweb/assests/images/f4.png') }}" alt="Feature 4" style="width: 80px; height: auto;">
                    </div>
                    <div class="f-content">
                        <h6 style="font-size: 14px; line-height: 1.4; word-wrap: break-word;">
                            Order from your Approved Price List
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- features ended -->




<!-- brands Section Start -->
@foreach ($festivalandoffers as $festival)
<section class="category-section-3 my-5">
    <div class="container-fluid-lg">

        <div class="title">
            @php
                $parts = explode(' ', $festival->festival_offier_name, 2);
            @endphp

            <h3 class="heading" style="font-size:15px">
                <span class="text-primary">{{ $parts[0] }}</span>
                @isset($parts[1])
                    <span class="fw-600">{{ $parts[1] }}</span>
                @endisset
            </h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="owl-theme owl-carousel owl-carousel-offers"
                     id="owl-carousel-{{ $festival->id }}">

                    @forelse ($festival->brandImages as $brandImage)
                        <div class="item">
                            <div class="brands-div">
                                <a href="{{ $brandImage->search_by === 'brand'
                                    ? route('subcateg', ['brand_name' => $brandImage->brand_name])
                                    : route('subcateg', ['category_id' => $brandImage->category_id]) }}">

                                    <img src="{{ asset('uploads/' . $brandImage->brand_image) }}"
                                         class="newimagecss"
                                         alt="{{ $brandImage->brand_name }}">
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No banners available</p>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</section>
@endforeach

<!-- brands Section End -->










<!-- discount started -->
<!-- <section class="discount-slider mb-5">
        <div class="container">
            <h3 class="heading mb-4"><span class="text-primary">DEALS </span><span class="fw-600"> &nbsp; &
                    OFFERS</span></h3>

            <div class="owl-carousel owl-theme " id="owl-carousel8">
                <div class="item">
                    <img src="{{ asset('frontweb/assests/images/d1.png') }}" class="img-fluid">
                </div>
                <div class="item">
                    <img src="{{ asset('frontweb/assests/images/d2.png') }}" class="img-fluid">
                </div>
                <div class="item">
                    <img src="{{ asset('frontweb/assests/images/d3.png') }}" class="img-fluid">
                </div>
                <div class="item">
                    <img src="{{ asset('frontweb/assests/images/d4.png') }}" class="img-fluid">
                </div>
                <div class="item">
                    <img src="{{ asset('frontweb/assests/images/d1.png') }}" class="img-fluid">
                </div>
                <div class="item">
                    <img src="{{ asset('frontweb/assests/images/d2.png') }}" class="img-fluid">
                </div>

            </div>
        </div>
    </section> -->
<!-- discount ended -->

<!-- Product Fruit & Vegetables Section Start -->
<section class="product-section-3 bg-light padscss">
    <div class="container-fluid-lg">
        @foreach ($categories as $category)
            @if ($category->products->isNotEmpty())
                <div class="row align-items-center">
                    <div class="col-md-8 col-sm-8">
                        <div class="title d-flex bre-img align-items-center">
                            {{-- Only display if the category image is valid --}}
                            @if ($category->image !== '1718876959.jpg')
                                <img src="/uploads/{{ $category->image }}" alt="{{ $category->category_name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                <h2>{{ $category->category_name }}</h2>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 text-end">
                        <a onclick="redirectsubcat('subcateg?category_id={{ $category->id }}')" class="red-btn">View All</a>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="owl-carousel owl-theme owl-carousel7">
                            @foreach ($category->products->where('status', 'active') as $key => $product)
                                @if ($product->category_id == $category->id && $product->image !== '1718876959.jpg')
                                    <div class="item">
                                        <div class="product-box-4 wow fadeInUp">
                                            <div class="product-image product-image-2">
                                                <a @if (Auth::user()) href="{{ route('product-details', $product->id) }}" @endif>
                                                    <img src="/uploads/{{ $product->image }}" 
                                                         alt="{{ $product->product_name }}" 
                                                         class="img-fluid blur-up lazyload">
                                                </a>
                                            </div>
                                            <div class="product-detail">
                                                <a @if (Auth::user()) href="{{ route('product-details', $product->id) }}" @endif>
                                                    <h5 class="name text-title">{{ $product->product_name }}</h5>
                                                </a>
                                                <!-- <h5 class="price price-p">Pack of {{ $product->product_quantity }}</h5> -->
                                                @if (Auth::user())
                                                    <div>
                                                        <input type="hidden" name="quantity" id="quantityInput">
                                                        <div class="quantity product-{{ $product->id }}">
                                                            <div class="row g-1 mt-2 mb-3">
                                                                <div class="col-md-5 col-sm-5">
                                                                    <div class="box-btn boxbtncss" 
                                                                         onclick="updateQuantity('BOX', {{ $product->id }})">
                                                                        BOX
                                                                    </div>
                                                                    <div class="border-red pcs-box pcs-{{ $product->id }}" style="display:none;">
                                                                    {{ $product->carton_size ?? 0 }} PCS
                                                                   </div>
                                                                </div>
                                                                <div class="col-md-5 col-sm-5">
                                                                    <div class="loose-btn loosbtncss" 
                                                                         onclick="updateQuantity('LOOSE', {{ $product->id }})">
                                                                        LOOSE
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button onclick="submit({{ $product->id }})" type="button" 
                                                                            class="add-button addcart-button btn buy-button text-light">
                                                                       <span style="color:#fff; border-color: #fff;">+</span>
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
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>
<script>
    
$(document).ready(function () {
    const $carousel = $('#owl-carousel-mobile');
    let itemCount = $carousel.find('.item').length;

    if (itemCount > 1) {
        // Enable sliding only when more than one banner exists
        $carousel.owlCarousel({
            autoplay: true,
            loop: true,
            margin: 10,
            nav: false,
            items: 1,
            center: true,
            stagePadding: 50,
            responsive: {
                0: {
                    items: 1,
                    margin: 20,
                    stagePadding: 50,
                },
            },
            onInitialized: function () {
                let maxHeight = 0;

                $carousel.find('.owl-item img').each(function () {
                    const itemHeight = $(this).outerHeight();
                    if (itemHeight > maxHeight) {
                        maxHeight = itemHeight;
                    }
                });

                // Apply the calculated height
                $carousel.find('.owl-stage-outer').height(maxHeight);
                $carousel.find('.owl-item').height(maxHeight);
                $carousel.css('visibility', 'visible'); // Show carousel after initialization
            },
        });
    } else {
        // If only one banner exists, show it without Owl Carousel and adjust height
        $carousel.css({ 'visibility': 'visible', 'display': 'block', 'text-align': 'center' });
        $carousel.find('.item').css({ 'display': 'inline-block' });
        $carousel.find('.item img').css({ 'height': '190px' }); // Set height to 200px
    }
});


</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("product-search-input");
    const placeholders = [
        "Search for beverages...",
        "Find your favorite drink...",
        "Discover amazing deals...",
        "Enter product name or brand...",
    ];

    let index = 0;

    // Function to rotate placeholders
    function changePlaceholder() {
        searchInput.setAttribute("placeholder", placeholders[index]);
        index = (index + 1) % placeholders.length; // Cycle through the array
    }

    // Initial placeholder setup
    changePlaceholder();

    // Change placeholder every 3 seconds
    setInterval(changePlaceholder, 3000);
});



// $(window).on('load', function() {
//     setTimeout(function(){
//         $(window).scrollTop(0);
//     }, 50); // Adjust the delay (50ms) if needed
// });




</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Attach CSRF token to all axios requests
    axios.defaults.headers.common['X-CSRF-TOKEN'] =
        document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    axios.post('/popup-track')
        .then(response => {
            if (response.data.show) {
                let modal = new bootstrap.Modal(document.getElementById('quickNoteModal'));
                modal.show();
            }
        })
        .catch(err => console.error(err));
});
</script>

<!-- Product Fruit & Vegetables Section End -->
@endsection
