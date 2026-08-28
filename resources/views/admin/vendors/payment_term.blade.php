@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-sm-8 m-auto">

                <div class="card">
                    <div class="card-body">

                        <div class="card-header-2 mb-3">
                            <h5>Vendor Payment Terms</h5>
                        </div>

                      <form method="POST" action="{{ route('vendor.payment-term.save',$vendor->id) }}">
                            @csrf

                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                            {{-- Vendor Name --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Vendor Name</label>
                                    <input type="text" class="form-control"
                                           value="{{ $vendor->name }}"
                                           readonly>
                                </div>

                                {{-- Credit Status --}}
                            <div class="col-md-4">
                                <label class="form-label">Credit Status</label>
                                <select name="credit_status" class="form-control">
                                    <option value="" disabled
                                        {{ old('credit_status', $paymentTerm->credit_status ?? '') === '' ? 'selected' : '' }}>
                                        Select Credit Status
                                    </option>

                                    <option value="active"
                                        {{ old('credit_status', $paymentTerm->credit_status ?? '') === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="inactive"
                                        {{ old('credit_status', $paymentTerm->credit_status ?? '') === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>

                                @error('credit_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                                {{-- Credit Limit --}}
                                <div class="col-md-4">
                                    <label class="form-label">Credit Limit</label>
                                    <input type="number" name="credit_limit" class="form-control"
                                           value="{{ old('credit_limit', $paymentTerm->credit_limit ?? '') }}">
                                </div>
                            </div>

                            {{-- Due Days + Verified --}}
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label">Due Limit Days</label>
                                    <input type="number" name="due_limit_days"  id="due_limit_days"  class="form-control"
                                           value="{{ old('due_limit_days', $paymentTerm->due_limit_days ?? '') }}">
                                </div>

                            <div class="col-md-4">
                                <label class="form-label">Verified Status</label>
                                <select name="verified_status" class="form-control">
                                    <option value="" disabled
                                        {{ old('verified_status', $paymentTerm->verified_status ?? '') === '' ? 'selected' : '' }}>
                                        Select Verified Status
                                    </option>

                                    <option value="verified"
                                        {{ old('verified_status', $paymentTerm->verified_status ?? '') === 'verified' ? 'selected' : '' }}>
                                        Verified
                                    </option>

                                    <option value="unverified"
                                        {{ old('verified_status', $paymentTerm->verified_status ?? '') === 'unverified' ? 'selected' : '' }}>
                                        Unverified
                                    </option>
                                </select>

                                @error('verified_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            </div>

                            {{-- RANGE BASED TERMS --}}
                            <div class="row mt-4">
                                <div class="col-md-2">
                                    <label class="form-label">From Range</label>
                                    <input type="number" name="from_range" class="form-control"
                                           value="{{ old('from_range', $paymentTerm->from_range ?? '') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">To Range</label>
                                    <input type="number" name="to_range" class="form-control"
                                           value="{{ old('to_range', $paymentTerm->to_range ?? '') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Days</label>
                                    <input type="number" name="days" class="form-control"
                                           value="{{ old('days', $paymentTerm->days ?? '') }}">
                                </div>

                                <div class="col-md-4 d-flex align-items-center mt-4">
                                    <input type="checkbox"
                                           name="custom_payment_term"
                                           id="custom_payment_term"
                                           value="1"
                                           {{ old('custom_payment_term', $paymentTerm->custom_payment_term ?? false) ? 'checked' : '' }}>
                                    <label class="ms-2">Custom Payment Term</label>
                                </div>
                            </div>



                            @if(!empty($vendor->pan_document) || !empty($vendor->gst_document) || !empty($vendor->fssai_document))
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h6 class="mb-3">Uploaded Documents</h6>
                                        </div>

                                        {{-- PAN --}}
                                        @if(!empty($vendor->pan_document))
                                            <div class="col-md-4">
                                                <label class="form-label">PAN Document</label>
                                                <a href="{{ asset('uploads/vendor_pan/'.$vendor->pan_document) }}"
                                                target="_blank"
                                                class="btn btn-outline-primary w-100">
                                                    View PAN Document
                                                </a>
                                            </div>
                                        @endif

                                        {{-- GST --}}
                                        @if(!empty($vendor->gst_document))
                                            <div class="col-md-4">
                                                <label class="form-label">GST Document</label>
                                                <a href="{{ asset('uploads/vendor_gst/'.$vendor->gst_document) }}"
                                                target="_blank"
                                                class="btn btn-outline-primary w-100">
                                                    View GST Document
                                                </a>
                                            </div>
                                        @endif

                                        {{-- FSSAI --}}
                                        @if(!empty($vendor->fssai_document))
                                            <div class="col-md-4">
                                                <label class="form-label">FSSAI Document</label>
                                                <a href="{{ asset('uploads/vendor_fssai/'.$vendor->fssai_document) }}"
                                                target="_blank"
                                                class="btn btn-outline-primary w-100">
                                                    View FSSAI Document
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif


                            @if(session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif

                            

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    Save Payment Terms
                                </button>
                                <a href="{{ route('vendors.index') }}" class="btn btn-secondary px-4">
                                    Back
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const customCheckbox = document.getElementById('custom_payment_term');
    const dueLimitInput  = document.getElementById('due_limit_days');

    function toggleDueLimit() {
        if (customCheckbox.checked) {
            dueLimitInput.value = '';
            dueLimitInput.setAttribute('disabled', true);
        } else {
            dueLimitInput.removeAttribute('disabled');
        }
    }


    toggleDueLimit();


    customCheckbox.addEventListener('change', toggleDueLimit);
});
</script>
@endsection
