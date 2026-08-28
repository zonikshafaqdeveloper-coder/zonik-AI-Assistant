@extends('admin.layouts.appnew')

@section('content')

<style>
    .revision-summary-card {
        border: 1px solid #eef0f3;
        border-radius: 12px;
    }

    .revision-item-table td,
    .revision-item-table th {
        vertical-align: middle;
        font-size: 13px;
    }

    .revision-price-row td {
        background: #fffdf5;
    }

    .revision-addition-row td {
        background: #f6fbff;
    }

    .revision-type-info {
        font-size: 11px;
        color: #667085;
        margin-top: 3px;
    }

    .revision-price {
        font-weight: 600;
        font-size: 14px;
    }

    .revision-muted {
        color: #98a2b3;
        font-size: 12px;
    }
</style>


<div class="page-body">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12 my-5">

                <div class="col-sm-12 m-auto">


                    {{-- ================= HEADER ================= --}}

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h3>
                            Revised Invoice Request #{{ $returnRequest->id }}
                        </h3>

                        <a
                            href="{{ url()->previous() }}"
                            class="btn btn-secondary"
                        >
                            ← Back
                        </a>

                    </div>



                    {{-- ================= REQUEST DETAILS ================= --}}

                    <div class="card mb-3 revision-summary-card">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-3">

                                    <strong>Order</strong>

                                    <br>

                                    {{ $returnRequest->order->order_id ?? '-' }}

                                </div>


                                <div class="col-md-3">

                                    <strong>Requested By</strong>

                                    <br>

                                    {{ $returnRequest->requestedBy->name ?? '-' }}

                                </div>


                                <div class="col-md-3">

                                    <strong>Status</strong>

                                    <br>


                                    @if($returnRequest->status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>


                                    @elseif($returnRequest->status === 'approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>


                                    @else

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @endif

                                </div>


                                <div class="col-md-3">

                                    <strong>Requested On</strong>

                                    <br>

                                    {{ $returnRequest->created_at->format('d-m-Y H:i') }}

                                </div>

                            </div>



                            {{-- ================= APPROVAL DETAILS ================= --}}

                            @if($returnRequest->status !== 'pending')

                                <hr>


                                <div class="row">

                                    <div class="col-md-4">

                                        <strong>

                                            {{ $returnRequest->status === 'approved'
                                                ? 'Approved'
                                                : 'Rejected'
                                            }}
                                            By

                                        </strong>

                                        <br>

                                        {{ $returnRequest->approvedBy->name ?? '-' }}

                                    </div>


                                    <div class="col-md-4">

                                        <strong>Date</strong>

                                        <br>

                                        {{ $returnRequest->approved_at
                                            ? \Carbon\Carbon::parse(
                                                $returnRequest->approved_at
                                            )->format('d-m-Y H:i')
                                            : '-'
                                        }}

                                    </div>


                                    <div class="col-md-4">

                                        <strong>Reason</strong>

                                        <br>

                                        {{ $returnRequest->reject_reason ?? '-' }}

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>



                    {{-- ================= ITEMS ================= --}}

                    <div class="card revision-summary-card">

                        <div class="card-body">

                            <h5 class="mb-3">
                                Revision Items
                            </h5>


                            <div class="table-responsive">

                                <table
                                    class="table table-bordered align-middle revision-item-table"
                                >

                                    <thead class="table-light">

                                        <tr>

                                            <th style="width:140px;">
                                                Revision Type
                                            </th>

                                            <th>
                                                Product
                                            </th>

                                            <th style="width:110px;">
                                                Quantity
                                            </th>

                                            <th style="width:150px;">
                                                Price
                                            </th>

                                            <th style="width:200px;">
                                                Original Location
                                            </th>

                                            <th style="width:200px;">
                                                Return Location
                                            </th>

                                            <th style="width:140px;">
                                                Batch / Expiry
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($returnRequest->items as $item)

                                            @php

                                                $returnQty = (int) (
                                                    $item->return_qty ?? 0
                                                );


                                                /*
                                                 * NEW PRODUCT
                                                 */
                                                $isAddition =
                                                    $item->item_type === 'addition';


                                                /*
                                                 * PRICE ONLY
                                                 *
                                                 * Existing product
                                                 * Return Qty = 0
                                                 */
                                                $isPriceOnly =
                                                    $item->item_type === 'return'
                                                    && $returnQty === 0;


                                                /*
                                                 * ACTUAL STOCK RETURN
                                                 */
                                                $isStockReturn =
                                                    $item->item_type === 'return'
                                                    && $returnQty > 0;


                                                /*
                                                 * Revised customer price.
                                                 */
                                                $newPrice =
                                                    $item->customer_price
                                                    ?? $item->purchase_rate
                                                    ?? 0;

                                            @endphp


                                            <tr
                                                class="
                                                    @if($isAddition)
                                                        revision-addition-row
                                                    @elseif($isPriceOnly)
                                                        revision-price-row
                                                    @endif
                                                "
                                            >


                                                {{-- ================= TYPE ================= --}}

                                                <td>

                                                    @if($isAddition)

                                                        <span class="badge bg-info text-dark">
                                                            New Product
                                                        </span>

                                                        <div class="revision-type-info">
                                                            Added to invoice
                                                        </div>


                                                    @elseif($isPriceOnly)

                                                        <span class="badge bg-warning text-dark">
                                                            Price Change
                                                        </span>

                                                        <div class="revision-type-info">
                                                            No stock movement
                                                        </div>


                                                    @elseif($isStockReturn)

                                                        <span class="badge bg-secondary">
                                                            Return
                                                        </span>

                                                        <div class="revision-type-info">
                                                            Stock returned
                                                        </div>

                                                    @endif

                                                </td>



                                                {{-- ================= PRODUCT ================= --}}

                                                <td>

                                                    <strong>
                                                        {{ $item->product->product_name ?? 'N/A' }}
                                                    </strong>

                                                </td>



                                                {{-- ================= QUANTITY ================= --}}

                                                <td>

                                                    @if($isAddition)

                                                        <strong class="text-success">
                                                            +{{ $returnQty }}
                                                        </strong>

                                                        <br>

                                                        <small class="text-muted">
                                                            Added
                                                        </small>


                                                    @elseif($isPriceOnly)

                                                        <span class="text-muted">
                                                            No Change
                                                        </span>

                                                        <br>

                                                        <small class="text-muted">
                                                            Qty: 0
                                                        </small>


                                                    @else

                                                        <strong>
                                                            -{{ $returnQty }}
                                                        </strong>

                                                        <br>

                                                        <small class="text-muted">
                                                            Returned
                                                        </small>

                                                    @endif

                                                </td>



                                                {{-- ================= PRICE ================= --}}

                                                <td>

                                                    @if($isAddition)

                                                        <div class="revision-price">

                                                            ₹{{ number_format(
                                                                $item->customer_price ?? 0,
                                                                2
                                                            ) }}

                                                        </div>

                                                        <small class="text-muted">

                                                            Cost:
                                                            ₹{{ number_format(
                                                                $item->purchase_rate ?? 0,
                                                                2
                                                            ) }}

                                                        </small>


                                                    @elseif($isPriceOnly)

                                                        <div class="revision-price">

                                                            ₹{{ number_format(
                                                                $newPrice,
                                                                2
                                                            ) }}

                                                        </div>

                                                        <small class="text-warning">
                                                            Revised Price
                                                        </small>


                                                    @else

                                                        <div class="revision-price">

                                                            ₹{{ number_format(
                                                                $newPrice,
                                                                2
                                                            ) }}

                                                        </div>

                                                        <small class="text-muted">
                                                            Invoice Price
                                                        </small>

                                                    @endif

                                                </td>



                                                {{-- ================= ORIGINAL LOCATION ================= --}}

                                                <td>

                                                    @if($isAddition)

                                                        <span class="revision-muted">
                                                            Not applicable
                                                        </span>


                                                    @elseif($isPriceOnly)

                                                        <span class="revision-muted">
                                                            Not required
                                                        </span>

                                                        <br>

                                                        <small class="text-muted">
                                                            Price change only
                                                        </small>


                                                    @else

                                                        <strong>Rack:</strong>
                                                        {{ $item->rack_no ?: '-' }}

                                                        <br>

                                                        <strong>Level:</strong>
                                                        {{ $item->level_no ?: '-' }}

                                                        <br>

                                                        <strong>Slot:</strong>
                                                        {{ $item->slot_no ?: '-' }}

                                                    @endif

                                                </td>



                                                {{-- ================= NEW LOCATION ================= --}}

                                                <td>

                                                    @if($isAddition)

                                                        <span class="revision-muted">
                                                            Not applicable
                                                        </span>


                                                    @elseif($isPriceOnly)

                                                        <span class="badge bg-light text-dark border">
                                                            No Stock Movement
                                                        </span>


                                                    @elseif($item->new_rack_no)

                                                        <strong>Rack:</strong>
                                                        {{ $item->new_rack_no ?: '-' }}

                                                        <br>

                                                        <strong>Level:</strong>
                                                        {{ $item->new_level_no ?: '-' }}

                                                        <br>

                                                        <strong>Slot:</strong>
                                                        {{ $item->new_slot_no ?: '-' }}


                                                    @elseif($returnRequest->status === 'pending')

                                                        <span class="badge bg-warning text-dark">
                                                            Pending Approval
                                                        </span>


                                                    @else

                                                        <span class="text-muted">
                                                            —
                                                        </span>

                                                    @endif

                                                </td>



                                                {{-- ================= BATCH / EXPIRY ================= --}}

                                                <td>

                                                    @if($isPriceOnly)

                                                        <span class="revision-muted">
                                                            Not applicable
                                                        </span>


                                                    @else

                                                        <strong>Batch:</strong>

                                                        {{ $item->batch_no ?: '-' }}

                                                        <br>


                                                        <small class="text-muted">

                                                            Exp:

                                                            {{ $item->expiry_date
                                                                ? \Carbon\Carbon::parse(
                                                                    $item->expiry_date
                                                                )->format('d-m-Y')
                                                                : '-'
                                                            }}

                                                        </small>

                                                    @endif

                                                </td>


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