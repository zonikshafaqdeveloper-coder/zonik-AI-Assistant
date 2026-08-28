@extends('web.layouts.app')
@section('content')


<style>

.buy-buttonss{
    display: flex
;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    height: 30px;
    position: absolute;
    right: -3px;
    padding: 7px 11px;
    background-color: #e97457;
    width: 30px;
    font-size: 25px;
    color: #fff;
    /*margin-top: -37px;*/
    border-radius: 50px;
}

.pcs-box {
        display: none;
    }
    
@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px; /* Optional: Add margin for small screens */
    }

    .modal-header {
        padding: 10px;
    }

    .modal-footer {
        padding: 15px;
    }

    .filter-name-div {
        padding: 10px;
    }

    .modal-content {
        padding: 15px;
    }

    .label {
        font-size: 14px;
    }
}


    .nav-link.active {
        background: #a558c7 !important;
        color: #fff !important;
    }

    .modal-w1 {
        width: 50% !important;
    }

    .filter-main.div {
        background: #fff;
        border-radius: 20px;
        height: 540px;
        overflow-y: auto;
        margin: 0 24px 24px;
    }

    .filter-div {
        padding: 20px 40px 20px 40px !important;
        border-radius: 24px !important;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .filter-name {
        padding-top: 12px;
        padding-bottom: 12px;
        font-size: 14px !important;
    }

    .fs-14 {
        font-size: 14px !important;
        font-weight: 500;
    }

    .filter-name-div {
        background: #f0f0f0;
        margin-top: 15px;
        border-radius: 24px;
        margin-bottom: 15px;
        overflow-y: scroll;
    }

    .break-line {
        font-size: 0;
        width: 100%;
        border-top: 1px solid #ebeb;
    }

    .p-15 {
        padding: 15px;
    }

    .scrollable-div {
        height: 450px;
        /* Adjust height as needed */
        overflow: auto;
        /* Enable scrolling */
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .scrollable-div::-webkit-scrollbar {
        display: none;
        /* Hide the scrollbar in webkit browsers */
    }

    .contact-div {
        border-radius: 24px;
        box-shadow: 0 1px 4px rgba(28, 28, 28, .06);
        display: flex;
        border: 1px solid #d6d6d6;
        background-color: #e3e7e8;
        padding-top: 16px;
        height: 140px;
        width: 100%;
    }

    .conact-icon {
        background-size: contain;
        width: 80px;
        height: 86px;
        background-repeat: no-repeat;
        background-position-y: center;
        margin-left: 16px;
        /* transform: translateY(14px); */
    }

    .form-content {
        display: flex;
        margin-left: 16px;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .form-comtent1 {
        color: #363636;
        font-weight: 500;
        line-height: 35px;
    }

    .mr-16 {
        margin-right: 16px;
    }

    .pt-50 {
        padding-top: 50px;
    }

    .pb-20 {
        padding-bottom: 20px;
    }


    .d-sm-none {
        display: block
    }

    @media (max-width: 767px) {
        .contact-div {
            display: block;
            height: auto;
            text-align: center;
        }

        .form-content {
            display: block;
            text-align: center;

        }

        .d-sm-none {
            display: none
        }
        .pcs-box {
        display: none;
    }
    }


    @media (max-width: 768px) {
        .f-content h6 {
            font-size: 12px;
        }

      .buy-buttonss{
            width: 25px;
        font-size: x-large;
        margin-left: 1rem;
        margin-top: 0px;
        height: 24px;
        /* position: absolute; */
        border-radius: 50%;
        right: 25px;
    
    }   
     
     
    
    .border-red {
        font-weight: 500 !important;
        margin-top: 2px;
        margin-bottom: -37px;
        border: 1px solid #a558c8;
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
     .col-sm-5{
        width: 33%;
        font-size: 10px;
    }

     /*.boxbtncss{*/
     /*       font-size: 10px;*/
     /*       width: 45px;*/
     /*   }*/
     /*   .loosbtncss{*/
     /*       font-size: 10px;*/
     /*   width: 57px;*/
     /*   margin-left: -3.45rem;*/
     /*   }*/
    }


/* @media (max-width: 768px) {
    .modal.fade .modal-dialog {
        max-height: 90vh; Limits the modal height
        margin: 0 auto; /* Centers the modal */
    /* }

    .modal-content {
        max-height: fit-content; /* Ensures the content does not exceed modal height */
        /* overflow-y: auto; /* Enables vertical scrolling if content overflows */
    /* }

    .filter-name-div {
        max-height: fit-content; /* Restricts the height of the content */
        /* overflow-y: auto; /* Allows scrolling within this section */
        /* padding-bottom: 15px; /* Adds some space at the bottom */
    /* } */
/* 
    .modal-footer {
        position: sticky; /* Keeps footer fixed at the bottom of the modal */
        /* bottom: 0; */ 
        /* background-color: #fff; /* Matches modal background */
        /* z-index: 10;
        padding: 10px;
    /* } */ 
/* } */ 

/* Brand Popup Responsive */

#exampleModalScrollable .modal-dialog {
    max-width: 900px;
    width: 90%;
}

#exampleModalScrollable .filter-div {
    max-height: 85vh;
    display: flex;
    flex-direction: column;
}

#exampleModalScrollable .filter-name-div {
    flex: 1;
    overflow-y: auto;
    max-height: 60vh;
}

#exampleModalScrollable .modal-footer {
    position: sticky;
    bottom: 0;
    background: #fff;
    z-index: 99;
}

