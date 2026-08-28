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
                                   <h3>Stock Receiving Pending</h3>
                                <p class="text-muted">
                                    Purchase Orders where stock is not fully received yet
                                </p>
                                    

                                    <!-- <a href="{{ route('stock.create') }}"
                                       class="btn btn-primary">
                                        + Add Stock Record
                                    </a> -->
                                </div>

                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="stockTable">

                                        <thead class="b-shadow">
                                              <tr>
                                                <th>#</th>
                                                <th>PO Number</th>
                                                <th>Vendor</th>
                                                <th>PO Date</th>
                                                <th class="text-end">Total Qty</th>
                                                <th class="text-end">Received Qty</th>
                                                <th class="text-end">Pending Qty</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                        <tbody>
                            @forelse($pendingPOs as $index => $po)

                                @php
                                    $totalQty = $po->items->sum('quantity');
                                    $receivedQty = $po->items->sum('received_qty');
                                    $pendingQty = $totalQty - $receivedQty;
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <strong>{{ $po->purchase_order_number }}</strong>
                                    </td>

                                    <td>
                                        {{ $po->vendor->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($po->order_date)->format('d-m-Y') }}
                                    </td>

                                    <td class="text-end">
                                        {{ number_format($totalQty, 2) }}
                                    </td>

                                    <td class="text-end text-success">
                                        {{ number_format($receivedQty, 2) }}
                                    </td>

                                    <td class="text-end text-danger">
                                        {{ number_format($pendingQty, 2) }}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('stock.create', ['po_id' => $po->id]) }}"
                                           class="btn btn-sm btn-success">
                                            Receive Stock
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        No pending stock receiving found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>


                                    </table>
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

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'warning',
        title: 'Rack Allocation Pending',
        text: @json(session('error')),
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'View Rack Allocation',
        cancelButtonColor: '#3085d6',
        confirmButtonColor: '#d33',
        reverseButtons: true
    }).then((result) => {
        @if(session('rack_allocation_id'))
        if (result.dismiss === Swal.DismissReason.cancel) {
            window.open(`/admin/rack-receiving/{{ session('rack_allocation_id') }}/create`, '_blank');
        }
        @endif
    });
});
</script>
@endif



@endsection
