@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                             <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">{{ $pageTitle }}</h3>
                               

                                <div>
                                    <a href="{{ route('admin.reports.inventory-details.export', $type) }}" class="btn btn-success">
                                        <i class="fa fa-file-excel"></i> Export Excel
                                    </a>

                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back to Dashboard</a>


                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="nonRunningTable">

                                    @if($reportType === 'stock')
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Batch No</th>
                                            <th>Quantity</th>
                                            @if($type === 'expired')
                                                <th>Expiry Date</th>
                                                <th>Days Expired</th>
                                            @elseif($type === 'near_expiry')
                                                <th>Expiry Date</th>
                                                <th>Days To Expiry</th>
                                            @elseif($type === 'non_moving')
                                                <th>Last Sale Date</th>
                                                <th>Days Since Last Sale</th>
                                            @endif
                                            <th>Rack Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($details as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $row->product_name }}</td>
                                            <td>{{ $row->brand }}</td>
                                            <td>{{ $row->category }}</td>
                                            <td>{{ $row->batch_no }}</td>
                                            <td>{{ $row->quantity }}</td>
                                            @if($type === 'expired')
                                                <td>{{ \Carbon\Carbon::parse($row->expiry_date)->format('d-m-Y') }}</td>
                                                <td><span class="badge bg-danger">{{ $row->days_expired }} days</span></td>
                                            @elseif($type === 'near_expiry')
                                                <td>{{ \Carbon\Carbon::parse($row->expiry_date)->format('d-m-Y') }}</td>
                                                <td><span class="badge bg-warning">{{ $row->days_to_expiry }} days</span></td>
                                            @elseif($type === 'non_moving')
                                                <td>{{ $row->last_sale_date }}</td>
                                                <td><span class="badge bg-secondary">{{ $row->days_since_last_sale }} days</span></td>
                                            @endif
                                            <td>{{ $row->rack_no }}/{{ $row->level_no }}/{{ $row->slot_no }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="9" class="text-center text-muted py-4">No records found.</td></tr>
                                        @endforelse
                                    </tbody>

                                    @else

                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Vendor</th>
                                            <th>Stock</th>
                                            <th>Last 30 Days</th>
                                            <th>Daily Consumption</th>
                                            <th>ROP (NOS)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($details as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $row['product'] }}</td>
                                            <td>{{ $row['brand'] }}</td>
                                            <td>{{ $row['category'] }}</td>
                                            <td>{{ $row['vendor_name'] }}</td>
                                            <td>{{ $row['stock'] }}</td>
                                            <td>{{ $row['last_30_days'] }}</td>
                                            <td>{{ $row['daily_consumption'] }}</td>
                                            <td>{{ $row['rop_nos'] }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match($row['status']) {
                                                        'CRITICAL' => 'bg-danger',
                                                        'REORDER'  => 'bg-warning',
                                                        'WATCH'    => 'bg-info',
                                                        'CAREFUL'  => 'bg-secondary',
                                                        default    => 'bg-success',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $row['status'] }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="10" class="text-center text-muted py-4">No records found.</td></tr>
                                        @endforelse
                                    </tbody>

                                    @endif

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