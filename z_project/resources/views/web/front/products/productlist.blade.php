@extends('web.layouts.app')
@section('content')

<style>


#brandModal .modal-body {
    padding: 10px !important;
    background: #f0f0f0;
    margin-top: 15px;
    border-radius: 24px;
    margin-bottom: 15px;
}

#brandModal .modal-title {
    color: #121286;
    font-size: 25px;
    font-weight: 500;
}

#brandModal .modal-content {
    padding: 20px 40px 20px 40px !important;
    border-radius: 24px !important;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}

#brandModal .row {
    margin-right: 0 !important;
    margin-left: 0 !important;
}


#categoryModal .modal-body {
    padding: 10px !important;
    background: #f0f0f0;
    margin-top: 15px;
    border-radius: 24px;
    margin-bottom: 15px;
}

#categoryModal .modal-title {
    color: #121286;
    font-size: 25px;
    font-weight: 500;
}

#categoryModal .modal-content {
    padding: 20px 40px 20px 40px !important;
    border-radius: 24px !important;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}

#categoryModal .row {
    margin-right: 0 !important;
    margin-left: 0 !important;
}


    @media (max-width: 576px) {
    .modal-fullscreen-sm-down .modal-content {
        border-radius: 0;
    }
    .modal-header, .modal-footer {
        padding: 12px;
    }
    .brand-list {
        max-height: 250px !important;
    }

    #brandModal .modal-content {
     padding: 4px 12px 46px 12px !important;
    }
     #categoryModal .modal-content {
     padding: 4px 12px 46px 12px !important;
  }
}

.modal-footer .btn {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    padding: 10px;
    font-size: 14px;
}

