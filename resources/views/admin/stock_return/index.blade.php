@extends('admin.layouts.appnew')
@section('content')

<style>
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    .badge-approved {
        background-color: #28a745;
        color: #fff;
    }

    .badge-rejected {
        background-color: #dc3545;
        color: #fff;
    }
</style>

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
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">
                                        Stock Return Requests
                                    </h3>
                                </div>

                                <p class="card-description">
                                    Manage stock return and revised invoice requests
                                </p>


                                <div class="row mb-3">
                                    <div class="col-md-3 ms-auto">
                                        <a href="{{ route('stock-return.create') }}"
                                           class="btn btn-primary w-50">

                                            + New Revise Invoice

                                        </a>
                                    </div>
                                </div>


                                {{-- STATUS FILTER --}}
                                <div class="row mb-3">

                                    <div class="col-md-3">

                                        <form method="GET">

                                            <label class="form-label">
                                                Status
                                            </label>

                                            <select name="status"
                                                    class="form-select"
                                                    onchange="this.form.submit()">

                                                <option value="all"
                                                    {{ request('status') == 'all' ? 'selected' : '' }}>
                                                    All Statuses
                                                </option>

                                                <option value="pending"
                                                    {{ request('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>

                                                <option value="approved"
                                                    {{ request('status') == 'approved' ? 'selected' : '' }}>
                                                    Approved
                                                </option>

                                                <option value="rejected"
                                                    {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                                    Rejected
                                                </option>

                                            </select>

                                        </form>

                                    </div>

                                </div>


                                {{-- TABLE --}}
                                <div class="table-responsive">

                                    <table class="table table-striped table-bordered" id="nonRunningTable">


                                        <thead class="table-dark">

                                            <tr>
                                                <th>#</th>
                                                <th>Order</th>
                                                <th>Requested By</th>
                                                <th>Items</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th class="text-center">
                                                    Action
                                                </th>
                                            </tr>

                                        </thead>


                                        <tbody>

                                            @forelse($requests as $index => $req)

                                                @php

                                                    $statusClass = match ($req->status) {
                                                        'pending'  => 'badge-pending',
                                                        'approved' => 'badge-approved',
                                                        'rejected' => 'badge-rejected',
                                                        default    => 'bg-dark'
                                                    };

                                                @endphp


                                                <tr>

                                                    {{-- SERIAL NUMBER --}}
                                                    <td>
                                                        @if(method_exists($requests, 'firstItem'))
                                                            {{ $requests->firstItem() + $index }}
                                                        @else
                                                            {{ $index + 1 }}
                                                        @endif
                                                    </td>


                                                    {{-- ORDER --}}
                                                    <td>
                                                        {{ $req->order->order_id ?? '-' }}
                                                    </td>


                                                    {{-- REQUESTED BY --}}
                                                    <td>
                                                        {{ $req->requestedBy->name ?? '-' }}
                                                    </td>


                                                    {{-- ITEMS --}}
                                                    <td>
                                                        {{ $req->items->count() }}
                                                    </td>


                                                    {{-- STATUS --}}
                                                    <td>

                                                        <span class="badge {{ $statusClass }}">
                                                            {{ ucfirst($req->status) }}
                                                        </span>

                                                    </td>


                                                    {{-- DATE --}}
                                                    <td>

                                                        {{ $req->created_at
                                                            ? $req->created_at->format('d M Y')
                                                            : '-' }}

                                                    </td>


                                                    {{-- ACTION --}}
                                                    <td class="text-center">

                                                        <a href="{{ route('stock-return.show', $req->id) }}"
                                                           class="btn btn-sm btn-warning">

                                                            View

                                                        </a>


                                                        @if($req->status === 'pending')

                                                            <!-- <a href="{{ route('stock-return.edit', $req->id) }}"
                                                               class="btn btn-sm btn-primary ms-1">

                                                                Edit

                                                            </a> -->

                                                        @endif

                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>

                                                    <td colspan="7"
                                                        class="text-center text-muted">

                                                        No stock return requests found

                                                    </td>

                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>


                                {{-- PAGINATION --}}
                                <!--@if(method_exists($requests, 'hasPages') && $requests->hasPages())-->

                                <!--    <div class="mt-3">-->

                                <!--        {{ $requests->withQueryString()->links() }}-->

                                <!--    </div>-->

                                <!--@endif-->


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