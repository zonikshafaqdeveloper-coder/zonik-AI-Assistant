@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
    <body>
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">

                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif

                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Purchase Order Approval</h3>
                                    </div>

                                    <p class="card-description">
                    
                                    </p>
                                    <div class="table-responsive">
                                    <div class="col-md-3 ms-auto">
                                        <!-- <a href="{{ route('admin.purchase-orders.create') }}"
                                        class="btn btn-primary w-50">
                                            + Create Purchase Orders
                                        </a> -->
                                    </div>
                              <div class="table-responsive">
    <table class="table table-striped all-package theme-table" id="purchaseOrdersTable">
        <thead class="b-shadow">
            <tr>
                <th>#</th>
                <th>PO No</th>
                <th>Vendor</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($purchaseOrders as $index => $po)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $po->purchase_order_number }}</td>
                    <td>{{ $po->vendor->name }}</td>
                    <td>{{ number_format($po->grand_total, 2) }}</td>
                   <td>
                        @if ($po->status === 'sent')
                            <span class="badge bg-warning">Pending</span>

                        @elseif ($po->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                            
                         @elseif ($po->status === 'received')
                            <span class="badge bg-success">Approved</span>    

                        @elseif ($po->status === 'draft' && $po->rejection_reason)
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>

                    <td>
                        
                        <a href="{{ route('admin.purchase-orders.review', $po->id) }}"
                           class="btn btn-sm btn-primary">
                            Bill View
                        </a>
                        
                          @if(in_array($po->status, ['approved', 'received']))
                            <a href="{{ route('admin.purchase-orders.pdf', $po->id) }}"
                            target="_blank"
                            class="btn btn-sm btn-success">
                                Download PO
                            </a>
                        @endif
                        
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No pending purchase orders found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4 p-3 bg-light border rounded">

    <h5 class="mb-0">
        Total Basic Amount (Without GST):
        <strong>₹ {{ number_format($overallBasicTotal, 2) }}</strong>
    </h5>

</div>
</div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



           
@endsection
