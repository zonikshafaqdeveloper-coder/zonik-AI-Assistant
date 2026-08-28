@extends('web.layout.app')

@section('content')
    <h1>Your Quote List</h1>

    @if ($quoteItems->isEmpty())
        <p>Your quote list is empty.</p>
    @else
        <ul class="quote-list">
            @foreach ($quoteItems as $quoteItem)
                <li>
                    <h3>{{ $quoteItem->product->product_name }}</h3>
                    <h3>{{ $quoteItem->product->product_quantity }}</h3>
                 
                </li>
            @endforeach
        </ul>

        <form action="{{ route('quotes.submit') }}" method="POST">
            @csrf
            <button type="submit">Submit Quote Request</button>
        </form>
    @endif
@endsection
