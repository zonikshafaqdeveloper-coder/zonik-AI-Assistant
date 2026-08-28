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
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif

        <h4 class="mb-4">Pick List (Warehouse)</h4>

        <div class="table-responsive">
                 <table class="table table-striped all-package theme-table" id="pick_list">
                <thead class="b-shadow">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Outlet Name</th>
                        <th>Rack No</th>
                        <th>No of Boxes</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
               <tbody>
                    @forelse($orders as $i => $order)

                    @php
                        $deliveryStatus = optional($order->latestDelivery)->delivery_status;
                        $locked = in_array($deliveryStatus, ['in_progress','ready_for_dispatch','delivered','cancelled']);
                    @endphp

                    <tr>
                        <td>{{ $i+1 }}</td>

                        <td>{{ $order->order_id }}</td>

                        <td> {{ $order->outlet->outlet_name ?? $order->outlet->name ?? 'N/A' }}</td>

                        <td>{{ $order->logistics->dispatched_rack ?? '-' }}</td>

                        <td>{{ $order->logistics->number_of_boxes ?? '-' }}</td>

                        <td>
                            @if($locked)
                                <span class="badge bg-success">ACCEPTED</span>
                            @else
                                <span class="badge bg-warning">PENDING</span>
                            @endif
                        </td>

                        <td>
                            <!-- VIEW -->
                           <a href="{{ route('pick.list.view', $order->id) }}"
                            class="btn btn-sm btn-info">
                            View
                            </a>

                            @if(!$locked)
                            <a href="{{ route('pick.list.edit', $order->id) }}"
                            class="btn btn-sm btn-primary">
                            Edit
                            </a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                            Locked
                            </button>
                            @endif

                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No orders found
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
@endsection

<script>
function markPicked(id){
    Swal.fire({
        title: 'Confirm Pick',
        text: 'Are you sure this item is picked?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Picked'
    }).then((result) => {
        if(result.isConfirmed){
            $.post("{{ url('/pick-list') }}/" + id + "/picked", {
                _token: "{{ csrf_token() }}"
            }, function(resp){
                if(resp.status){
                    Swal.fire('Done', resp.message, 'success')
                        .then(()=>location.reload());
                } else {
                    Swal.fire('Error', 'Unable to update', 'error');
                }
            });
        }
    });
}
</script>
