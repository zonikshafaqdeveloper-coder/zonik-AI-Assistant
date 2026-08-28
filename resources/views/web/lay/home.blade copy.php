<!-- resources/views/home.blade.php -->
@extends('web.lay.app')

@section('content')


<div class="brand-category-tabs">
    <ul class="nav nav-tabs">
        @foreach($brandCategories as $brandCategory)
            <li class="nav-item">
                <a href="#" class="nav-link brand-category-link" data-brand-category="{{ $brandCategory->brand_id }}">{{ $brandCategory->name }}</a>
            </li>
        @endforeach
    </ul>
</div>




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
        <div class="products-container">
            @foreach($products as $product)
                <div class="product">
                <a href="#"> {{ $product->name }} </a>
                </div>
            @endforeach
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
    function loadAllProducts() {
        var productsHTML = '';
        @foreach($products as $product)
            productsHTML += '<div class="product">{{ $product->product_name }}</div>';
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



    ///brand category fetch

    $('.brand-category-link').on('click', function(e) {
        e.preventDefault();
        
        var brandCategoryId = $(this).data('brand-category');
        
        $.ajax({
            url: '/brandcategories/' + brandCategoryId + '/products',
            method: 'GET',
            success: function(data) {
                $('.products-container').html(data);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>
@endsection


