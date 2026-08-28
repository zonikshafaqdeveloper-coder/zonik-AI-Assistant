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
                                        <h3 class="card-title">Purchase Orders</h3>
                                    </div>

                                    <p class="card-description">
                    
                                    </p>
                                    <div class="table-responsive">
                                    <div class="col-md-3 ms-auto">
                                        <a href="{{ route('admin.purchase-orders.create') }}"
                                        class="btn btn-primary w-50">
                                            + Create Purchase Orders
                                        </a>
                                    </div>
                               <div class="table-responsive">
                                <table class="table table-striped all-package theme-table" id="purchaseOrdersTable">
                                    <thead class="b-shadow">
                                        <tr>
                                            <th>#</th>
                                            <th>PO Number</th>
                                            <th>Vendor Name</th>
                                            <th>PO Date</th>
                                            <th>Grand Total</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($purchaseOrders as $index => $po)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>

                                                <td>{{ $po->purchase_order_number }}</td>

                                                <td>
                                                    {{ $po->vendor->name ?? 'N/A' }}
                                                </td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($po->po_date)->format('d-m-Y') }}
                                                </td>

                                                <td>
                                                    ₹ {{ number_format($po->grand_total, 2) }}
                                                </td>

                                                <td>
                                                    @if ($po->status === 'draft')
                                                        <span class="badge bg-warning">Draft</span>
                                                    @elseif ($po->status === 'sent')
                                                        <span class="badge bg-info">Sent For Approval</span>
                                                    @elseif ($po->status === 'approved')
                                                        <span class="badge bg-primary">Approved By Admin</span>
                                                    @elseif ($po->status === 'received')
                                                        <span class="badge bg-success">Received</span>
                                                    @elseif ($po->status === 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <a href="{{ route('admin.purchase-orders.show', $po->id) }}"
                                                    class="btn btn-primary">
                                                        Bill View
                                                    </a>
                                                      @if(in_array($po->status, ['approved', 'received']))
                                                    <a href="{{ route('admin.purchase-orders.pdf', $po->id) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-success">
                                                            Download PO
                                                        </a>
                                                    @endif

                                               @if (in_array($po->status, ['draft', 'sent','approved']))
                                              <a href="{{ route('admin.purchase-orders.edit', $po->id) }}"
                                               class="btn btn-warning ms-1">
                                                 Edit
                                                 </a>
                                                @endif

                                                <!--   <form id="delete-form-{{ $po->id }}"-->
                                                <!--        action="{{ route('admin.purchase-orders.destroy', $po->id) }}"-->
                                                <!--        method="POST"-->
                                                <!--        class="d-none">-->
                                                <!--        @csrf-->
                                                <!--        @method('DELETE')-->
                                                <!--    </form>-->


                                                <!--   <button type="button"-->
                                                <!--        class="btn btn-danger ms-1"-->
                                                <!--        onclick="confirmDelete('{{ $po->id }}')">-->
                                                <!--    </i> Delete-->
                                                <!--</button>-->
                                               



                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    No Purchase Orders Found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <!-- Add total basic value -->
                                 <h5 class="mb-3">
    Total Basic Amount: ₹ {{ number_format($overallBasicAmount, 2) }}
</h5>
                                <!-- Add total basic value -->
                            </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This Purchase Order will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>


           
@endsection
