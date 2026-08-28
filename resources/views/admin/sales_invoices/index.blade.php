@extends('admin.layouts.appnew')
@section('content')

<style>
.badge-received {
    background-color: #ffc107;
    color: #000;
}

.badge-accepted {
    background-color: #17a2b8; 
    color: #fff;
}

.badge-dispatched {
    background-color: #6f42c1; 
    color: #fff;
}

.badge-delivered {
    background-color: #28a745;
    color: #fff;
}

.badge-cancelled {
    background-color: #dc3545;
    color: #fff;
}

.badge-final-check {
    background-color: #fd7e14;
    color: #fff;
}

</style>

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
                                        <h3 class="card-title">Customer Sale Order</h3>
                                    </div>

                                    <p class="card-description">
                    
                                    </p>
                                    <div class="table-responsive">
                                    <div class="col-md-3 ms-auto">
                                        <a href="{{ route('admin.invoice.create') }}"
                                        class="btn btn-primary w-50">
                                            + Create Sale Order
                                        </a>
                                    </div>
  <div class="table-responsive">
                                     <table class="table table-striped table-bordered" id="nonRunningTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Order No</th>
                                          <th>Outlet Name</th>
                                        <!--<th>Customer Name</th>-->
                                        <th>Company Name</th>
                                         <th>Order Status</th>
                                        <th>Created At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($orders as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>

                                           <td>SO-{{ sprintf('%04d', $row->order_id) }}</td>


                                           <td>{{ $row->order->outlet->outlet_name ?? 'N/A' }}</td>
                                            <!--<td>{{ $row->customer->name ?? 'N/A' }}</td>-->
                                            <td>{{ $row->customer->outlet_name ?? 'N/A' }}</td>
                                          
                                            
                                             @php
                                            $status = $row->order?->latestDelivery?->delivery_status ?? 'pending';
                                                $statusMap = [
                                                    'pending' => 'Received',
                                                    'in_progress' => 'Accepted',
                                                    'final_check_done' => 'Final Check Done',
                                                    'ready_for_dispatch' => 'Dispatched',
                                                    'delivered' => 'Delivered',
                                                    'cancelled' => 'Cancelled',
                                                ];

                                       $statusClass = match ($status) {
                                                'pending' => 'badge-received',
                                                'in_progress' => 'badge-accepted',
                                                'final_check_done' => 'badge-final-check', // NEW COLOR
                                                'ready_for_dispatch' => 'badge-dispatched',
                                                'delivered' => 'badge-delivered',
                                                'cancelled' => 'badge-cancelled',
                                                default => 'bg-dark'
                                            };
                                            @endphp

                                            <td>
                                                <span class="badge {{ $statusClass }}">
                                                    {{ $statusMap[$status] ?? ucfirst($status) }}
                                                </span>
                                            </td>

                                            <td>{{ $row->created_at->format('d M Y') }}</td>

                                            <td class="text-center">
                                                {{-- VIEW --}}
                                                <a href="{{ route('admin.orders.show', $row->id) }}"
                                                class="btn btn-sm btn-warning">
                                                    View
                                                </a>
                                                
                                            @if($status === 'pending')
                                                <a href="{{ route('admin.orders.edit', $row->order_id) }}"
                                                   class="btn btn-sm btn-primary ms-1">
                                                    Edit
                                                </a>
                                            @endif


                                               {{-- DELETE --}}
                                            <!--<form id="delete-form-{{ $row->order_id }}"-->
                                            <!--    action="{{ route('admin.orders.destroy', $row->order_id) }}"-->
                                            <!--    method="POST"-->
                                            <!--    class="d-none">-->
                                            <!--    @csrf-->
                                            <!--    @method('DELETE')-->
                                            <!--</form>-->

                                            <!--<button type="button"-->
                                            <!--        class="btn btn-sm btn-danger ms-1"-->
                                            <!--        onclick="confirmDelete({{ $row->order_id }})">-->
                                            <!--    Delete-->
                                            <!--</button>-->

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No invoices found
                                            </td>
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

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This invoice will be permanently deleted.',
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
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if (session()->has('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
        });
    @endif

    @if (session()->has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
        });
    @endif

});
</script>
@endsection