@media (max-width: 768px) {

    #exampleModalScrollable .modal-dialog {
        width: 100%;
        max-width: 100%;
        margin: 0;
        height: 100vh;
    }

    #exampleModalScrollable .filter-div {
        height: 100vh;
        border-radius: 0 !important;
        padding: 15px !important;
    }

    #exampleModalScrollable .filter-name-div {
        max-height: calc(100vh - 180px);
        overflow-y: auto;
    }

    #exampleModalScrollable .modal-footer {
        position: sticky;
        bottom: 0;
        background: #fff;
    }
}

    
</style>


<!-- Catalogue -->
<section class="catalogue-section">
    <div class="container-fluid pt-50 pb-20">
        <ul class="nav second-pills nav-pills mb-4 align-items-center justify-content-center" id="pills-tab" role="tablist">
            @foreach ($categories as $categoryItem)
            @if (isset($categoryItem))
            <li class="nav-item" role="presentation">

                <a class="nav-link {{ $selectedCategory && $selectedCategory->id == $categoryItem->id ? 'active' : '' }}" href="?category_id={{ $categoryItem->id }}">
                    {{ $categoryItem->category_name }}
                </a>
            </li>
            @endif
            @endforeach
        </ul>

        <!-- <ul class="nav second-pills nav-pills mb-4  align-items-center justify-content-center"
                id="pills-tab" role="tablist">
                @foreach ($categories as $categoryItem)
                    @if (isset($categoryItem))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active " href="?category_id={{ $categoryItem->id }}"
                                class="{{ $selectedCategory && $selectedCategory->id == $categoryItem->id ? 'active' : '' }}">
                                {{ $categoryItem->category_name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul> -->

        <!-- <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                <div class="tab-pane fade" id="pills-contact1" role="tabpanel" aria-labelledby="pills-contact-tab1">...</div>
                <div class="tab-pane fade" id="pills-contact2" role="tabpanel" aria-labelledby="pills-contact-tab2">...</div>
                <div class="tab-pane fade" id="pills-contact3" role="tabpanel" aria-labelledby="pills-contact-tab3">...</div>
              </div> -->

        <div class="row  mb-5">
            <div class="col-md-3 col-sm-2 shadow">
                <div class="catalogue-left-bar">
                     <ul>
                        <li>
                            <div class="d-flex  justify-content-center">
                                        <img src="assets/images/c1.png" class="catalogue-img"></div>
                            <strong style="cursor:pointer;" onclick="reload()" class="cursor-pointer">
                                <a href="?category_id={{ $selectedCategory->id }}" class="nav-link"> All </a>
                            </strong>
                        </li>

                        @foreach ($subcategories as $subcategory)
     @php
        $imagePath = public_path('uploads/' . $subcategory->image);
    @endphp
    @if (!empty($subcategory->image) && file_exists($imagePath) && !empty($subcategory->name) && $subcategory->image !== '1718876959.jpg')
    <a href="subcateg?category_id={{ $selectedCategory->id }}&sub_id={{ $subcategory->id }}" data-subcategory="{{ $subcategory->id }}">
                     
    <li class="{{ $selectedSubCategoryId && $selectedSubCategoryId == $subcategory->id ? 'active' : '' }}">
                            <div class="d-flex  justify-content-center"><img src="/uploads/{{ $subcategory->image }}" class="catalogue-img"></div>
                            {{-- subcategory-link --}}
                            <a href="subcateg?category_id={{ $selectedCategory->id }}&sub_id={{ $subcategory->id }}" class="nav-link" data-subcategory="{{ $subcategory->id }}">
                                {{ $subcategory->name }}
                            </a>
                        </li>
                        
                        </a>

        @endif
@endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-10">
                <div class="filters mt-4">
                    <div class="page-wrap">

                        <section>
                            <!-- Button trigger modal-->
                            <div>


                                <ul class="nav mb-4" id="pills-tab" role="tablist">
                                    <li role="presentation" class="d-flex pills-scroll-li">
                                        <div onclick="toggel('exampleModalScrollable')" class="category-filter rounded-pill fs-6 shadow-lg p-2 px-3">
                                            <span class="filter-title">Brand </span>
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </div>

                                        @php
                                        $uniquetags = [];

                                        foreach ($products as $tag) {
                                        if ($tag->tags) {
                                        $tagsArray = explode(',', $tag->tags);
                                        $uniquetags = array_merge($uniquetags, $tagsArray);
                                        }
                                        }

                                        $allUniqueTags = array_unique($uniquetags);
                                        @endphp

                                        @foreach ($allUniqueTags as $tag)
                                        <div class="type-filter rounded-pill fs-6 shadow-lg p-2 px-3" onclick="tagSelected('{{ $tag }}', {{ $selectedCategoryId }})">
                                            {{ $tag }}
                                        </div>
                                        @endforeach


                                        <div onclick="toggelTypes('exampleModalScrollableTypes')" class="category-filter rounded-pill fs-6 shadow-lg p-2 px-3">
                                            <span class="filter-title">Type</span>
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </div>
                                    </li>
                                </ul>

                            </div>
                            <!-- Modal-->
                           <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-full screen-sm-down vh-100" role="document">
                                    <div class="modal-content filter-div" style="padding: 20px;">
                                        <div class="modal-header">
                                            <h5 class="modal-title filter-brands" id="exampleModalLabel">Filter Brands</h5>
                                            <a class="close pointer" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                        </div>
                                        @if ($products && count($products) > 0)
                                        <div class="modal-body filter-name-div">
                                            @php
                                            $uniqueBrands = [];
                            
                                            foreach ($products as $product) {
                                                if ($product->brands) {
                                                    $brandsArray = explode(',', $product->brands);
                                                    $uniqueBrands = array_merge($uniqueBrands, $brandsArray);
                                                }
                                            }
                            
                                            $uniqueBrands = array_unique($uniqueBrands);
                                            @endphp
                                            @if (count($uniqueBrands) > 0)
                                            @foreach ($uniqueBrands as $individualBrand)
                                            <div class="d-flex p-15 filter-name justify-content-between gap-4">
                                                <label for="{{ $individualBrand }}" class="label fs-14">
                                                    {{ $individualBrand }}
                                                </label>
                                                <input id="{{ $individualBrand }}" class="form-check-input brand-checkbox" type="checkbox" name="brand_list" value="{{ $individualBrand }}" onchange="brandList(this.value)">
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="alert alert-warning mt-3" role="alert">
                                                No brands found.
                                            </div>
                                            @endif
                                        </div>
                                        @else
                                        <div class="alert alert-warning mt-3" role="alert">
                                            No brands found.
                                        </div>
                                         
                                        @endif
                                        <div class="modal-footer">
                                            <button onclick="filter({{ $selectedCategoryId }})" type="button" class="btn red-btn font-weight-bold">Apply</button>
                                            <button type="button" class="btn clear-btn font-weight-bold" data-bs-dismiss="modal">Clear All</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- Types --}}

                        <div class="modal fade" id="exampleModalScrollableTypes" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-full screen-sm-down vh-100" role="document">
        <div class="modal-content filter-div">
            <div class="modal-header">
                <h5 class="modal-title filter-brands" id="exampleModalLabel">Filter Types</h5>
                <a class="close pointer" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>

            @if ($products)
            <div class="modal-body filter-name-div">
                @php
                $uniqueTypes = [];

                foreach ($products as $type) {
                    if ($type->types) {
                        $typesArray = explode(',', $type->types);
                        $uniqueTypes = array_merge($uniqueTypes, $typesArray);
                    }
                }

                $uniqueTypes = array_unique($uniqueTypes);
                @endphp
                @foreach ($uniqueTypes as $individualTypes)
                <div class="d-flex p-15 filter-name justify-content-between gap-4 mb-2">
                    <label for="{{ $individualTypes }}" class="label fs-14">
                        {{ $individualTypes }}
                    </label>
                    <input id="{{ $individualTypes }}" class="form-check-input brand-checkbox" type="checkbox" name="type_list" value="{{ $individualTypes }}" onchange="typeList(this.value)">
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-warning mt-3" role="alert">
                No Types found.
            </div>
            @endif

            <div class="modal-footer">
                <button onclick="filterTypeData({{ $selectedCategoryId }})" type="button" class="btn red-btn">Apply</button>
                <button type="button" class="btn clear-btn" data-bs-dismiss="modal">Clear All</button>
            </div>
        </div>
    </div>
