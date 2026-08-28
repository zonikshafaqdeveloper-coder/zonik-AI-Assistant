<style>
    .buy-buttonss{
    display: flex
;
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
    /*background-color: #a558c8;*/
    width: 30px;
    font-size: 25px;
    color: #fff;
    /*margin-top: -37px;*/
    border-radius: 50px;
}

@media (max-width: 768px) {

    .buy-buttonss{
        width: 25px;
        font-size: x-large;
        margin-left: 1rem;
        /* margin-top: 0px; */
        height: 24px;
        position: absolute;
        border-radius: 50%;
        right: 25px;
    
    }   

.boxbtncss{
    font-size: 10px;
    width: 45px;
    gap: 10px;
}
.loosbtncss {
font-size: 10px;
width: 57px;
margin-left: 3.5rem;
margin-top: -1.80rem;
}
}
</style>



<div class="products-div">
    <div class="row gy-4">
        @foreach ($products as $product)
        <div class="col-md-3">
            <div class="product-box-4 br">
                <div class="product-image product-image-2">
                    <a href="#">
                        <img src="/uploads/{{ $product->image }}" class="img-fluid blur-up lazyload" alt="">
                    </a>
                </div>

                <div class="product-detail">
                    <a @if (Auth::user()) href="{{ route('product-details', $product->id) }}" @endif>
                        <h5 class="name text-title">{{ $product->product_name }}</h5>
                    </a>
                    <!-- <h5 class="price price-p">Pack of {{ $product->product_quantity }}</h5> -->
                    @if (Auth::user())
                    <div>
                    <input type="hidden" name="quantity" id="quantityInput">
                        <div class="quantity product-{{ $product->id }}">
                            <div class="row g-1 mt-2 mb-3">
                                <div class="col-md-5 col-sm-5">
                                    <div class="box-btn boxbtncss" onclick="updateQuantity('BOX', {{ $product->id }})">BOX
                                    </div>
                                    <div class="border-red pcs-box pcs-{{ $product->id }}" style="display:none;">
                                                                        {{ $product->carton_size ?? 0 }} PCS
                                                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="loose-btn loosbtncss" onclick="updateQuantity('LOOSE', {{ $product->id }})">LOOSE
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-5">
                                    <button onclick="submit({{ $product->id }})" type="button"
                                        class="add-button addcart-button btn buy-buttonss">+
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.product-box-4').find('.24-pcs').hide(); // Hide all .24-pcs initially

        $('.box-btn').on('click', function() {
            $('.box-btn, .loose-btn').removeClass('active').css({
                'background-color': '',
                'color': '#000'
            });
            $(this).addClass('active').css({
                'background-color': '#652A80',
                'color': '#ffff'
            });
            // Show .24-pcs only if its parent product-box-4 has .active box-btn
            $(this).closest('.product-box-4').find('.24-pcs').show();
            $('.product-box-4').not($(this).closest('.product-box-4')).find('.24-pcs').hide();
        });

        $('.loose-btn').on('click', function() {
            $('.box-btn, .loose-btn').removeClass('active').css({
                'background-color': '',
                'color': '#000'
            });
            $(this).addClass('active').css({
                'background-color': '#652A80',
                'color': '#ffff'
            });
            // Hide all .24-pcs when loose-btn is clicked
            $('.product-box-4').find('.24-pcs').hide();
        });
    });
</script>
