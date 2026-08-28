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

.select2-container--default .select2-selection--single .select2-selection__clear{
    display:none;
}

#invoiceTable tfoot {
    border-top: 3px solid #dee2e6;
}

#invoiceTable tfoot tr:first-child td {
    padding-top: 20px;
}
.form-check .form-check-input{
    float: left;
    margin-left: 0;
}
</style>
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
                    
                      @if ($purchaseOrder->status === 'draft' && $purchaseOrder->rejection_reason)
                            <div class="alert alert-danger mb-2">
                                <h6 class="mb-1">
                                    <i class="ri-error-warning-line"></i>
                                    Purchase Order Rejected
                                </h6>
                                <p class="mb-0">
                                    <strong>Reason:</strong><br>
                                    {{ $purchaseOrder->rejection_reason }}
                                </p>
                            </div>
                        @endif
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header-2 mb-3">
                                    <h3>Edit Purchase Order</h3>
                                </div>

                                 <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Vendor <span class="text-danger">*</span></label>
                <select id="vendor_id" class="form-control select2">
                    <option value="">Select Vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}"
                            {{ $purchaseOrder->vendor_id == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Location <span class="text-danger">*</span> </label>
                <input type="text" id="location"
                       class="form-control"
                       value="{{ $purchaseOrder->location }}"
                       readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label>Pincode <span class="text-danger">*</span> </label>
                <input type="text" id="pincode"
                       class="form-control"
                       value="{{ $purchaseOrder->pincode }}"
                       readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Gst Number <span class="text-danger"></span></label>
                <input type="text" id="gst_no" class="form-control" readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label>Purchase Order # <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control"
                       value="{{ $purchaseOrder->purchase_order_number }}"
                       readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label>Reference#</label>
                <input type="text" id="reference"
                       class="form-control"
                       value="{{ $purchaseOrder->reference }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>PO Date <span class="text-danger">*</span></label>
                <input type="date" id="po_date"
                       class="form-control"
                       value="{{ $purchaseOrder->po_date }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Delivery Date <span class="text-danger">*</span></label>
                <input type="date" id="delivery_date"
                       class="form-control"
                       value="{{ $purchaseOrder->delivery_date }}">
            </div>
        </div>

        <hr>

        {{-- =====================
           PRODUCTS TABLE
        ====================== --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="invoiceTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Vendor Price</th>
                        <th>Cgst + Sgst</th>
                        <th>Qty</th>
                        <th>Free Qty</th>
                        <th>Mrp</th>
                        <th>Amount</th>
                        <th>

                         <button type="button" class="btn btn-sm" style="background: #e97457; color: #fff;"
                                                id="addRowBtn">
                                                    + Add

                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($purchaseOrder->items as $i => $item)
                        <tr>
                            <td class="sr">{{ $i + 1 }}</td>

                            <td>
                                <select class="form-control product-select">
                                    <option value="{{ $item->product_id }}">
                                        {{ $item->product->product_name }}
                                    </option>
                                </select>
                            </td>

                            <td>
                                <input type="text"
                                       class="form-control vendor-price"
                                       value="{{ $item->vendor_price }}"
                                       readonly>
                            </td>

                             <td>
                                        <div class="position-relative">
                                            <input type="number"
                                                class="form-control row-tax fw-bold text-end pe-4"
                                                 value="{{ $item->row_tax }}">
                                            <span class="position-absolute top-50 end-0 translate-middle-y me-2 fw-bold">%</span>
                                          </div>
                                       </td>
                                        
                                       
                                       <td style="display:none;">
                                               <input type="text" class="form-control tax_total_amount1">
                                        </td>

                            <td>
                                <input type="number"
                                       class="form-control quantity"
                                       value="{{ $item->quantity }}">
                                       <small class="text-muted carton-size"></small>
                            </td>

                                 <td>
                                    <input type="number" class="form-control free-quantity" value="{{ $item->free_quantity }}">
                                </td>
                                
                                  <td>
                                    <input type="number" class="form-control mrp-price" value="{{ $item->mrp }}">
                                </td>
                                
                            <td>
                                <input type="text"
                                       class="form-control amount"
                                       value="{{ $item->amount }}"
                                       readonly>
                            </td>

                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-sm btn-danger remove-row" style="background:red;color:#fff;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        
                    @endforeach

                    
                </tbody>
            </table>
        </div>

        {{-- =====================
           TOTALS
        ====================== --}}
        <div class="row justify-content-end mt-4">
            <div class="col-md-5">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-end">Subtotal</th>
                        <td class="text-end">
                            <input id="subtotal_basic" class="form-control text-end"
                                   value="{{ $purchaseOrder->subtotal_basic }}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">Discount (%)</th>
                        <td>
                            <input id="product_discount" class="form-control text-end"
                                   value="{{ $purchaseOrder->product_discount }}">
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">Tax (%)</th>
                        <td>
                            <input id="tax_total_amount" readonly class="form-control text-end"
                                   value="{{ $purchaseOrder->tax_total }}">
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">Delivery Charges</th>
                        <td>
                            <input id="delivery_charges" class="form-control text-end"
                                   value="{{ $purchaseOrder->delivery_charges }}">
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">Grand Total</th>
                        <td>
                            <input id="grand_total" class="form-control text-end"
                                   value="{{ $purchaseOrder->grand_total }}" readonly>
                        </td>
                    </tr>

                    <tr>
 
                    <td class="fw-bold text-end align-middle">Payment Term</td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input"
                                type="radio"
                                name="payment_term"
                                id="pay_on_delivery"
                                value="pay_on_delivery"
                                {{ $purchaseOrder->payment_method === 'pay_on_delivery' ? 'checked' : '' }}>
                            <label class="form-check-label" for="pay_on_delivery">
                                Pay on Delivery
                            </label>
                        </div>

                        <div class="form-check mt-1"
                            id="credit_option_wrapper"
                            style="{{ $purchaseOrder->payment_method === 'credit' ? '' : 'display:none;' }}">
                            <input class="form-check-input"
                                type="radio"
                                name="payment_term"
                                id="credit_order"
                                value="credit"
                                {{ $purchaseOrder->payment_method === 'credit' ? 'checked' : '' }}>
                            <label class="form-check-label" for="credit_order">
                                Place Order on Credit
                            </label>
                        </div>
                    </td>
                
                </tr>

                <tr>
                
                    <td class="fw-bold text-end align-middle">Save Type</td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input"
                                type="radio"
                                name="save_type"
                                id="save_as_draft"
                                value="draft"
                                {{ $purchaseOrder->save_type === 'draft' ? 'checked' : '' }}>
                            <label class="form-check-label" for="save_as_draft">
                                Save as Draft
                            </label>
                        </div>

                        <div class="form-check mt-1">
                            <input class="form-check-input"
                                type="radio"
                                name="save_type"
                                id="save_and_send"
                                value="sent"
                                {{ $purchaseOrder->save_type !== 'draft' ? 'checked' : '' }}>
                            <label class="form-check-label" for="save_and_send">
                                Save and Send
                            </label>
                        </div>
                    </td>

                </tr>

                    
                </table>
            </div>
        </div>

        {{-- =====================
           ACTIONS
        ====================== --}}
        <div class="text-end mt-4">
            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button id="placeOrderBtn" class="btn btn-primary">
                Update Purchase Order
            </button>
        </div>

    

                                
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
    const PURCHASE_ORDER_ID = {{ $purchaseOrder->id }};
</script>

<script>

    function resetRow(row) {
    row.find('.mrp').val('');
    row.find('.vendor-price').val('');
    row.find('.profit-margin').val('');
    row.find('.quantity').val('');
    row.find('.row-tax').val('');
    row.find('.tax_total_amount1').val('0.00');
    row.find('.amount').val('0.00');
}

 function getSelectedProducts() {
        let selected = [];

        $('.product-select').each(function () {
            let val = $(this).val();
            if (val) {
                selected.push(val);
            }
        });

        return selected;
    }

    function loadVendorDetails(vendorId) {

    if (!vendorId) return;

    fetch(`/vendors/${vendorId}/details`)
        .then(res => res.json())
        .then(data => {
            
            if (!data.verified_status || data.verified_status === 'unverified') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Vendor Not Verified',
                            text: 'This vendor payment term is not verified. Please select another vendor.'
                        });

                        
                        $('#vendor_id').val(null).trigger('change');

                        return; 
                    }
                    
            $('#location').val(data.location ?? '');
            $('#pincode').val(data.pincode ?? '');
            $('#gst_no').val(data.gst_no ?? '');
        });
}


$(document).ready(function () {

    const vendorId = $('#vendor_id').val();

    if (vendorId) {
        loadVendorDetails(vendorId);
    }

});


$(document).ready(function () {

    const tableBody = $('#invoiceTable tbody');

    /* =========================
       RESET ROW
    ========================== */
  
    /* =========================
       INIT PRODUCT SELECT2
    ========================== */
    function initProductSelect2($select, placeholder) {

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            width: '100%',
            allowClear: true,
            placeholder: placeholder,
            dropdownParent: $('#invoiceTable')
        });
    }

    /* =========================
       LOAD PRODUCTS
    ========================== */
$(document).ready(function () {

    let vendorId = $('#vendor_id').val();

    if (!vendorId) {
        console.warn('Vendor not selected yet');
        return;
    }

    loadEditRows();
});

function loadEditRows() {

    $('#invoiceTable tbody tr').each(function () {

        let row = $(this);
        let select = row.find('.product-select');
        let productId = select.find('option:first').val();

        if (productId) {
            loadProducts(select, productId, row);
        }
    });
}

function loadProducts($select, selectedProductId = null, row = null) {

    const vendorId = $('#vendor_id').val();
    if (!vendorId) return;

    fetch(`/vendors/${vendorId}/products`)
        .then(res => res.json())
        .then(products => {
            
            let selectedProducts = getSelectedProducts();
            let currentVal = $select.val(); 

            let options = `<option value="">Select Product</option>`;

            products.forEach(p => {
                
                 if (selectedProducts.includes(String(p.product_id)) && String(p.product_id) !== currentVal) {
                    return;
                }
                
                options += `
                    <option value="${p.product_id}"
                        data-cost="${p.cost_per_item}"
                        data-vendor-price="${p.vendor_price}"
                        data-qty="${p.carton_size}"
                        data-gst="${p.gst_percent}">
                        ${p.product_name}
                    </option>`;
            });

            
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.html(options).val(currentVal);

            initProductSelect2($select, 'Select Product');

            if (selectedProductId) {
                $select.val(selectedProductId).trigger('change.select2');
                
                 if (row) {
                    let selected = $select.find(`option[value="${selectedProductId}"]`);
                    let qty = parseFloat(selected.data('qty')) || 1;

                    row.find('.carton-size').text(`(Box: ${qty})`);

                     $('.product-select').each(function () {
                    loadProducts($(this));
                    });
                }
                
            }

         
            if (row) {
                calculateRowAmount(row);
                calculateSubtotal();
            }
        });
}


$('#vendor_id').on('change', function () {
    loadEditRows();
});






    
    /* =========================
       VENDOR SELECT2
    ========================== */
    $('#vendor_id').select2({
        width: '100%',
        placeholder: 'Select Vendor',
        allowClear: true
    });

    $('#vendor_id').on('change', function () {

        $('#location').val('');
        $('#pincode').val('');
        $('#gst_no').val('');

        const vendorId = $(this).val();

        if (vendorId) {
        loadVendorDetails(vendorId);
    }

        // if (vendorId) {
        //     fetch(`/vendors/${vendorId}/details`)
        //         .then(res => res.json())
        //         .then(data => {
        //             $('#location').val(data.location);
        //             $('#pincode').val(data.pincode);
        //             $('#gst_no').val(data.gst_no);
        //         });
        // }

        $('.product-select').each(function () {
        let row = $(this).closest('tr');
        resetRow(row);
        const selectedProductId = null;
        loadProducts($(this), selectedProductId);
    });

        calculateSubtotal();
        validateVendorCredit();
    });



 






    function validateVendorCredit() {

    const vendorId = $('#vendor_id').val();
    const grandTotal = parseFloat($('#grand_total').val()) || 0;

    $('#credit_option_wrapper').hide();
    $('#credit_order').prop('checked', false);
    $('#pay_on_delivery').prop('checked', true);

    if (!vendorId || grandTotal <= 0) {
        return;
    }

    fetch(`/vendors/${vendorId}/credit-eligibility`)
        .then(res => res.json())
        .then(data => {

            if (
                data.eligible === true &&
                data.credit_limit >= grandTotal
            ) {
                $('#credit_option_wrapper').show();
            } else {
                $('#credit_option_wrapper').hide();
            }
        });
}


    /* =========================
       PRODUCT CHANGE
    ========================== */
    $(document).on('change', '.product-select', function () {

        let row = $(this).closest('tr');
        let selected = $(this).find(':selected');

        let cost  = parseFloat(selected.data('cost')) || 0;
        let price = parseFloat(selected.data('vendor-price')) || 0;
        let taxpercentage = parseFloat(selected.data('gst')) || 0;
        let qty   = parseFloat(selected.data('qty')) || 1;
        row.find('.carton-size').text(`(Box: ${qty})`);

        let margin = '';
        if (cost > 0 && price > 0) {
            margin = (((price - cost) / cost) * 100).toFixed(2) + ' %';
        }

        row.find('.mrp').val(cost.toFixed(2));
        row.find('.vendor-price').val(price.toFixed(2));
        row.find('.profit-margin').val(margin);
        row.find('.quantity').val(0);
        row.find('.carton-size').val(qty);
        row.find('.row-tax').val(taxpercentage);

        calculateRowAmount(row);
        calculateSubtotal();
    });

    /* =========================
       QUANTITY CHANGE
    ========================== */
$(document).on('input', '.quantity, .row-tax', function () {
    let row = $(this).closest('tr');
    calculateRowAmount(row);
    calculateSubtotal();
});

    /* =========================
       CALCULATIONS
    ========================== */
    function calculateRowAmount(row) {

    let qty        = parseFloat(row.find('.quantity').val()) || 0;
    let price      = parseFloat(row.find('.vendor-price').val()) || 0;
    let taxPercent = parseFloat(row.find('.row-tax').val()) || 0;
    let freeQty = parseFloat(row.find('.free-quantity').val()) || 0;

    

    let baseAmount = qty * price;
    let taxAmount  = (baseAmount * taxPercent) / 100;
    // console.log(taxAmount);

    row.find('.amount').val(baseAmount.toFixed(2));
    row.find('.tax_total_amount1').val(taxAmount.toFixed(2));
   

}

     function calculateSubtotal() {

    let subtotal = 0;
    let totalTax = 0;

    $('.amount').each(function () {
        subtotal += parseFloat($(this).val()) || 0;
    });

    $('.tax_total_amount1').each(function () {
        totalTax += parseFloat($(this).val()) || 0;
    });

    $('#subtotal_basic').val(subtotal.toFixed(2));
    $('#tax_total_amount').val(totalTax.toFixed(2));

    calculateGrandTotal();
}

    function calculateGrandTotal() {

    let subtotal        = parseFloat($('#subtotal_basic').val()) || 0;
    let discountPercent = parseFloat($('#product_discount').val()) || 0;
    let totalTax        = parseFloat($('#tax_total_amount').val()) || 0;
    let delivery        = parseFloat($('#delivery_charges').val()) || 0;


    let netAmount = subtotal + totalTax + delivery;
    let discountAmount = (netAmount * discountPercent) / 100;
    let grandTotal = netAmount - discountAmount;

    $('#grand_total').val(grandTotal.toFixed(2));

    validateVendorCredit();
}


    $('#product_discount, #delivery_charges').on('input', calculateGrandTotal);

    /* =========================
       ADD ROW
    ========================== */
       $('#addRowBtn').on('click', function () {

        const rowCount = tableBody.children('tr').length + 1;

        const $newRow = $(`
            <tr>
                <td class="sr">${rowCount}</td>
                <td>
                    <select class="form-control product-select">
                        <option value="">Select vendor first</option>
                    </select>
                </td>
                <td><input type="text" class="form-control vendor-price" readonly></td>
                <td> 
                <div class="position-relative">
                    <input type="number"
                        id="row-tax"
                        class="form-control row-tax fw-bold text-end pe-4"
                        placeholder="0">
                    <span class="position-absolute top-50 end-0 translate-middle-y me-2 fw-bold">%</span>
                </div>
                </td>
                 <td style="display:none;">
                                               <input type="text" class="form-control tax_total_amount1">
                                        </td>
                <td><input type="number" class="form-control quantity">
                <small class="text-muted carton-size"></small>
                </td>
                <td>
                        <input type="number" class="form-control free-quantity" value="0">
                    </td>
                <td>
                        <input type="number" class="form-control mrp-price" value="0">
                    </td>    
                <td><input type="text" class="form-control amount" value="0.00" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm remove-row" style="background:red;color:#fff;">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        tableBody.append($newRow);

        if ($('#vendor_id').val()) {
            loadProducts($newRow.find('.product-select'));
        } else {
            initProductSelect2($newRow.find('.product-select'), 'Select vendor first');
        }

        updateSr();
    });

    /* =========================
       REMOVE ROW
    ========================== */
    tableBody.on('click', '.remove-row', function () {

        if (tableBody.children('tr').length === 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Action Not Allowed',
                text: 'At least one product row is required.',
            });
            return;
        }

        const row = $(this).closest('tr');
        const select = row.find('.product-select');

        if (select.hasClass('select2-hidden-accessible')) {
            select.select2('destroy'); // ✅ important
        }

        row.remove();

        updateSr();
        calculateSubtotal();
        
         $('.product-select').each(function () {
                    loadProducts($(this));
                    });
    });


    /* =========================
       SERIAL UPDATE
    ========================== */
    function updateSr() {
        tableBody.find('.sr').each(function (i) {
            $(this).text(i + 1);
        });
    }

    /* =========================
       INITIAL LOAD
    ========================== */
   $('.product-select').each(function () {
    let row = $(this).closest('tr');
    const selectedProductId = $(this).val();
    loadProducts($(this), selectedProductId);
});


    calculateSubtotal();

});
</script>
<script>
function buildPurchaseOrderPayload() {

    let items = [];

    $('#invoiceTable tbody tr').each(function () {

        const row = $(this);
        const productId = row.find('.product-select').val();

        if (!productId) return;

        const qty    = parseFloat(row.find('.quantity').val()) || 0;
        const price  = parseFloat(row.find('.vendor-price').val()) || 0;
        const amount = parseFloat(row.find('.amount').val()) || 0;
        const row_tax = parseFloat(row.find('.row-tax').val()) || 0;
        const freeQty = parseFloat(row.find('.free-quantity').val()) || 0;
        const mrp = parseFloat(row.find('.mrp-price').val()) || 0;

        if (qty <= 0 || price <= 0) return;

        items.push({
            product_id   : productId,
            quantity     : qty,
            free_quantity: freeQty,
            mrp          : mrp,
            vendor_price : price,
            amount       : amount,
            row_tax       : row_tax
        });
    });

    let payload = {
        /* =====================
           PO HEADER
        ====================== */
        vendor_id             : $('#vendor_id').val(),
        purchase_order_number : $('#purchase_order_number').val(),
        reference             : $('#reference').val(),
        po_date               : $('#po_date').val(),
        delivery_date         : $('#delivery_date').val(),

        location              : $('#location').val(),
        pincode               : $('#pincode').val(),

        /* =====================
           FINANCIALS
        ====================== */
        subtotal_basic        : parseFloat($('#subtotal_basic').val()) || 0,
        product_discount      : parseFloat($('#product_discount').val()) || 0,
        tax_total             : parseFloat($('#tax_total_amount').val()) || 0,
        delivery_charges      : parseFloat($('#delivery_charges').val()) || 0,
        grand_total           : parseFloat($('#grand_total').val()) || 0,

        /* =====================
           PAYMENT
        ====================== */
        payment_term          : $('input[name="payment_term"]:checked').val() || null,
        save_type             : $('input[name="save_type"]:checked').val() || 'save_as_draft',

        /* =====================
           ITEMS
        ====================== */
        items                 : items
    };

    /* =====================
       EDIT MODE SUPPORT
    ====================== */
    if (typeof PURCHASE_ORDER_ID !== 'undefined' && PURCHASE_ORDER_ID) {
        payload.id = PURCHASE_ORDER_ID;
    }

    return payload;
}




$('#placeOrderBtn').on('click', function () {

    const payload = buildPurchaseOrderPayload();
    
    let hasInvalidQty = false;
        let hasValidQty = false;
        let hasInvalidMrp = false;

        payload.items.forEach(item => {

        if (item.quantity <= 0 || isNaN(item.quantity)) {
            hasInvalidQty = true;
        }

        if (item.quantity > 0) {
            hasValidQty = true;
        }

        if (item.mrp <= 0 || isNaN(item.mrp)) {
            hasInvalidMrp = true;
        }

    });

    if (!hasValidQty) {
    Swal.fire({
        icon: 'warning',
        title: 'Quantity Required',
        text: 'Please enter quantity greater than 0 for at least one product'
    });
    return;
}

if (hasInvalidQty) {
    Swal.fire({
        icon: 'warning',
        title: 'Invalid Quantity',
        text: 'Quantity must be greater than 0 for all selected products'
    });
    return;
}

if (hasInvalidMrp) {
    Swal.fire({
        icon: 'warning',
        title: 'MRP Required',
        text: 'Please enter valid MRP (greater than 0) for all products'
    });
    return;
}


    /* =====================
       VALIDATIONS
    ====================== */
    if (!payload.vendor_id) {
        Swal.fire('Vendor Required', 'Please select a vendor', 'warning');
        return;
    }

    if (!payload.location) {
        Swal.fire('Location Required', 'Vendor location is missing', 'warning');
        return;
    }

    if (!payload.pincode) {
        Swal.fire('Pincode Required', 'Vendor pincode is missing', 'warning');
        return;
    }

    if (!payload.po_date) {
        Swal.fire('PO Date Required', 'Please select PO date', 'warning');
        return;
    }

    if (!payload.delivery_date) {
        Swal.fire('Delivery Date Required', 'Please select delivery date', 'warning');
        return;
    }

    if (!payload.items || payload.items.length === 0) {
        Swal.fire('No Products', 'Please add at least one product', 'warning');
        return;
    }

    if (!payload.payment_term) {
        Swal.fire('Payment Term Required', 'Please select payment term', 'warning');
        return;
    }

    if (!payload.save_type) {
        Swal.fire('Save Type Required', 'Please select save type', 'warning');
        return;
    }

    /* =====================
       LOADING
    ====================== */
    Swal.fire({
        title: 'Saving Purchase Order...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const isEdit = !!payload.id;
    const url = isEdit
        ? `/admin/purchase-orders/update/${payload.id}`
        : `/admin/purchase-orders`;

    fetch(url, {
        method: isEdit ? 'PUT' : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(res => {

        if (!res.success) {
            Swal.fire('Failed', res.message || 'Unable to save Purchase Order', 'error');
            return;
        }

        Swal.fire({
            icon: 'success',
            title: isEdit ? 'Purchase Order Updated' : 'Purchase Order Saved',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = res.redirect_url;
        });
    })
    .catch(() => {
        Swal.fire('Server Error', 'Something went wrong', 'error');
    });
});


</script>

@endsection
