$(document).ready(function() {
    // Function to fetch subcategories based on category selection
    function getSubcategories(categoryId) {
        $.get(`/categsub?category_id=${categoryId}`, function(data) {
            $('#subcategoryList').empty();
            data.forEach(function(subcategory) {
                $('#subcategoryList').append(`<li><a class="subcategory-link" data-subcategory="${subcategory.id}">${subcategory.name}</a></li>`);
            });
        });
    }

    $(document).on('click', '.category-link', function(e) {
        e.preventDefault();
        let categoryId = $(this).data('category');
        let subcategoryList = $(`.categsub[data-category=${categoryId}]`);
        getSubcategories(categoryId, subcategoryList);
    });






    // Function to fetch brands based on subcategory selection
    function getBrands(subcategoryId) {
        $.get(`/brandscateg?subcategory_id=${subcategoryId}`, function(data) {
            $('#brandList').empty();
            data.forEach(function(brand) {
                $('#brandList').append(`<li><input type="checkbox" class="brand-checkbox" value="${brand.id}" data-brand="${brand.id}">${brand.name}</li>`);
            });
        });
    }

    // Function to filter products based on selected subcategories and brands
    function filterProducts(subcategoryIds, brandIds) {
        $.get(`/filter-products-categ?subcategory_ids=${subcategoryIds}&brand_ids=${brandIds}`, function(data) {
            $('#productList').empty();
            data.forEach(function(product) {
                $('#productList').append(`<li>${product.name}</li>`);
            });
        });
    }

    // Event listener for subcategory selection
    $(document).on('click', '.subcategory-link', function() {
        let subcategoryId = $(this).data('subcategory');
        getBrands(subcategoryId);
    });

    // Event listener for brand selection
    $(document).on('change', '.brand-checkbox', function() {
        let subcategoryIds = $('.subcategory-link').map(function() {
            return $(this).data('subcategory');
        }).get();
        let brandIds = $('.brand-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        filterProducts(subcategoryIds, brandIds);
    });

    // Initial load of subcategories (e.g., for the default category)
    getSubcategories(DEFAULT_CATEGORY_ID);
});







