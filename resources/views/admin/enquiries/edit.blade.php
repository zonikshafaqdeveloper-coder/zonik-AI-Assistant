@extends('admin.layouts.app')
@section('content')
    <div class="page-body">

        <!-- New Product Add Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-8 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>Edit Brand Category</h5>
                                    </div>
                                    <form class="theme-form theme-form-2 mega-form"
                                        action="{{ route('enquiry.update', $enquiry->id) }}" method="POST">
                                        @csrf


                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Product Name</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="product_id"
                                                    id="product_id">
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            @if ($product->id == $enquiry->product_id) selected @endif>
                                                            {{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Quantity</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="quantity"
                                                    placeholder="quantity" value="{{ $enquiry->quantity }}">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">offer price</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="offer_price"
                                                    placeholder="offer price" value="{{ $enquiry->offer_price }}">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">mrp</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="mrp" placeholder="mrp"
                                                    value="{{ $enquiry->mrp }}">
                                            </div>
                                        </div>


                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">discount</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="discount"
                                                    placeholder="discount" value="{{ $enquiry->discount }}">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">expected price value</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="expected_price_value"
                                                    placeholder="expected price value" value="{{ $enquiry->expected_price_value }}">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Status</label>
                                            <div class="col-sm-9">

                                                <select class="w-100" name="status" id="status">
                                                    <option value="">select status</option>
                                                    @foreach(['submitted' => 'Admin Approved' ,'accept' => 'Accept'] as $value => $label)
                                                        <option value="{{ $value }}" {{ $enquiry->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                
                                                
                                                 
                                            </div>
                                        </div>

                                        

                                        <button type="submit" class="btn btn-primary ms-auto mt-4">Save</button>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New Product Add End -->
    @endsection
