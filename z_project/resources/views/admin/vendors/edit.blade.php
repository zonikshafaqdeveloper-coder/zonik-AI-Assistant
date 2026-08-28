@extends('admin.layouts.appnew')
@section('content')

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
                            <div class="card-body">

                                <div class="card-header-2 mb-3">
                                    <h3>Edit Vendor</h3>
                                </div>

                                <form action="{{ route('vendors.update', $vendor->id) }}"
                                      method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Row 1 --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Vendor Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                   name="name"
                                                   value="{{ old('name', $vendor->name) }}"
                                                   class="form-control @error('name') is-invalid @enderror">
                                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Mobile <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                   name="mobile"
                                                   value="{{ old('mobile', $vendor->mobile) }}"
                                                   class="form-control @error('mobile') is-invalid @enderror">
                                            @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email"
                                                   name="email"
                                                   value="{{ old('email', $vendor->email) }}"
                                                   class="form-control @error('email') is-invalid @enderror">
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    <!--</div>-->

                                    {{-- Row 2 --}}
                                    <!--<div class="row mb-3">-->
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Location <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                   name="location"
                                                   value="{{ old('location', $vendor->location) }}"
                                                   class="form-control @error('location') is-invalid @enderror">
                                            @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Pincode <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                   name="pincode"
                                                   value="{{ old('pincode', $vendor->pincode) }}"
                                                   class="form-control @error('pincode') is-invalid @enderror">
                                            @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        
                                        
                                         <div class="col-md-6">
                                                <label class="form-label">Lead Time <span class="text-danger">*</span></label>
                                                <input type="number" name="lead_time"
                                                    value="{{ old('lead_time', $vendor->lead_time) }}"
                                                    class="form-control">
                                                      @error('lead_time') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">MOQ Type</label>
                                                <select name="moq_type" class="form-control">
                                                    <option value="BOX" {{ $vendor->moq_type == 'BOX' ? 'selected' : '' }}>BOX</option>
                                                    <option value="LOOSE" {{ $vendor->moq_type == 'LOOSE' ? 'selected' : '' }}>LOOSE</option>
                                                </select>
                                            </div>
                                    </div>

                                    <hr>

                                    {{-- PAN --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">PAN Number  <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="pan_number"
                                                   value="{{ old('pan_number', $vendor->pan_number) }}"
                                                   class="form-control @error('pan_number') is-invalid @enderror">
                                            @error('pan_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">PAN Document  <span class="text-danger">*</span></label>
                                            <input type="file"
                                                   name="pan_document"
                                                   class="form-control @error('pan_document') is-invalid @enderror">
                                            @error('pan_document') <small class="text-danger">{{ $message }}</small> @enderror

                                            @if($vendor->pan_document)
                                                <a href="{{ asset('uploads/vendor_pan/'.$vendor->pan_document) }}"
                                                   target="_blank"
                                                   class="d-block mt-1">
                                                    View Existing PAN
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- GST --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">GST Number  <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="gst_number"
                                                   value="{{ old('gst_number', $vendor->gst_number) }}"
                                                   class="form-control @error('gst_number') is-invalid @enderror">
                                            @error('gst_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">GST Document  <span class="text-danger">*</span></label>
                                            <input type="file"
                                                   name="gst_document"
                                                   class="form-control @error('gst_document') is-invalid @enderror">
                                            @error('gst_document') <small class="text-danger">{{ $message }}</small> @enderror

                                            @if($vendor->gst_document)
                                                <a href="{{ asset('uploads/vendor_gst/'.$vendor->gst_document) }}"
                                                   target="_blank"
                                                   class="d-block mt-1">
                                                    View Existing GST
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- FSSAI --}}
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">FSSAI Number  <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="fssai_number"
                                                   value="{{ old('fssai_number', $vendor->fssai_number) }}"
                                                   class="form-control @error('fssai_number') is-invalid @enderror">
                                            @error('fssai_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">FSSAI Document  <span class="text-danger">*</span></label>
                                            <input type="file"
                                                   name="fssai_document"
                                                   class="form-control @error('fssai_document') is-invalid @enderror">
                                            @error('fssai_document') <small class="text-danger">{{ $message }}</small> @enderror

                                            @if($vendor->fssai_document)
                                                <a href="{{ asset('uploads/vendor_fssai/'.$vendor->fssai_document) }}"
                                                   target="_blank"
                                                   class="d-block mt-1">
                                                    View Existing FSSAI
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="row">
                                        <div class="col-md-4 ms-auto">
                                            <button type="submit" class="btn btn-primary w-100">
                                                Update Vendor
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
