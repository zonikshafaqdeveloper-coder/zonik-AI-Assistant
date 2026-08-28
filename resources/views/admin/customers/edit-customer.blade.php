@extends('admin.layouts.appnew')
@section('content')
<style>
    .disabled {
        pointer-events: none;
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
    <div class="page-body">

        <!-- New Product Add Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 my-5">
                    <div class="row">
                        <div class="col-sm-8 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>Edit Customer</h5>
                                    </div>
                                    <form method="POST" action="{{ route('update-customer', $customer->id) }}" id="editCustomerForm">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="name" class="form-label">Customer Name</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="credit_status" class="form-label">Credit Status</label>
                                                <select class="form-select form-control" id="credit_status" name="credit_status">
                                                    <option value="Active" {{ old('credit_status', $customer->credit_status) == 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive" {{ old('credit_status', $customer->credit_status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4" id="creditLimitField">
                                                <label for="credit_limit" class="form-label">Credit Limit</label>
                                                <input type="number" class="form-control" id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}">
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label for="status" class="form-label">Customer Status</label>
                                               <select class="form-select form-control" id="status" name="status">
                                                    <option value="Active"   {{ strtolower(old('status', $customer->status)) == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive" {{ strtolower(old('status', $customer->status)) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="verified_status" class="form-label">Verified Status</label>
                                                <select class="form-select form-control" id="verified_status" name="verified_status">
                                                    <option value="verified" {{ old('verified_status', $customer->verified_status) == 'verified' ? 'selected' : '' }}>verified</option>
                                                    <option value="unverified" {{ old('verified_status', $customer->verified_status) == 'unverified' ? 'selected' : '' }}>unverified</option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr class="mt-4">

                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <label class="form-label d-block fw-bold">
                                                    Select Credit Term <span class="text-danger">*</span>
                                                    <small class="text-muted d-block" style="font-weight: normal;">Choose one — Due Limit Days or Outlet Payment Term.</small>
                                                </label>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input credit-term-radio" type="radio"
                                                           name="credit_term_type" id="term_due_days" value="due_days"
                                                           {{ old('credit_term_type', $selectedCreditTermType ?? '') == 'due_days' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="term_due_days">Due Limit Days</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input credit-term-radio" type="radio"
                                                           name="credit_term_type" id="term_outlet" value="outlet"
                                                           {{ old('credit_term_type', $selectedCreditTermType ?? '') == 'outlet' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="term_outlet">Outlet Payment Term</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TERM 1: Due Limit Days -->
                                        <div class="row mt-3 term-section" id="section_due_days">
                                            <div class="col-md-4">
                                                <label for="due_days_limit" class="form-label">Due Limit Days</label>
                                                <input type="number" class="form-control" id="due_days_limit" name="due_days_limit"
                                                       value="{{ old('due_days_limit', $customer->due_days_limit) }}">
                                            </div>
                                        </div>

                                        <!-- TERM 2: Outlet Payment Term -->
                                        <div class="row mt-3 term-section" id="section_outlet">
                                            <div class="col-md-2">
                                                <label class="form-label">From Range</label>
                                                <input type="number" name="from_range" class="form-control"
                                                    value="{{ old('from_range', $paymentTerm->from_range ?? '') }}" placeholder="0">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">To Range</label>
                                                <input type="number" name="to_range" class="form-control"
                                                    value="{{ old('to_range', $paymentTerm->to_range ?? '') }}" placeholder="0">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Days</label>
                                                <input type="number" name="days" class="form-control"
                                                    value="{{ old('days', $paymentTerm->days ?? '') }}" placeholder="0">
                                            </div>

                                            <div class="col-md-3 d-flex align-items-center mt-4">
                                                <label class="me-2">Custom Payment Term</label>
                                                <input type="checkbox" name="is_active" value="1"
                                                    {{ old('is_active', $paymentTerm->is_active ?? false) ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <!-- TERM 3: Dairy Payment Term (independent, optional add-on — not part of the mandatory toggle) -->
                                        <hr class="mt-4">
                                        <div class="row mt-2">
                                            <div class="col-12 d-flex align-items-center">
                                                <label class="form-label mb-0 me-2 fw-bold">Dairy Payment Term</label>
                                                <small class="text-muted me-3">(optional — admin can enable independently)</small>

                                                <input type="hidden" name="dairy_is_active" value="0">
                                                <input type="checkbox" name="dairy_is_active" id="dairy_is_active_toggle" value="1"
                                                    {{ old('dairy_is_active', optional($customer->dairyPaymentTerm)->is_active) == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <div class="row mt-3" id="section_dairy">
                                            <div class="col-md-4">
                                                <label>Dairy Due Limit Days</label>
                                                <input type="number" name="due_limit_days" class="form-control" id="due_limit_days_input"
                                                    value="{{ old('due_limit_days', $customer->dairyPaymentTerm->due_limit_days ?? '') }}">
                                            </div>
                                        </div>

                                        @if (session('success'))
                                            <div class="alert alert-success mt-3">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                                    </form>

                                </div>
                            </div>

                        </div>

                        <div class="col-sm-8 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>Kyc Documents -  <b>{{ $customer->verified_status }}</b></h5>
                                        <br>
                                    </div>

                                    <div class="row">
                                        @if(!empty($kyc->gst_no))
                                        <div class="col-md-4">
                                            <label for="gst_document" class="form-label">GST No</label>
                                            <input type="text" class="form-control" id="gstno" name="gstno" value="{{ $kyc->gst_no ?? '' }}" readonly>
                                        </div>
                                        @endif

                                        @if(!empty($kyc->pan_no))
                                        <div class="col-md-4">
                                            <label for="pan_document" class="form-label">PAN No</label>
                                            <input type="text" class="form-control" id="panno" name="panno" value="{{ $kyc->pan_no ?? '' }}" readonly>
                                        </div>
                                        @endif

                                        @if(!empty($kyc->fssai))
                                        <div class="col-md-4">
                                            <label for="fassai_document" class="form-label">FSSAI No</label>
                                            <input type="text" class="form-control" id="fassaino" name="fassaino" value="{{ $kyc->fssai ?? '' }}" readonly>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        @if(!empty($kyc->gst_document))
                                        <div class="col-md-4">
                                            <label for="gst_document" class="form-label">GST Document</label>
                                            @php
                                                $gstDocumentPath = url('storage/gst_docs/' . basename($kyc->gst_document));
                                            @endphp
                                            <a href="{{ $gstDocumentPath }}" target="_blank" class="btn btn-primary mb-2" style="width: -webkit-fill-available;">View GST Document</a>
                                        </div>
                                        @endif

                                        @if(!empty($kyc->pan_document))
                                        <div class="col-md-4">
                                            <label for="pan_document" class="form-label">PAN Document</label>
                                            @php
                                                $panDocumentPath = url('storage/pancard_docs/' . basename($kyc->pan_document));
                                            @endphp
                                            <a href="{{ $panDocumentPath }}" target="_blank" class="btn btn-primary mb-2" style="width: -webkit-fill-available;">View PAN Document</a>
                                        </div>
                                        @endif

                                        @if(!empty($kyc->fssai_document))
                                        <div class="col-md-4">
                                            <label for="fssai_document" class="form-label">FSSAI Document</label>
                                            @php
                                                $fssaiDocumentPath = url('storage/fssai_docs/' . basename($kyc->fssai_document));
                                            @endphp
                                            <a href="{{ $fssaiDocumentPath }}" target="_blank" class="btn btn-primary mb-2" style="width: -webkit-fill-available;">View FSSAI Document</a>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New Product Add End -->
    @endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const creditStatus = document.getElementById('credit_status');
    const creditLimitField = document.getElementById('creditLimitField');
    const creditLimitInput = document.getElementById('credit_limit');

    const termRadios = document.querySelectorAll('.credit-term-radio');
    const sections = {
        due_days: document.getElementById('section_due_days'),
        outlet:   document.getElementById('section_outlet'),
    };

    const dairyToggle = document.getElementById('dairy_is_active_toggle');
    const dairySection = document.getElementById('section_dairy');
    const dairyInput = document.getElementById('due_limit_days_input');

    // --- Toggle Credit Limit + Credit Term fields based on Credit Status ---
    function toggleCreditDependentFields() {
        const isActive = creditStatus.value === 'Active';

        // Credit limit field
        if (isActive) {
            creditLimitField.classList.remove('disabled');
            creditLimitInput.removeAttribute('disabled');
        } else {
            creditLimitField.classList.add('disabled');
            creditLimitInput.setAttribute('disabled', 'true');
            creditLimitInput.value = '';
        }

        // Credit term radios (Due Days / Outlet) — only usable when Active
        termRadios.forEach(function (radio) {
            if (isActive) {
                radio.removeAttribute('disabled');
            } else {
                radio.checked = false;
                radio.setAttribute('disabled', 'true');
            }
        });

        // Dairy Payment Term toggle — only usable when Active
        if (isActive) {
            dairyToggle.removeAttribute('disabled');
        } else {
            dairyToggle.checked = false;
            dairyToggle.setAttribute('disabled', 'true');
        }

        // Refresh dependent sections since selections may have just been cleared
        toggleTermSections();
        toggleDairySection();
    }

    creditStatus.addEventListener('change', toggleCreditDependentFields);

    // --- Toggle the two mandatory, mutually-exclusive term sections ---
    function toggleTermSections() {
        const selected = document.querySelector('.credit-term-radio:checked');
        const selectedValue = selected ? selected.value : null;

        Object.keys(sections).forEach(function (key) {
            const section = sections[key];
            const inputs = section.querySelectorAll('input');

            if (key === selectedValue) {
                section.classList.remove('disabled');
                inputs.forEach(input => input.removeAttribute('disabled'));
            } else {
                section.classList.add('disabled');
                inputs.forEach(function (input) {
                    if (input.type === 'checkbox') {
                        input.checked = false;
                    }
                    input.setAttribute('disabled', 'true');
                });
            }
        });
    }

    termRadios.forEach(function (radio) {
        radio.addEventListener('change', toggleTermSections);
    });
    toggleTermSections();

    // --- Dairy Payment Term: fully independent, optional toggle ---
    function toggleDairySection() {
        if (dairyToggle.checked) {
            dairySection.classList.remove('disabled');
            dairyInput.removeAttribute('disabled');
        } else {
            dairySection.classList.add('disabled');
            dairyInput.setAttribute('disabled', 'true');
            dairyInput.value = '';
        }
    }

    dairyToggle.addEventListener('change', toggleDairySection);
    toggleDairySection();

    // Run once now that all toggle functions exist, to correctly disable
    // term radios / dairy toggle if Credit Status starts as Inactive
    toggleCreditDependentFields();

    // --- Client-side guard: make sure a mandatory credit term is selected before submit ---
    document.getElementById('editCustomerForm').addEventListener('submit', function (e) {
        const isActive = creditStatus.value === 'Active';
        const selected = document.querySelector('.credit-term-radio:checked');

        if (isActive && !selected) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Credit Term Required',
                text: 'Please select either Due Limit Days or Outlet Payment Term before saving.'
            });
            return false;
        }

        // Re-enable disabled inputs of the selected section right before submit
        // (disabled inputs are excluded from POST data)
        if (isActive && selected) {
            const selectedSection = sections[selected.value];
            if (selectedSection) {
                selectedSection.querySelectorAll('input').forEach(function (input) {
                    input.removeAttribute('disabled');
                });
            }
            creditLimitInput.removeAttribute('disabled');
            if (dairyToggle.checked) {
                dairyInput.removeAttribute('disabled');
            }
        }
    });

    // --- Show SweetAlert for server-side validation errors ---
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Please fix the following',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    @endif

    @if (session('swal_error'))
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: @json(session('swal_error'))
        });
    @endif

});
</script>