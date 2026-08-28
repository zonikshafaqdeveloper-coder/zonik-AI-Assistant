<!-- Display Categories -->
<ul id="categoryList">
    @foreach ($categories as $category)
        <li>
            <a href="#" class="category-link" data-category="{{ $category->id }}">{{ $category->category_name }}</a>
            <ul class="subcategories" data-category="{{ $category->id }}"></ul>
        </li>
    @endforeach
</ul>


<ul id="subcategoryList"></ul>

<!-- Display Brands -->
<ul id="brandList"></ul>

<!-- Display Filtered Products -->
<ul id="productList"></ul>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include JavaScript -->
<script src="{{ asset('js/categories.js') }}"></script>
 