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

                                 <div class="row mb-3">
                    <div class="col-md-12">
                        <h3>Rack Receiving Record</h3>
                        <p class="text-muted">
                            Only approved Stock Receivings (GRN) are shown here for rack allocation.
                        </p>
                    </div>
                </div>

                               
                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="stockTable">

                                        <thead class="b-shadow">
                                            <tr>
                                                <th>#</th>
                                                <th>GRN No</th>
                                                <th>Bill No</th>
                                                <th>Vendor</th>
                                                <th>PO No</th>
                                                <th>Receipt Date</th>
                                                <th class="text-end">Grand Total</th>
                                                <th>Status</th>
                                                <th width="180">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($grns as $index => $grn)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>

                                                    <td>
                                                        IGGRN-{{ str_pad($grn->id, 5, '0', STR_PAD_LEFT) }}
                                                    </td>

                                                    <td>
                                                        {{ $grn->bill_no ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $grn->vendor->name ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $grn->purchaseOrder->purchase_order_number ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ \Carbon\Carbon::parse($grn->receipt_date)->format('d-m-Y') }}
                                                    </td>

                                                    <td class="text-end">
                                                        ₹ {{ number_format($grn->grand_total, 2) }}
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="badge bg-success">
                                                            Approved
                                                        </span>
                                                    </td>

                                                   <td class="text-center">
                                                            @if($grn->rackStocks->isEmpty())
                                                                <a href="{{ route('admin.rack.receiving.create', $grn->id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                    Allocate Rack
                                                                </a>
                                                            @else
                                                                <!--<a href="{{ route('admin.rack.receiving.edit', $grn->id) }}"-->
                                                                <!--class="btn btn-sm btn-warning">-->
                                                                <!--    Edit Rack-->
                                                                <!--</a>-->

                                                                <a href="{{ route('admin.rack.receiving.show', $grn->id) }}"
                                                                class="btn btn-sm btn-info">
                                                                    View
                                                                </a>
                                                            @endif
                                                        </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">
                                                        No approved stock receivings found for rack allocation.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                              

                            </div> {{-- card-body --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
