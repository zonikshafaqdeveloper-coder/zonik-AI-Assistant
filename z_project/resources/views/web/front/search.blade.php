@extends('web.layouts.app')
@section('content')

<style>

.buy-buttonss{
    display: flex;
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
        right: 55px;
    
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


@media (max-width: 768px) {
    .modal.fade .modal-dialog {
        max-height: 90vh; /* Limits the modal height */
        margin: 0 auto; /* Centers the modal */
    }

    .modal-content {
        max-height: fit-content; /* Ensures the content does not exceed modal height */
        overflow-y: auto; /* Enables vertical scrolling if content overflows */
    }

    .filter-name-div {
        max-height: fit-content; /* Restricts the height of the content */
        overflow-y: auto; /* Allows scrolling within this section */
        padding-bottom: 15px; /* Adds some space at the bottom */
    }

    .modal-footer {
        position: sticky; /* Keeps footer fixed at the bottom of the modal */
        bottom: 0;
        background-color: #fff; /* Matches modal background */
        z-index: 10;
        padding: 10px;
    }
}

    
</style>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="my-3 align-items-center justify-content-between">
                <h2 class="">
                    {{ $data->count() }} results for <span class="text-primary">"{{ $searchTerm }}"</span>
                </h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="products-div">
            <div class="row gy-4 products-container">
                @foreach ($data as $product)
                @if ($product->image !== '1718876959.jpg')

                <div class="col-md-3">
                    <div class="product-box-4 wow fadeInUp br">
                        <div class="product-image product-image-2">
                            <a href="#">
                                <img src="/uploads/{{ $product->image }}" class="img-fluid blur-up lazyload" alt="">
                            </a>

                        </div>

                        <div class="product-detail">

                            <a @if (Auth::user()) href="{{ route('product-details', $product->id) }}" @endif>

                                <h5 class="name text-title">{{ $product->product_name }} </h5>
                            </a>

                            <h5 class="price price-p"> Pack of {{ $product->product_quantity }}</h5>
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
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>

// $(document).ready(function () {
//     $('.search_list').hide();

//     $('.search').on('keyup', function (event) {
//         var query = $(this).val().trim(); // Trim whitespace from the query

//         if (event.key === "Enter") {
//             window.location.href = "search/" + query;
//             return;
//         }

//         searchQuery(query);
//     });

//     function searchQuery(query) {
//         if (query.length <= 0) {
//             $('.search_list').hide();
//             return;
//         }

//         switch (true) {
//             case query.length >= 3:
//                 executeSearch(query.slice(-3));
//                 break;
//             case query.length == 2:
//                 executeSearch(query.slice(-2));
//                 break;
//             case query.length == 1:
//                 executeSearch(query);
//                 break;
//             default:
//                 $('.search_list').hide();
//         }
//     }

//     function executeSearch(searchQuery) {
//         $.ajax({
//             url: "search", // Make sure this URL is correct for your backend search endpoint
//             type: "GET",
//             data: {
//                 'search': searchQuery
//             },
//             success: function (data) {
//                 if (data == 'No results') {
//                     searchFallback(searchQuery);
//                 } else {
//                     $('.search_list').show();
//                     $('.search_list').html(data);
//                 }
//             }
//         });
//     }

//     function searchFallback(query) {
//         var fallbackQuery = query.slice(0, -1);
//         if (fallbackQuery.length > 0) {
//             executeSearch(fallbackQuery);
//         } else {
//             $('.search_list').html('No results');
//             $('.search_list').show();
//         }
//     }
// });


</script>


@endsection
