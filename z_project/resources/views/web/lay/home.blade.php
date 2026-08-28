<!-- resources/views/home.blade.php -->
@extends('web.lay.app')

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Display Subcategories as Tabs -->
        <div class="subcategory-tabs">
            <ul class="nav nav-tabs">
            @foreach($subcategories as $subcategory)
            <li class="nav-item">
            <a href="#" class="nav-link subcategory-link" data-subcategory="{{ $subcategory->id }}">{{ $subcategory->name }}</a>
            </li>
            @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <!-- Display Products -->
        <!-- <div class="products-container"> -->
        <div class="products-container">
         
            <!-- @foreach($products as $product)
                <div class="product">
                <a href="#"> {{ $product->name }} </a>
                </div>
            @endforeach -->



            <div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-md-3 row-cols-2 g-sm-4 g-3 no-arrow section-b-space products-container">
                       @foreach($products as $product)
                        <div>
                        
                            <div class="product-box product-white-bg wow fadeIn ">
                                <div class="product-image">
                                    <a href="product-left-thumbnail.html">
                                        <img src="/upload
                                        s/{{ $product->image }}"
                                            class="img-fluid blur-up lazyload" alt="">
                                    </a>
                                    <ul class="product-option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </li>

                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
                                            <a href="compare.html">
                                                <i data-feather="refresh-cw"></i>
                                            </a>
                                        </li>

                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="wishlist.html" class="notifi-wishlist">
                                                <i data-feather="heart"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-detail position-relative  ">
                                    <a href="product-left-thumbnail.html " class="product">
                                        {{ $product->product_name }}
                                    </a>

                                    <h6 class="sold weight text-content fw-normal">{{ $product->product_qty }}</h6>

                                    <h6 class="price theme-color">{{ $product->product_price }}</h6>

                                    <div class="add-to-cart-btn-2 addtocart_btn">
                                        <button class="btn addcart-button btn buy-button"><i
                                                class="fa-solid fa-plus"></i></button>
                                        <div class="cart_qty qty-box-2">
                                            <div class="input-group">
                                                <button type="button" class="qty-left-minus" data-type="minus"
                                                    data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text"
                                                    name="quantity" value="1">
                                                <button type="button" class="qty-right-plus" data-type="plus"
                                                    data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
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
@endsection


@section('styles')
<style>
    /* Additional styles specific to this page */
    .subcategory-tabs {
        padding: 20px;
        border-right: 1px solid #ddd;
    }

    .nav-tabs {
        flex-direction: column;
    }

    .nav-item {
        margin-bottom: 10px;
    }

    .nav-link {
        color: #333;
    }

    .nav-link:hover {
        color: #ff6600;
    }

    .products-container {
        padding: 20px;
    }

    .product {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #fff;
    }
</style>
@endsection

@section('scripts')
<script>
    // Function to load all products
    // function loadAllProducts1() {
    //     var productsHTML = '';
    //     @foreach($products as $product)
    //         productsHTML += '<div class="product">{{ $product->product_name }}</div>';
         
    //     @endforeach
    //     $('.products-container').html(productsHTML);
    // }



    function loadAllProducts() {
    var productsHTML = '';

    @foreach($products as $product)
        productsHTML += `
            <div class="product-box product-white-bg wow fadeIn">
                <div class="product-image">
                    <a href="product-left-thumbnail.html">
                        <img src="/uploads/{{ $product->image }}" class="img-fluid blur-up lazyload" alt="">
                    </a>
                    <ul class="product-option">
                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view">
                                <i data-feather="eye"></i>
                            </a>
                        </li>
                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
                            <a href="compare.html">
                                <i data-feather="refresh-cw"></i>
                            </a>
                        </li>
                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                            <a href="wishlist.html" class="notifi-wishlist">
                                <i data-feather="heart"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="product-detail position-relative">
                    <a href="product-left-thumbnail.html" class="product">
                        {{ $product->product_name }}
                    </a>
                    <h6 class="sold weight text-content fw-normal">{{ $product->product_qty }}</h6>
                    <h6 class="price theme-color">{{ $product->product_price }}</h6>
                    <div class="add-to-cart-btn-2 addtocart_btn">
                        <button class="btn addcart-button btn buy-button"><i class="fa-solid fa-plus"></i></button>
                        <div class="cart_qty qty-box-2">
                            <div class="input-group">
                                <button type="button" class="qty-left-minus" data-type="minus" data-field="">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                                <input class="form-control input-number qty-input" type="text" name="quantity" value="1">
                                <button type="button" class="qty-right-plus" data-type="plus" data-field="">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    @endforeach

    $('.products-container').html(productsHTML);
}







    // AJAX request to load products based on selected subcategory
    $(document).ready(function() {
        // Load all products initially
        loadAllProducts();

        $('.subcategory-link').on('click', function(e) {
            e.preventDefault();
            
            var subcategoryId = $(this).data('subcategory');
            
            $.ajax({
                url: '/subcategories/' + subcategoryId + '/products',
                method: 'GET',
                success: function(data) {
                  
                    $('.products-container').html(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>
@endsection















