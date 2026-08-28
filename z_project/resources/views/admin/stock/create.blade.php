@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.select2-container .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
    opacity: 1 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
}
</style>


<div class="page-body">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-12 m-auto">

                        {{-- Alerts --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-body">

                                <div class="card-header-2 mb-3">
                                    <h3>Stock Receiving Record</h3>
                                </div>

               
                                   <!-- form start from here -->

 <form>
    {{-- =====================
        HEADER SECTION
    ====================== --}}
    <div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Purchase Order <span class="text-danger">*</span></label>
        <select id="purchase_order_id" name="purchase_order_id" class="form-control select2">
            <option value="">Select PO</option>

            @foreach ($purchaseOrders as $po)
        <option value="{{ $po->id }}"
            {{ (isset($selectedPoId) && $selectedPoId == $po->id) ? 'selected' : '' }}>
            {{ $po->purchase_order_number }}
        </option>
    @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
        <input type="text" id="vendor_name" name="vendor_name" class="form-control" readonly>
    </div>

    <div class="col-md-4">
        <label class="form-label">PO Date <span class="text-danger">*</span></label>
        <input type="date" id="po_date" name="po_date" class="form-control" readonly>
    </div>

    <input type="hidden" name="vendor_id" id="vendor_id">
</div>


    <div class="row mb-3">

        <div class="col-md-4">
            <label class="form-label">Material Receipt Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="receipt_date" name="receipt_date">
        </div>

        <div class="col-md-4">
            <label class="form-label">Bill No  <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="bill_no" name="bill_no">
        </div>

        <div class="col-md-4">
            <label class="form-label">Bill Date <span class="text-danger">*</span> </label>
            <input type="date" class="form-control" id="bill_date" name="bill_date">
        </div>

    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <label class="form-label">Upload Original Bill <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="original_bill" id="original_bill">
        </div>
    </div>

    <hr>

    {{-- =====================
        ITEM TABLE
    ====================== --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Brand</th>
                    <th>UOM</th>
                    <th>Purchase Rate</th>
                    <th>free Qty</th>
                    <th>PO Qty</th>
                    <th>Cgst + Sgst</th>
                    <th>Actual Qty <span class="text-danger">*</span></th>
                    <th>Returned Qty</th>
                    <th>Return Reason</th>
                    <th>To Be Return Qty</th>
                    <th>Reason</th>
                    <th>Short Qty</th>
                    <th>Batch No <span class="text-danger">*</span></th>
                    <th>Expiry Date <span class="text-danger">*</span></th>
                    <th>MRP <span class="text-danger">*</span></th>
                    <th>SubTotal</th>
                    <th style="width:7rem;">Action</th>
                </tr>
            </thead>

          <tbody id="poItemsBody">
            <tr>
                <td colspan="15" class="text-center text-muted">
                    Select a Purchase Order to load items
                </td>
            </tr>
        </tbody>

        </table>
    </div>

    <div class="row mt-3">
    <div class="col-md-4 offset-md-8">
        <table class="table table-sm table-borderless">
           <tr>
                <th class="text-end">Subtotal</th>
                <td class="text-end">₹ <span id="subTotal">0.00</span></td>
            </tr>
            <tr>
                <th class="text-end">Discount  (<span id="discountPercent">0</span>%)</th>
                <td class="text-end">₹ <span id="discountAmount">0.00</span></td>
            </tr>
            <tr>
                <th class="text-end">Tax</th>
                <td class="text-end">₹ <span id="taxAmount">0.00</span></td>
            </tr>
            <tr>
                <th class="text-end">Delivery Charges </th>
                <td class="text-end">₹ <span id="delivery_charges">0.00</span></td>
            </tr>
            <tr>
                <th class="text-end">Grand Total</th>
                <td class="text-end">₹ <span id="grandTotal">0.00</span></td>
            </tr>

        </table>
    </div>
</div>

    {{-- =====================
        ACTIONS
    ====================== --}}
    <div class="row mt-4">
        <div class="col-md-4 offset-md-8 d-grid gap-2">
            <button type="button" class="btn btn-secondary">
                Save as Draft
            </button>

            <button type="button" class="btn btn-success">
                Submit & Convert to Bill
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

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

   const $poSelect = $('#purchase_order_id');

    // 1️⃣ Init Select2
    $poSelect.select2({
        width: '100%',
        placeholder: 'Select Purchase Order'
    });

     $poSelect.on('change', function () {

        const poId = $(this).val();
        $('#poItemsBody').html('');

        if (!poId) {
            $('#vendor_name').val('');
            $('#po_date').val('');
            $('#vendor_id').val('');
            $('#grandTotal').text('0.00');
            return;
        }

        fetch(`/admin/purchase-orders/${poId}/for-stock-receiving`)
            .then(res => res.json())
            .then(data => {

               
                $('#vendor_name').val(data.vendor_name);
                $('#po_date').val(data.po_date);
                $('#vendor_id').val(data.vendor_id);

               
                window.dbSubtotal = parseFloat(data.subtotal_basic) || 0;
                window.dbDiscount = parseFloat(data.product_discount) || 0;
                window.dbTax      = parseFloat(data.gst) || 0;
                window.dbdelivery_charges = parseFloat(data.delivery_charges) || 0;
                window.dbGrand    = parseFloat(data.grand_total) || 0;

                
               
               const initialDiscountAmount = window.dbSubtotal * window.dbDiscount / 100;

                $('#subTotal').text(window.dbSubtotal.toFixed(2));
                $('#discountPercent').text(window.dbDiscount.toFixed(2));
                $('#discountAmount').text(initialDiscountAmount.toFixed(2));
                $('#taxPercent').text(window.dbTax.toFixed(2));
                $('#taxAmount').text(window.dbTax.toFixed(2));
                $('#delivery_charges').text(dbdelivery_charges.toFixed(2));
                $('#grandTotal').text(window.dbGrand.toFixed(2));

                let rows = '';

                data.items.forEach((item, index) => {
                    rows += `
                       <tr  data-po-item-id="${item.po_item_id}" data-product-id="${item.product_id}" data-rate="${item.purchase_rate}" data-free_qty="${item.free_qty}" data-po-qty="${item.po_qty}"  data-original-po-qty="${item.po_qty}" data-mrp_price="${item.mrp}" data-tax="${item.po_cgst_sgst}" >
                            <td>${index + 1}</td>
                            <td>${item.product_name}</td>
                            <td>${item.brand}</td>
                            <td>${item.uom}</td>
                            <td>
                            <input type="number"
                                class="form-control purchase-rate"
                                min="0"
                                value="${item.purchase_rate}">
                            </td>
                            
                              <td>
                                <span class="free_qty" readonly>${item.free_qty}</span>
                            </td>

                            <td>
                                <span class="remaining-qty">${item.po_qty}</span>
                            </td>

                            <td>${item.po_cgst_sgst}</td>

                            <td>
                                <input type="number"
                                       class="form-control actual-qty"
                                       min="0"
                                       value="0">
                            </td>

                            <td><input type="number" class="form-control returned-qty" value="0"></td>
                            <td><input type="text" class="form-control return-reason"></td>
                            <td><input type="number" class="form-control to-be-return-qty" value="0"></td>
                            <td><input type="text" class="form-control to-be-return-reason"></td>
                            <td><input type="number" class="form-control short-qty" value="0"></td>
                            <td><input type="text" class="form-control batch-no"></td>
                            <td><input type="date" class="form-control expiry-date"></td>
                          <td><input type="number" class="form-control mrp_price" 
                            value="${item.mrp}"></td>
                             <td><input type="text" class="form-control row-subtotal" value="0.00" readonly></td>   
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-success add-row">+</button>
                                <button type="button" class="btn btn-sm btn-danger remove-row">–</button>
                            </td>


                        </tr>
                    `;
                });

                $('#poItemsBody').html(rows);
                calculateGrandTotal();

            });
    });

     if ($poSelect.val()) {
        $poSelect.trigger('change');
        // OR for Select2 specifically:
        // $poSelect.trigger('change.select2');
    }


// function updateRemainingQty(productId) {

//     const rows = $('#poItemsBody tr').filter(function () {
//         return $(this).data('product-id') == productId;
//     });

//     let originalPO = parseFloat(rows.first().data('original-po-qty')) || 0;
//     let runningBalance = originalPO;

//     rows.each(function () {
//         const actual = parseFloat($(this).find('.actual-qty').val()) || 0;

//         const used = actual > 0 ? actual : 0;
//         runningBalance = runningBalance - used;
//         if (runningBalance < 0) runningBalance = 0;

      
//         $(this).data('remaining', runningBalance);
//         $(this).find('.remaining-qty').text(runningBalance.toFixed(2));
//     });
// }

function updateRemainingQty(productId) {

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

    // update UI for all rows
    rows.each(function () {
        $(this).data('remaining', remaining);
        $(this).find('.remaining-qty').text(remaining.toFixed(2));
    });
}





// $(document).on('input', '.actual-qty', function () {

//     const $row = $(this).closest('tr');
//     const productId = $row.data('product-id');
//     const poQty = parseFloat($row.data('original-po-qty')) || 0;

//     let totalAllocated = 0;

//     $('#poItemsBody tr').each(function () {
//         if ($(this).data('product-id') == productId) {
//             totalAllocated += parseFloat($(this).find('.actual-qty').val()) || 0;
//         }
//     });

//     if (totalAllocated > poQty) {
//         Swal.fire(
//             'Invalid Quantity',
//             'Total Actual Quantity cannot exceed PO Quantity.',
//             'error'
//         );

        
//         $(this).val($(this).data('prev') || 0);

//         return;
//     }

   
//     $(this).data('prev', $(this).val());

//     updateRemainingQty(productId);
//     calculateGrandTotal();
// });

$(document).on('input', '.actual-qty, .returned-qty, .to-be-return-qty, .short-qty', function () {

    const $row = $(this).closest('tr');
    const productId = $row.data('product-id');
    const poQty = parseFloat($row.data('original-po-qty')) || 0;

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

    if (totalAllocated > poQty) {

        Swal.fire(
            'Invalid Quantity',
            'Total (Actual + Returned + To-Be-Return) cannot exceed PO Quantity.',
            'error'
        );


        $(this).val($(this).data('prev') || 0);

        return;
    }


    $(this).data('prev', $(this).val());

    updateRemainingQty(productId);
});



// $(document).on('click', '.add-row', function () {

//     const $row = $(this).closest('tr');
//     const productId = $row.data('product-id');
//     const poQty = parseFloat($row.data('po-qty')) || 0;

//     let totalAllocated = 0;

//     $('#poItemsBody tr').each(function () {
//         if ($(this).data('product-id') == productId) {
//             const aq = parseFloat($(this).find('.actual-qty').val()) || 0;
//             const pq = parseFloat($(this).data('po-qty')) || 0;
//             const eq = aq > 0 ? aq : pq;
//             totalAllocated += eq;
//         }
//     });

//     if (totalAllocated >= poQty) {
//         Swal.fire(
//             'Limit Reached',
//             'Total received quantity already equals PO quantity.',
//             'warning'
//         );
//         return;
//     }

//     const newRow = $row.clone();
//     newRow.find('.actual-qty').val(0);
//     newRow.find('.returned-qty').val(0);
//     newRow.find('.to-be-return-qty').val(0);
//     newRow.find('.batch-no').val('');
//     newRow.find('.expiry-date').val('');
//     newRow.find('.mrp').val('');

//     $row.after(newRow);
//     updateRemainingQty(productId);
// });



$(document).on('click', '.add-row', function () {

    const $row = $(this).closest('tr');
    const productId = $row.data('product-id');
    const poQty = parseFloat($row.data('original-po-qty')) || 0;

    let totalUsed = 0;

    $('#poItemsBody tr').each(function () {

        if ($(this).data('product-id') == productId) {

            const actual   = parseFloat($(this).find('.actual-qty').val()) || 0;
            const returned = parseFloat($(this).find('.returned-qty').val()) || 0;
            const toReturn = parseFloat($(this).find('.to-be-return-qty').val()) || 0;
            const shortQty = parseFloat($(this).find('.short-qty').val()) || 0;

            totalUsed += actual + returned + toReturn + shortQty;
        }
    });

    const remaining = poQty - totalUsed;

    if (remaining <= 0) {
        Swal.fire(
            'Limit Reached',
            'Total (Actual + Returned + To-Be-Return) already equals PO quantity.',
            'warning'
        );
        return;
    }

    const newRow = $row.clone();

    // reset fields
    newRow.find('.actual-qty').val(0);
    newRow.find('.returned-qty').val(0);
    newRow.find('.to-be-return-qty').val(0);
    newRow.find('.short-qty').val(0);
    newRow.find('.batch-no').val('');
    newRow.find('.expiry-date').val('');
    newRow.find('.mrp_price').val('');

    $row.after(newRow);

    updateRemainingQty(productId);
});

function calculateRowSubtotal($row) {
    const actualQty = parseFloat($row.find('.actual-qty').val()) || 0;
    const rate      = parseFloat($row.find('.purchase-rate').val()) || 0;

    const rowSubtotal = actualQty * rate;

    $row.find('.row-subtotal').val(rowSubtotal.toFixed(2));

    return rowSubtotal;
}
   
function calculateGrandTotal() {

    let subtotal = 0;
    let totalTax = 0;

    $('#poItemsBody tr').each(function () {

        const row = $(this);

        const actualQty = parseFloat(row.find('.actual-qty').val()) || 0;
        const poQty     = parseFloat(row.data('po-qty')) || 0;
        const rate      = parseFloat(row.find('.purchase-rate').val()) || 0;
        const taxPct    = parseFloat(row.data('tax')) || 0;

          calculateRowSubtotal(row);
        // const effectiveQty = actualQty > 0 ? actualQty : poQty;
        
         if (actualQty <= 0) {
            return;
        }

        const rowBase = actualQty * rate;
        const rowTax  = rowBase * taxPct / 100;

        subtotal += rowBase;
        totalTax += rowTax;
    });

    const netAmount = subtotal + totalTax + (window.dbdelivery_charges || 0);
    const discountAmount = (netAmount * (window.dbDiscount || 0)) / 100;
    const grandTotal = netAmount - discountAmount;

    $('#subTotal').text(subtotal.toFixed(2));
    $('#discountAmount').text(discountAmount.toFixed(2));
    $('#taxAmount').text(totalTax.toFixed(2));
    $('#grandTotal').text(grandTotal.toFixed(2));
}




   $(document).on('input', '.actual-qty', function () {

    const poQty = parseFloat($(this).closest('tr').data('po-qty')) || 0;
    let val = parseFloat($(this).val()) || 0;

    if (val < 0) {
        $(this).val(0);
        return;
    }

    if (val > poQty) {
        $(this).addClass('border-danger');
    } else {
        $(this).removeClass('border-danger');
    }

    calculateGrandTotal();
});

$(document).on('input', '.purchase-rate', function () {
    calculateGrandTotal();
});


$(document).on('click', '.remove-row', function () {

    const rowCount = $('#poItemsBody tr').length;
    const productId = $(this).closest('tr').data('product-id');

    if (rowCount <= 1) {
        Swal.fire(
            'Action Not Allowed',
            'At least one item must remain.',
            'warning'
        );
        return;
    }

    $(this).closest('tr').remove();
      updateRemainingQty(productId);
    calculateGrandTotal();
});



});
</script>

<script>
$('.btn-secondary').on('click', function () {
    console.log('draft');
    submitStockReceiving('draft');
});

$('.btn-success').on('click', function () {
    console.log('submit');
    submitStockReceiving('submit');
});

    function buildStockReceivingPayload(saveType = 'draft') {

    let items = [];

    $('#poItemsBody tr').each(function () {

        const row = $(this);

        const actualQty = parseFloat(row.find('.actual-qty').val()) || 0;
        const originalPO = parseFloat(row.data('original-po-qty')) || 0;
         const freeqty = parseFloat(row.data('free_qty')) || 0;
        const remaining = parseFloat(row.data('remaining')) || originalPO;
        const rate = parseFloat(row.find('.purchase-rate').val()) || 0;
        const row_tax      = parseFloat(row.data('tax')) || 0;
        const batchNo         = row.find('.batch-no').val().trim();
        const expiryDate      = row.find('.expiry-date').val();
        const returnedQtyRaw   = row.find('.returned-qty').val();
        const toBeReturnQtyRaw = row.find('.to-be-return-qty').val();
        const mrpRaw = row.find('.mrp_price').val();
        const shortQty = parseFloat(row.find('.short-qty').val()) || 0;

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
        
        const today = new Date();
        today.setHours(0, 0, 0, 0); 
        
        const selectedExpiry = new Date(expiryDate);
        selectedExpiry.setHours(0, 0, 0, 0); // normalize
        
        if (selectedExpiry < today) {
            throw {
                title: 'Invalid Expiry Date',
                message: 'Expiry date cannot be in the past.'
            };
        }
        
        // const today = new Date();
        // const minExpiryDate = new Date();
        // minExpiryDate.setDate(today.getDate() + 90);

        // const selectedExpiry = new Date(expiryDate);
        // console.log(selectedExpiry);

        // if (selectedExpiry < minExpiryDate) {
        //     throw {
        //         title: 'Invalid Expiry Date',
        //         message: 'Expiry date must be at least 90 days from today.'
        //     };
        // }
        
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

           if (
                actualQty === 0 &&
                returnedQty === 0 &&
                toBeReturnQty === 0 &&
                shortQty === 0
            ) {
                return;
            }

        items.push({
            po_item_id           : row.data('po-item-id') || null,
            product_id           : row.data('product-id') || null,
            po_qty               : originalPO,
            freeqty              : freeqty,
            row_tax              : row_tax,
            actual_qty           : actualQty,
            returned_qty         : returnedQty,
            return_reason        : row.find('.return-reason').val() || null,
            to_be_return_qty     : toBeReturnQty,
            to_be_return_reason  : row.find('.to-be-return-reason').val() || null,
            short_qty            : shortQty, 
            purchase_rate        : rate,
            batch_no             : batchNo,
            expiry_date          : expiryDate,
            mrp                  : mrp
        });
    });


let productTotals = {};

$('#poItemsBody tr').each(function () {
    const row = $(this);

    const productId   = row.data('product-id');
    const productName = row.find('td:nth-child(2)').text().trim(); 
    const originalPO  = parseFloat(row.data('original-po-qty')) || 0;

    const actualQty       = parseFloat(row.find('.actual-qty').val()) || 0;
    const returnedQty     = parseFloat(row.find('.returned-qty').val()) || 0;
    const toBeReturnQty   = parseFloat(row.find('.to-be-return-qty').val()) || 0;
    const shortQty = parseFloat(row.find('.short-qty').val()) || 0;

    const remaining   = parseFloat(row.data('remaining')) || originalPO;

    // const effectiveQty = actualQty > 0 ? actualQty : remaining;
    
    const totalRowQty = actualQty + returnedQty + toBeReturnQty + shortQty;

    if (!productTotals[productId]) {
        productTotals[productId] = {
            total: 0,
            poQty: originalPO,
            name : productName
        };
    }

    productTotals[productId].total += totalRowQty;
});



    Object.keys(productTotals).forEach(pid => {
    const total = productTotals[pid].total;
    const poQty = productTotals[pid].poQty;
    const name  = productTotals[pid].name;

    if (total !== poQty) {
         throw {
            title: 'Quantity Mismatch',
            message: `Product "${name}": PO Qty = ${poQty}, 
                      Actual + Returned + To-Be-Return = ${total}.
                      Total quantity must be exactly equal to PO quantity.`
        };
    }
});


    return {
        purchase_order_id : $('#purchase_order_id').val(),
        vendor_id         : $('#vendor_id').val(),
        receipt_date      : $('input[name="receipt_date"]').val(),
        bill_no           : $('input[name="bill_no"]').val(),
        bill_date         : $('input[name="bill_date"]').val(),
        subtotal          : parseFloat($('#subTotal').text()) || 0,
        discount_percent  : window.dbDiscount || 0,
        tax_amount        : parseFloat($('#taxAmount').text()) || 0,
        delivery_charges  : window.dbdelivery_charges || 0,
        grand_total       : parseFloat($('#grandTotal').text()) || 0,
        save_type         : saveType,
        items             : items
    };
}

function submitStockReceiving(saveType) {

    let payload;

    try {
        payload = buildStockReceivingPayload(saveType);
    } catch (err) {
        Swal.fire(err.title || 'Error', err.message || 'Validation failed', 'warning');
        return;
    }

    if (!payload.purchase_order_id) {
        Swal.fire('PO Required', 'Please select a Purchase Order', 'warning');
        return;
    }
    
    if (!payload.bill_no) {
        Swal.fire('PO Bill No', 'Please Enter Bill No ', 'warning');
        return;
    }

    if (!payload.receipt_date) {
        Swal.fire('Receipt Date Required', 'Please select receipt date', 'warning');
        return;
    }
   
  
    if (!payload.bill_date) {
        Swal.fire('Bill Date Required', 'Please select bill date', 'warning');
        return;
    }

    if (payload.items.length === 0) {
        Swal.fire('No Items', 'Please enter at least one quantity', 'warning');
        return;
    }


    const formData = new FormData();

    
    Object.keys(payload).forEach(key => {
        if (key === 'items') {
            formData.append('items', JSON.stringify(payload.items));
        } else {
            formData.append(key, payload[key]);
        }
    });

   
    const billFile = document.getElementById('original_bill').files[0];
 
    if (!billFile) {
    Swal.fire('Original Bill Required', 'Please upload the original bill document', 'warning');
    return;
    }

     

    if (billFile) {
        formData.append('original_bill', billFile);
    }

   

    Swal.fire({
        title: saveType === 'submit'
            ? 'Submitting & Generating Bill...'
            : 'Saving Draft...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch('/admin/stock-receivings', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              'Accept': 'application/json'
        },
        body: formData
    })
  .then(async response => {
    const data = await response.json();

    if (!response.ok) {
        let message = 'Something went wrong';

        // Laravel validation errors
        if (data.errors) {
            message = Object.values(data.errors).flat().join('\n');
        } 
        // Other backend errors
        else if (data.message) {
            message = data.message;
        }

        throw new Error(message);
    }

    return data;
})
.then(res => {
    Swal.fire({
        icon: 'success',
        title: saveType === 'submit'
            ? 'Stock Received & Bill Generated'
            : 'Stock Receiving Saved as Draft',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = res.redirect_url;
    });
})
.catch(err => {
    Swal.fire('Validation Error', err.message, 'warning');
});

}


</script>


@endsection
