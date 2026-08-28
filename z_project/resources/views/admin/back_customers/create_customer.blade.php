@extends('admin.layouts.appnew')
@section('content')
<style>
.card-header h3{
    font-size: 24px;
    font-weight: 600;
    color: #222;
    margin: 0;
}

.card-header .btn{
    font-weight: 500;
    padding: 8px 18px;
}
</style>
<div class="page-body">





    <!-- New Product Add Start -->
    <div class="container-fluid">

    
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-8 m-auto">

                    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

                       <div class="card">

                      <div class="card-header bg-white border-bottom d-flex 
                      justify-content-between align-items-center py-3 px-4">

                      <h3 class="mb-0 fw-bold">
                      Create Customer
                      </h3>

                    <a href="{{ route('export.create_customer') }}"
                     class="btn btn-success btn-sm rounded-pill px-3">
                    <i class="fa fa-file-excel me-1"></i> Export To Excel
                    </a>
                </div>
                
                    <div class="card-body p-4">

                                <!--<div class="card-header-2 mb-3">-->
                                <!--    <h3>Create Customer</h3>-->
                                <!--</div>-->

                               
<form  action="{{ route('customer.save') }}" method="POST" enctype="multipart/form-data">
    @csrf



    <div class="row g-3">

        {{-- Customer Name --}}
        <div class="col-md-6">
            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
            <input type="text" 
                name="name" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name') }}"
                placeholder="Enter customer name">

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Outlet Name --}}
        <div class="col-md-6">
            <label class="form-label">Company Name <span class="text-danger">*</span></label>
            <input type="text" 
                name="outlet_name" 
                class="form-control @error('outlet_name') is-invalid @enderror" 
                value="{{ old('outlet_name') }}"
                placeholder="Enter company name">

            @error('outlet_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
       
        {{-- designation --}}
        <div class="col-md-6">
            <label class="form-label">Designation <span class="text-danger"></span></label>
            <input type="text" 
                name="designation" 
                class="form-control @error('designation') is-invalid @enderror" 
                value="{{ old('designation') }}"
                placeholder="Enter Designation">

            @error('designation')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Mobile --}}
        <div class="col-md-6">
            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
            <input type="text" 
                name="mobile_number" 
                class="form-control @error('mobile_number') is-invalid @enderror" 
                value="{{ old('mobile_number') }}"
                placeholder="Enter mobile number"
                maxlength="15">

            @error('mobile_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Email --}}
        <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email') }}"
                placeholder="Enter email">

            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Location --}}
        <div class="col-md-6">
            <label class="form-label">Location <span class="text-danger">*</span></label>
            <input type="text" 
                name="location" 
                class="form-control @error('location') is-invalid @enderror" 
                value="{{ old('location') }}"
                placeholder="Enter location">

            @error('location')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Pincode --}}
        <div class="col-md-6">
            <label class="form-label">Pincode <span class="text-danger">*</span></label>
            <input type="text" 
                name="pincode" 
                class="form-control @error('pincode') is-invalid @enderror" 
                value="{{ old('pincode') }}"
                placeholder="Enter pincode">

            @error('pincode')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary px-4">Save Customer</button>
    </div>

</form>

                             

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    

</div>
@endsection






