@if (count($Subcategories))
    @foreach ($Subcategories as $Subcategory)
        <div class="col-md-2">

            <a  @if (Auth::user()) href="{{ route('subcateg') }}?category_id={{$Subcategory->category_id}}&sub_id={{$Subcategory->id}}" @endif>

                <div class="product-div text-center">
                    <img src="uploads/{{ $Subcategory->image }}" class="product-img" style="height: 60px">
                    <h5 class="pt-3" style="color: #942525;font-size:18px;height:50px">{{ $Subcategory->name }}</h5>
                </div>
            </a>
        </div>
    @endforeach
@else
    <h5 class="text-center" style="color: red">No Sub category avaliable..</h5>
@endif
