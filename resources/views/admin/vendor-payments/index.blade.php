@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">

                            <div class="card-body">

                                {{-- Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="card-title">Vendor Payments</h3>
                                </div>



                <div class="table-responsive">
                    <table class="table all-package theme-table" id="stock_table">
                        <thead class="b-shadow ">
                            <tr>
                               <th>#</th>
                                <th>Bill No</th>
                                <th>Vendor</th>
                                <th>Bill Amount</th>
                                <th>Paid Amount</th>
                                <th>Status</th>
                                <th width="220">Action</th>
                            </tr>
                        </thead>
                     <tbody>
@forelse($bills as $index => $bill)
    @php $paid = $bill->payments->sum('amount'); @endphp
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $bill->bill_no }}</td>
        <td>{{ $bill->vendor->name ?? '-' }}</td>
        <td>₹ {{ number_format($bill->grand_total, 2) }}</td>
        <td>₹ {{ number_format($paid, 2) }}</td>
        <td>
            <span class="badge 
                {{ $bill->status === 'paid' ? 'bg-success' : ($bill->status === 'partial' ? 'bg-warning' : 'bg-danger') }}">
                {{ ucfirst($bill->status) }}
            </span>
        </td>
        <td>
    @if($bill->status !== 'paid')
        <a href="{{ route('admin.vendor-payments.create', $bill->id) }}"
           class="btn btn-sm btn-primary">
            Pay
        </a>
    @endif

    <a href="{{ route('admin.vendor-payments.show', $bill->id) }}"
       class="btn btn-sm btn-info">
        Paymnet histories
    </a>
</td>

    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted">No vendor bills found</td>
    </tr>
@endforelse
</tbody>

                    </table>
                </div>

              


                              

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
@endsection
