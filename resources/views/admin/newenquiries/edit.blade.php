@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
   <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                                    <div class="card-header-2 mb-5">
                                        <h4>Edit Category</h4>
                                    </div>
                                    <form class="theme-form theme-form-2 mega-form"
                                        action="{{ route('enquiry.updatestatus', $enquiry->id) }}" method="POST">
                                        {{ csrf_field() }}

@if ($errors->any())
<div class="alert alert-danger">
<!-- <strong>Whoops!</strong> There were some problems with your input.<br><br> -->
<ul>
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
@endforeach
 </ul>
 </div>
 @endif



                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4 row align-items-center">
                                            <div class="col-sm-12">
                                                <label class="form-label-title mb-2">Product Name</label>
                                           
                                                <select class="js-example-basic-single w-100 h-51" name="product_id"
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
                                                </div>

                                                <div class="col-md-6">
                                                        <div class="mb-4 row align-items-center">
                                           <div class="col-sm-12">
                                            <label class="form-label-title mb-2">Quantity</label>
                                          
                                                <input class="form-control" type="text" name="quantity"
                                                    placeholder="quantity" value="{{ $enquiry->quantity }}">
                                            </div>
                                          
                                        </div>
                                                </div>
                                            </div>
                                    
                                        
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-4 row align-items-center">
                                            <div class="col-sm-12">
                                                <label class="form-label-title mb-2">Offer price</label>
                                       
                                                <input class="form-control" type="text" name="offer_price"
                                                    placeholder="offer price" value="{{ $enquiry->offer_price }}">
                                            </div>
                                     
                                        </div>
                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="mb-4 row align-items-center">
                                            <div class="col-sm-12">
                                                <label class="form-label-title mb-2">MRP</label>
                                          
                                                <input class="form-control" type="text" name="mrp" placeholder="mrp"
                                                    value="{{ $enquiry->mrp }}">
                                           
                                            </div>
                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="mb-4 row align-items-center">
                                            <div class="col-sm-12">
                                                 <label class="form-label-title mb-2">Discount</label>
                                                <input class="form-control" type="text" name="discount"
                                                    placeholder="discount" value="{{ $enquiry->discount }}">
                                       
                                            </div>
                                        </div>
                                                    </div>
                                                </div>


                                        
                                    <div class="row">
                                        <div class="col-md-6">
                                                <div class="mb-4 row align-items-center">
                                            <div class="col-sm-12">
                                            <label class="form-label-title mb-2">Expected price value</label>
                                                <input class="form-control" type="text" name="expected_price_value"
                                                    placeholder="expected price value" value="{{ $enquiry->expected_price_value }}">
                                            </div>
                                        </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-4 row align-items-center">
                                            <div class="col-sm-12">
                                                 <label class="form-label-title mb-2">Status</label>
                                                <select class="w-100 h-51" name="status" id="status">
                                                    <option value="">Select status</option>
                                                    @foreach(['submitted' => 'Admin Approved' ,'accept' => 'Accept'] as $value => $label)
                                                        <option value="{{ $value }}" {{ $enquiry->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>   
                                        </div>
                                        </div>
                                    </div>


                                        <button type="submit" class="btn btn-success btn-user  btn-w mt-4">Save</button>

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
