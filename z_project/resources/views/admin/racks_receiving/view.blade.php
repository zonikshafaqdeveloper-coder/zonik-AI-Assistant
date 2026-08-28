@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-11 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <h3 class="mb-4">Rack Allocation – View Only</h3>

                            {{-- GRN Info --}}
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label">GRN No</label>
                                    <input type="text" class="form-control"
                                           value="IGGRN-{{ str_pad($grn->id,5,'0',STR_PAD_LEFT) }}" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Vendor</label>
                                    <input type="text" class="form-control"
                                           value="{{ $grn->vendor->name ?? '-' }}" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Receipt Date</label>
                                    <input type="text" class="form-control"
                                           value="{{ $grn->receipt_date }}" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Bill No</label>
                                    <input type="text" class="form-control"
                                           value="{{ $grn->bill_no ?? '-' }}" readonly>
                                </div>
                            </div>

                            {{-- Rack Allocation Table --}}
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Bay</th>
                                            <th>Column</th>
                                            <th>Floor</th>
                                            <th>Stored Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($grn->rackStocks as $index => $stock)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $stock->product->product_name }}</td>
                                                <td>{{ $stock->batch_no ?? '-' }}</td>
                                                <td>{{ $stock->expiry_date ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $stock->rack_no }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $stock->level_no }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        {{ $stock->slot_no }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ $stock->quantity }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    No rack allocation found for this GRN.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Back --}}
                            <div class="row mt-4">
                                <div class="col-md-4 offset-md-8 d-grid">
                                    <a href="{{ route('admin.rack.receiving.index') }}"
                                       class="btn btn-secondary">
                                        Back
                                    </a>
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
