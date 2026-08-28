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
                                    <h3 class="card-title">Stock Damaged</h3>

                                    <a href="{{ route('stock-damaged.create') }}" class="btn btn-primary">
                                        + Create Damaged
                                    </a>
                                </div>

                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="stock_table">
                                        <thead class="b-shadow">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Batch</th>
                                                <th>Expiry</th>
                                                <th>Type</th>
                                                <th>Qty</th>
                                                <th>Unit Cost</th>
                                                <th>Total Value</th>
                                                <th>Reason</th>
                                                <th>Disposed By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($disposals as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>

                                                    <td>
                                                        {{ $item->product->product_name ?? '-' }}
                                                    </td>

                                                    <td>{{ $item->batch_no ?? '-' }}</td>

                                                    <td>
                                                        {{ $item->expiry_date 
                                                            ? \Carbon\Carbon::parse($item->expiry_date)->format('d-m-Y') 
                                                            : '-' }}
                                                    </td>

                                                    <td>
                                                        @if($item->stock_type == 'GRN')
                                                            <span class="badge bg-success">GRN</span>
                                                        @else
                                                            <span class="badge bg-info">Opening</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-warning text-dark">
                                                            {{ $item->quantity }}
                                                        </span>
                                                    </td>

                                                    <td>₹ {{ number_format($item->unit_cost, 2) }}</td>

                                                    <td>
                                                        <strong class="text-danger">
                                                            ₹ {{ number_format($item->total_value, 2) }}
                                                        </strong>
                                                    </td>

                                                    <td>
                                                        {{ $item->reason ?? '—' }}
                                                    </td>

                                                    <td>
                                                        {{ $item->user->name ?? 'System' }}
                                                    </td>

                                                    <td>
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}
                                                    </td>
                                                </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center text-muted">
                                                        No disposal records found
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
</div>

@endsection