</div>


                        </section>


                        <div class="products-div {{ $selectedSubCategoryId ? '' : 'scrollable-div' }}">
                        <!--<div class="products-div {{ $selectedSubCategoryId ?? '' }}">-->
                            <div class="row gy-4 products-container">
                                @foreach ($products as $product)
                                <div class="col-md-3">
                                <div class="product-box-4  fadeInUp">
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
                                                                <!--<div class="col-md-2">-->
                                                                <!--    <button onclick="submit()" type="button"-->
                                                                <!--        class="add-button addcart-button btn buy-button">-->
                                                                <!--        +-->
                                                                <!--    </button>-->
                                                                <!--</div>-->
                                                               <div class="col-md-2 col-sm-5">
                                                                <button onclick="submit({{ $product->id }})" type="button" 
                                                                            class="add-button addcart-button btn buy-buttonss">
                                                                        +
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                </div>

                                
                                @endforeach
                                <div class="contact-div  d-lg-flex align-items-center p-2">
                                    <div class="d-lg-flex align-items-center">
                                        <img src="{{ asset('frontweb/assests/images/search.png') }}" class="conact-icon" alt="">
                                    </div>

                                    <div class="form-content">
                                        <div>
                                            <h3 class="form-comtent1">Looking for something else?</h3>
                                            <p class="form-comtent1">Tell us and we’ll add it to the shop.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('requestproduct') }}" class="mr-16">
                                        <button type="button" class="btn red-btn" style="margin: auto;">Request a Product</button>
                                    </a>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>



