<!-- resources/views/subcategories.blade.php -->
@foreach($subcategories as $subcategory)
    <a href="{{ route('home.products', ['subcategory' => $subcategory->id]) }}" class="subcategory-link">{{ $subcategory->name }}</a>
@endforeach
