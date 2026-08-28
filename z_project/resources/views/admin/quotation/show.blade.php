@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0">Quotation — {{ $quotation->quotation_number }}</h3>
                                <div>
                                    <a href="{{ route('quotations.invoice', $quotation->id) }}" target="_blank" class="btn btn-sm" style="background:#e97457;color:#fff;">Invoice</a>
                                    <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('quotations.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4 mb-2">
                                    <strong>Outlet Name:</strong>
                                    <div>{{ $quotation->leadCustomer->outlet_name ?? '-' }}</div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Customer Name:</strong>
                                    <div>{{ $quotation->leadCustomer->customer_name ?? '-' }}</div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Mobile Number:</strong>
                                    <div>{{ $quotation->leadCustomer->mobile_number ?? '-' }}</div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Address:</strong>
                                    <div>{{ $quotation->leadCustomer->address ?? '-' }}</div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Payment Term:</strong>
                                    <div>{{ $quotation->leadCustomer->payment_term ?? '-' }}</div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Quotation Date:</strong>
                                    <div>{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-m-Y') }}</div>
                                </div>
                            </div>

                            <hr>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Cost Per Item</th>
                                            <th>Sale Price (Basic)</th>
                                            <th>Profit Margin %</th>
                                            <th>Customer Price</th>
                                            <th>Total Saving %</th>
                                            <th>Last GRN Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quotation->items as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item->product->product_name ?? 'Unknown Product' }}</td>
                                            <td>{{ $item->brand }}</td>
                                            <td>{{ $item->category }}</td>
                                            <td>₹{{ number_format($item->cost_per_item, 2) }}</td>
                                            <td>₹{{ number_format($item->sale_price_basic, 2) }}</td>
                                            <td>{{ $item->profit_margin }}%</td>
                                            <td>₹{{ number_format($item->customer_price, 2) }}</td>
                                            <td>{{ $item->total_saving_percent }}%</td>
                                            <td>{{ $item->last_grn_date ? \Carbon\Carbon::parse($item->last_grn_date)->format('d-m-Y') : 'No GRN Yet' }}</td>
                                        </tr>
                                        @endforeach
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