@endsection


<!-- latest jquery-->
<script src="{{ asset('frontweb/assets/js/jquery-3.6.0.min.js') }}"></script>

<!-- jquery ui-->
<script src="{{ asset('frontweb/assets/js/jquery-ui.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

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

    let branditems = [];
    let sub_category_id = '';

    function brandList(branditem) {
        // alert(branditems)
        branditems.push(branditem);
    }

    let typeitems = [];
    let type_sub_category_id = '';

    function typeList(typeitem) {
        // alert(typeitem)
        typeitems.push(typeitem);

    }

    // let tag_ids = [];

    function tagSelected(tag, selectedCategoryId) {
        // alert(tag)
        $.ajax({
            url: '/subcat/products/tag/filter'
            , method: 'GET'
            , data: {
                tag: tag
                , selectedCategoryId: selectedCategoryId
                , sub_category_id: sub_category_id, // Use the correct parameter name
            }
            , success: function(data) {
                loadProducts(data);
                //  document.getElementsByID('type-filter').classList.add('bg-danger');

                // tag_ids = [];

            }
            , error: function(error) {
                console.log(error);
            }
        });
    }


    function filter(selectedCategoryId) {
        // alert(selectedCategoryId)
        $.ajax({
            url: '/subcat/products/filter'
            , method: 'GET'
            , data: {
                branditems: branditems
                , selectedCategoryId: selectedCategoryId
                , sub_category_id: sub_category_id
            , }
            , success: function(data) {
                console.log(data);
                loadProducts(data);
                branditems = [];
                toggel('exampleModalScrollable')

            }
            , error: function(error) {
                console.log(error);
            }
        });
    }


    function filterTypeData(selectedCategoryId) {
        $.ajax({
            url: '/subcat/products/filtertype'
            , method: 'GET'
            , data: {
                typeitems: typeitems ? typeitems : undefined
                , selectedCategoryId: selectedCategoryId ? selectedCategoryId : undefined
                , type_sub_category_id: type_sub_category_id ? type_sub_category_id : undefined
            , }
            , success: function(response) {
                loadProducts(response);
                typeitems = [];
                toggelTypes('exampleModalScrollableTypes')

            }
            , error: function(error) {
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
            url: '/subcategories_lists/' + subcategoryId + '/products_lists'
            , method: 'GET'
            , success: function(data) {
                // console.log(data);
                loadProducts(data);
            }
            , error: function(error) {
                console.log(error);
            }
        });
    }


    function loadBrandCategoryProducts(brandCategoryId) {
        $.ajax({
            url: '/brandcategories/' + brandCategoryId + '/products'
            , method: 'GET'
            , success: function(data) {
                products - brands(data);
            }
            , error: function(error) {
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

            // $.ajax({
            //     url: '/subcateg',
            //     method: 'GET',
            //     data: {
            //         sub_id: subcategoryId,
            //     },
            //     success: function(data) {
            //         console.log(data);
            //     },
            //     error: function(error) {
            //         console.log(error);
            //     }
            // });

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
            items: 4
            , margin: 20
            , autoplay: true
            , loop: true
            , nav: false
        , });
    });

</script>
<script>
    $(function() {
        // Owl Carousel
        var owl = $("#owl-carousel1");
        owl.owlCarousel({
            items: 5
            , margin: 20
            , autoplay: true
            , loop: true
            , nav: true
        , });
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
