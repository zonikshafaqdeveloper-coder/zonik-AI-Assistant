@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
   <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper ">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <h5 class="card-title">Add Product</h5>
                                   


                                  
<form class="theme-form theme-form-2 mega-form" action="{{ route('productss.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Product Name --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">Product Name <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <input class="form-control @error('product_name') is-invalid @enderror" 
                        type="text" 
                        name="product_name"
                        value="{{ old('product_name') }}"
                        placeholder="Product Name" 
                        required>
                    @error('product_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Unique Reference ID --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">Unique Reference Id <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <input class="form-control @error('unique_reference_id') is-invalid @enderror" 
                        type="text" 
                        name="unique_reference_id"
                        value="{{ old('unique_reference_id') }}"
                        placeholder="Unique Reference Id" 
                        required>
                    @error('unique_reference_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- HSN Code --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">HSN Code</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control @error('hsn_code') is-invalid @enderror" 
                        type="text" 
                        name="hsn_code"
                        value="{{ old('hsn_code') }}"
                        placeholder="HSN Code">
                    @error('hsn_code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Product Quantity --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">Product Quantity</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control @error('product_quantity') is-invalid @enderror" 
                        type="number" 
                        name="product_quantity"
                        step="0.01"
                        value="{{ old('product_quantity') }}"
                        placeholder="Product Quantity">
                    @error('product_quantity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Product MRP --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">Product MRP <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <input class="form-control @error('product_mrp') is-invalid @enderror" 
                        type="number" 
                        name="product_mrp"
                        step="0.01"
                        value="{{ old('product_mrp') }}"
                        placeholder="Product MRP" 
                        required>
                    @error('product_mrp')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Product Slug --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">Product Slug <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <input class="form-control @error('slug') is-invalid @enderror" 
                        type="text" 
                        name="slug"
                        value="{{ old('slug') }}"
                        placeholder="product-slug" 
                        >
                    @error('slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Category Name --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">Category <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                   {{-- Category Name --}}
        <select class="js-example-basic-single w-100 form-select @error('category_id') is-invalid @enderror" 
            name="category_id"
            id="category_id" 
            required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->category_name }}
                </option>
            @endforeach
        </select>
                    @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Sub Category Name --}}
        <div class="col-md-6">
            <div class="mb-4 row align-items-center">
                <div class="col-md-4">
                    <label class="form-label-title mb-0">Sub Category <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <select class="js-example-basic-single w-100 form-select @error('subcategory_id') is-invalid @enderror" 
                        name="subcategory_id"
                        id="subcategory_id" 
                        required>
                        <option value="">Select Subcategory</option>
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" 
                                {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subcategory_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="col-md-12">
            <div class="mb-4 row align-items-center">
                <div class="col-md-2">
                    <label class="form-label-title mb-0">Description</label>
                </div>
                <div class="col-md-10">
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                        style="height: 150px;" 
                        name="description"
                        placeholder="Product Description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Unit --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Unit</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('unit') is-invalid @enderror" 
                        type="text" 
                        name="unit"
                        value="{{ old('unit') }}" 
                        placeholder="kg/pcs/ltr">
                    @error('unit')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Pieces Per Pack --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Pieces Per Pack</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('peices_per_pack') is-invalid @enderror" 
                        type="number" 
                        name="peices_per_pack"
                        value="{{ old('peices_per_pack', 1) }}" 
                        placeholder="Pieces Per Pack">
                    @error('peices_per_pack')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Carton Size --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Carton Size</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('carton_size') is-invalid @enderror" 
                        type="number" 
                        name="carton_size"
                        step="0.01"
                        value="{{ old('carton_size') }}" 
                        placeholder="Carton Size"
                        required>
                    @error('carton_size')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Varieties --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Varieties</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('varieties') is-invalid @enderror" 
                        type="text" 
                        name="varieties"
                        value="{{ old('varieties') }}" 
                        placeholder="Varieties">
                    @error('varieties')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Cost Per Item --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Cost Per Item</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('cost_per_item') is-invalid @enderror" 
                        type="number" 
                        name="cost_per_item"
                        step="0.01"
                        value="{{ old('cost_per_item') }}" 
                        placeholder="Cost Per Item">
                    @error('cost_per_item')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- GST --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">GST (%)</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('gst') is-invalid @enderror" 
                        type="text" 
                        name="gst"
                        step="0.01"
                        value="{{ old('gst') }}" 
                        placeholder="GST">
                    @error('gst')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- SGST --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">SGST (%)</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('sgst') is-invalid @enderror" 
                        type="text" 
                        name="sgst"
                        step="0.01"
                        value="{{ old('sgst') }}" 
                        placeholder="SGST">
                    @error('sgst')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- CGST --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">CGST (%)</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('cgst') is-invalid @enderror" 
                        type="text" 
                        name="cgst"
                        step="0.01"
                        value="{{ old('cgst') }}" 
                        placeholder="CGST" required >
                    @error('cgst')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- IGST --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">IGST (%)</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('igst') is-invalid @enderror" 
                        type="text" 
                        name="igst"
                        step="0.01"
                        value="{{ old('igst') }}" 
                        placeholder="IGST">
                    @error('igst')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- CESS --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">CESS</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('cess') is-invalid @enderror" 
                        type="text" 
                        name="cess"
                        step="0.01"
                        value="{{ old('cess') }}" 
                        placeholder="CESS">
                    @error('cess')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Total with Tax --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Total with Tax</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('total_with_tax') is-invalid @enderror" 
                        type="text" 
                        name="total_with_tax"
                        step="0.01"
                        value="{{ old('total_with_tax') }}" 
                        placeholder="Total with Tax">
                    @error('total_with_tax')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Sale Price Loose Pcs --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Sale Price Loose Pcs <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('sale_price_loose_pcs') is-invalid @enderror" 
                        type="number" 
                        name="sale_price_loose_pcs"
                        step="0.01"
                        value="{{ old('sale_price_loose_pcs') }}"
                        placeholder="Sale Price Loose Pcs"
                        required>
                    @error('sale_price_loose_pcs')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Sale Price Carton --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Sale Price Carton</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('sale_price_carton') is-invalid @enderror" 
                        type="number" 
                        name="sale_price_carton"
                        step="0.01"
                        value="{{ old('sale_price_carton') }}"
                        placeholder="Sale Price Carton">
                    @error('sale_price_carton')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Product Weight --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Product Weight (grams)</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('product_weight_grams') is-invalid @enderror" 
                        type="number" 
                        name="product_weight_grams"
                        step="0.01"
                        value="{{ old('product_weight_grams') }}"
                        placeholder="Product Weight (grams)"
                        required >
                    @error('product_weight_grams')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Total Discount --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Total Discount</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('total_discount') is-invalid @enderror" 
                        type="text" 
                        name="total_discount"
                        value="{{ old('total_discount') }}"
                        placeholder="Total Discount">
                    @error('total_discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Supplier Traced --}}
        <!--<div class="col-md-4">-->
        <!--    <div class="mb-4 row align-items-center">-->
        <!--        <div class="col-md-6">-->
        <!--            <label class="form-label-title mb-0">Supplier Traced</label>-->
        <!--        </div>-->
        <!--        <div class="col-md-6">-->
        <!--            <input class="form-control @error('supplier_traced') is-invalid @enderror" -->
        <!--                type="text" -->
        <!--                name="supplier_traced"-->
        <!--                value="{{ old('supplier_traced') }}"-->
        <!--                placeholder="Supplier Traced">-->
        <!--            @error('supplier_traced')-->
        <!--                <span class="text-danger">{{ $message }}</span>-->
        <!--            @enderror-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        
        <div class="col-md-4">
        <div class="mb-4 row align-items-center">
            <div class="col-md-6">
                <label class="form-label-title mb-0">Vendor</label>
            </div>
            <div class="col-md-6">

                {{-- Dropdown --}}
                <select class="form-control" name="vendor_id"  id="vendor_id">
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">
                            {{ $vendor->name }}
                        </option>
                    @endforeach
                </select>

                {{-- OR Create New --}}
                <input type="text" 
                    name="new_vendor" 
                    id="new_vendor"
                    class="form-control mt-2"
                    placeholder="Or enter new vendor">

            </div>
        </div>
    </div>


        {{-- Status --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Status <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Type --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Type</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('types') is-invalid @enderror" 
                        type="text" 
                        name="types"
                        value="{{ old('types') }}" 
                        placeholder="Types">
                    @error('types')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Tag --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Tag</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('tags') is-invalid @enderror" 
                        type="text" 
                        name="tags"
                        value="{{ old('tags') }}" 
                        placeholder="Tags">
                    @error('tags')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Brand --}}
        <div class="col-md-4">
            <div class="mb-4 row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-title mb-0">Brand</label>
                </div>
                <div class="col-md-6">
                    <input class="form-control @error('brands') is-invalid @enderror" 
                        type="text" 
                        name="brands"
                        value="{{ old('brands') }}" 
                        placeholder="Brands">
                    @error('brands')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Product Image --}}
        <div class="col-md-12">
            <div class="mb-4 row align-items-center">
                <label class="form-label-title col-sm-3 mb-0">
                    Product Image (500px X 500px) <span class="text-danger">*</span>
                </label>
                <div class="col-sm-9">
                    <input class="form-control @error('image') is-invalid @enderror" 
                        type="file" 
                        name="image" 
                        id="image"
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" 
                        required>
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small class="text-muted">Allowed formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB)</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-7 mt-4">
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Product Units</h5>

            <div id="units-wrapper">

                <div class="row mb-2 unit-row">
                    <div class="col-md-10">
                        <input type="text" name="units[]" class="form-control"
                               placeholder="Example: 500 ml / 1 Kg / Piece">
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-unit">Remove</button>
                    </div>
                </div>

            </div>

            <button type="button" class="btn btn-primary mt-2" id="add-unit">
                + Add Unit
            </button>

        </div>
    </div>
</div>

    {{-- Submit Button --}}
    <div class="text-end">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Save Product
        </button>
        <a href="{{ route('productss.index') }}" class="btn btn-secondary">
            <i class="fa fa-times"></i> Cancel
        </a>
    </div>
</form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New Product Add End -->
        
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

document.getElementById('add-unit').addEventListener('click', function () {

    let html = `
        <div class="row mb-2 unit-row">
            <div class="col-md-10">
                <input type="text" name="units[]" class="form-control"
                       placeholder="Example: 1 Kg">
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-unit">Remove</button>
            </div>
        </div>
    `;

    document.getElementById('units-wrapper').insertAdjacentHTML('beforeend', html);

});

document.addEventListener('click', function(e){

    if(e.target.classList.contains('remove-unit')){
        e.target.closest('.unit-row').remove();
    }

});

</script>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    let vendor = document.getElementById('vendor_id').value;
    let newVendor = document.getElementById('new_vendor').value.trim();

    if (!vendor && !newVendor) {
        e.preventDefault();
        alert('Please select a vendor OR enter a new vendor');
        return;
    }
});
</script>
    @endsection
