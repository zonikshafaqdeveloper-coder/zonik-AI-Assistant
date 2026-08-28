@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 m-auto">

                <div class="card">
                    <div class="card-header">
                        <strong>Vendor Payment Details</strong>
                    </div>

                    <div class="card-body">

                        {{-- =====================
                            BILL SUMMARY
                        ====================== --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Bill No</label>
                                <input class="form-control" value="{{ $bill->bill_no }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label>Vendor</label>
                                <input class="form-control"
                                       value="{{ $bill->vendor->name ?? '-' }}"
                                       readonly>
                            </div>

                            <div class="col-md-4">
                                <label>Status</label>
                                <input class="form-control"
                                       value="{{ ucfirst($bill->status) }}"
                                       readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label>Bill Amount</label>
                                <input class="form-control"
                                       value="₹ {{ number_format($bill->grand_total, 2) }}"
                                       readonly>
                            </div>

                            <div class="col-md-4">
                                <label>Total Paid</label>
                                <input class="form-control"
                                       value="₹ {{ number_format($totalPaid, 2) }}"
                                       readonly>
                            </div>

                            <div class="col-md-4">
                                <label>Pending Amount</label>
                                <input class="form-control"
                                       value="₹ {{ number_format($pending, 2) }}"
                                       readonly>
                            </div>
                        </div>

                        {{-- =====================
                            PAYMENT HISTORY
                        ====================== --}}
                        <h5 class="mb-2">Payment History</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Mode</th>
                                        <th>Reference</th>
                                        <th>Document</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bill->payments as $index => $payment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>₹ {{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->payment_mode) }}</td>
                                            <td>{{ $payment->reference_no ?? '-' }}</td>
                                            <td>
                                                @if($payment->payment_document)
                                                    <a href="{{ asset('uploads/vendor_payments/'.$payment->payment_document) }}"
                                                       target="_blank">
                                                        View
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                No payments recorded
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('admin.vendor-payments.index') }}"
                           class="btn btn-secondary">
                            Back
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
