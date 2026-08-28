@extends('admin.layouts.appnew')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0">Add Lead Customer</h3>
                                <a href="{{ route('lead-customers.index') }}" class="btn btn-secondary">
                                    Back to List
                                </a>
                            </div>

                            <form id="leadCustomerForm">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Outlet Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="outlet_name" name="outlet_name">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Location Cluster <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="location_cluster" name="location_cluster">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Area <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="area" name="area">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" maxlength="15">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Payment Term (Days) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="payment_term" name="payment_term" min="0" step="0.01">
                                    </div>
                                </div>
                               
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Outbound Sales Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="outbound_sale_name" name="outbound_sale_name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Inbound Account Lead <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="inbound_account_lead" name="inbound_account_lead">
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success" id="saveLeadBtn">
                                        Save Lead Customer
                                    </button>
                                    <a href="{{ route('lead-customers.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('#saveLeadBtn').on('click', function () {

    const payload = {
        customer_name    : $('#customer_name').val().trim(),
        outlet_name      : $('#outlet_name').val().trim(),
        location_cluster : $('#location_cluster').val().trim(),
        area             : $('#area').val().trim(),
        address          : $('#address').val().trim(),
        mobile_number    : $('#mobile_number').val().trim(),
        payment_term     : $('#payment_term').val(),
        outbound_sale_name    : $('#outbound_sale_name').val().trim(),
        inbound_account_lead    : $('#inbound_account_lead').val().trim()
    };

    // basic client-side checks
    for (const [key, value] of Object.entries(payload)) {
        if (value === '' || value === null) {
            Swal.fire('Missing Field', `Please fill in ${key.replace(/_/g, ' ')}.`, 'warning');
            return;
        }
    }

    if (isNaN(payload.payment_term) || parseFloat(payload.payment_term) < 0) {
        Swal.fire('Invalid Payment Term', 'Payment term must be a valid number.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Saving...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch("{{ route('lead-customers.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) {
            let message = data.message || 'Something went wrong';
            if (data.errors) message = Object.values(data.errors).flat().join('\n');
            throw new Error(message);
        }
        return data;
    })
    .then(data => {
        Swal.fire('Success', data.message, 'success').then(() => {
            window.location.href = data.redirect_url;
        });
    })
    .catch(err => {
        Swal.fire('Error', err.message, 'error');
    });
});
</script>

@endsection