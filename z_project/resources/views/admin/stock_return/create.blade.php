@extends('admin.layouts.appnew')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
    box-sizing: border-box;
}

.select2-container--default
.select2-selection--single
.select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px !important;
}

.select2-container--default
.select2-selection--single
.select2-selection__arrow {
    height: 44px !important;
}

.select2-container--default
.select2-selection--single
.select2-selection__clear {
    display: none;
}

.item-row-loc {
    font-size: 11px;
    color: #667085;
}

.item-row-exp-red {
    color: #dc3545;
    font-weight: 600;
}

.wr-not-selected {
    opacity: 0.5;
}

.wr-not-selected td {
    background: #fafbfc;
}

.section-loader {
    text-align: center;
    padding: 40px 0;
}

.section-loader .spinner-border {
    width: 2.5rem;
    height: 2.5rem;
}

.items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.new-row-stock-hint {
    font-size: 10.5px;
    color: #98a2b3;
    margin-top: 2px;
}

.addition-row td {
    background: #f6fbff;
}

.price-changed {
    border-color: #ffc107 !important;
    background-color: #fffbea !important;
}

.price-change-hint {
    font-size: 10px;
    margin-top: 3px;
    color: #b58100;
}
</style>


<div class="page-body">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12 my-5">

                <div class="col-sm-12 m-auto">

                    <div class="card">

                        <div class="card-body">

                            <div class="card-header-2 mb-3">
                                <h3>Create Revise Invoice</h3>
                            </div>


                            <form id="stockReturnForm">

                                @csrf


                                {{-- ================= ORDER ================= --}}

                                <div class="row mb-4">

                                    <div class="col-md-3">

                                        <label class="form-label">

                                            Select Invoice / Order

                                            <span class="text-danger">*</span>

                                        </label>

                                        <select
                                            id="order_select"
                                            class="form-control"
                                            style="width:100%;"
                                        >

                                            <option value="">
                                                Search Order ID...
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                {{-- ================= LOADER ================= --}}

                                <div
                                    id="loadingSection"
                                    class="section-loader"
                                    style="display:none;"
                                >

                                    <div
                                        class="spinner-border text-danger"
                                        role="status"
                                    ></div>

                                    <p class="mt-2 text-muted">
                                        Loading order items...
                                    </p>

                                </div>


                                {{-- ================= ITEMS ================= --}}

                                <div
                                    id="itemsSection"
                                    style="display:none;"
                                >

                                    <hr class="my-3">


                                    <div class="items-header">

                                        <h5 class="mb-0">

                                            Order Items —

                                            <span id="orderIdLabel"></span>

                                        </h5>


                                        <button
                                            type="button"
                                            id="addRowBtn"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            + Add Row
                                        </button>

                                    </div>


                                    <div class="alert alert-light border py-2">

                                        <small>

                                            <strong>Price only:</strong>
                                            Check the item and change Rate.
                                            Return Qty can remain empty.

                                            &nbsp; | &nbsp;

                                            <strong>Stock return:</strong>
                                            Enter Return Qty.

                                        </small>

                                    </div>


                                    {{-- ================= EXISTING ITEMS ================= --}}

                                    <div class="table-responsive">

                                        <table
                                            class="table table-bordered align-middle"
                                        >

                                            <thead class="table-light">

                                                <tr>

                                                    <th style="width:60px;">
                                                        Revise?
                                                    </th>

                                                    <th style="width:220px;">
                                                        Product
                                                    </th>

                                                    <th style="width:120px;">
                                                        Rate
                                                    </th>

                                                    <th style="width:90px;">
                                                        Ordered Qty
                                                    </th>

                                                    <th style="width:110px;">
                                                        Return Qty
                                                    </th>

                                                    <th style="width:180px;">
                                                        Location
                                                        (Rack / Level / Slot)
                                                    </th>

                                                    <th style="width:110px;">
                                                        Batch No
                                                    </th>

                                                    <th style="width:120px;">
                                                        Expiry Date
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody id="itemsBody"></tbody>

                                        </table>

                                    </div>



                                    {{-- ================= ADD PRODUCTS ================= --}}

                                    <div
                                        id="additionsSection"
                                        style="display:none;"
                                    >

                                        <h5 class="mb-3 mt-4">
                                            Add New Products to Invoice
                                        </h5>


                                        <div class="table-responsive">

                                            <table
                                                class="table table-bordered align-middle"
                                            >

                                                <thead class="table-light">

                                                    <tr>

                                                        <th style="width:260px;">
                                                            Product
                                                        </th>

                                                        <th style="width:100px;">
                                                            Cost / Item
                                                        </th>

                                                        <th style="width:100px;">
                                                            Customer Price
                                                        </th>

                                                        <th style="width:90px;">
                                                            Profit Margin
                                                        </th>

                                                        <th style="width:130px;">
                                                            Quantity
                                                        </th>

                                                        <th style="width:110px;">
                                                            Amount
                                                        </th>

                                                        <th style="width:60px;">
                                                            Action
                                                        </th>

                                                    </tr>

                                                </thead>


                                                <tbody id="additionsBody"></tbody>

                                            </table>

                                        </div>

                                    </div>


                                    {{-- ================= SUBMIT ================= --}}

                                    <div class="text-end mt-4">

                                        <button
                                            type="submit"
                                            class="btn btn-lg"
                                            style="background:#e97457;color:#fff;"
                                        >

                                            Submit for Warehouse Approval

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



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    let currentOrderId = null;

    let currentCustomerId = null;

    let currentOutletId = null;

    let itemsData = [];

    let PRODUCT_MAP = {};

    let SELECT2_DATA = [];

    let additionRowCounter = 0;

    let selectedProductIds = new Set();



    /*
    |--------------------------------------------------------------------------
    | ORDER SELECT
    |--------------------------------------------------------------------------
    */

    $('#order_select').select2({

        placeholder: 'Search Order ID...',

        allowClear: true,

        ajax: {

            url: '{{ route("stock-return.search-orders") }}',

            dataType: 'json',

            delay: 300,

            data: function (params) {

                return {
                    term: params.term
                };

            },

            processResults: function (data) {

                return {
                    results: data
                };

            }

        },

        minimumInputLength: 1

    });



    /*
    |--------------------------------------------------------------------------
    | ORDER CHANGE
    |--------------------------------------------------------------------------
    */

    $('#order_select').on('change', function () {


        currentOrderId = $(this).val();


        $('#itemsSection').hide();

        $('#additionsSection').hide();

        $('#additionsBody').empty();

        $('#itemsBody').empty();


        additionRowCounter = 0;

        selectedProductIds = new Set();


        if (!currentOrderId) {

            $('#loadingSection').hide();

            return;

        }


        $('#loadingSection').show();


        $.get(

            '{{ url("admin/stock-return/order-items") }}/'
            + currentOrderId,

            function (res) {


                itemsData = res.items;


                currentCustomerId = res.customer_id;

                currentOutletId = res.outlet_id;


                $('#orderIdLabel').text(res.order_id);


                loadCustomerProducts(

                    res.customer_id,

                    res.outlet_id,

                    function () {


                        renderItems();


                        $('#loadingSection').hide();

                        $('#itemsSection').show();

                    }

                );

            }

        ).fail(function () {


            $('#loadingSection').hide();


            Swal.fire(

                'Error',

                'Could not load order items.',

                'error'

            );

        });

    });



    /*
    |--------------------------------------------------------------------------
    | LOAD CUSTOMER PRODUCTS
    |--------------------------------------------------------------------------
    */

    function loadCustomerProducts(
        customerId,
        outletId,
        callback
    ) {


        $.get(

            '{{ url("admin/stock-return/products-by-customer") }}/'
            + customerId
            + '/'
            + outletId,

            function (products) {


                PRODUCT_MAP = {};

                SELECT2_DATA = [];


                products.forEach(function (p) {


                    PRODUCT_MAP[p.id] = p;


                    SELECT2_DATA.push({

                        id: String(p.id),

                        text:
                            p.product_name
                            + ' (Stock: '
                            + parseFloat(
                                p.stock || 0
                            ).toFixed(2)
                            + ')'

                    });

                });


                callback();

            }

        ).fail(function () {


            Swal.fire(

                'Error',

                'Could not load customer-assigned products.',

                'error'

            );

        });

    }



    /*
    |--------------------------------------------------------------------------
    | EXPIRY CHECK
    |--------------------------------------------------------------------------
    */

    function isExpiringSoon(dateStr) {


        if (!dateStr) {

            return false;

        }


        const days = (

            new Date(dateStr) - new Date()

        ) / (

            1000 * 60 * 60 * 24

        );


        return days <= 30;

    }



    /*
    |--------------------------------------------------------------------------
    | RENDER EXISTING ORDER ITEMS
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | data-original-rate is required.
    |
    | It allows us to detect:
    |
    | ₹100 -> ₹90
    |
    | without requiring Return Qty.
    |
    */

    function renderItems() {


        const $body = $('#itemsBody');


        $body.empty();


        itemsData.forEach(function (item, idx) {


            const expClass =
                isExpiringSoon(item.expiry_date)
                    ? 'item-row-exp-red'
                    : '';


            const saleRate =
                parseFloat(item.sale_rate || 0);


            const row = `

                <tr
                    data-idx="${idx}"
                    class="wr-not-selected"
                >

                    <td class="text-center">

                        <input
                            type="checkbox"
                            class="form-check-input f-return-check"
                            style="width:20px;height:20px;"
                        >

                    </td>


                    <td>

                        ${item.product_name}

                        <input
                            type="hidden"
                            class="f-order-item-id"
                            value="${item.order_item_id}"
                        >

                        <input
                            type="hidden"
                            class="f-product-id"
                            value="${item.product_id}"
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            class="form-control f-rate"
                            value="${saleRate.toFixed(2)}"
                            data-original-rate="${saleRate.toFixed(2)}"
                            readonly
                        >

                        <div
                            class="price-change-hint"
                            style="display:none;"
                        >
                            Price changed
                        </div>

                    </td>


                    <td class="text-center">

                        ${item.max_qty}

                    </td>


                    <td>

                        <input
                            type="number"
                            class="form-control f-return-qty"
                            min="0"
                            max="${item.max_qty}"
                            placeholder="Optional"
                            readonly
                        >

                        <small class="text-muted">
                            Leave empty for price only
                        </small>

                    </td>


                    <td class="item-row-loc">

                        Rack:
                        ${item.rack_no ?? '-'}

                        <br>

                        Level:
                        ${item.level_no ?? '-'}

                        <br>

                        Slot:
                        ${item.slot_no ?? '-'}


                        <input
                            type="hidden"
                            class="f-rack-no"
                            value="${item.rack_no ?? ''}"
                        >

                        <input
                            type="hidden"
                            class="f-level-no"
                            value="${item.level_no ?? ''}"
                        >

                        <input
                            type="hidden"
                            class="f-slot-no"
                            value="${item.slot_no ?? ''}"
                        >

                    </td>


                    <td>

                        ${item.batch_no ?? '-'}


                        <input
                            type="hidden"
                            class="f-batch-no"
                            value="${item.batch_no ?? ''}"
                        >

                    </td>


                    <td class="${expClass}">

                        ${item.expiry_date ?? '-'}


                        <input
                            type="hidden"
                            class="f-expiry-date"
                            value="${item.expiry_date ?? ''}"
                        >

                    </td>

                </tr>

            `;


            $body.append(row);

        });

    }



    /*
    |--------------------------------------------------------------------------
    | ENABLE EXISTING ITEM
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.f-return-check',
        function ()
        {


            const $row =
                $(this).closest('tr');


            const $qtyInput =
                $row.find('.f-return-qty');


            const $rateInput =
                $row.find('.f-rate');


            if ($(this).is(':checked')) {


                $row.removeClass(
                    'wr-not-selected'
                );


                /*
                | Quantity is optional.
                */

                $qtyInput
                    .prop('readonly', false)
                    .prop('required', false);


                /*
                | Price can be changed.
                */

                $rateInput
                    .prop('readonly', false)
                    .focus();


            } else {


                $row.addClass(
                    'wr-not-selected'
                );


                /*
                | Clear quantity.
                */

                $qtyInput
                    .prop('readonly', true)
                    .prop('required', false)
                    .val('');


                /*
                | Restore original price.
                */

                const originalRate =
                    parseFloat(
                        $rateInput.attr(
                            'data-original-rate'
                        )
                    ) || 0;


                $rateInput
                    .val(
                        originalRate.toFixed(2)
                    )
                    .prop('readonly', true)
                    .removeClass(
                        'price-changed'
                    );


                $row
                    .find('.price-change-hint')
                    .hide();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | PRICE CHANGE VISUAL INDICATOR
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '.f-rate',
        function ()
        {


            const $input = $(this);


            const $row =
                $input.closest('tr');


            const oldPrice =
                parseFloat(
                    $input.attr(
                        'data-original-rate'
                    )
                ) || 0;


            const newPrice =
                parseFloat(
                    $input.val()
                ) || 0;


            if (
                Math.abs(
                    oldPrice - newPrice
                ) > 0.0001
            ) {


                $input.addClass(
                    'price-changed'
                );


                $row
                    .find(
                        '.price-change-hint'
                    )
                    .show();


            } else {


                $input.removeClass(
                    'price-changed'
                );


                $row
                    .find(
                        '.price-change-hint'
                    )
                    .hide();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | ADD NEW PRODUCT
    |--------------------------------------------------------------------------
    */

    $('#addRowBtn').on(
        'click',
        function ()
        {


            $('#additionsSection').show();


            if (
                $('#additionsBody tr').length === 0
            ) {

                addNewProductRow();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | CREATE NEW PRODUCT ROW
    |--------------------------------------------------------------------------
    */

    function addNewProductRow() {


        additionRowCounter++;


        const rowId =
            'add-row-'
            + additionRowCounter;


        const row = `

            <tr
                class="addition-row"
                data-row-id="${rowId}"
            >

                <td
                    style="
                        width:260px;
                        max-width:260px;
                    "
                >

                    <select
                        class="
                            form-control
                            addition-product-select
                        "
                        id="select-${rowId}"
                    ></select>

                </td>


                <td>

                    <input
                        type="text"
                        class="form-control f-cost"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="text"
                        class="
                            form-control
                            f-customer-price
                        "
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="text"
                        class="form-control f-margin"
                        readonly
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="form-control f-qty"
                        min="1"
                        placeholder="Qty"
                        disabled
                    >


                    <div
                        class="
                            new-row-stock-hint
                            f-stock-hint
                        "
                    ></div>

                </td>


                <td>

                    <input
                        type="text"
                        class="form-control f-amount"
                        readonly
                    >

                </td>


                <td class="text-center">

                    <button
                        type="button"
                        class="
                            btn
                            btn-sm
                            btn-outline-danger
                            remove-row-btn
                        "
                        title="Remove row"
                    >
                        &times;
                    </button>

                </td>

            </tr>

        `;


        $('#additionsBody').append(row);


        refreshProductOptions();

    }



    /*
    |--------------------------------------------------------------------------
    | REFRESH PRODUCT OPTIONS
    |--------------------------------------------------------------------------
    */

    function refreshProductOptions() {


        $('.addition-product-select')
            .each(function () {


                const $sel = $(this);


                const ownVal =
                    $sel.val() || '';


                const data = [

                    {
                        id: '',
                        text: ''
                    }

                ].concat(

                    SELECT2_DATA.filter(
                        function (opt)
                        {


                            return (
                                !selectedProductIds.has(
                                    opt.id
                                )
                                ||
                                opt.id === ownVal
                            );

                        }
                    )

                );


                if (
                    $sel.hasClass(
                        'select2-hidden-accessible'
                    )
                ) {

                    $sel.select2(
                        'destroy'
                    );

                }


                $sel.empty();


                $sel.select2({

                    width: '100%',

                    placeholder:
                        'Search Product',

                    dropdownParent:
                        $('body'),

                    data: data

                });


                if (ownVal) {

                    $sel
                        .val(ownVal)
                        .trigger(
                            'change.select2'
                        );

                }

            });

    }



    /*
    |--------------------------------------------------------------------------
    | NEW PRODUCT SELECT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.addition-product-select',
        function ()
        {


            const $sel = $(this);


            const $row =
                $sel.closest('tr');


            const productId =
                $sel.val() || '';


            const product =
                PRODUCT_MAP[productId];


            const $qtyInput =
                $row.find('.f-qty');


            const prevVal =
                $sel.data('prevVal') || '';


            if (
                prevVal
                &&
                prevVal !== productId
            ) {

                selectedProductIds.delete(
                    prevVal
                );

            }


            if (productId) {

                selectedProductIds.add(
                    productId
                );

            }


            $sel.data(
                'prevVal',
                productId
            );


            /*
            | Product removed
            */

            if (!product) {


                $row.find(
                    '.f-cost, '
                    + '.f-customer-price, '
                    + '.f-margin, '
                    + '.f-amount'
                ).val('');


                $row
                    .find('.f-stock-hint')
                    .text('');


                $qtyInput
                    .prop('disabled', true)
                    .val('');


                refreshProductOptions();


                return;

            }


            const cost =
                parseFloat(
                    product.cost_per_item || 0
                );


            $row
                .find('.f-cost')
                .val(
                    cost.toFixed(2)
                );


            $row
                .find('.f-customer-price')
                .val('...');


            $row
                .find('.f-margin')
                .val('');


            $row
                .find('.f-stock-hint')
                .text(
                    'Available Stock: '
                    + parseFloat(
                        product.stock || 0
                    ).toFixed(2)
                );


            $qtyInput
                .prop('disabled', false)
                .attr(
                    'max',
                    product.stock || 0
                )
                .focus();



            /*
            |--------------------------------------------------------------------------
            | CUSTOMER PRODUCT PRICE
            |--------------------------------------------------------------------------
            */

            $.get(

                '{{ url("admin/customer-product-price") }}/'
                + currentCustomerId
                + '/'
                + currentOutletId
                + '/'
                + productId,

                function (res)
                {


                    const hasPrice =
                        res.product_price !== null
                        &&
                        res.product_price !== undefined;


                    const customerPrice =
                        parseFloat(
                            hasPrice
                                ? res.product_price
                                : cost
                        );


                    const margin =
                        customerPrice > 0

                            ? (
                                (
                                    (
                                        customerPrice
                                        -
                                        cost
                                    )
                                    /
                                    customerPrice
                                )
                                *
                                100
                            )

                            : 0;


                    $row
                        .find(
                            '.f-customer-price'
                        )
                        .val(
                            customerPrice
                                .toFixed(2)
                        );


                    $row
                        .find('.f-margin')
                        .val(
                            margin.toFixed(1)
                            + '%'
                        );


                    recalcAmount($row);

                }

            ).fail(function () {


                $row
                    .find(
                        '.f-customer-price'
                    )
                    .val(
                        cost.toFixed(2)
                    );


                $row
                    .find('.f-margin')
                    .val('0.0%');


                recalcAmount($row);

            });



            refreshProductOptions();



            /*
            | Automatically create another
            | empty product row.
            */

            const isLastRow =
                $row.is(
                    '#additionsBody tr:last-child'
                );


            if (isLastRow) {

                addNewProductRow();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | REMOVE NEW PRODUCT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.remove-row-btn',
        function ()
        {


            const $row =
                $(this).closest('tr');


            const productId =
                $row
                    .find(
                        '.addition-product-select'
                    )
                    .val();


            if (productId) {

                selectedProductIds.delete(
                    productId
                );

            }


            $row.remove();


            refreshProductOptions();


            if (
                $('#additionsBody tr').length
                === 0
            ) {

                $('#additionsSection').hide();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | NEW PRODUCT QUANTITY
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '.f-qty',
        function ()
        {

            recalcAmount(
                $(this).closest('tr')
            );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | RECALCULATE NEW PRODUCT AMOUNT
    |--------------------------------------------------------------------------
    */

    function recalcAmount($row) {


        const qty =
            parseFloat(
                $row.find('.f-qty').val()
            ) || 0;


        const customerPrice =
            parseFloat(
                $row
                    .find(
                        '.f-customer-price'
                    )
                    .val()
            ) || 0;


        $row
            .find('.f-amount')
            .val(
                (
                    qty *
                    customerPrice
                ).toFixed(2)
            );

    }



    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#stockReturnForm').on(
        'submit',
        function (e)
        {


            e.preventDefault();


            const items = [];


            let hasError = false;



            /*
            |--------------------------------------------------------------------------
            | EXISTING ORDER ITEMS
            |--------------------------------------------------------------------------
            */

            $('#itemsBody tr')
                .each(function ()
                {


                    const $row =
                        $(this);


                    /*
                    | Ignore unchecked rows.
                    */

                    if (
                        !$row
                            .find(
                                '.f-return-check'
                            )
                            .is(':checked')
                    ) {

                        return;

                    }



                    /*
                    |--------------------------------------------------------------------------
                    | QUANTITY
                    |--------------------------------------------------------------------------
                    |
                    | Empty = 0
                    |
                    | This means price-only revision.
                    |
                    */

                    const qtyValue =
                        $row
                            .find(
                                '.f-return-qty'
                            )
                            .val();


                    const returnQty =
                        qtyValue === ''
                        ||
                        qtyValue === null

                            ? 0

                            : parseInt(
                                qtyValue,
                                10
                            );


                    const maxQty =
                        parseInt(
                            $row
                                .find(
                                    '.f-return-qty'
                                )
                                .attr('max'),
                            10
                        ) || 0;



                    /*
                    |--------------------------------------------------------------------------
                    | PRICE
                    |--------------------------------------------------------------------------
                    */

                    const $rateInput =
                        $row.find('.f-rate');


                    const oldPrice =
                        parseFloat(
                            $rateInput.attr(
                                'data-original-rate'
                            )
                        ) || 0;


                    const newPrice =
                        parseFloat(
                            $rateInput.val()
                        );


                    /*
                    | Price cannot be empty.
                    */

                    if (
                        isNaN(newPrice)
                        ||
                        newPrice < 0
                    ) {


                        Swal.fire(

                            'Invalid Price',

                            'Please enter a valid price.',

                            'warning'

                        );


                        hasError = true;


                        return false;

                    }



                    const priceChanged =
                        Math.abs(
                            oldPrice
                            -
                            newPrice
                        ) > 0.0001;


                    const qtyChanged =
                        returnQty > 0;



                    /*
                    |--------------------------------------------------------------------------
                    | NO CHANGE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !priceChanged
                        &&
                        !qtyChanged
                    ) {


                        Swal.fire(

                            'No Changes',

                            'Please change the price or enter a return quantity for the selected item.',

                            'warning'

                        );


                        hasError = true;


                        return false;

                    }



                    /*
                    |--------------------------------------------------------------------------
                    | QUANTITY VALIDATION
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isNaN(returnQty)
                        ||
                        returnQty < 0
                    ) {


                        Swal.fire(

                            'Invalid Quantity',

                            'Return quantity cannot be negative.',

                            'warning'

                        );


                        hasError = true;


                        return false;

                    }


                    if (
                        returnQty > maxQty
                    ) {


                        Swal.fire(

                            'Invalid Quantity',

                            'Return quantity cannot exceed ordered quantity.',

                            'warning'

                        );


                        hasError = true;


                        return false;

                    }



                    /*
                    |--------------------------------------------------------------------------
                    | ADD EXISTING ITEM
                    |--------------------------------------------------------------------------
                    */

                    items.push({

                        item_type:
                            'return',

                        order_item_id:
                            $row
                                .find(
                                    '.f-order-item-id'
                                )
                                .val(),

                        product_id:
                            $row
                                .find(
                                    '.f-product-id'
                                )
                                .val(),


                        /*
                        | Keeping this because
                        | your controller validates it.
                        */

                        purchase_rate:
                            newPrice,


                        /*
                        | IMPORTANT:
                        |
                        | approve() reads this
                        | as revised selling price.
                        */

                        customer_price:
                            newPrice,


                        /*
                        | Price only = 0
                        */

                        return_qty:
                            returnQty,


                        rack_no:
                            $row
                                .find(
                                    '.f-rack-no'
                                )
                                .val(),

                        level_no:
                            $row
                                .find(
                                    '.f-level-no'
                                )
                                .val(),

                        slot_no:
                            $row
                                .find(
                                    '.f-slot-no'
                                )
                                .val(),

                        batch_no:
                            $row
                                .find(
                                    '.f-batch-no'
                                )
                                .val(),

                        expiry_date:
                            $row
                                .find(
                                    '.f-expiry-date'
                                )
                                .val()
                            || null

                    });

                });



            if (hasError) {

                return;

            }



            /*
            |--------------------------------------------------------------------------
            | NEW PRODUCTS
            |--------------------------------------------------------------------------
            */

            $('#additionsBody tr')
                .each(function ()
                {


                    const $row =
                        $(this);


                    const productId =
                        $row
                            .find(
                                '.addition-product-select'
                            )
                            .val();


                    /*
                    | Ignore completely empty row.
                    */

                    if (!productId) {

                        return;

                    }


                    const qty =
                        parseInt(
                            $row
                                .find(
                                    '.f-qty'
                                )
                                .val(),
                            10
                        );


                    const maxQty =
                        parseFloat(
                            $row
                                .find(
                                    '.f-qty'
                                )
                                .attr('max')
                        ) || 0;



                    /*
                    |--------------------------------------------------------------------------
                    | NEW PRODUCT MUST HAVE QUANTITY
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isNaN(qty)
                        ||
                        qty < 1
                    ) {


                        Swal.fire(

                            'Missing Quantity',

                            'Please enter quantity for every newly added product.',

                            'warning'

                        );


                        hasError = true;


                        return false;

                    }



                    /*
                    | Prevent exceeding available stock.
                    */

                    if (
                        qty > maxQty
                    ) {


                        Swal.fire(

                            'Insufficient Stock',

                            'New product quantity cannot exceed available stock.',

                            'warning'

                        );


                        hasError = true;


                        return false;

                    }



                    const cost =
                        parseFloat(
                            $row
                                .find('.f-cost')
                                .val()
                        ) || 0;


                    const customerPrice =
                        parseFloat(
                            $row
                                .find(
                                    '.f-customer-price'
                                )
                                .val()
                        );


                    if (
                        isNaN(customerPrice)
                        ||
                        customerPrice < 0
                    ) {


                        Swal.fire(

                            'Invalid Price',

                            'Customer price is invalid for one of the newly added products.',

                            'warning'

                        );


                        hasError = true;


                        return false;

                    }



                    /*
                    |--------------------------------------------------------------------------
                    | ADD NEW PRODUCT
                    |--------------------------------------------------------------------------
                    */

                    items.push({

                        item_type:
                            'addition',

                        order_item_id:
                            null,

                        product_id:
                            productId,

                        purchase_rate:
                            cost,

                        customer_price:
                            customerPrice,

                        return_qty:
                            qty

                    });

                });



            if (hasError) {

                return;

            }



            /*
            |--------------------------------------------------------------------------
            | NOTHING SELECTED
            |--------------------------------------------------------------------------
            */

            if (
                items.length === 0
            ) {


                Swal.fire(

                    'No Changes',

                    'Please revise a price, return an item, or add a new product.',

                    'warning'

                );


                return;

            }



            /*
            |--------------------------------------------------------------------------
            | CONFIRM
            |--------------------------------------------------------------------------
            */

            Swal.fire({

                title:
                    'Submit Revised Invoice?',

                text:
                    'The request will be sent for warehouse approval.',

                icon:
                    'question',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Yes, Submit',

                cancelButtonText:
                    'Cancel'

            }).then(function (result) {


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
                        'Submitting...',

                    allowOutsideClick:
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
                        '{{ route("stock-return.store") }}',

                    method:
                        'POST',

                    data: {

                        order_id:
                            currentOrderId,

                        items:
                            items

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

                                'Submitted',

                                res.message,

                                'success'

                            ).then(function () {


                                window.location.reload();

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


                            /*
                            | Laravel validation errors
                            */

                            if (
                                xhr.responseJSON
                                &&
                                xhr.responseJSON.errors
                            ) {


                                const errors =
                                    Object.values(
                                        xhr.responseJSON.errors
                                    )
                                    .flat();


                                if (
                                    errors.length > 0
                                ) {

                                    message =
                                        errors.join('<br>');

                                }

                            }


                            Swal.fire({

                                title:
                                    'Error',

                                html:
                                    message,

                                icon:
                                    'error'

                            });

                        }

                });

            });

        }
    );


});

</script>

@endsection