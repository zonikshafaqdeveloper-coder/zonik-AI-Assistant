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

.select2-container--default .select2-selection--single .select2-selection__clear {
    display: none;
}

#invoiceTable tfoot {
    border-top: 3px solid #dee2e6;
}

#invoiceTable tfoot tr:first-child td {
    padding-top: 20px;
}

.form-check .form-check-input {
    float: left;
    margin-left: 0;
}

.qty-ok {
    border: 2px solid #28a745 !important; /* green */
}

.qty-low {
    border: 2px solid #fd7e14 !important; /* orange */
}

.qty-zero {
    border: 2px solid #dc3545 !important; /* red */
}

</style>

@php
    $editOrder = [
        'order_id'         => $order->id,
        'customer_id'      => $order->customer_id,
        'outlet_id'        => $order->outlet_id,
        'customer_name'    => optional($order->customer)->name,
        'company_name'     => $order->company_name,
        'billing_address'  => $order->billing_address,
        'shipping_pincode' => $order->shipping_pincode,
        'delivery_date'    => $order->delivery_date,
        'delivery_time'    => $order->delivery_time,
        'delivery_slot_type' => $order->delivery_slot_type,
        'delivery_charges' => $order->delivery_charges,
        'packing_charges'  => $order->packing_charges,
        'other_charges'    => $order->other_charges,
        'payment_term'     => $order->payment_method,
        'save_type'        => $order->status,
        'cart' => []
    ];

    foreach ($order->orderItems as $i) {
        $editOrder['cart'][] = [
            'product_id'     => $i->product_id,
            'product_name'   => optional($i->product)->product_name,
            'mrp'            => $i->mrp,
            'customer_price' => $i->offer_price,
            'quantity'       => $i->quantity,
            'amount'         => $i->price,
        ];
    }
@endphp