.w-33 {
    width: 32%;
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
    
    .pcs-box {
        display: none;
    }
@media (max-width: 768px) {

    .mobile-row {
        display: flex;
        align-items: flex-start;
    }

    .product-name-mobile {
        min-width: 0;
        max-width: 60%;
        white-space: normal !important;   
        text-align: left !important;
    }

    .product-title-mobile {
        font-size: 12px;
        line-height: 16px;
        white-space: normal !important;   
        overflow: visible;
        display: block;   
        color:#000; 
        text-align: left !important;
    }

    .mobile-btn {
        flex-shrink: 0;
        padding: 3px 6px;
        font-size: 11px;
        border-radius: 5px;
        white-space: nowrap;
    }

    .buy-button {
        width: 28px !important;
        height: 28px !important;
        line-height: 26px !important;
        font-size: 18px !important;
        padding: 0;
        border-radius: 50%;
        flex-shrink: 0;
        border: 1px solid #e97457;
        background-color: #e97457;
        color:#fff;
    }

    .border-red {
        margin-top: 0px;
    }
     
    .pcs-box {
        display: none;
    }

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



</style>

<div class="container mt-3 mb-5">

<div class="d-flex justify-content-end mb-3 gap-2">
    <button type="button" class="btn px-4" style="background-color: #121286; color: #fff;   border-radius: 9px;"data-bs-toggle="modal" data-bs-target="#brandModal">
        Brands
    </button>


       <button type="button" class="btn px-4"
            style="background-color:#e97457;color:#fff;border-radius:9px;"
            data-bs-toggle="modal" data-bs-target="#categoryModal">
        Categories
    </button>

</div>

<form class="mb-4" id="searchForm">
    <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <span class="input-group-text bg-white border-0">
            <i class="fa fa-search text-secondary"></i>
        </span>

        <input type="text" id="searchInput" name="search" value="{{ $searchTerm }}"
            class="form-control border-0" placeholder="Search products...">

        <button class="btn px-4" style="background-color: #121286; color:#fff;" type="submit">
            Search
        </button>
    </div>
</form>

<div id="productContainer">
    <div class="row" id="productRow">
        @include('web.front.products.product_grid', ['data' => $data])
    </div>
</div>


<div id="loader" class="text-center mt-3" style="display:none;">
    <img src="/assets/Loading_2.gif" width="50">
</div>


<!-- BRAND FILTER MODAL -->
<div class="modal fade" id="brandModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md modal-fullscreen-sm-down">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Filter by Brands</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0 m-0">
                    @foreach($allBrands as $brand)
                        <div class="col-6 col-md-3 p-2">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input brand-checkbox" value="{{ $brand }}">
                                <span class="form-check-label">{{ $brand }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- BUTTONS ON RIGHT SIDE -->
            <div class="modal-footer justify-content-end gap-2">

                <!-- CLEAR ALL BUTTON -->
               <button type="button" id="clearBrandFilter"
                class="btn px-4"
                style="background-color: #121286; color: #fff;">
            Clear All
        </button>

        <button type="button" id="applyBrandFilter"
                class="btn px-4"
                style="background-color: #e97457; color: #fff;">
            Apply
       </button>


            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md modal-fullscreen-sm-down">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Filter by Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0 m-0">
                    @foreach($allCategories as $id => $catName)
                        <div class="col-6 col-md-3 p-2">
                            <label class="form-check">
                                <input type="checkbox"
                                       class="form-check-input category-checkbox"
                                       value="{{ $id }}">
                                <span class="form-check-label">{{ $catName }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" id="clearCategoryFilter"
                        class="btn px-4"
                        style="background-color:#121286;color:#fff;">
                    Clear All
                </button>

                <button type="button" id="applyCategoryFilter"
                        class="btn px-4"
                        style="background-color:#e97457;color:#fff;">
                    Apply
                </button>
            </div>

        </div>
    </div>
</div>



</div>


<script>
let offset = 20;
let loading = false;
let searchTerm = $("#searchInput").val();
let hasMore = true;
let selectedBrands = [];
let selectedCategories = [];

// 🔍 Live Search
$("#searchInput").on('keyup', function () {
    performNewSearch();
});

// 🔍 Search Button
$("#searchForm").on("submit", function(e){
    e.preventDefault();
    performNewSearch();
});

$("#applyBrandFilter").on("click", function () {
    selectedBrands = [];

    $(".brand-checkbox:checked").each(function () {
        selectedBrands.push($(this).val());
    });

    $("#brandModal").modal("hide");

    performNewSearch(); // Reset + reload
});

$("#applyCategoryFilter").on("click", function () {

    selectedCategories = [];

    $(".category-checkbox:checked").each(function () {
        selectedCategories.push($(this).val());
    });

    $("#categoryModal").modal("hide");

    performNewSearch();
});


function performNewSearch() {
    searchTerm = $("#searchInput").val();
    offset = 0;
    hasMore = true;

    // Re-attach scroll listener (for new search)
    $(window).off('scroll', scrollHandler);
    $(window).on('scroll', scrollHandler);

    loadProducts(true);
}

// ♾ Scroll handler
function scrollHandler() {
    if (loading || !hasMore) return;

    let scrollPosition = $(window).scrollTop() + $(window).height();
    let productBottom = $('#productContainer').offset().top + $('#productContainer').outerHeight();

    if (scrollPosition >= productBottom - 200) {
        loadProducts(false);
    }
}

function loadProducts(clearHtml = false) {
    loading = true;
    $("#loader").show();

    $.ajax({
        url: "{{ route('productlist') }}",
        type: "GET",
        data: {
            search: searchTerm,
            offset: offset,
             brands: selectedBrands.join(","),
             categories: selectedCategories.join(",") 
        },
success: function (res) {

    if (clearHtml) {
        $("#productRow").html(res.html);

        // Show "No products found" ONLY on new search
        if (res.empty) {
            hasMore = false;
            $("#loader").hide();
            $(window).off('scroll', scrollHandler);
            return;
        }

    } else {

        // Append products correctly
        if (res.count > 0) {
            $("#productRow").append(res.html);
        }

        // No more data on scroll
        if (res.no_more) {
            hasMore = false;
            $("#loader").hide();
            $(window).off('scroll', scrollHandler);
            return;
        }
    }

    if (res.count > 0) {
        offset += res.count;
    } else {
        hasMore = false;
        $(window).off('scroll', scrollHandler);
    }

    loading = false;
    $("#loader").hide();
}




    });
}

$("#clearBrandFilter").on("click", function () {
    $(".brand-checkbox").prop("checked", false);

    selectedBrands = [];

    $("#brandModal").modal("hide");

    // Reload all products (no filter)
    performNewSearch();
});

$("#clearCategoryFilter").on("click", function () {
    $(".category-checkbox").prop("checked", false);
    selectedCategories = [];
    $("#categoryModal").modal("hide");
    performNewSearch();
});


// Attach scroll only on initial page load
$(window).on('scroll', scrollHandler);
</script>



@endsection
