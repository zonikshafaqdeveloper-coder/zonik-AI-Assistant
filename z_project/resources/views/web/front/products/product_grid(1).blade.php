@if($data->count() == 0 && $offset == 0)
    <div class="col-12 text-center py-5">
        <h4 class="text-muted">No products found</h4>
    </div>
@else
    @foreach ($data as $product)
        @if ($product->image !== '1718876959.jpg')
            <div class="col-md-3 mb-4">
                <div class="product-box-4 br">

                    <div class="product-image product-image-2">
                        <img src="/uploads/{{ $product->image }}" class="img-fluid" alt="">
                    </div>

                    <div class="product-detail">
                        <a @if (Auth::user()) href="{{ route('product-details', $product->id) }}" @endif>
                            <h5 class="name text-title">{{ $product->product_name }}</h5>
                        </a>

                        @if (Auth::user())
                        <div>
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
            <button onclick="submit()" type="button" 
                class="add-button addcart-button btn buy-button text-light"> + </button>
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
@endif
