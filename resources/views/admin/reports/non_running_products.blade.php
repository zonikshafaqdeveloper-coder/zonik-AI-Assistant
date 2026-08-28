@extends('admin.layouts.appnew')

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Non-Running Products Report</h3>
                        <span class="text-muted small">Stock received 30+ days ago (via GRN) with no sale in the last 30 days</span>
                    </div>

                    {{-- Summary cards --}}
                    @php
                        $totalItems = count($report);
                        $totalQty   = collect($report)->sum('quantity');
                        $totalValue = collect($report)->sum('stock_value');
                        $neverSold  = collect($report)->where('last_sale_date', 'Never Sold')->count();
                    @endphp

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="text-muted small">Non-Running Batches</div>
                                    <div class="h4 mb-0">{{ $totalItems }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="text-muted small">Total Quantity Stuck</div>
                                    <div class="h4 mb-0">{{ number_format($totalQty) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="text-muted small">Blocked Stock Value</div>
                                    <div class="h4 mb-0">₹{{ number_format($totalValue, 2) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="text-muted small">Never Sold (since GRN)</div>
                                    <div class="h4 mb-0 text-danger">{{ $neverSold }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="nonRunningTable">

                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Vendor</th>
                                    <th>Batch No</th>
                                    <th>Expiry</th>
                                    <th>Rack Location</th>
                                    <th>GRN Bill No</th>
                                    <th>Receipt Date</th>
                                    <th>Days Since GRN</th>
                                    <th>Qty</th>
                                    <th>Purchase Price</th>
                                    <th>Stock Value</th>
                                    <th>Last Sale Date</th>
                                    <th>Days Since Last Sale</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($report as $i => $row)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $row['product'] }}</td>
                                    <td>{{ $row['brand'] }}</td>
                                    <td>{{ $row['category'] }}</td>
                                    <td>{{ $row['vendor_name'] }}</td>
                                    <td>{{ $row['batch_no'] }}</td>
                                    <td>{{ $row['expiry_date'] ? \Carbon\Carbon::parse($row['expiry_date'])->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $row['rack_location'] }}</td>
                                    <td>{{ $row['bill_no'] }}</td>
                                    <td>{{ $row['receipt_date'] }}</td>
                                    <td>{{ $row['days_since_grn'] }}</td>
                                    <td>{{ number_format($row['quantity']) }}</td>
                                    <td>₹{{ number_format($row['purchase_price'], 2) }}</td>
                                    <td>₹{{ number_format($row['stock_value'], 2) }}</td>

                                    <td class="last_sale">
                                        @if($row['last_sale_date'] === 'Never Sold')
                                            <span class="badge bg-danger">Never Sold</span>
                                        @else
                                            {{ $row['last_sale_date'] }}
                                        @endif
                                    </td>

                                    <td class="days_since">
                                        <span class="badge {{ $row['days_since_last_sale'] > 60 ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $row['days_since_last_sale'] }} days
                                        </span>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="16" class="text-center text-muted py-4">
                                        No non-running products found. Everything is moving 🎉
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection