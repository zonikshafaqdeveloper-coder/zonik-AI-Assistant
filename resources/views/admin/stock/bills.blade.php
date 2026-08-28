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
                                  <h3> Bills generated from stock receiving entries</h3>
                                </div>
                             
                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="stockTable">

                                        <thead class="b-shadow">

                                         <tr>
                                <th>#</th>
                                <th>Bill No</th>
                                <th>GRN ID</th>
                                <th>PO Number</th>
                                <th>Vendor</th>
                                <th>Bill Date</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Payment Status</th>
                                <th class="text-center">Bill Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($bills as $index => $bill)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                   <td>
                                    @if($bill->stockReceiving->status === 'approved' || $bill->stockReceiving->status === 'approved_with_changes' )
                                        <a href="{{ route('admin.vendor-payments.create', $bill->id) }}"
                                        class="fw-bold text-success">
                                            {{ $bill->bill_no }}
                                        </a>
                                    @else
                                        <strong>{{ $bill->bill_no }}</strong>
                                    @endif
                                  </td>

                                    <td>
                                       IGGRN-{{ str_pad($bill->stock_receiving_id, 5, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td>
                                         {{ $bill->stockReceiving->purchaseOrder->purchase_order_number ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $bill->vendor->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}
                                    </td>

                                    @php
                                        $debitAmount = $bill->stockReceiving->debitNote->total_amount ?? 0;
                                        $displayAmount = $bill->grand_total + $debitAmount;
                                    @endphp

                                    <td class="text-end">
                                        ₹ {{ number_format($displayAmount, 2) }}
                                    </td>


                                    <td class="text-center">
                                        @if($bill->status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($bill->status === 'partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        @if($bill->stockReceiving->status === 'submitted')
                                            <span class="badge bg-success">Pending</span>
                                        @elseif($bill->stockReceiving->status === 'approved')
                                            <span class="badge bg-primary">Approved</span>
                                        @elseif($bill->stockReceiving->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning"> {{ $bill->stockReceiving->status }} </span>
                                        @endif
                                    </td>

                                    <td class="text-center">

                                      @if($bill->stockReceiving->status === 'submitted')
                                        <a href="{{ route('admin.stock-receivings.bills.edit', $bill->id) }}"
                                        class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        @endif

                                        <a href="{{ route('admin.stock-receivings.bills.show', $bill->id)}}"
                                           class="btn btn-sm btn-info">
                                            View & Approval
                                        </a>

            
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        No stock receiving bills found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                                        


                                    </table>
                                    <!-- Add basic value -->
                                    <div class="mb-3">
                                 <h5 class="">
                              Total Basic Amount (Without GST): ₹ {{ number_format($overallBasicAmount, 2) }}
                                </h5>
                              </div>
                            <!--  -->
                                </div>

                              

                            </div> {{-- card-body --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




@endsection
