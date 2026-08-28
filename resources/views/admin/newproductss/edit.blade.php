@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
    <!-- partial -->
    <div class=" page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper container">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Edit Product</h5>

                                <!-- <form action="{{ route('productss.store') }}" method="POST" enctype="multipart/form-data"> -->
                                <form class="theme-form theme-form-2 mega-form"
                                    action="{{ route('productss.update', $product->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title mb-1">Product Name</label>

                                            <input class="form-control" type="text" name="product_name"
                                                value="{{ $product->product_name }}" placeholder="Product Name">

                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Product quantity</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="product_quantity"
                                                    value="{{ $product->product_quantity }}"
                                                    placeholder="Product quantity">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Product MRP</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="product_mrp"
                                                    value="{{ $product->product_mrp }}" placeholder="Product MRP">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Product Offer Price</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="offer_price"
                                                    value="{{ $product->offer_price }}"
                                                    placeholder="Product Offer Price">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title mb-1">Product Discount</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="discount"
                                                    value="{{ $product->discount }}" placeholder="Product Discount">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class=" col-form-label form-label-title mb-1">Subcategory
                                                Name</label>
                                            <div class="">
                                                <select class="js-example-basic-single w-100" name="subcategory_id"
                                                    id="category_id" value="">
                                                    @foreach ($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}" @if($subcategory->id ==
                                                        $product->subcategory_id) selected
                                                        @endif>{{ $subcategory->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-4 col-md-12 align-items-center">
                                            <label class="form-label-title  mb-1">Description</label>
                                            <div class="">
                                                <textarea class="form-control h-20"
                                                    style="height: 60px;line-height:20px;" type="text"
                                                    name="description" value=""
                                                    placeholder="Description">{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class=" col-form-label form-label-title mb-1">Category Name</label>
                                            <div class="">
                                                <select class="js-example-basic-single w-100" name="category_id"
                                                    id="category_id" value="">
                                                    @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @if($category->id ==
                                                        $product->category_id) selected
                                                        @endif>{{ $category->category_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Unit -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Unit</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="unit"
                                                    value="{{ $product->unit }}" placeholder="Unit">
                                            </div>
                                        </div>

                                        <!-- Pieces Per Pack -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Pieces Per Pack</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="peices_per_pack"
                                                    value="{{ $product->peices_per_pack }}"
                                                    placeholder="Pieces Per Pack">
                                            </div>
                                        </div>

                                        <!-- Carton Size -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title mb-1">Carton Size</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="carton_size"
                                                    value="{{ $product->carton_size }}" placeholder="Carton Size">
                                            </div>
                                        </div>

                                        <!-- Varieties -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Varieties</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="varieties"
                                                    value="{{ $product->varieties }}" placeholder="Varieties">
                                            </div>
                                        </div>

                                        <!-- Cost Per Item -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Cost Per Item</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="cost_per_item"
                                                    value="{{ $product->cost_per_item }}" placeholder="Cost Per Item">
                                            </div>
                                        </div>

                                        <!-- HSN -->
                                       <div class="mb-4 col-md-4 align-items-center">
                                       <label class="form-label-title mb-1">HSN Code</label>
                                        <input class="form-control" type="text" name="hsn_code"
                                         value="{{ $product->hsn_code }}" placeholder="HSN Code">
                                        </div>


                                        <!-- GST -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">GST</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="gst"
                                                    value="{{ $product->gst }}" placeholder="GST">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">sgst</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="sgst"
                                                    value="{{ $product->sgst }}" placeholder="sgst">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">cgst</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="cgst"
                                                    value="{{ $product->cgst }}" placeholder="cgst">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">igst</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="igst"
                                                    value="{{ $product->igst }}" placeholder="igst">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">cess</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="cess"
                                                    value="{{ $product->cess }}" placeholder="cess">
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Total with Tax</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="total_with_tax"
                                                    value="{{ $product->total_with_tax }}" placeholder="Total with Tax">
                                            </div>
                                        </div>

                                        <!-- Sale Price Loose Pcs -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Sale Price Loose Pcs</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="sale_price_loose_pcs"
                                                    value="{{ $product->sale_price_loose_pcs }}"
                                                    placeholder="Sale Price Loose Pcs">
                                            </div>
                                        </div>

                                        <!-- Sale Price Carton -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Sale Price Carton</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="sale_price_carton"
                                                    value="{{ $product->sale_price_carton }}"
                                                    placeholder="Sale Price Carton">
                                            </div>
                                        </div>

                                        <!-- Product Weight (in grams) -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Product Weight (grams)</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="product_weight_grams"
                                                    value="{{ $product->product_weight_grams }}"
                                                    placeholder="Product Weight (grams)">
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title mb-1">Status</label>
                                            <div class="">
                                                <select class="form-control" name="status">
                                                    <option value="active"
                                                        {{ $product->status === 'active' ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="inactive"
                                                        {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title mb-1">Vendor</label>
                                        
                                            {{-- Dropdown --}}
                                            <select class="form-control" name="vendor_id">
                                                <option value="">Select Vendor</option>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}"
                                                        {{ $product->vendor_id == $vendor->id ? 'selected' : '' }}>
                                                        {{ $vendor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        
                                            {{-- OR Create New --}}
                                            <input type="text" 
                                                name="new_vendor" 
                                                class="form-control mt-2"
                                                placeholder="Or enter new vendor">
                                        </div>

                                        <!-- Types -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Type</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="types"
                                                    value="{{ $product->types }}" placeholder="Types">

                                                {{-- <select class="js-example-basic-single w-100" name="type_id"  id="category_id" value="">
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}" @if($type->id ==
                                                $product->type_id) selected @endif>{{ $type->type_name }}
                                                </option>
                                                @endforeach
                                                </select> --}}
                                            </div>
                                        </div>

                                        <!-- Tags -->
                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Tag</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="tags"
                                                    value="{{ $product->tags }}" placeholder="Tags">
                                                {{-- <select class="js-example-basic-single w-100" name="tag_id"  id="category_id" value="">
                                                    @foreach ($tags as $tag)
                                                        <option value="{{ $tag->id }}" @if($tag->id ==
                                                $product->tag_id) selected @endif>{{ $tag->tag_name }}
                                                </option>
                                                @endforeach
                                                </select> --}}
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class=" col-form-label form-label-title mb-1">Brand</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="brands"
                                                    value="{{ $product->brands }}" placeholder="Drands">
                                                {{-- <select class="js-example-basic-single w-100" name="brands" id="brand_id">
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}" @if($brand->id ==
                                                $product->brand_id) selected @endif>{{ $brand->name }}
                                                </option>
                                                @endforeach
                                                </select> --}}
                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title mb-1">Product Image(500px X 500px)</label>
                                            <div class="">
                                                <input class="form-control" type="file" name="image" id="image"
                                                    accept="image/*" placeholder="Product Image">
                                                <img src="/uploads/{{ $product->image }}" class="p-im">

                                            </div>
                                        </div>

                                        <div class="mb-4 col-md-4 align-items-center">
                                            <label class="form-label-title  mb-1">Product Slug</label>
                                            <div class="">
                                                <input class="form-control" type="text" name="product_slug"
                                                    value="{{ $product->slug }}" placeholder="Product slug">
                                            </div>
                                        </div>
                                    </div>
                                    
                                                                        <div class="col-md-8 mt-4">
                                        <h5>Product Units</h5>

                                        <div id="units-wrapper">

                                            @foreach($product->units as $unit)

                                            <div class="row mb-2 unit-row">

                                                <div class="col-md-10">
                                                    <input type="text" name="units[]" class="form-control"
                                                        value="{{ $unit->unit_name }}"
                                                        placeholder="Example: 500 ml">
                                                </div>

                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger remove-unit">
                                                        Remove
                                                    </button>
                                                </div>

                                            </div>

                                            @endforeach

                                        </div>

                                        <button type="button" id="add-unit" class="btn btn-primary mt-2">
                                            + Add Unit
                                        </button>
                                    </div>

                                    <button type="submit" name="submit"
                                        class="btn btn-primary ms-auto mt-4">Save</button>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>

document.getElementById('add-unit').addEventListener('click', function(){

    let html = `
        <div class="row mb-2 unit-row">
            <div class="col-md-10">
                <input type="text" name="units[]" class="form-control"
                       placeholder="Example: 1 Kg">
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-unit">
                    Remove
                </button>
            </div>
        </div>
    `;

    document.getElementById('units-wrapper')
        .insertAdjacentHTML('beforeend', html);

});

document.addEventListener('click', function(e){

    if(e.target.classList.contains('remove-unit')){
        e.target.closest('.unit-row').remove();
    }

});

</script>

    <!-- New Product Add End -->
    @endsection
