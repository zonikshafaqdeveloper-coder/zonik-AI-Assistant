@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.select2-container .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
    opacity: 1 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
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
                            <div class="card-body">

                                <div class="card-header-2 mb-3">
                                    <h3>Pay Vendor Bill</h3>
                                </div>

                                
                <form method="POST"
                action="{{ route('admin.vendor-payments.store') }}"
                enctype="multipart/form-data"
                id="paymentForm">
                @csrf

                <input type="hidden" name="vendor_bill_id" value="{{ $bill->id }}">

                <div class="card-body">
                    <div class="row g-3">

                        {{-- Bill No --}}
                        <div class="col-md-4">
                            <label>Bill No</label>
                            <input class="form-control" value="{{ $bill->bill_no }}" readonly>
                        </div>

                        {{-- Grand Total --}}
                        <div class="col-md-4">
                            <label>Bill Amount</label>
                            <input class="form-control"
                                id="billTotal"
                                value="{{ number_format($bill->grand_total, 2) }}"
                                readonly>
                        </div>

                        {{-- Total Paid --}}
                        <div class="col-md-4">
                            <label>Total Paid</label>
                            <input class="form-control"
                                id="totalPaid"
                                value="{{ number_format($paid, 2) }}"
                                readonly>
                        </div>

                        {{-- Pending Amount --}}
                        <div class="col-md-4">
                            <label>Pending Amount</label>
                            <input class="form-control"
                                id="pendingAmount"
                                value="{{ number_format($bill->grand_total - $paid, 2) }}"
                                readonly>
                        </div>

                        {{-- Payment Date --}}
                        <div class="col-md-4">
                            <label>Payment Date</label>
                            <input type="date"
                                name="payment_date"
                                class="form-control"
                                required>
                        </div>

                        {{-- Payment Mode --}}
                        <div class="col-md-4">
                            <label>Payment Mode</label>
                            <select name="payment_mode"
                                    class="form-control select2"
                                    required>
                                <option value="">Select</option>
                                <option value="upi">UPI</option>
                                <option value="cash">Cash</option>
                                <option value="razorpay">Razorpay</option>
                                <option value="cheque">Cheque</option>
                                <option value="neft">NEFT</option>
                                <option value="imps">IMPS</option>
                            </select>
                        </div>

                        {{-- Amount --}}
                        <div class="col-md-4">
                            <label>Amount</label>
                            <input type="number"
                                step="0.01"
                                name="amount"
                                id="payAmount"
                                class="form-control"
                                required>
                        </div>

                        {{-- Reference --}}
                        <div class="col-md-4">
                            <label>Reference No</label>
                            <input type="text"
                                name="reference_no"
                                class="form-control"
                                required>
                        </div>

                        {{-- Document --}}
                        <div class="col-md-4">
                            <label>Payment Document</label>
                            <input type="file"
                                name="payment_document"
                                class="form-control"
                                required>
                        </div>

                        {{-- Remarks --}}
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea name="remarks"
                                    class="form-control"
                                    rows="4"></textarea>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <button class="btn btn-success">Save Payment</button>
                    <a href="{{ route('admin.vendor-payments.index') }}"
                    class="btn btn-secondary">
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
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    $('.select2').select2({
        width: '100%'
    });

    $('#payAmount').on('input', function () {

        const pending = parseFloat($('#pendingAmount').val().replace(/,/g, '')) || 0;
        const amount  = parseFloat($(this).val()) || 0;

        if (amount > pending) {
            Swal.fire(
                'Invalid Amount',
                'Payment amount cannot exceed pending amount.',
                'warning'
            );
            $(this).val(pending.toFixed(2));
        }
    });

    $('#paymentForm').on('submit', function (e) {

        const pending = parseFloat($('#pendingAmount').val().replace(/,/g, '')) || 0;
        const amount  = parseFloat($('#payAmount').val()) || 0;

        if (amount <= 0 || amount > pending) {
            e.preventDefault();

            Swal.fire(
                'Invalid Payment',
                'Please enter a valid amount not exceeding pending balance.',
                'error'
             ).then(() => {
            $('#payAmount').val('').focus();
        });
        }
    });
});
</script>


@endsection
