@extends('admin.layouts.appnew')
@section('content')
<style>
    .table-responsive {
    overflow-y: hidden;
}
</style>

    <div class="page-body">

        <body>

            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row">

                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif


                                        <div class="title-header option-title">
                                            <h4 class="card-title"></h4>
                                            <div class="d-flex justify-content-between">
                                                <h3 class="card-title">Product Management</h3>
                                                <a href="{{ route('productss.create') }}" type="button"
                                                    class="btn btn-primary">Add Product</a>
                                            </div>

                                            <div class="row display:flex align-items-center enquire-box w-500">
                                                <div class="col">
                                                    {{-- {{route('users.uploadproducts')}} --}}
                                                    <form action="{{ route('productss.import') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class=" w-350">
                                                            <div class="form-group row">
                                                                <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                                                                    <span style="color:red;">*</span>File
                                                                    Input(Datasheet)</label>
                                                                    <input type="file"
                                                                        class="form-control form-control-user @error('file') is-invalid @enderror"
                                                                        id="exampleFile" name="file"
                                                                        value="{{ old('file') }}">

                                                                    @error('file')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <!-- <button type="submit"
                                                                        class="btn btn-success btn-user float-right mb-3">Import
                                                                        Product</button> -->
                                                            <!-- <a class="btn btn-primary float-right mr-3 mb-3" href="">Cancel</a> -->
                                                            <div class="row d-flex">
                                                                <div class="col-md-6">
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-user float-right mb-3">Import
                                                                        Product</button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <a href="{{ route('productss.export') }}"
                                                                        class="btn btn-sm btn-success m-19">
                                                                        <i class="fas fa-check"></i> Export To Excel
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </form>

                                                </div>
                                                <!-- <div class="col-md-4">
                                                            <a href="{{ route('productss.export') }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Export To Excel
                                                            </a>
                                                        </div> -->
                                            </div>



                                            <form class="d-inline-flex">
                                                <a href="{{ route('productss.create') }}"
                                                    class="align-items-center btn btn-theme d-flex">
                                                    <i data-feather="plus-square"></i>Add Product
                                                </a>
                                            </form>


                                        </div>

                                        <div class="table-responsive category-table">
                                            <div>
                                                <table class="table all-package theme-table table-bordered"
                                                    id="productsdata">
                                                    <thead class="b-shadow">
                                                        <tr class="text-capitalize">
                                                            <th>Sr.</th>
                                                            <th class="text-center">Product Image</th>
                                                            <th class="text-center">Category Name</th>
                                                            <th class="text-center">Subcategory Name</th>
                                                            <th class="text-center">Product Name</th>
                                                            <th class="text-center">Unit</th>
                                                            <th class="text-center">Pack (Qty.)</th>
                                                            <th class="text-center">Peices Per Pack</th>
                                                            <th class="text-center">Carton Size</th>
                                                            <th class="text-center">MRP</th>
                                                            <th class="text-center">Cost Per Item</th>
                                                            <th class="text-center">Total GST (%)</th>
                                                            <th class="text-center">Total Cost with Tax</th>
                                                            <th class="text-center">sgst</th>
                                                            <th class="text-center">cgst</th>
                                                            <th class="text-center">igst</th>
                                                            <th class="text-center">cess</th>
                                                            <th class="text-center">Loose Sale Price (Basic)</th>
                                                            <th class="text-center">Carton Sale Price (Basic)</th>
                                                            <th class="text-center">Product Weight (Grams)</th>
                                                            <th class="text-center">Supplier Traced</th>
                                                            <th class="text-center">Carton Discount (Basic)</th>
                                                            <th class="text-center">Loose Discount (Basic)</th>
                                                            <th class="text-center">brand</th>
                                                            <th class="text-center">type</th>
                                                            <th class="text-center">tag</th>
                                                            <th class="text-center">Last updated Price</th>
                                                            <th class="text-center">status</th>
                                                            <th class="text-center">Action</th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($products as $key => $product)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>
                                                                    <img src="/uploads/{{ $product->image }}" height="50px"
                                                                        width="50px">
                                                                </td>
                                                                <td>{{ $product->category?->category_name }}</td>
                                                                <td class="text-center">{{ $product->subcategory?->name }}</td>
                                                                <td class="text-center">{{ $product->product_name }}</td>
                                                                <td class="text-center">{{ $product?->unit }}</td>
                                                                <td class="text-center">{{ $product->product_quantity }}</td>
                                                                <td class="text-center">{{ $product->peices_per_pack }}</td>
                                                                <td class="text-center">{{ $product->carton_size }}</td>
                                                                <td class="text-center">{{ $product->product_mrp }}</td>
                                                                <td class="text-center">{{ $product->cost_per_item }}</td>
                                                                @php 
                                                                $totalGST = 0;
                                                                $totalGST= $product->sgst + $product->cgst;
                                                                @endphp
                                                                <td class="text-center">{{ $totalGST }}%</td>
                                                                <td class="text-center">{{ $product->total_with_tax }}</td>
                                                                <td class="text-center">{{ $product->sgst }}</td>
                                                                <td class="text-center">{{ $product->cgst }}</td>
                                                                <td class="text-center">{{ $product->igst }}</td>
                                                                <td class="text-center">{{ $product->cess }}</td>
                                                                <td class="text-center">{{ $product->sale_price_loose_pcs }}</td>
                                                                <td class="text-center">{{ $product->sale_price_carton }}</td>
                                                                <td class="text-center">{{ $product->product_weight_grams }}</td>
                                                                <td class="text-center">
                                                                        {{ $product->vendor ? $product->vendor->name : 'N/A' }}
                                                                </td>
                                                                <td class="text-center">{{ $product->carton_discount_basic }}</td>
                                                                <td class="text-center">{{ $product->loose_discount_basic }}</td>
                                                                <td style="color:#0da487">{{ $product->brands }}</td>
                                                                <td style="color:#0da487">{{ $product->types }}</td>
                                                                <td style="color:#0da487">{{ $product->tags }}</td>
                                                                

                                                                 <td class="text-center">{{ $product->last_update_price }}</td>
                                                                <td class="text-center">{{ $product->status }}</td>

                                                                <td class="text-center" style="display:flex; gap: 20px;">

                                                                    <a href="{{ route('productss.edit', $product->id) }}"
                                                                        class="align-items-center btn btn-success category-btn">Edit</a>

                                                                    <form method="POST"
                                                                        action="{{ url('delete-productss/' . $product->id) }}">
                                                                        @csrf
                                                                        @method('delete')

                                                                        <button type='submit'
                                                                            class="align-items-center btn btn-danger d-flex">Delete

                                                                        </button>
                                                                    </form>


                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endsection

                <!-- All User Table Ends-->
