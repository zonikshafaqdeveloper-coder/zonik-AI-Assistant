@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-sm-8 m-auto">

                <div class="card">
                    <div class="card-body">

                        <h5 class="mb-3">
                            {{ isset($user) ? 'Edit Outlet' : 'Add Outlet' }}
                        </h5>

                        <form action="{{ isset($user) ? route('update.outlet', $user->id) : route('outlet.save') }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($user))
                                @method('PUT')
                            @else
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                            @endif

                            {{-- Account Type --}}
                            @php
                                $accountType = old('account_type', $kyc->account_type ?? 'personal');
                            @endphp

                            <div class="mb-3">
                                <label class="form-label">
                                    Account Type <span class="text-danger">*</span>
                                </label>
                                <div class="btn-group">
                                    <input type="radio" class="btn-check" name="account_type" id="personal" value="personal" {{ $accountType == 'personal' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="personal">Personal</label>

                                    <input type="radio" class="btn-check" name="account_type" id="business" value="business" {{ $accountType == 'business' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="business">Business</label>
                                </div>
                                @error('account_type')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row g-3">

                                {{-- Customer Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Customer Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $user->name ?? '') }}">
                                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Outlet Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Outlet Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="outlet_name" class="form-control"
                                           value="{{ old('outlet_name', $user->outlet_name ?? '') }}">
                                    @error('outlet_name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Mobile --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Mobile Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="mobile_number" class="form-control"
                                           value="{{ old('mobile_number', $user->mobile_number ?? '') }}">
                                    @error('mobile_number')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', $user->email ?? '') }}">
                                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Location --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Location <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="location" class="form-control"
                                           value="{{ old('location', $user->location ?? '') }}">
                                    @error('location')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Pincode --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Pincode <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="pincode" class="form-control"
                                           value="{{ old('pincode', $user->pincode ?? '') }}">
                                    @error('pincode')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Pancard --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Pancard Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="pancard" class="form-control"
                                           value="{{ old('pancard', $kyc->pan_no ?? '') }}">
                                    @error('pancard')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Pancard Document --}}
                                <div class="col-md-6">
                                    <label class="form-label">Pancard Document</label>
                                    <input type="file" name="pancard_docs" class="form-control">
                                    @error('pancard_docs')<small class="text-danger">{{ $message }}</small>@enderror
                                    @if(!empty($kyc->pan_document))
                                        <small class="d-block mt-1">
                                            Uploaded:
                                            <a href="{{ asset('storage/'.$kyc->pan_document) }}" target="_blank">View Document</a>
                                        </small>
                                    @endif
                                </div>

                                {{-- BUSINESS FIELDS --}}
                                <div id="businessFields" class="row g-3 d-none">

                                    {{-- GST --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            GST Number <span class="text-danger business-required">*</span>
                                        </label>
                                        <input type="text" name="gst_no" class="form-control"
                                               value="{{ old('gst_no', $kyc->gst_no ?? '') }}">
                                        @error('gst_no')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            GST Document <span class="text-danger business-required">*</span>
                                        </label>
                                        <input type="file" name="gst_docs" class="form-control">
                                        @error('gst_docs')<small class="text-danger">{{ $message }}</small>@enderror
                                        @if(!empty($kyc->gst_document))
                                            <small class="d-block mt-1">
                                                Uploaded:
                                                <a href="{{ asset('storage/'.$kyc->gst_document) }}" target="_blank">View GST Doc</a>
                                            </small>
                                        @endif
                                    </div>

                                    {{-- FSSAI --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            FSSAI Number <span class="text-danger business-required">*</span>
                                        </label>
                                        <input type="text" name="fssai" class="form-control"
                                               value="{{ old('fssai', $kyc->fssai ?? '') }}">
                                        @error('fssai')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            FSSAI Document <span class="text-danger business-required">*</span>
                                        </label>
                                        <input type="file" name="fssai_docs" class="form-control">
                                        @error('fssai_docs')<small class="text-danger">{{ $message }}</small>@enderror
                                        @if(!empty($kyc->fssai_document))
                                            <small class="d-block mt-1">
                                                Uploaded:
                                                <a href="{{ asset('storage/'.$kyc->fssai_document) }}" target="_blank">View FSSAI Doc</a>
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                {{-- Billing Address --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Billing Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="billing_address" class="form-control">{{ old('billing_address', $kyc->billing_address ?? '') }}</textarea>
                                    @error('billing_address')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Billing Pincode --}}
                                <div class="col-md-6">
                                    <label class="form-label">Billing Pincode</label>
                                    <input type="text" name="billing_pincode" class="form-control"
                                           value="{{ old('billing_pincode', $kyc->billing_pincode ?? '') }}">
                                    @error('billing_pincode')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Outlet Address --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Outlet Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="outlet_address" class="form-control">{{ old('outlet_address', $kyc->outlet_address ?? '') }}</textarea>
                                    @error('outlet_address')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                                {{-- Outlet Pincode --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Outlet Pincode <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="outlet_pincode" class="form-control"
                                           value="{{ old('outlet_pincode', $kyc->outlet_pincode ?? '') }}">
                                    @error('outlet_pincode')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>

                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('customer.indexx', ['type' => 'outlet']) }}" class="btn btn-secondary px-4">
                                    Back
                                </a>

                                <button type="submit" class="btn btn-primary px-4">
                                    {{ isset($user) ? 'Update Outlet' : 'Save Outlet' }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const businessFields = document.getElementById('businessFields');
    const personal = document.getElementById('personal');
    const business = document.getElementById('business');

    function toggle() {
        businessFields.classList.toggle('d-none', !business.checked);
    }

    toggle();
    personal.addEventListener('change', toggle);
    business.addEventListener('change', toggle);

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            confirmButtonText: 'OK'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            confirmButtonText: 'OK'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
@endsection
