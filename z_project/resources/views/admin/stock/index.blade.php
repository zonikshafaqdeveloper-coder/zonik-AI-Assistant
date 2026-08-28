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
                                    <h3 class="card-title">Stock Receiving Record</h3>

                                    <a href="{{ route('stock.create') }}"
                                       class="btn btn-primary">
                                        + Add Stock Record
                                    </a>
                                </div>
                                 
                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="stockTable">

                                        <thead class="b-shadow">
                                            <tr>
                                                <th>#</th>
                                                <th>GRN No</th>
                                                <th>Supplier</th>
                                                <th>PO No</th>
                                                <th>Receipt Date</th>
                                                <th>Bill No</th>
                                                <th>Total In Qty</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                         <tbody>
                                   
                                    @forelse($stockReceivings as $index => $grn)

                                        @php
                                            $totalInQty = $grn->items->sum('actual_qty');
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>

                                            <td>
                                                IGGRN-{{ str_pad($grn->id, 5, '0', STR_PAD_LEFT) }}
                                            </td>

                                            <td>
                                                {{ $grn->purchaseOrder->vendor->name ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $grn->purchaseOrder->purchase_order_number ?? '-' }}
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($grn->receipt_date)->format('d-m-Y') }}
                                            </td>

                                            <td>
                                                {{ $grn->bill_no ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $totalInQty }}
                                            </td>

                                            <td>
                                            @php
                                                $statusColors = [
                                                    'draft'     => 'secondary',
                                                    'submitted' => 'info',
                                                    'approved'  => 'primary',
                                                    'received'  => 'success',
                                                    'rejected'  => 'danger',
                                                ];

                                                $color = $statusColors[$grn->status] ?? 'dark';
                                            @endphp

                                            <span class="badge bg-{{ $color }}">
                                                {{ ucfirst($grn->status) }}
                                            </span>
                                        </td>

                                            <td class="text-center">

                                        {{-- View (always allowed) --}}
                                        <a href="{{ route('admin.stock-receivings.show', $grn->id) }}"
                                        class="btn btn-sm btn-info">
                                            View
                                        </a>

                                        {{-- Edit (only draft) --}}
                                        @if($grn->status === 'draft')
                                            <a href="{{ route('admin.stock-receivings.edit', $grn->id) }}"
                                            class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                        @endif

                                        {{-- Delete (only draft & no bill) --}}
                                        @if($grn->status === 'draft' && !$grn->vendorBill)
                                            <!-- <form method="POST"
                                                action="{{ route('admin.stock-receivings.destroy', $grn->id) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this draft?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form> -->
                                        @endif

                                        {{-- Convert to Bill (only draft & no bill) --}}
                                        <!--@if($grn->status === 'draft' && !$grn->vendorBill)-->
                                        <!--    <form class="d-inline convert-to-bill-form"-->
                                        <!--        data-action="{{ route('admin.stock-receivings.convert-to-bill', $grn->id) }}">-->
                                        <!--        @csrf-->
                                        <!--        <button type="submit" class="btn btn-sm btn-success">-->
                                        <!--            Convert to Bill-->
                                        <!--        </button>-->
                                        <!--    </form>-->
                                        <!--@endif-->

                                    </td>

                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                No Stock Receiving Records Found
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>


                                    </table>

                                  <!-- Add total quantity function -->                    
                                 <div class="mt-1">
                                <h5>Overall Total Quantity: <strong>{{ $overallTotalQty }}</strong></h5>
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.convert-to-bill-form').forEach(form => {

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const actionUrl = this.dataset.action;
            const token = this.querySelector('input[name="_token"]').value;

            Swal.fire({
                title: 'Convert to Bill?',
                text: 'This will generate vendor bill. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Convert',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#198754'
            }).then((result) => {

                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {

                    if (!res.success) {
                        Swal.fire('Failed', res.message || 'Conversion failed', 'error');
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Converted Successfully',
                        text: 'Vendor bill has been generated.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = res.redirect_url;
                    });

                })
                .catch(() => {
                    Swal.fire('Error', 'Server error occurred', 'error');
                });

            });
        });

    });

});
</script>

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