<script>
const EDIT_ORDER = @json($editOrder);
</script>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-12 m-auto">

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
                                    <h3>Edit Order &nbsp;
                                        <span class="text-muted fs-6">
                                            SO-{{ sprintf('%04d', $order->id) }}
                                        </span>
                                    </h3>
                                </div>

                                <form action="">

                                    <div class="row">

                                        {{-- Outlet --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                Outlet Name <span class="text-danger">*</span>
                                            </label>
                                            <select id="outlet_id" class="form-control select2">
                                                <option value="">Select Outlet</option>
                                                @foreach($outlets as $outlet)
                                                    <option value="{{ $outlet->id }}"
                                                        {{ $outlet->id == $order->outlet_id ? 'selected' : '' }}>
                                                        {{ $outlet->outlet_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Customer Name --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                Customer Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="customer_name" class="form-control" readonly>
                                            <input type="hidden" id="customer_id" name="customer_id">
                                        </div>

                                        {{-- Company Name --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                Company Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="company_name" class="form-control" readonly>
                                        </div>

                                        {{-- Outlet Address --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                Outlet Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="location" class="form-control" readonly>
                                        </div>

                                        {{-- Pincode --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                Outlet Pincode <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="pincode" class="form-control" readonly>
                                        </div>

                                        {{-- Delivery Slot --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                Select Delivery Slot <span class="text-danger">*</span>
                                            </label>
                                            <select id="delivery_date" class="form-control select2" disabled>
                                                <option value="">Select Delivery Slot</option>
                                            </select>
                                        </div>

                                        <div class="text-danger mt-2 d-none" id="delivery_error"></div>

                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">Invoice Products</h4>
                                        <a href="{{ route('admin.customer.price.create') }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus me-1"></i> Create Price List
                                        </a>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle" id="invoiceTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:5%">Sr</th>
                                                    <th style="width:20%">Product Name</th>
                                                    <th style="width:10%">Cost Per Item</th>
                                                    <th style="width:10%">Customer Price</th>
                                                    <th style="width:10%">Profit Margin</th>
                                                    <th style="width:10%">Quantity</th>
                                                    <th style="width:15%">Amount</th>
                                                    <th style="width:10%" class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm"
                                                            style="background: #e97457; color: #fff;"
                                                            id="addRowBtn">
                                                            + Add
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                {{-- Rows are injected by JS on page load --}}
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end">Subtotal (Basic)</td>
                                                    <td>
                                                        <input type="text" id="subtotal_basic"
                                                            class="form-control fw-bold text-end" readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end">Product Discounts</td>
                                                    <td>
                                                        <input type="text" id="product_discount"
                                                            class="form-control fw-bold text-end" readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end">CGST + SGST</td>
                                                    <td>
                                                        <input type="text" id="tax_total"
                                                            class="form-control fw-bold text-end" readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end">Delivery Charges</td>
                                                    <td>
                                                        <input type="text" id="delivery_charges"
                                                            class="form-control fw-bold text-end">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end">Packing Charges</td>
                                                    <td>
                                                        <input type="text" id="packing_charges"
                                                            class="form-control fw-bold text-end" value="0.00">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end">Other Charges</td>
                                                    <td>
                                                        <input type="text" id="other_charges"
                                                            class="form-control fw-bold text-end" value="0.00">
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end">Grand Total</td>
                                                    <td>
                                                        <input type="text" id="grand_total"
                                                            class="form-control fw-bold text-end" readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="fw-bold text-end align-middle">Payment Term</td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="payment_term" id="pay_on_delivery"
                                                                value="pay_on_delivery">
                                                            <label class="form-check-label" for="pay_on_delivery">
                                                                Pay on Delivery
                                                            </label>
                                                        </div>

                                                        <div class="form-check mt-1" id="credit_option_wrapper">
                                                            <input class="form-check-input" type="radio"
                                                                name="payment_term" id="credit_order"
                                                                value="credit">
                                                            <label class="form-check-label" for="credit_order">
                                                                Place Order on Credit
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="form-check mt-1 d-none" id="special_credit_wrapper">
                                                            <input class="form-check-input"
                                                                type="radio"
                                                                name="payment_term"
                                                                id="special_credit"
                                                                value="special_credit">
                                                
                                                            <label class="form-check-label" for="special_credit">
                                                                Place on Credit (Special Items)
                                                            </label>
                                                        </div>
                                                        
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Save Type</label>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="save_type" id="save_as_draft" value="draft" checked>
                                                <label class="form-check-label" for="save_as_draft">
                                                    Save as Draft
                                                </label>
                                            </div>

                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="radio"
                                                    name="save_type" id="save_and_send" value="sent">
                                                <label class="form-check-label" for="save_and_send">
                                                    Save and Send
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-end d-flex align-items-end">
                                            <button type="button" id="placeOrderBtn"
                                                class="btn btn-lg w-100"
                                                style="background: #e97457; color: #fff;">
                                                Save Draft
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

{{-- ══════════════════════════════════════════════════════════════
     SHARED STATE
══════════════════════════════════════════════════════════════ --}}
<script>
let CUSTOMER_CREDIT_STATUS = null;
let CUSTOMER_CREDIT_LIMIT  = 0;
let PRODUCT_CACHE          = [];
let CUSTOMER_SPECIAL_CREDIT = false;
let IS_EDIT_MODE = false;
</script>

{{-- ══════════════════════════════════════════════════════════════
     UTILITY: SR NUMBERS
══════════════════════════════════════════════════════════════ --}}
<script>
function updateSr() {
    $('#invoiceTable tbody .sr').each(function (i) {
        $(this).text(i + 1);
    });
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     ADD ROW
══════════════════════════════════════════════════════════════ --}}
<script>
$('#addRowBtn').on('click', function () {
    addNewRow();
});

function addNewRow() {
    const tableBody  = $('#invoiceTable tbody');
    const rowCount   = tableBody.children().length + 1;

    const $newRow = $(`
        <tr>
            <td class="sr">${rowCount}</td>
            <td>
                <select class="form-control product-select select2">
                    <option value="">Search Product</option>
                </select>
            </td>
            <td><input type="text" class="form-control mrp" readonly></td>
            <td><input type="text" class="form-control customer-price" readonly></td>
            <td><input type="text" class="form-control profit-margin" readonly></td>
            <td>
                <input type="text" class="form-control quantity">
                <small class="text-muted carton-size"></small>
                <small class="text-muted stock-info"></small>
            </td>
            <td><input type="text" class="form-control amount" value="0.00" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm remove-row" style="background: red; color: #fff;">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    `);

    tableBody.append($newRow);
    populateDropdown($newRow.find('.product-select'));
    initSelect2($newRow);
    updateSr();

    return $newRow;
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     MARGIN CALCULATION
══════════════════════════════════════════════════════════════ --}}
<script>
function calculateMargin($row) {
    const cost  = parseFloat($row.find('.mrp').val()) || 0;
    const price = parseFloat($row.find('.customer-price').val()) || 0;

    let marginText = '';
    if (cost > 0 && price > 0) {
        marginText = (((price - cost) / cost) * 100).toFixed(2) + ' %';
    }

console.log(marginText);
    $row.find('.profit-margin').val(marginText);
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     SELECT2 INIT & PRODUCT DROPDOWN HELPERS
══════════════════════════════════════════════════════════════ --}}
<script>
function initSelect2($context) {
    $context.find('.product-select').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
        $(this).select2({
            width: '100%',
            allowClear: true,
            dropdownParent: $('#invoiceTable')
        });
    });
}

function getSelectedProducts() {
    let selected = [];
    $('.product-select').each(function () {
        let val = $(this).val();
        if (val) selected.push(val);
    });
    return selected;
}

function populateDropdown($select) {
    let selectedProducts = getSelectedProducts();
    let currentVal       = $select.val();
    let options          = `<option value="">Search Product</option>`;

    PRODUCT_CACHE.forEach(p => {
        if (selectedProducts.includes(String(p.id)) && String(p.id) !== currentVal) return;

        options += `
            <option value="${p.id}"
                data-cost="${p.cost_per_item}"
                data-qty="${p.carton_size}"
                data-discount="${p.total_discount}"
                data-cgst="${p.cgst}"
                data-sgst="${p.sgst}"
                data-productmrp="${p.product_mrp}"
                data-stock="${p.stock}">
                ${p.product_name} (Stock: ${p.stock})
            </option>
        `;
    });

    $select.html(options).val(currentVal);
    initSelect2($select.closest('tr'));
}

function loadProductsOnce(customerId, outletId) {
    return fetch(`/admin/products/by-customer/${customerId}/${outletId}`)
        .then(res => res.json())
        .then(products => {
            PRODUCT_CACHE = products;
        });
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     CREDIT ELIGIBILITY
══════════════════════════════════════════════════════════════ --}}
<script>
// function evaluateCreditEligibility() {
//     const grandTotal = parseFloat(document.getElementById('grand_total')?.value) || 0;

//     if (
//         CUSTOMER_CREDIT_STATUS === 'Active' &&
//         grandTotal > 0 &&
//         grandTotal <= CUSTOMER_CREDIT_LIMIT
//     ) {
//         $('#credit_option_wrapper').show();
//     } else {
//         $('#credit_option_wrapper').hide();
//         $('#pay_on_delivery').prop('checked', true);
//     }
// }

// function evaluateCreditEligibility() {

//     const grandTotal = parseFloat($('#grand_total').val()) || 0;
//     const selectedPayment = $('input[name="payment_term"]:checked').val();

//     const isEligible =
//         CUSTOMER_CREDIT_STATUS === 'Active' &&
//         grandTotal > 0 &&
//         grandTotal <= CUSTOMER_CREDIT_LIMIT;

//     if (isEligible) {
//         $('#credit_option_wrapper').show();

//         // ❌ DO NOT override if already credit
//         return;
//     }

//     $('#credit_option_wrapper').hide();

//     // 🚨 Only force change if NOT edit mode
//     if (!IS_EDIT_MODE && selectedPayment === 'credit') {
//         $('#pay_on_delivery').prop('checked', true);
//     }
// }

function evaluateCreditEligibility() {

    const selectedPayment = $('input[name="payment_term"]:checked').val();

    if (CUSTOMER_SPECIAL_CREDIT) {

        $('#special_credit_wrapper')
            .removeClass('d-none');

    } else {

        $('#special_credit_wrapper')
            .addClass('d-none');

        if (selectedPayment === 'special_credit') {

            $('#pay_on_delivery')
                .prop('checked', true);
        }
    }
}

</script>

{{-- ══════════════════════════════════════════════════════════════
     DELIVERY CHARGES
══════════════════════════════════════════════════════════════ --}}
<script>
let DELIVERY_SINGLE = 0;
let DELIVERY_BULK   = 0;
let PACKING_CHARGE  = 0;
let OTHER_CHARGE    = 0;

function fetchDeliveryCharges(pincode) {
    fetch(`/admin/get-delivery-charges/${pincode}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success && data.not_servicable) {
                alert(data.message);
                $('#delivery_charges').val('0.00');
                $('#packing_charges').val('0.00');
                $('#other_charges').val('0.00');
                calculateSubtotal();
                return;
            }

            DELIVERY_SINGLE = parseFloat(data.single_delivery_charges) || 0;
            DELIVERY_BULK   = parseFloat(data.bulk_delivery_charges)   || 0;
            PACKING_CHARGE  = parseFloat(data.packing_charges)         || 0;
            OTHER_CHARGE    = parseFloat(data.other_charges)           || 0;

            applyDeliveryCharges();
        });
}

function applyDeliveryCharges() {
    const totalQty      = getTotalQuantity();
    const deliveryCharge = totalQty > 24 ? DELIVERY_BULK : DELIVERY_SINGLE;

    document.getElementById('delivery_charges').value = deliveryCharge.toFixed(2);
    $('#packing_charges').val(PACKING_CHARGE.toFixed(2));
    $('#other_charges').val(OTHER_CHARGE.toFixed(2));

    calculateSubtotal();
}

function getTotalQuantity() {
    let totalQty = 0;
    document.querySelectorAll('#invoiceTable tbody tr').forEach(row => {
        totalQty += parseFloat(row.querySelector('.quantity')?.value) || 0;
    });
    return totalQty;
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     SUBTOTAL / GRAND TOTAL
══════════════════════════════════════════════════════════════ --}}
<script>
function calculateSubtotal() {
    let subtotal             = 0;
    let totalProductDiscount = 0;
    let totalGST             = 0;

    document.querySelectorAll('#invoiceTable tbody tr').forEach(row => {
        const qty   = parseFloat(row.querySelector('.quantity')?.value)  || 0;
        const mrp   = parseFloat(row.querySelector('.mrp')?.value)       || 0;
        const price = parseFloat(row.querySelector('.customer-price')?.value) || 0;

        const productSelect = row.querySelector('.product-select');
        if (!productSelect || !productSelect.value) {
            row.querySelector('.amount').value = '0.00';
            return;
        }

        const discountPct    = parseFloat(productSelect.selectedOptions[0]?.dataset.discount) || 0;
        const cgst           = parseFloat(productSelect.selectedOptions[0]?.dataset.cgst)     || 0;
        const sgst           = parseFloat(productSelect.selectedOptions[0]?.dataset.sgst)     || 0;
        const totalGSTPercent = cgst + sgst;

        const rowTotal   = price > 0 ? qty * price : qty * mrp;
        row.querySelector('.amount').value = rowTotal.toFixed(2);
        subtotal += rowTotal;

        totalProductDiscount += discountPct > 0 ? (rowTotal * discountPct) / 100 : 0;
        totalGST             += totalGSTPercent > 0 ? (rowTotal * totalGSTPercent) / 100 : 0;
    });

    const deliveryCharges = parseFloat(document.getElementById('delivery_charges')?.value) || 0;
    const packingCharges  = parseFloat(document.getElementById('packing_charges')?.value)  || 0;
    const otherCharges    = parseFloat(document.getElementById('other_charges')?.value)    || 0;
    const grandTotal      = subtotal + totalGST + deliveryCharges + packingCharges + otherCharges;

    document.getElementById('subtotal_basic').value   = subtotal.toFixed(2);
    document.getElementById('product_discount').value = totalProductDiscount.toFixed(2);
    document.getElementById('tax_total').value        = totalGST.toFixed(2);
    document.getElementById('grand_total').value      = grandTotal.toFixed(2);

    evaluateCreditEligibility();
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     DELIVERY SLOTS
══════════════════════════════════════════════════════════════ --}}
<script>
/**
 * Fetch delivery slots for a pincode, then optionally pre-select a value.
 * @param {string} pincode
 * @param {string|null} preselectDate  – delivery_date value to re-select (edit mode)
 */
function fetchDeliverySlots(pincode, preselectDate = null) {
    const $deliverySelect = $('#delivery_date');
    const $errorBox       = $('#delivery_error');

    $deliverySelect
        .empty()
        .append('<option value="">Select Delivery Slot</option>')
        .prop('disabled', true)
        .trigger('change');

    $errorBox.addClass('d-none').text('');

    fetch(`/admin/get-delivery-slots/${pincode}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success && data.not_servicable) {
                $errorBox.removeClass('d-none').text(data.message);
                return;
            }

            data.delivery_options.forEach(option => {
                let slotType = '';
                if (option.slot.includes('Slot 1')) slotType = 'slot-1';
                else if (option.slot.includes('Slot 2')) slotType = 'slot-2';

                const opt = new Option(option.slot, option.date);
                opt.setAttribute('data-time', option.time_only);
                opt.setAttribute('data-slot-type', slotType);
                $deliverySelect.append(opt);
            });

            $deliverySelect.prop('disabled', false);

            // Re-select previously chosen slot in edit mode
            if (preselectDate) {
                $deliverySelect.val(preselectDate);
            }

            $deliverySelect.trigger('change');
        });
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     MAIN DOCUMENT READY
══════════════════════════════════════════════════════════════ --}}
<script>
$(document).ready(function () {

    // ── Init top-level Select2 fields ───────────────────────────
    $('#outlet_id, #delivery_date').select2({
        width: '100%',
        allowClear: true
    });

    const tableBody = $('#invoiceTable tbody');

    /* ──────────────────────────────────────────────────────────
       OUTLET CHANGE  (same logic as create, used when user
       manually changes the outlet while editing)
    ────────────────────────────────────────────────────────── */
    $('#outlet_id').on('select2:select select2:clear', function () {

        const outletId = $(this).val();

        $('#customer_id').val('');
        $('#customer_name, #company_name, #location, #pincode').val('');

        tableBody.find('tr').each(function () {
            const $row = $(this);
            $row.find('.product-select')
                .empty()
                .append('<option value="">Select Outlet First</option>')
                .trigger('change');
            $row.find('.mrp, .customer-price, .profit-margin, .amount').val('');
            $row.find('.quantity').val('');
        });

        calculateSubtotal();

        if (!outletId) return;

        // Fetch outlet details
        fetch(`/admin/get-outlet-details/${outletId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.verified_status || data.verified_status === 'unverified') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Outlet Not Verified',
                        text: 'This outlet does not have a verified payment term.'
                    });
                    $('#outlet_id').val(null).trigger('change');
                    return;
                }

                $('#location').val(data.location ?? '');
                $('#pincode').val(data.pincode ?? '');
                $('#customer_name').val(data.customer_name ?? '');
                $('#company_name').val(data.company_name ?? '');
                $('#customer_id').val(data.customer_id ?? '');

                if (data.pincode) {
                    fetchDeliveryCharges(data.pincode);
                    fetchDeliverySlots(data.pincode);
                }

                const customerId = $('#customer_id').val();
                loadProductsOnce(customerId, outletId).then(() => {
                    $('.product-select').each(function () {
                        populateDropdown($(this));
                    });
                });
            });

        // Fetch credit info
        fetch(`/admin/get-customer-credit/${outletId}`)
            .then(res => res.json())
            .then(data => {
                // CUSTOMER_CREDIT_STATUS = data.credit_status;
                // CUSTOMER_CREDIT_LIMIT  = parseFloat(data.credit_limit) || 0;
                 CUSTOMER_SPECIAL_CREDIT = data.special_credit || false;
                evaluateCreditEligibility();
            });
    });

    /* ──────────────────────────────────────────────────────────
       PRODUCT CHANGE
    ────────────────────────────────────────────────────────── */
    tableBody.on('change', '.product-select', function () {
        const $row    = $(this).closest('tr');
        const selected = $(this).find(':selected');
        const productSelected = $(this).val();
        const carton  = selected.data('qty')   || 0;
        const stock   = selected.data('stock') || 0;
        
        $row.find('.quantity').val(0).attr('data-stock', stock);

        // console.log('SETTING STOCK:', stock);

        $row.find('.mrp').val(selected.data('cost') || 0);
        $row.find('.quantity').val(0);
        $row.find('.carton-size').text(`Box: ${carton}`);
        $row.find('.customer-price').val('');
        $row.find('.profit-margin').val('');
        $row.find('.stock-info').text(`Stock: ${stock}`);

        calculateMargin($row);
        calculateSubtotal();

        if (productSelected && $row.is(':last-child')) {
            addNewRow();
        }

        $('.product-select').each(function () {
            populateDropdown($(this));
        });

        const customerId = $('#customer_id').val();
        const outletId   = $('#outlet_id').val();
        const productId  = productSelected;

        if (!customerId || !productId || !outletId) return;

        fetch(`/admin/customer-product-price/${customerId}/${outletId}/${productId}`)
            .then(res => res.json())
            .then(data => {
                $row.find('.customer-price').val(data.product_price ?? '');
                calculateMargin($row);
                calculateSubtotal();
                applyDeliveryCharges();
            });
    });

    tableBody.on('input', '.customer-price, .quantity', function () {
        const $row = $(this).closest('tr');
        calculateMargin($row);
        calculateSubtotal();
    });

    /* ──────────────────────────────────────────────────────────
       REMOVE ROW
    ────────────────────────────────────────────────────────── */
    tableBody.on('click', '.remove-row', function () {
        if (tableBody.children().length === 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Action Not Allowed',
                text: 'At least one product row is required.',
                confirmButtonText: 'OK'
            });
            return;
        }
        $(this).closest('tr').remove();
        updateSr();
        calculateSubtotal();
        applyDeliveryCharges();

        $('.product-select').each(function () {
            populateDropdown($(this));
        });
    });

    /* ──────────────────────────────────────────────────────────
       QUANTITY VALIDATION
    ────────────────────────────────────────────────────────── */
    tableBody.on('input', '.quantity', function () {
        const $row = $(this).closest('tr');
        const qty  = parseFloat($(this).val()) || 0;
        const stock = parseFloat(
            $row.find('.product-select option:selected').data('stock')
        ) || 0;

        // if (qty > stock) {
        //     Swal.fire({
        //         icon: 'warning',
        //         title: 'Stock Limit Exceeded',
        //         text: `Only ${stock} items available in stock`
        //     });
        //     $(this).val('');
        //     return;
        // }

        calculateSubtotal();
        applyDeliveryCharges();
    });

    /* ──────────────────────────────────────────────────────────
       CHARGE FIELDS
    ────────────────────────────────────────────────────────── */
    $(document).on('input', '#delivery_charges, #packing_charges, #other_charges', function () {
        calculateSubtotal();
    });

    /* ──────────────────────────────────────────────────────────
       SAVE TYPE BUTTON LABEL
    ────────────────────────────────────────────────────────── */
    $('input[name="save_type"]').on('change', function () {
        $('#placeOrderBtn').text($(this).val() === 'draft' ? 'Save Draft' : 'Update Order');
    });

    /* ══════════════════════════════════════════════════════════
       PRE-FILL FORM FROM EDIT_ORDER (edit mode bootstrap)
    ══════════════════════════════════════════════════════════ */
    prefillEditForm();
});

/* ──────────────────────────────────────────────────────────────
   PRE-FILL: loads outlet details silently, populates all fields,
   then re-renders saved cart rows.
────────────────────────────────────────────────────────────── */
function prefillEditForm() {

    const d = EDIT_ORDER;

    if (!d.outlet_id) return;

    // Step 1: fetch outlet details (address, pincode, customer data)
    fetch(`/admin/get-outlet-details/${d.outlet_id}`)
        .then(res => res.json())
        .then(outletData => {

            $('#location').val(outletData.location ?? '');
            $('#pincode').val(outletData.pincode   ?? '');
            $('#customer_name').val(d.customer_name ?? outletData.customer_name ?? '');
            $('#company_name').val(d.company_name   ?? outletData.company_name  ?? '');
            $('#customer_id').val(d.customer_id     ?? outletData.customer_id   ?? '');

            const pincode    = outletData.pincode ?? d.shipping_pincode;
            const customerId = $('#customer_id').val();
            const outletId   = d.outlet_id;

            // Step 2: load delivery charges for the pincode
            if (pincode) {
                fetch(`/admin/get-delivery-charges/${pincode}`)
                    .then(r => r.json())
                    .then(chargeData => {
                        if (chargeData.success !== false) {
                            DELIVERY_SINGLE = parseFloat(chargeData.single_delivery_charges) || 0;
                            DELIVERY_BULK   = parseFloat(chargeData.bulk_delivery_charges)   || 0;
                            PACKING_CHARGE  = parseFloat(chargeData.packing_charges)         || 0;
                            OTHER_CHARGE    = parseFloat(chargeData.other_charges)           || 0;
                        }

                        // Override with saved values from the order
                        $('#delivery_charges').val(parseFloat(d.delivery_charges).toFixed(2));
                        $('#packing_charges').val(parseFloat(d.packing_charges).toFixed(2));
                        $('#other_charges').val(parseFloat(d.other_charges).toFixed(2));
                    });

                // Step 3: fetch & pre-select delivery slots
                fetchDeliverySlots(pincode, d.delivery_date);
            }

            // Step 4: load product cache, then rebuild cart rows
            loadProductsOnce(customerId, outletId).then(() => {
                rebuildCartRows(d.cart);
            });

IS_EDIT_MODE = true;

fetch(`/admin/get-customer-credit/${outletId}`)
.then(r => r.json())
.then(data => {

    // CUSTOMER_CREDIT_STATUS = creditData.credit_status;
    // CUSTOMER_CREDIT_LIMIT  = parseFloat(creditData.credit_limit) || 0;

    // const paymentTerm = d.payment_term === 'credit' ? 'credit' : 'pay_on_delivery';

    // $('input[name="payment_term"]').prop('checked', false);
    // $(`input[name="payment_term"][value="${paymentTerm}"]`).prop('checked', true);
    
        CUSTOMER_SPECIAL_CREDIT = data.special_credit || false;

    $('input[name="payment_term"]').prop('checked', false);

    const paymentTerm = d.payment_term || 'pay_on_delivery';

    $(`input[name="payment_term"][value="${paymentTerm}"]`)
        .prop('checked', true);
        

    evaluateCreditEligibility();

    IS_EDIT_MODE = false;
});
        });

    // Pre-select payment term
console.log("Before set:", d.payment_term);

$('input[name="payment_term"]').prop('checked', false);

$(`input[name="payment_term"][value="${d.payment_term}"]`).prop('checked', true);

console.log("After set:", $('input[name="payment_term"]:checked').val());
   

    // Pre-select save type & update button label
    const saveType = d.save_type === 'sent' ? 'sent' : 'draft';
    $(`input[name="save_type"][value="${saveType}"]`).prop('checked', true);
    $('#placeOrderBtn').text(saveType === 'draft' ? 'Save Draft' : 'Update Order');

// Payment Term Fix

}

/* ──────────────────────────────────────────────────────────────
   REBUILD CART: insert one row per saved cart item, set values,
   then fetch the customer price to confirm/fill customer-price.
────────────────────────────────────────────────────────────── */
function rebuildCartRows(cart) {

    const tableBody = $('#invoiceTable tbody');
    tableBody.empty(); // clear any existing rows

    if (!cart || cart.length === 0) {
        addNewRow(); // at least one empty row
        return;
    }

    cart.forEach((item, index) => {

        const $row = addNewRow();

        // Find matching option in PRODUCT_CACHE
        const product = PRODUCT_CACHE.find(p => String(p.id) === String(item.product_id));

        if (!product) {
            // Product not in price list — skip gracefully
            console.warn(`Product ${item.product_id} not found in cache`);
            return;
        }

        // Set the select value and trigger to populate data-* attributes
        const $select = $row.find('.product-select');
        $select.val(item.product_id).trigger('change.select2');

        // Manually set field values (change event resets them, so set after)
        $row.find('.mrp').val(parseFloat(product.cost_per_item).toFixed(2));
        $row.find('.customer-price').val(parseFloat(item.customer_price).toFixed(2));
        $row.find('.quantity').val(item.quantity);
        $row.find('.carton-size').text(`Box: ${product.carton_size || 0}`);
        $row.find('.stock-info').text(`Stock: ${product.stock || 0}`);
        
         const stock = parseFloat(product.stock) || 0;
         const $qty = $row.find('.quantity');
         
        $qty.attr('data-stock', stock).val(item.quantity);
        updateQtyUI($qty);

        calculateMargin($row);
    });

    // Add one blank row at the end for new additions
    addNewRow();

    // Refresh all dropdowns to exclude already-selected products
    $('.product-select').each(function () {
        populateDropdown($(this));
    });

    calculateSubtotal();
    applyDeliveryCharges();
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     BUILD PAYLOAD
══════════════════════════════════════════════════════════════ --}}
<script>
function buildInvoicePayload() {
    let cart     = [];
    let totalQty = 0;

    $('#invoiceTable tbody tr').each(function () {
        const $row          = $(this);
        const $productSelect = $row.find('.product-select');
        const productId     = $productSelect.val();
        if (!productId) return;

        const qty           = parseFloat($row.find('.quantity').val())         || 0;
        const mrp           = parseFloat($row.find('.mrp').val())              || 0;
        const customerPrice = parseFloat($row.find('.customer-price').val())   || 0;
        const productMRP    = parseFloat($productSelect.find(':selected').data('productmrp')) || 0;

        totalQty += qty;

        const amount      = customerPrice > 0 ? qty * customerPrice : qty * mrp;
        const offer_price = customerPrice > 0 ? customerPrice : mrp;

        cart.push({
            product_id     : productId,
            quantity       : qty,
            offer_price    : offer_price,
            product_mrp    : productMRP,
            mrp            : mrp,
            customer_price : customerPrice,
            amount         : parseFloat(amount.toFixed(2)),
        });
    });

    const $selected        = $('#delivery_date option:selected');
    const deliveryDate     = $('#delivery_date').val() || null;
    const deliveryTime     = $selected.data('time')      || null;
    const deliverySlotType = $selected.data('slot-type') || null;

    return {
        order_id           : EDIT_ORDER.order_id,   
        customer_id        : $('#customer_id').val(),
        outlet_id          : $('#outlet_id').val(),
        company_name       : $('#company_name').val(),
        billing_address    : $('#location').val(),
        shipping_address   : $('#location').val(),
        shipping_pincode   : $('#pincode').val(),
        delivery_date      : deliveryDate,
        deliveryTime       : deliveryTime,
        delivery_slot_type : deliverySlotType,
        subtotal           : parseFloat($('#subtotal_basic').val())   || 0,
        product_discount   : parseFloat($('#product_discount').val()) || 0,
        tax_total          : parseFloat($('#tax_total').val())        || 0,
        delivery_charges   : parseFloat($('#delivery_charges').val()) || 0,
        packing_charges    : parseFloat($('#packing_charges').val())  || 0,
        other_charges      : parseFloat($('#other_charges').val())    || 0,
        grand_total        : parseFloat($('#grand_total').val())      || 0,
        payment_term       : $('input[name="payment_term"]:checked').val(),
        total_quantity     : totalQty,
        cart               : cart,
    };
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     PLACE ORDER BUTTON
══════════════════════════════════════════════════════════════ --}}
<script>
$('#placeOrderBtn').on('click', function () {
    const payload  = buildInvoicePayload();
    const saveType = $('input[name="save_type"]:checked').val();
    payload.save_type = saveType;

    if (saveType === 'draft') {
        saveDraft(payload);   
    } else {
        updateOrder(payload);
    }
});
</script>

{{-- ══════════════════════════════════════════════════════════════
     SAVE DRAFT  (identical to create page)
══════════════════════════════════════════════════════════════ --}}
<script>
function saveDraft(payload) {

    Swal.fire({
        title: 'Saving Draft...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(`/admin/orders/${payload.order_id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({
            ...payload,
            save_type: 'draft' // force draft
        })
    })
    .then(res => res.json())
    .then(res => {

        if (!res.success) {
            Swal.fire('Error', res.message, 'error');
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Draft Updated',
            text: 'Draft updated successfully'
        }).then(() => {
            window.location.href = res.redirect_url;
        });
    })
    .catch(() => {
        Swal.fire('Error', 'Something went wrong', 'error');
    });
}
</script>

{{-- ══════════════════════════════════════════════════════════════
     UPDATE ORDER  (edit-specific, calls PUT /admin/orders/{id})
══════════════════════════════════════════════════════════════ --}}
<script>
function updateOrder(payload) {

    // ── Validations (same as placeOrder) ────────────────────────
    let hasInvalidQty = false;
    let hasValidQty   = false;

    payload.cart.forEach(item => {
        if (item.quantity <= 0 || isNaN(item.quantity)) hasInvalidQty = true;
        if (item.quantity > 0) hasValidQty = true;
    });

    if (!hasValidQty) {
        Swal.fire({ icon: 'warning', title: 'Quantity Required', text: 'Please enter quantity > 0 for at least one product' });
        return;
    }
    if (hasInvalidQty) {
        Swal.fire({ icon: 'warning', title: 'Invalid Quantity', text: 'Quantity must be > 0 for all selected products' });
        return;
    }
    if (!payload.customer_id) {
        Swal.fire({ icon: 'warning', title: 'Customer Required', text: 'Please select a customer' });
        return;
    }
    if (!payload.outlet_id) {
        Swal.fire({ icon: 'warning', title: 'Outlet Required', text: 'Please select an outlet' });
        return;
    }
    if (!payload.billing_address) {
        Swal.fire({ icon: 'warning', title: 'Outlet Address Required', text: 'Outlet address is missing.' });
        return;
    }
    if (!payload.shipping_pincode) {
        Swal.fire({ icon: 'warning', title: 'Pincode Required', text: 'Please select an outlet with a valid pincode' });
        return;
    }
    if (!payload.delivery_date || !payload.deliveryTime) {
        Swal.fire({ icon: 'warning', title: 'Delivery Slot Required', text: 'Please select a delivery date and time slot' });
        return;
    }
    if (payload.cart.length === 0) {
        Swal.fire({ icon: 'warning', title: 'No Products', text: 'Please add at least one product' });
        return;
    }

    // ── Loading ──────────────────────────────────────────────────
    Swal.fire({
        title: 'Updating Order...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    // ── PUT request ──────────────────────────────────────────────
    fetch(`/admin/orders/${payload.order_id}`, {
        method: 'PUT',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(payload)
    })
    .then(async res => {
        if (!res.ok) { const text = await res.text(); throw new Error(text); }
        return res.json();
    })
    .then(res => {
        if (!res.success) {
            Swal.fire({ icon: 'error', title: 'Update Failed', text: res.message || 'Something went wrong' });
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Order Updated Successfully',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = res.redirect_url;
        });
    })
    .catch(err => {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Server Error', text: 'Something went wrong. Please try again.' });
    });
}
</script>
<script>
const tableBody = $('#invoiceTable tbody');
tableBody.on('input', '.quantity', function () {

    let input = $(this);

    let qty = parseInt(input.val()) || 0;
    let stock = parseInt(input.data('stock')) || 0;

    // console.log('QTY:', qty);
    // console.log('STOCK:', stock);
    // console.log('DATA-STOCK RAW:', input.data('stock'));

    // Remove old classes
    input.removeClass('qty-ok qty-low qty-zero');

    if (stock === 0) {
        // console.log('CASE: ZERO STOCK');
        input.addClass('qty-zero');

    } else if (qty > stock) {
        // console.log('CASE: LOW STOCK');
        input.addClass('qty-low');

    } else {
        // console.log('CASE: OK STOCK');
        input.addClass('qty-ok');
    }

});

function updateQtyUI(input) {

    let qty = parseFloat(input.val()) || 0;
    let stock = parseFloat(input.attr('data-stock')) || 0;

    // console.log('QTY:', qty);
    // console.log('STOCK:', stock);

    input.removeClass('qty-ok qty-low qty-zero');

    if (stock === 0) {
        input.addClass('qty-zero');

    } else if (qty > stock) {
        input.addClass('qty-low');

    } else {
        input.addClass('qty-ok');
    }
}
</script>

@endsection
