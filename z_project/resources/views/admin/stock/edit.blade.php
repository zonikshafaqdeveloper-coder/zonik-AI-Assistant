@extends('admin.layouts.appnew')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    window.dbDiscount = {{ $grn->discount_percent ?? 0 }};
    window.dbTax = {{ $grn->tax_amount ?? 0 }};
    window.dbdelivery_charges = {{ $grn->delivery_charges ?? 0 }};
</script>


<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <h3 class="mb-4">Edit Stock Receiving (Draft)</h3>

                            {{-- =====================
                                HEADER
                            ====================== --}}
                            <form id="stockEditForm">

                                <input type="hidden" id="stock_receiving_id" value="{{ $grn->id }}">
                                <input type="hidden" id="purchase_order_id" value="{{ $grn->purchase_order_id }}">
                                <input type="hidden" id="vendor_id" value="{{ $grn->vendor_id }}">

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Purchase Order <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $grn->purchaseOrder->purchase_order_number }}"
                                               readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Vendor <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $grn->purchaseOrder->vendor->name ?? '' }}"
                                               readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">PO Date <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control"
                                               value="{{ $grn->purchaseOrder->po_date }}"
                                               readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Receipt Date <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control"
                                               id="receipt_date"
                                               value="{{ $grn->receipt_date }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Bill Date <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control"
                                               id="bill_date"
                                               value="{{ $grn->bill_date }}">
                                    </div>
                                </div>

                                <hr>

                                {{-- =====================
                                    ITEMS TABLE
                                ====================== --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Brand</th>
                                                <th>UOM</th>
                                                <th>Rate</th>
                                                <th>Free Qty</th>
                                                <th>PO Qty</th>
                                                <th>Tax %</th>
                                                <th>Actual Qty <span class="text-danger">*</span></th>
                                                <th>Returned Qty</th>
                                                <th>Return Reason</th>
                                                <th>To Be Return Qty</th>
                                                <th>To Be Return Reason</th>
                                                <th>Short Qty</th>
                                                <th>Batch No <span class="text-danger">*</span></th>
                                                <th>Expiry Date <span class="text-danger">*</span></th>
                                                <th>MRP <span class="text-danger">*</span></th>
                                                <th>Subtotal</th>
                                                <th style="width:7rem;">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="poItemsBody">
                                            @foreach($grn->items as $index => $item)
                                           <tr  
                                            data-po-item-id="{{ $item->purchase_order_item_id }}"
                                            data-product-id="{{ $item->product_id }}"
                                            data-rate="{{ $item->purchase_rate }}"
                                            data-free-qty="{{ $item->free_quantity }}"
                                            data-po-qty="{{ $item->po_qty }}"
                                            data-original-po-qty="{{ $item->po_qty }}"
                                            data-tax="{{ $item->row_tax ?? 0 }}">
                                               <td>{{ $index + 1 }}</td>
<td>{{ $item->product->product_name }}</td>
<td>{{ $item->product->brands ?? '' }}</td>
<td>{{ $item->product->unit ?? '' }}</td>

<td>
  <input type="number"
         class="form-control purchase-rate"
         min="0"
         value="{{ $item->purchase_rate }}">
</td>

<td>
  <span class="free-qty">{{ $item->free_quantity }}</span>
</td>

<td>
  <span class="remaining-qty">{{ $item->po_qty }}</span>
</td>

<td>{{ $item->row_tax ?? 0 }}</td>

<td>
  <input type="number"
         class="form-control actual-qty"
         min="0"
         value="{{ $item->actual_qty }}">
</td>

<td>
  <input type="number"
         class="form-control returned-qty"
         value="{{ $item->returned_qty }}">
</td>

<td>
  <input type="text"
         class="form-control return-reason"
         value="{{ $item->return_reason }}">
</td>

<td>
  <input type="number"
         class="form-control to-be-return-qty"
         value="{{ $item->to_be_return_qty }}">
</td>

<td>
  <input type="text"
         class="form-control to-be-return-reason"
         value="{{ $item->to_be_return_reason }}">
</td>

<td>
  <input type="number"
         class="form-control short-qty"
         value="{{ $item->short_qty ?? 0 }}">
</td>

<td>
  <input type="text"
         class="form-control batch-no"
         value="{{ $item->batch_no }}">
</td>

<td>
  <input type="date"
         class="form-control expiry-date"
         value="{{ $item->expiry_date }}">
</td>

<td>
  <input type="number"
         class="form-control mrp"
         value="{{ $item->mrp }}">
</td>

<td>
  <input type="text"
         class="form-control row-subtotal"
         value="{{ number_format(($item->actual_qty ?? 0) * ($item->purchase_rate ?? 0), 2) }}"
         readonly>
</td>

<td class="text-center">
  <button type="button" class="btn btn-sm btn-success add-row">+</button>
  <button type="button" class="btn btn-sm btn-danger remove-row">–</button>
</td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- =====================
                                    TOTALS
                                ====================== --}}
                               <div class="row mt-3">
                                    <div class="col-md-4 offset-md-8">
                                        <table class="table table-borderless table-sm">

                                            <tr>
                                                <th class="text-end">Subtotal</th>
                                                <td class="text-end">
                                                    ₹ <span id="subTotal">{{ number_format($grn->subtotal, 2) }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="text-end">Discount ({{ $grn->discount_percent }}%)</th>
                                                <td class="text-end">
                                                    ₹ <span id="discountAmount">
                                                        {{ number_format(
                                                            (($grn->subtotal + $grn->tax_amount) * $grn->discount_percent / 100),
                                                            2
                                                        ) }}
                                                    </span>
                                                </td>
                                            </tr>


                                            <tr>
                                                <th class="text-end">Tax</th>
                                                <td class="text-end">
                                                    ₹ <span id="taxAmount">{{ number_format($grn->tax_amount, 2) }}</span>
                                                </td>
                                            </tr>
                                           
                                            <tr>
                                                <th class="text-end">Delivery Charges</th>
                                                <td class="text-end">
                                                    ₹ <span id="">{{ number_format($grn->delivery_charges, 2) }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="text-end">Grand Total</th>
                                                <td class="text-end">
                                                    ₹ <span id="grandTotal">{{ number_format($grn->grand_total, 2) }}</span>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>


                                {{-- =====================
                                    ACTIONS
                                ====================== --}}
                                <div class="row mt-4">
                                    <div class="col-md-4 offset-md-8 d-grid gap-2">
                                        <button type="button"
                                                class="btn btn-secondary"
                                                onclick="submitEdit('draft')">
                                            Update Draft
                                        </button>

                                        <button type="button"
                                                class="btn btn-success"
                                                onclick="submitEdit('submit')">
                                            Update & Submit
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================
    JS (EDIT ONLY)
====================== --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// function updateEditRemainingQty(productId) {

//     const rows = $('#poItemsBody tr').filter(function () {
//         return $(this).data('product-id') == productId;
//     });

//     let originalPO = parseFloat(rows.first().data('original-po-qty')) || 0;
//     let running = originalPO;

//     rows.each(function () {
//         const actual = parseFloat($(this).find('.actual-qty').val()) || 0;

//         running -= actual;
//         if (running < 0) running = 0;

//         $(this).data('remaining', running);         
//         $(this).find('.remaining-qty').text(running);
//     });
// }

function updateEditRemainingQty(productId) {

    const rows = $('#poItemsBody tr').filter(function () {
        return $(this).data('product-id') == productId;
    });

    let originalPO = parseFloat(rows.first().data('original-po-qty')) || 0;

    let totalUsed = 0;

    rows.each(function () {

        const actual   = parseFloat($(this).find('.actual-qty').val()) || 0;
        const returned = parseFloat($(this).find('.returned-qty').val()) || 0;
        const toReturn = parseFloat($(this).find('.to-be-return-qty').val()) || 0;
        const shortQty = parseFloat($(this).find('.short-qty').val()) || 0;

        totalUsed += actual + returned + toReturn + shortQty;
    });

    let remaining = originalPO - totalUsed;
    if (remaining < 0) remaining = 0;

    rows.each(function () {
        $(this).data('remaining', remaining);
        $(this).find('.remaining-qty').text(remaining.toFixed(2));
    });
}

$(document).ready(function () {
    $('#poItemsBody tr').each(function () {
        updateEditRemainingQty($(this).data('product-id'));
    });
});

function calculateRowSubtotal($row) {
    const actualQty = parseFloat($row.find('.actual-qty').val()) || 0;
    const rate      = parseFloat($row.find('.purchase-rate').val()) || 0;

    const rowSubtotal = actualQty * rate;

    $row.find('.row-subtotal').val(rowSubtotal.toFixed(2));

    return rowSubtotal;
}

function calculateEditTotals() {

    let subtotal = 0;
    let totalTax = 0;

    $('#poItemsBody tr').each(function () {

        const row = $(this);

        const actualQty  = parseFloat(row.find('.actual-qty').val()) || 0;
        const originalPO = parseFloat(row.data('original-po-qty')) || 0;
        const remaining  = parseFloat(row.data('remaining')) || originalPO;
        const rate       = parseFloat(row.find('.purchase-rate').val()) || 0;
        const taxPct     = parseFloat(row.data('tax')) || 0;
        
        calculateRowSubtotal(row);

        // const effectiveQty = actualQty > 0 ? actualQty : remaining;

        // if (effectiveQty <= 0) return;
        
        if (actualQty <= 0) {
            return;
        }

        const rowBase = actualQty * rate;
        const rowTax  = rowBase * taxPct / 100;

        subtotal += rowBase;
        totalTax += rowTax;
    });

    const netAmount = subtotal + totalTax + (window.dbdelivery_charges || 0);
    const discountAmount = (netAmount * window.dbDiscount) / 100;
    const grandTotal = netAmount - discountAmount;

    $('#subTotal').text(subtotal.toFixed(2));
    $('#taxAmount').text(totalTax.toFixed(2));
    $('#discountAmount').text(discountAmount.toFixed(2));
    $('#grandTotal').text(grandTotal.toFixed(2));
}

$(document).on('focus', '.actual-qty, .returned-qty, .to-be-return-qty, .short-qty', function () {
    $(this).data('prev', $(this).val());
});


// $(document).on('input', '.actual-qty', function () {

//     const row = $(this).closest('tr');
//     const productId = row.data('product-id');
//     const originalPO = parseFloat(row.data('original-po-qty')) || 0;

//     let totalAllocated = 0;

//     $('#poItemsBody tr').each(function () {
//         if ($(this).data('product-id') == productId) {
//             totalAllocated += parseFloat($(this).find('.actual-qty').val()) || 0;
//         }
//     });

//     if (totalAllocated > originalPO) {
//         Swal.fire(
//             'Invalid Quantity',
//             'Total Actual Quantity cannot exceed PO Quantity.',
//             'error'
//         );

//         $(this).val($(this).data('prev') || 0);
//         return;
//     }

//     $(this).data('prev', $(this).val());

//     updateEditRemainingQty(productId);
//     calculateEditTotals();
// });

$(document).on('input', '.actual-qty, .returned-qty, .to-be-return-qty, .short-qty', function () {

    const $row = $(this).closest('tr');
    const productId = $row.data('product-id');
    const originalPO = parseFloat($row.data('original-po-qty')) || 0;

    let totalAllocated = 0;

  
    $('#poItemsBody tr').each(function () {
        if ($(this).data('product-id') == productId) {

            const actual   = parseFloat($(this).find('.actual-qty').val()) || 0;
            const returned = parseFloat($(this).find('.returned-qty').val()) || 0;
            const toReturn = parseFloat($(this).find('.to-be-return-qty').val()) || 0;
            const shortQty = parseFloat($(this).find('.short-qty').val()) || 0;

            totalAllocated += actual + returned + toReturn + shortQty;
        }
    });

  
    if (totalAllocated > originalPO) {

        Swal.fire(
            'Invalid Quantity',
            'Total (Actual + Returned + To-Be-Return) cannot exceed PO Quantity.',
            'error'
        );

       
        $(this).val($(this).data('prev') || 0);

        return;
    }

    
    $(this).data('prev', $(this).val());

    updateEditRemainingQty(productId);
    calculateEditTotals();
});


// $(document).on('click', '.add-row', function () {

//     const $row = $(this).closest('tr');
//     const productId = $row.data('product-id');

//     const $productRows = $('#poItemsBody tr').filter(function () {
//         return $(this).data('product-id') == productId;
//     });

//     const originalPO = parseFloat($productRows.first().data('original-po-qty')) || 0;

//     let totalAllocated = 0;
//     $productRows.each(function () {
//         totalAllocated += parseFloat($(this).find('.actual-qty').val()) || 0;
//     });

//     if (totalAllocated >= originalPO) {
//         Swal.fire('Limit Reached', 'All quantity already allocated.', 'warning');
//         return;
//     }

//     const newRow = $row.clone();

//     newRow.find('.actual-qty').val(0);
//     newRow.find('.returned-qty').val(0);
//     newRow.find('.return-reason').val('');
//     newRow.find('.to-be-return-qty').val(0);
//     newRow.find('.to-be-return-reason').val('');
//     newRow.find('.batch-no').val('');
//     newRow.find('.expiry-date').val('');
//     newRow.find('.mrp').val('');

//     $row.after(newRow);

//     updateEditRemainingQty(productId);
//     calculateEditTotals();
// });


$(document).on('click', '.add-row', function () {

    const $row = $(this).closest('tr');
    const productId = $row.data('product-id');

    const rows = $('#poItemsBody tr').filter(function () {
        return $(this).data('product-id') == productId;
    });

    const originalPO = parseFloat(rows.first().data('original-po-qty')) || 0;

    let totalUsed = 0;

    rows.each(function () {
        const actual   = parseFloat($(this).find('.actual-qty').val()) || 0;
        const returned = parseFloat($(this).find('.returned-qty').val()) || 0;
        const toReturn = parseFloat($(this).find('.to-be-return-qty').val()) || 0;
        const shortQty = parseFloat($(this).find('.short-qty').val()) || 0;
        // const shortQty = parseFloat(row.find('.short-qty').val()) || 0;

        totalUsed += actual + returned + toReturn + shortQty;
    });

    if (totalUsed >= originalPO) {
        Swal.fire('Limit Reached', 'All quantity already allocated.', 'warning');
        return;
    }
    
    const currentRate = $row.find('.purchase-rate').val();

    const newRow = $row.clone();

    newRow.find('input').val('');
    newRow.find('.purchase-rate').val(currentRate);
    newRow.find('.row-subtotal').val('0.00');

    $row.after(newRow);

    updateEditRemainingQty(productId);
    calculateEditTotals();
});

$(document).on('click', '.remove-row', function () {

    const rowCount = $('#poItemsBody tr').length;

    if (rowCount <= 1) {
        Swal.fire('Action Not Allowed', 'At least one item must remain.', 'warning');
        return;
    }

    const productId = $(this).closest('tr').data('product-id');

    $(this).closest('tr').remove();

    updateEditRemainingQty(productId);
    calculateEditTotals();
});





</script>
<script>
function parseAmount(value) {
return parseFloat(value.replace(/,/g, '')) || 0;
}

function submitEdit(type) {

try {
    let productTotals = {};

    $('#poItemsBody tr').each(function () {
        const row = $(this);

        const productId   = row.data('product-id');
        const productName = row.find('td:nth-child(2)').text().trim();
        const poQty       = parseFloat(row.data('original-po-qty')) || 0;

        const actualQty     = parseFloat(row.find('.actual-qty').val()) || 0;
        const returnedQtyRaw   = row.find('.returned-qty').val();
        const toBeReturnQtyRaw = row.find('.to-be-return-qty').val();
        const shortQty = parseFloat(row.find('.short-qty').val()) || 0;
        const batchNo          = row.find('.batch-no').val().trim();
        const expiryDate       = row.find('.expiry-date').val();
        const mrpRaw           = row.find('.mrp').val();

        // ===============================
        // FIELD VALIDATIONS (ROW LEVEL)
        // ===============================
        if (returnedQtyRaw === '' || isNaN(returnedQtyRaw)) {
            throw {
                title: 'Returned Quantity Required',
                message: 'Please enter Returned Quantity (0 is allowed).'
            };
        }

        if (toBeReturnQtyRaw === '' || isNaN(toBeReturnQtyRaw)) {
            throw {
                title: 'To-Be-Return Quantity Required',
                message: 'Please enter To-Be-Return Quantity (0 is allowed).'
            };
        }

        const returnedQty   = parseFloat(returnedQtyRaw);
        const toBeReturnQty = parseFloat(toBeReturnQtyRaw);

        if (returnedQty < 0) {
            throw {
                title: 'Invalid Returned Quantity',
                message: 'Returned quantity cannot be negative.'
            };
        }

        if (toBeReturnQty < 0) {
            throw {
                title: 'Invalid To-Be-Return Quantity',
                message: 'To-be-return quantity cannot be negative.'
            };
        }

        if (!batchNo) {
            throw {
                title: 'Batch Number Required',
                message: 'Please enter Batch Number for all items.'
            };
        }

        if (!expiryDate) {
            throw {
                title: 'Expiry Date Required',
                message: 'Please select Expiry Date for all items.'
            };
        }

        if (mrpRaw === '' || isNaN(mrpRaw)) {
            throw {
                title: 'MRP Required',
                message: 'Please enter MRP for all items.'
            };
        }

        const mrp = parseFloat(mrpRaw);

        if (mrp < 0) {
            throw {
                title: 'Invalid MRP',
                message: 'MRP cannot be negative.'
            };
        }

        // ===============================
        // PRODUCT QTY TOTAL CALCULATION
        // ===============================
        const totalQty = actualQty + returnedQty + toBeReturnQty + shortQty;

        if (!productTotals[productId]) {
            productTotals[productId] = {
                total: 0,
                poQty: poQty,
                name : productName
            };
        }

        productTotals[productId].total += totalQty;
    });

    // ===============================
    // PRODUCT LEVEL VALIDATION
    // ===============================
    Object.keys(productTotals).forEach(pid => {
        const total = productTotals[pid].total;
        const poQty = productTotals[pid].poQty;
        const name  = productTotals[pid].name;

        if (total !== poQty) {
            throw {
                title: 'Quantity Mismatch',
                message: `Product "${name}":
PO Qty = ${poQty}
Actual + Returned + To-Be-Return = ${total}
Total quantity must be exactly equal to PO quantity.`
            };
        }
    });

} catch (err) {
    Swal.fire(err.title || 'Error', err.message || 'Validation failed', 'warning');
    return;
}


    // ===================================
    // 2. BUILD ITEMS PAYLOAD (ALL FIELDS)
    // ===================================
    let items = [];

    $('#poItemsBody tr').each(function () {
        const row = $(this);
        
        const rowTax = parseFloat(
        row.data('tax') !== undefined
            ? row.data('tax')
            : row.attr('data-tax')
        ) || 0;

        items.push({
            po_item_id   : row.data('po-item-id'),
            product_id   : row.data('product-id'),
            po_qty       : row.data('original-po-qty'),
            freeQty       : row.data('free-qty'),
            row_tax      : rowTax,

            actual_qty   : parseFloat(row.find('.actual-qty').val()) || 0,
            returned_qty : parseFloat(row.find('.returned-qty').val()) || 0,

            return_reason       : row.find('.return-reason').val() || null,
            to_be_return_qty    : parseFloat(row.find('.to-be-return-qty').val()) || 0,
            to_be_return_reason : row.find('.to-be-return-reason').val() || null,
            
            short_qty       : parseFloat(row.find('.short-qty').val()) || 0,

            purchase_rate : parseFloat(row.find('.purchase-rate').val()) || 0,

            batch_no     : row.find('.batch-no').val() || null,
            expiry_date  : row.find('.expiry-date').val() || null,

            mrp          : row.find('.mrp').val() !== ''
                            ? parseFloat(row.find('.mrp').val())
                            : null,
        });
    });

    // ===================================
    // 3. MAIN PAYLOAD
    // ===================================
    const payload = {
        receipt_date : $('#receipt_date').val(),
        bill_date    : $('#bill_date').val(),

        subtotal     : parseAmount($('#subTotal').text()),
        tax_amount   : parseAmount($('#taxAmount').text()),
        grand_total  : parseAmount($('#grandTotal').text()),

        save_type    : type,
        items        : JSON.stringify(items)
    };

    // ===================================
    // 4. SUBMIT
    // ===================================
    Swal.fire({
        title: type === 'submit' ? 'Updating & Submitting...' : 'Updating Draft...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(`/admin/stock-receivings/update/${$('#stock_receiving_id').val()}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(payload)
    })
    .then(res => res.json())
    .then(res => {
        Swal.close();

        if (!res.success) {
            Swal.fire('Error', res.message || 'Unable to update', 'error');
            return;
        }

        Swal.fire(
            'Success',
            type === 'submit'
                ? 'Stock Receiving Updated & Submitted'
                : 'Draft Updated Successfully',
            'success'
        ).then(() => {
            window.location.href = res.redirect_url;
        });
    })
    .catch(() => {
        Swal.close();
        Swal.fire('Server Error', 'Something went wrong', 'error');
    });
}


</script>

@endsection
