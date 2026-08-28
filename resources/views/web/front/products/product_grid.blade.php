@if($data->count() == 0 && $offset == 0)
    <div class="col-12 text-center py-5">
        <h4 class="text-muted">No products found</h4>
    </div>
@else
    @foreach ($data as $product)
        @if ($product->image !== '1718876959.jpg')
            <div class="col-md-3 mb-4">
                <div class="product-box-4 br">

                    {{-- DESKTOP IMAGE --}}
                    <div class="product-image product-image-2 d-none d-md-block">
                        <img src="/uploads/{{ $product->image }}" class="img-fluid" alt="">
                    </div>

                    {{-- DESKTOP PRODUCT DETAILS --}}
                    <div class="product-detail d-none d-md-block">
                        <a @if(Auth::user()) href="{{ route('product-details', $product->id) }}" @endif>
                            <h5 class="name text-title">{{ $product->product_name }}</h5>
                        </a>

                        @if (Auth::user())
                        <div class="d-none d-md-block">
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

                                    <div class="col-md-2">
                                        <button onclick="submit({{ $product->id }})" type="button"
                                            class="add-button addcart-button btn buy-button">
                                            +
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                    </div>



               
                 
@if (Auth::user())
<div class="d-flex d-md-none mt-2 mobile-row">

    {{-- PRODUCT NAME — AUTO-WRAP WHEN LONG --}}
    <div class="product-name-mobile flex-grow-1">
        <a @if(Auth::user()) href="{{ route('product-details', $product->id) }}" @endif>
        <h6 class="mb-0 product-title-mobile">{{ $product->product_name }}</h6>
        </a>
    </div>

    {{-- LOOSE --}}
    <div class="ms-2">
        <div class="loose-btn loosbtncss mobile-btn"
             onclick="updateQuantity('LOOSE', {{ $product->id }})">
            LOOSE
        </div>
    </div>

      {{-- BOX --}}
      <div class="ms-2">
        <div class="box-btn boxbtncss mobile-btn"
             onclick="updateQuantity('BOX', {{ $product->id }})">
            BOX
        </div>
        
         <div class="border-red pcs-box pcs-{{ $product->id }}" style="display:none;">
                                            {{ $product->carton_size ?? 0 }} PCS
                                        </div>
    </div>

    {{-- + BUTTON --}}
    <div class="ms-2">
        <button onclick="submit({{ $product->id }})" type="button"
                class="add-button addcart-button btn buy-button mobile-btn">
            +
        </button>
    </div>
</div>
@endif




                </div>
            </div>
        @endif
    @endforeach
@endif
