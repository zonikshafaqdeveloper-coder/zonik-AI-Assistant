@extends('web.layouts.app')
@section('content')

<!-- Catalogue -->
<section class="catalogue-section">
    <div class="container-fluid pt-100 pb-100">

        <ul class="nav second-pills nav-pills mb-4  align-items-center justify-content-center" id="pills-tab"
            role="tablist">
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
        </ul>

        <!-- <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                <div class="tab-pane fade" id="pills-contact1" role="tabpanel" aria-labelledby="pills-contact-tab1">...</div>
                <div class="tab-pane fade" id="pills-contact2" role="tabpanel" aria-labelledby="pills-contact-tab2">...</div>
                <div class="tab-pane fade" id="pills-contact3" role="tabpanel" aria-labelledby="pills-contact-tab3">...</div>
              </div> -->

        <div class="row  mb-5">
            <div class="col-md-3">
                <div class="catalogue-left-bar">
                    <ul>
                        <li>
                            <img src="assets/images/c1.png" class="catalogue-img">
                            <strong style="cursor:pointer;" onclick="reload()" class="cursor-pointer">
                                <a href="?category_id={{ $selectedCategory->id }}" class="nav-link"> All </a>
                            </strong>
                        </li>
                        @foreach ($subcategories as $subcategory)
                        <li>
                            <img src="/uploads/{{ $subcategory->image }}" class="catalogue-img">
                            {{-- subcategory-link --}}
                            <a href="subcateg?category_id={{ $selectedCategory->id }}&sub_id={{ $subcategory->id }}"
                                class="nav-link" data-subcategory="{{ $subcategory->id }}">{{ $subcategory->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="filters mt-4">
                    <div class="page-wrap">

                        <section>
                            <!-- Button trigger modal-->
                            <div>


                                <ul class="nav mb-4" id="pills-tab" role="tablist">
                                    <li role="presentation" class="d-flex">
                                        <div onclick="toggel('exampleModalScrollable')"
                                            class="category-filter rounded-pill fs-6 shadow-lg p-2 px-3">
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
                                        <div class="type-filter rounded-pill fs-6 shadow-lg p-2 px-3"
                                            onclick="tagSelected('{{ $tag }}', {{ $selectedCategoryId }})">
                                            {{ $tag }}
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
                                <div class="modal-dialog modal-dialog-centered modal-full screen-sm-down vh-100"
                                    role="document">
                                    <div class="modal-content" style="padding: 20px;">
                                        <div class="modal-header">
                                            <h5 class="modal-title filter-brands" id="exampleModalLabel">Filter
                                                Brands</h5>
                                            <a class="close pointer" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                        </div>

                                        @if ($products)
                                        <div class="modal-body">
                                            @php
                                            $uniqueBrands = [];

                                            foreach ($products as $brand) {
                                            if ($brand->brands) {
                                            $brandsArray = explode(',', $brand->brands);
                                            $uniqueBrands = array_merge($uniqueBrands, $brandsArray);
                                            }
                                            }

                                            $uniqueBrands = array_unique($uniqueBrands);
                                            @endphp
                                            @foreach ($uniqueBrands as $individualBrand)
                                            <div class="d-flex justify-content-between gap-4 mb-2">
                                                <label for="{{ $individualBrand }}" class="label">
                                                    {{ $individualBrand }}
                                                </label>
                                                <input id="{{ $individualBrand }}"
                                                    class="form-check-input brand-checkbox" type="checkbox"
                                                    name="brand_list" value="{{ $individualBrand }}"
                                                    onchange="brandList(this.value)">
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        <div class="modal-footer">

                                            <button onclick="filter({{ $selectedCategoryId }})" type="button"
                                                class="btn red-btn font-weight-bold">Apply</button>
                                            <button type="button" class="btn clear-btn font-weight-bold"
                                                data-bs-dismiss="modal">Clear All</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Types --}}

                            <div class="modal fade" id="exampleModalScrollableTypes" tabindex="-1" role="dialog"
                                aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-full screen-sm-down vh-100"
                                    role="document">
                                    <div class="modal-content" style="padding: 20px;">
                                        <div class="modal-header">
                                            <h5 class="modal-title filter-brands" id="exampleModalLabel">Filter Types
                                            </h5>
                                            <a class="close pointer" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                        </div>
                                        @if ($products)
                                        <div class="modal-body">
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
                                            <div class="d-flex justify-content-between gap-4 mb-2">
                                                <label for="{{ $individualTypes }}" class="label">
                                                    {{ $individualTypes }}
                                                </label>
                                                <input id="{{ $individualTypes }}"
                                                    class="form-check-input brand-checkbox" type="checkbox"
                                                    name="type_list" value="{{ $individualTypes }}"
                                                    onchange="typeList(this.value)">
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        <div class="modal-footer">
                                            <button onclick="filterTypeData({{ $selectedCategoryId }})" type="button"
                                                class="btn red-btn">Apply</button>
                                            <button type="button" class="btn clear-btn" data-bs-dismiss="modal">Clear
                                                All</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </section>


                        <div class="products-div">
                            <div class="row gy-4 products-container">
                                @foreach ($products as $product)
                                <div class="col-md-3">
                                    <div class="product-box-4 wow fadeInUp br">
                                        <div class="product-image product-image-2">
                                            <a href="#">
                                                <img src="/uploads/{{ $product->image }}"
                                                    class="img-fluid blur-up lazyload" alt="">
                                            </a>

                                        </div>

                                        <div class="product-detail">


                                            <a href="{{ route('product-details', $product->id) }}">
                                                <h5 class="name text-title">{{ $product->product_name }} </h5>
                                            </a>
                                            <h5 class="price price-p"> Pack of
                                                {{ $product->product_quantity }}
                                            </h5>

                                            <div class="addtocart_btn">


                                                {{-- <div class="modal location-modal fade theme-modal"
                                                            id="locationModal" tabindex="-1"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div
                                                                class="modal-dialog modal-dialog-centered modal-full screen-sm-down vh-100">
                                                                <div class="modal-content" id="mobileBox">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLabel">Enter mobile number
                                                                        </h5>
                                                                        <p class="mt-1 text-content">OTP will be sent
                                                                            to this number for verification</p>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <i class="fa-solid fa-xmark"></i>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="location-list">
                                                                            <div class="search-input">
                                                                                <input oninput="checkNumber()"
                                                                                    type="number" name="mobile"
                                                                                    id="mobile"
                                                                                    class="form-control"
                                                                                    placeholder="Enter Your Number">

                                                                                <input type="text" name="name"
                                                                                    id="name"
                                                                                    class="form-control"
                                                                                    placeholder="Enter Your Name">
                                                                            </div>
                                                                        </div>
                                                                        <button type="button" onclick="sendOtp()"
                                                                            class="btn btn-primary bg-primary">continue</button>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-content d-none" id="otpBox">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLabel">Enter verification
                                                                            code


                                                                        </h5>
                                                                        <p class="mt-1 text-content"> 6 digit OTP has
                                                                            been sent to +91 <span id="mobile_number">
                                                                        </p>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <i class="fa-solid fa-xmark"></i>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="location-list">
                                                                            <div class="search-input">
                                                                                <input type="number" name="otp"
                                                                                    id="otp"
                                                                                    class="form-control"
                                                                                    placeholder="Enter Your OTP">
                                                                            </div>
                                                                        </div>
                                                                        <button type="button" onclick="verifyOtp()"
                                                                            class="btn btn-primary bg-primary">Verify
                                                                            OTP</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                {{-- {{Auth::user()}} --}}
                                                @if (Auth::user())
                                                <div>
                                                    <div class="quantity">
                                                        <div class="row g-3 mt-2 mb-3">
                                                            <div class="col-md-5">
                                                                <div class="box-btn mb-2"
                                                                    onclick="updateQuantity('BOX', {{ $product->id }})">
                                                                    BOX</div>

                                                                <div class="border-red 24-pcs">24 PCS </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="loose-btn mb-2"
                                                                    onclick="updateQuantity('LOOSE', {{ $product->id }})">
                                                                    LOOSE</div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <button onclick="submit()" type="button"
                                                                    class="add-button addcart-button btn buy-button text-light">+
                                                                </button>
                                                            </div>


                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>

                                                    <!-- <button onclick="submit()" type="button"
                                                                    class="add-button addcart-button btn buy-button  buy-button1 text-light">ADD
                                                                    +
                                                                </button> -->
                                                </div>
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

@endsection


<!-- latest jquery-->
<script src="{{ asset('frontweb/assets/js/jquery-3.6.0.min.js') }}"></script>

<!-- jquery ui-->
<script src="{{ asset('frontweb/assets/js/jquery-ui.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


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
let quantityData = '';
let productId = '';

function updateQuantity(productType, Id) {
    quantityData = productType;
    productId = Id;
}

function submit() {
    axios.post('/quotes/add', {
        productType: quantityData,
        product_id: productId
    }).then(
        response => {
            if (response.data) {
                toastr.success('Quotation added successfully');
                location.reload();
            } else {
                toastr.error('Failed Data');
            }
        }
    )
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
    $.ajax({
        url: '/subcat/products/tag/filter',
        method: 'GET',
        data: {
            tag: tag,
            selectedCategoryId: selectedCategoryId,
            sub_category_id: sub_category_id,
        },
        success: function(data) {
            loadProducts(data);
        },
        error: function(error) {
            console.log(error);
        }
    });
}


function filter(selectedCategoryId) {
    var branditems = [];
    $('.brand-checkbox:checked').each(function() {
        branditems.push($(this).val());
    });

    // Convert branditems array to a comma-separated string
    var brandsQueryString = branditems.join(',');

    // Construct the redirect URL
    var redirectUrl = '/subcateg?brand_name=' + brandsQueryString;

    // Redirect the user to the constructed URL
    window.location.href = redirectUrl;
}


function filterTypeData(selectedCategoryId) {
    var selectedTypes = [];
    $('.brand-checkbox:checked').each(function() {
        selectedTypes.push($(this).val());
    });

    $.ajax({
        url: '/subcat/products/filtertype',
        method: 'GET',
        data: {
            selectedTypes: selectedTypes,
            selectedCategoryId: selectedCategoryId,
            type_sub_category_id: type_sub_category_id,
        },
        success: function(data) {
            // Handle success response
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