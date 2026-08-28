@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">
<div class="container-fluid">

<div class="row">
<div class="col-12 my-5">
<div class="col-sm-8 m-auto">

{{-- success message --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

{{-- validation errors --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="card">
<div class="card-body">

<h3 class="mb-4">Edit Customer</h3>

<form action="{{ route('customer.update', $customer->id) }}" method="POST">
@csrf

<div class="row g-3">

<div class="col-md-6">
<label>Customer Name *</label>
<input type="text" name="name" class="form-control"
value="{{ old('name', $customer->name) }}">
</div>

<div class="col-md-6">
<label>Company Name *</label>
<input type="text" name="outlet_name" class="form-control"
value="{{ old('outlet_name', $customer->outlet_name) }}">
</div>

<div class="col-md-6">
<label>Designation</label>
<input type="text" name="designation" class="form-control"
value="{{ old('designation', $customer->designation) }}">
</div>

<div class="col-md-6">
<label>Mobile *</label>
<input type="text" name="mobile_number" class="form-control"
value="{{ old('mobile_number', $customer->mobile_number) }}">
</div>

<div class="col-md-6">
<label>Email *</label>
<input type="email" name="email" class="form-control"
value="{{ old('email', $customer->email) }}">
</div>

<div class="col-md-6">
<label>Location *</label>
<input type="text" name="location" class="form-control"
value="{{ old('location', $customer->location) }}">
</div>

<div class="col-md-6">
<label>Pincode *</label>
<input type="text" name="pincode" class="form-control"
value="{{ old('pincode', $customer->pincode) }}">
</div>

</div>

<div class="mt-4 text-end">
<button type="submit" class="btn btn-primary">
    Update Customer
</button>
</div>

</form>

</div>
</div>

</div>
</div>
</div>
</div>
@endsection
