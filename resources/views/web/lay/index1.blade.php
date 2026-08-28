@extends('web.lay.app')

@section('content')
    <h1>Products</h1>

    @foreach ($products as $product)
        <div class="product">
            <h3>{{ $product->product_name }}</h3>
            <p>{{ $product->product_quantity }}</p>
            <form action="{{ route('quotes.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit">Request Quote</button>
            </form>
        </div>
    @endforeach
@endsection
