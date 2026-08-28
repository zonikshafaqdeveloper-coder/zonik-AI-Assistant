@extends('admin.layouts.appnew')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .wr-card {
        border: 1px solid #eef0f3;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .wr-card-head {
        background: #f7f8fa;
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .wr-item-row td {
        vertical-align: middle;
        font-size: 13px;
    }

    .wr-loc-input {
        width: 85px;
        display: inline-block;
    }

    .wr-stock-type {
        min-width: 170px;
    }

    .wr-location-wrap {
        white-space: nowrap;
    }

    .wr-price-only td {
        background: #fffdf5;
    }

    .wr-addition td {
        background: #f6fbff;
    }

    .wr-damaged-info {
        display: none;
        margin-top: 6px;
        font-size: 11px;
        color: #dc3545;
    }

    .wr-handling-info {
        margin-top: 5px;
        font-size: 11px;
    }

    .wr-product-name {
        font-weight: 600;
    }
</style>


<div class="page-body">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12 my-5">

                <div class="col-sm-12 m-auto">

                    <h3 class="mb-4">
                        Revised Invoice Requests — Pending Warehouse Approval
                    </h3>


                    @forelse($requests as $req)

                        <div
                            class="wr-card"
                            id="request-{{ $req->id }}"
                        >

                            {{-- HEADER --}}

                            <div class="wr-card-head">

                                <div>

                                    <strong>Order:</strong>
                                    {{ $req->order->order_id ?? '-' }}

                                    &nbsp;|&nbsp;

                                    <strong>Requested By:</strong>
                                    {{ $req->requestedBy->name ?? '-' }}

                                    &nbsp;|&nbsp;

                                    <strong>Date:</strong>
                                    {{ $req->created_at->format('d-m-Y H:i') }}

                                </div>

                            </div>


                            <div class="table-responsive p-3">

                                <table class="table table-bordered mb-3">

                                    <thead class="table-light">

                                        <tr>

                                            <th>Type</th>

                                            <th>Product</th>

                                            <th>Qty</th>

                                            <th>Rate</th>

                                            <th>Current Location</th>

                                            <th>Batch / Expiry</th>

                                            <th style="width:200px;">
                                                Return Handling
                                            </th>

                                            <th style="width:300px;">
                                                Return Location
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($req->items as $item)

                                            @php

                                                $returnQty =
                                                    (int) ($item->return_qty ?? 0);

                                                $isAddition =
                                                    $item->item_type === 'addition';

                                                $isPriceOnly =
                                                    $item->item_type === 'return'
                                                    && $returnQty === 0;

                                                $isReturn =
                                                    $item->item_type === 'return'
                                                    && $returnQty > 0;

                                                $newPrice =
                                                    $item->customer_price
                                                    ?? $item->purchase_rate
                                                    ?? 0;

                                            @endphp


                                            <tr
                                                class="
                                                    wr-item-row

                                                    @if($isAddition)
                                                        wr-addition
                                                    @elseif($isPriceOnly)
                                                        wr-price-only
                                                    @endif
                                                "

                                                data-item-id="{{ $item->id }}"

                                                data-item-type="{{ $item->item_type }}"

                                                data-return-qty="{{ $returnQty }}"
                                            >


                                                {{-- TYPE --}}

                                                <td>

                                                    @if($isAddition)

                                                        <span class="badge bg-info text-dark">
                                                            New Product
                                                        </span>

                                                        <div class="text-muted small mt-1">
                                                            Stock Out
                                                        </div>


                                                    @elseif($isPriceOnly)

                                                        <span class="badge bg-warning text-dark">
                                                            Price Change
                                                        </span>

                                                        <div class="text-muted small mt-1">
                                                            No stock movement
                                                        </div>


                                                    @else

                                                        <span class="badge bg-secondary">
                                                            Return
                                                        </span>

                                                    @endif

                                                </td>



                                                {{-- PRODUCT --}}

                                                <td>

                                                    <div class="wr-product-name">

                                                        {{ $item->product->product_name ?? 'N/A' }}

                                                    </div>

                                                </td>



                                                {{-- QUANTITY --}}

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


                                                    @else

                                                        <strong>
                                                            {{ $returnQty }}
                                                        </strong>

                                                        <br>

                                                        <small class="text-muted">
                                                            Return
                                                        </small>

                                                    @endif

                                                </td>



                                                {{-- RATE --}}

                                                <td>

                                                    @if($isAddition)

                                                        <strong>

                                                            ₹{{ number_format(
                                                                $item->customer_price ?? 0,
                                                                2
                                                            ) }}

                                                        </strong>

                                                        <br>

                                                        <small class="text-muted">

                                                            Cost:
                                                            ₹{{ number_format(
                                                                $item->purchase_rate ?? 0,
                                                                2
                                                            ) }}

                                                        </small>


                                                    @elseif($isPriceOnly)

                                                        <strong>

                                                            ₹{{ number_format(
                                                                $newPrice,
                                                                2
                                                            ) }}

                                                        </strong>

                                                        <br>

                                                        <small class="text-warning">
                                                            Revised Price
                                                        </small>


                                                    @else

                                                        ₹{{ number_format(
                                                            $newPrice,
                                                            2
                                                        ) }}

                                                    @endif

                                                </td>



                                                {{-- CURRENT LOCATION --}}

                                                <td>

                                                    @if($isAddition)

                                                        <span class="text-muted">
                                                            New stock issue
                                                        </span>


                                                    @elseif($isPriceOnly)

                                                        <span class="text-muted">
                                                            Not Required
                                                        </span>


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



                                                {{-- BATCH / EXPIRY --}}

                                                <td>

                                                    @if($isPriceOnly)

                                                        <span class="text-muted">
                                                            Not Applicable
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



                                                {{-- RETURN HANDLING --}}

                                                <td>

                                                    @if($isReturn)

                                                        <select
                                                            class="form-control wr-stock-type f-stock-type"
                                                        >

                                                            <option value="">
                                                                Select Handling
                                                            </option>


                                                            <option value="physical_in">
                                                                Physical In
                                                            </option>


                                                            <option value="no_physical_in">
                                                                No Physical In
                                                            </option>


                                                            <option value="damaged">
                                                                Damaged
                                                            </option>

                                                        </select>


                                                        {{-- Dynamic description --}}

                                                        <div
                                                            class="wr-handling-info text-muted"
                                                        >

                                                            Select how this returned stock should be handled.

                                                        </div>


                                                        <div class="wr-damaged-info">

                                                            Damaged stock will be recorded in Stock Disposal.

                                                        </div>


                                                    @elseif($isPriceOnly)

                                                        <span class="badge bg-light text-dark border">

                                                            Not Required

                                                        </span>


                                                    @else

                                                        <span class="text-muted">

                                                            Not Applicable

                                                        </span>

                                                    @endif

                                                </td>



                                                {{-- RETURN LOCATION --}}

                                                <td>

                                                    @if($isReturn)

                                                        {{-- Physical In location --}}

                                                        <div
                                                            class="wr-location-wrap"
                                                            style="display:none;"
                                                        >

                                                            <input
                                                                type="text"
                                                                class="form-control wr-loc-input f-new-rack"
                                                                placeholder="Rack"
                                                                value="{{ $item->rack_no }}"
                                                            >


                                                            <input
                                                                type="text"
                                                                class="form-control wr-loc-input f-new-level"
                                                                placeholder="Level"
                                                                value="{{ $item->level_no }}"
                                                            >


                                                            <input
                                                                type="text"
                                                                class="form-control wr-loc-input f-new-slot"
                                                                placeholder="Slot"
                                                                value="{{ $item->slot_no }}"
                                                            >

                                                        </div>


                                                        {{-- No location message --}}

                                                        <div
                                                            class="wr-no-location text-muted"
                                                        >

                                                            Select return handling

                                                        </div>


                                                    @elseif($isPriceOnly)

                                                        <span class="badge bg-light text-dark border">

                                                            No Stock Movement

                                                        </span>


                                                    @else

                                                        <span class="text-muted">

                                                            Not Applicable

                                                        </span>

                                                    @endif

                                                </td>


                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>



                                {{-- REASON --}}

                                <div class="row">

                                    <div class="col-md-8">

                                        <label class="form-label">

                                            Reason

                                            <span class="text-muted">

                                                (required only if rejecting)

                                            </span>

                                        </label>


                                        <textarea
                                            class="form-control wr-reason"
                                            rows="2"
                                            placeholder="Enter reason..."
                                        ></textarea>

                                    </div>


                                    <div
                                        class="col-md-4 d-flex align-items-end gap-2"
                                    >

                                        <button
                                            type="button"
                                            class="btn btn-success wr-approve-btn"
                                            data-id="{{ $req->id }}"
                                        >

                                            Approve

                                        </button>


                                        <button
                                            type="button"
                                            class="btn btn-danger wr-reject-btn"
                                            data-id="{{ $req->id }}"
                                        >

                                            Reject

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>


                    @empty

                        <div class="alert alert-info">

                            No pending revised invoice requests.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | RETURN HANDLING CHANGE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.f-stock-type',
        function ()
        {

            const $row =
                $(this).closest('.wr-item-row');


            const type =
                $(this).val();


            const $location =
                $row.find('.wr-location-wrap');


            const $noLocation =
                $row.find('.wr-no-location');


            const $damagedInfo =
                $row.find('.wr-damaged-info');


            const $handlingInfo =
                $row.find('.wr-handling-info');



            /*
            |--------------------------------------------------------------------------
            | PHYSICAL IN
            |--------------------------------------------------------------------------
            |
            | movement_type  = RETURN
            | reference_type = Revised Invoice
            |
            */

            if (type === 'physical_in') {

                $location.show();

                $noLocation.hide();

                $damagedInfo.hide();


                $handlingInfo
                    .removeClass('text-danger text-warning')
                    .addClass('text-success')
                    .text(
                        'Stock will be added back to usable inventory.'
                    );


                return;
            }



            /*
            |--------------------------------------------------------------------------
            | NO PHYSICAL IN
            |--------------------------------------------------------------------------
            |
            | movement_type  = PENDING_RETURN
            | reference_type = Revised Invoice
            |
            | ProductStock/RackStock not increased.
            |
            */

            if (type === 'no_physical_in') {

                $location.hide();


                $noLocation
                    .text(
                        'No stock location required'
                    )
                    .show();


                $damagedInfo.hide();


                $handlingInfo
                    .removeClass('text-success text-danger')
                    .addClass('text-warning')
                    .text(
                        'Invoice qty will reduce but physical stock will not be received.'
                    );


                return;
            }



            /*
            |--------------------------------------------------------------------------
            | DAMAGED
            |--------------------------------------------------------------------------
            |
            | movement_type  = RETURN
            | reference_type = DAMAGED
            |
            | Also create StockDisposal.
            |
            */

            if (type === 'damaged') {

                $location.hide();


                $noLocation
                    .text(
                        'No usable stock location required'
                    )
                    .show();


                $damagedInfo.show();


                $handlingInfo
                    .removeClass('text-success text-warning')
                    .addClass('text-danger')
                    .text(
                        'Damaged stock will not be added to usable inventory.'
                    );


                return;
            }



            /*
            |--------------------------------------------------------------------------
            | NOTHING SELECTED
            |--------------------------------------------------------------------------
            */

            $location.hide();


            $noLocation
                .text(
                    'Select return handling'
                )
                .show();


            $damagedInfo.hide();


            $handlingInfo
                .removeClass(
                    'text-success text-warning text-danger'
                )
                .addClass('text-muted')
                .text(
                    'Select how this returned stock should be handled.'
                );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    $('.wr-approve-btn').on(
        'click',
        function ()
        {

            const id =
                $(this).data('id');


            const $card =
                $('#request-' + id);


            const reason =
                $card.find('.wr-reason').val();


            const items = [];


            let hasError = false;



            /*
            |--------------------------------------------------------------------------
            | COLLECT ITEMS
            |--------------------------------------------------------------------------
            */

            $card
                .find('.wr-item-row')
                .each(function ()
                {

                    const $row =
                        $(this);


                    const itemType =
                        $row.data('item-type');


                    const returnQty =
                        parseInt(
                            $row.data('return-qty'),
                            10
                        ) || 0;


                    let stockType = null;

                    let rack = null;

                    let level = null;

                    let slot = null;



                    /*
                    |--------------------------------------------------------------------------
                    | ONLY ACTUAL RETURN NEEDS HANDLING
                    |--------------------------------------------------------------------------
                    */

                    if (
                        itemType === 'return'
                        &&
                        returnQty > 0
                    ) {

                        stockType =
                            $row
                                .find('.f-stock-type')
                                .val();



                        /*
                        | Handling mandatory
                        */

                        if (!stockType) {

                            Swal.fire(
                                'Return Handling Required',
                                'Please select Physical In, No Physical In or Damaged for every returned product.',
                                'warning'
                            );


                            hasError = true;


                            return false;
                        }



                        /*
                        |--------------------------------------------------------------------------
                        | PHYSICAL IN LOCATION
                        |--------------------------------------------------------------------------
                        */

                        if (
                            stockType === 'physical_in'
                        ) {

                            rack =
                                $row
                                    .find('.f-new-rack')
                                    .val()
                                    ?.trim();


                            level =
                                $row
                                    .find('.f-new-level')
                                    .val()
                                    ?.trim();


                            slot =
                                $row
                                    .find('.f-new-slot')
                                    .val()
                                    ?.trim();



                            if (
                                !rack
                                ||
                                !level
                                ||
                                !slot
                            ) {

                                Swal.fire(
                                    'Location Required',
                                    'Rack, Level and Slot are required when Physical In is selected.',
                                    'warning'
                                );


                                hasError = true;


                                return false;
                            }

                        }

                    }



                    /*
                    |--------------------------------------------------------------------------
                    | PUSH ITEM
                    |--------------------------------------------------------------------------
                    */

                    items.push({

                        item_id:
                            $row.data('item-id'),

                        return_stock_type:
                            stockType,

                        new_rack_no:
                            rack,

                        new_level_no:
                            level,

                        new_slot_no:
                            slot

                    });

                });



            if (hasError) {

                return;
            }



            /*
            |--------------------------------------------------------------------------
            | CONFIRM APPROVAL
            |--------------------------------------------------------------------------
            */

            Swal.fire({

                title:
                    'Approve this revised invoice?',

                html:
                    'Invoice and stock will be updated according to the selected return handling.',

                icon:
                    'question',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Yes, Approve',

                cancelButtonText:
                    'Cancel',

                confirmButtonColor:
                    '#28a745'

            }).then(function (result)
            {

                if (!result.isConfirmed) {

                    return;
                }



                /*
                |--------------------------------------------------------------------------
                | LOADING
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    title:
                        'Processing...',

                    text:
                        'Updating invoice and stock ledger.',

                    allowOutsideClick:
                        false,

                    allowEscapeKey:
                        false,

                    didOpen:
                        function ()
                        {

                            Swal.showLoading();

                        }

                });



                /*
                |--------------------------------------------------------------------------
                | AJAX
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url:
                        '{{ url("admin/warehouse/stock-return") }}/'
                        + id
                        + '/approve',

                    method:
                        'POST',


                    data: {

                        items:
                            items,

                        reason:
                            reason

                    },


                    headers: {

                        'X-CSRF-TOKEN':
                            $(
                                'meta[name="csrf-token"]'
                            )
                            .attr('content')

                    },


                    success:
                        function (res)
                        {

                            Swal.fire(
                                'Approved',
                                res.message,
                                'success'
                            )
                            .then(function ()
                            {

                                $card.fadeOut(
                                    300,
                                    function ()
                                    {

                                        $(this).remove();

                                    }
                                );

                            });

                        },


                    error:
                        function (xhr)
                        {

                            let message =
                                'Something went wrong.';


                            if (
                                xhr.responseJSON
                                &&
                                xhr.responseJSON.message
                            ) {

                                message =
                                    xhr.responseJSON.message;

                            }


                            Swal.fire(
                                'Error',
                                message,
                                'error'
                            );

                        }

                });

            });

        }
    );



    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    $('.wr-reject-btn').on(
        'click',
        function ()
        {

            const id =
                $(this).data('id');


            const $card =
                $('#request-' + id);


            const reason =
                $card
                    .find('.wr-reason')
                    .val()
                    .trim();



            /*
            | Reject reason required
            */

            if (
                !reason
                ||
                reason.length < 5
            ) {

                Swal.fire(
                    'Reason Required',
                    'Please enter a reason (at least 5 characters) before rejecting.',
                    'warning'
                );


                $card
                    .find('.wr-reason')
                    .focus();


                return;
            }



            /*
            | Confirmation
            */

            Swal.fire({

                title:
                    'Reject this revised invoice?',

                text:
                    'The revised invoice request will be rejected.',

                icon:
                    'warning',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Yes, Reject',

                cancelButtonText:
                    'Cancel',

                confirmButtonColor:
                    '#dc3545'

            }).then(function (result)
            {

                if (!result.isConfirmed) {

                    return;
                }



                $.ajax({

                    url:
                        '{{ url("admin/warehouse/stock-return") }}/'
                        + id
                        + '/reject',

                    method:
                        'POST',


                    data: {

                        reason:
                            reason

                    },


                    headers: {

                        'X-CSRF-TOKEN':
                            $(
                                'meta[name="csrf-token"]'
                            )
                            .attr('content')

                    },


                    success:
                        function (res)
                        {

                            Swal.fire(
                                'Rejected',
                                res.message,
                                'success'
                            )
                            .then(function ()
                            {

                                $card.fadeOut(
                                    300,
                                    function ()
                                    {

                                        $(this).remove();

                                    }
                                );

                            });

                        },


                    error:
                        function (xhr)
                        {

                            Swal.fire(
                                'Error',
                                xhr.responseJSON?.message
                                ||
                                'Something went wrong.',
                                'error'
                            );

                        }

                });

            });

        }
    );

});

</script>

@endsection