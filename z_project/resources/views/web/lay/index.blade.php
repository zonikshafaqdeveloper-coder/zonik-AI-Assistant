<!-- categories.blade.php -->
@foreach($categories as $category)
    <a href="{{ route('home1', ['category_id' => $category->id, 'brand_id' => $category->brand_id]) }}" class="category-link">{{ $category->category_name }}</a>
@endforeach
