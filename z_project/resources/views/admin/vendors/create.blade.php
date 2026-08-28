@extends('admin.layouts.appnew')
@section('content')
<style>
.export{
    margin:15px 0px 0px 20px !important;
}
a{
    text-decoration:none;
    padding:5px !important;
}
</style>
<div class="page-body">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-10 m-auto">

                        {{-- Alerts --}}
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
                             <!-- Add export to excel function -->
                                     
                            <div class="export col-md-3">
                            <a href="{{ route('export.vendors') }}" class="btn-export btn-success">
                            ✔ Export To Excel
                            </a>                        
                            </div>
                                   
                            <!-- end -->
                            <div class="card-body">

                                <div class="card-header-2 mb-3">
                                    <h3>Create Vendor</h3>
                                </div>

                                <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Row 1 --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Vendor Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name"
                                                value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                            <input type="text" name="mobile"
                                                value="{{ old('mobile') }}"
                                                class="form-control @error('mobile') is-invalid @enderror" maxlength="15">
                                            @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email"
                                                value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror">
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    

                                    {{-- Row 2 --}}
                                    <!--<div class="row mb-3">-->
                                        <div class="col-md-6">
                                            <label class="form-label">Location <span class="text-danger">*</span></label>
                                            <input type="text" name="location"
                                                value="{{ old('location') }}"
                                                class="form-control @error('location') is-invalid @enderror">
                                            @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                            <input type="text" name="pincode"
                                                value="{{ old('pincode') }}"
                                                class="form-control @error('pincode') is-invalid @enderror">
                                            @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        
                                                                            {{-- Lead Time --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Lead Time (Days) <span class="text-danger">*</span> </label>
                                        <input type="number" name="lead_time"
                                            value="{{ old('lead_time') }}"
                                            class="form-control @error('lead_time') is-invalid @enderror"
                                            placeholder="Enter lead time">
                                        @error('lead_time') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    {{-- MOQ Type --}}
                                    <div class="col-md-6">
                                        <label class="form-label">MOQ Type</label>
                                        <select name="moq_type" class="form-control @error('moq_type') is-invalid @enderror">
                                            <option value="">Select MOQ Type</option>
                                            <option value="BOX" {{ old('moq_type') == 'BOX' ? 'selected' : '' }}>BOX</option>
                                            <option value="LOOSE" {{ old('moq_type') == 'LOOSE' ? 'selected' : '' }}>LOOSE</option>
                                        </select>
                                        @error('moq_type') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    
                                    </div>

                                    <hr>

                                    {{-- PAN --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">PAN Number  <span class="text-danger">*</span></label>
                                            <input type="text" name="pan_number"
                                                value="{{ old('pan_number') }}"
                                                class="form-control @error('pan_number') is-invalid @enderror" maxlength="15">
                                            @error('pan_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">PAN Document  <span class="text-danger">*</span></label>
                                            <input type="file" name="pan_document"
                                                class="form-control @error('pan_document') is-invalid @enderror">
                                            @error('pan_document') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    {{-- GST --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">GST Number  <span class="text-danger">*</span></label>
                                            <input type="text" name="gst_number"
                                                value="{{ old('gst_number') }}"
                                                class="form-control @error('gst_number') is-invalid @enderror" maxlength="15">
                                            @error('gst_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">GST Document  <span class="text-danger">*</span> </label>
                                            <input type="file" name="gst_document"
                                                class="form-control @error('gst_document') is-invalid @enderror">
                                            @error('gst_document') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    {{-- FSSAI --}}
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">FSSAI Number  <span class="text-danger">*</span> </label>
                                            <input type="text" name="fssai_number"
                                                value="{{ old('fssai_number') }}"
                                                class="form-control @error('fssai_number') is-invalid @enderror" maxlength="15">
                                            @error('fssai_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">FSSAI Document  <span class="text-danger">*</span></label>
                                            <input type="file" name="fssai_document"
                                                class="form-control @error('fssai_document') is-invalid @enderror">
                                            @error('fssai_document') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="row">
                                        <div class="col-md-4 ms-auto">
                                            <button type="submit" class="btn btn-primary w-100">
                                                Save Vendor
                                            </button>
                                        </div>
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
