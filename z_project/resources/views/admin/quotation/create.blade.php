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
.last-grn-date.grn-orange { border-color: #fd7e14 !important; background-color: #fff3e0 !important; color: #b35900; font-weight: 600; }
.last-grn-date.grn-red { border-color: #dc3545 !important; background-color: #fdecea !important; color: #a4161a; font-weight: 600; }
#marginSortHeader:hover { background-color: #f0f0f0; }
#marginSortIcon { font-size: 11px; color: #888; }

.profit-margin.margin-negative {
    border-color: #dc3545 !important;
    background-color: #fdecea !important;
    color: #a4161a;
    font-weight: 600;
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
                                <h3>Create Quotation</h3>
                            </div>

                            <form>

                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Outlet Name <span class="text-danger">*</span></label>
                                        <select id="lead_customer_id" class="form-control select2">
                                            <option value="">Select Outlet</option>
                                            @foreach($leadCustomers as $lead)
                                                <option value="{{ $lead->id }}">{{ $lead->outlet_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Customer Name</label>
                                        <input type="text" id="customer_name" class="form-control" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mobile Number</label>
                                        <input type="text" id="mobile_number" class="form-control" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" id="address" class="form-control" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Payment Term</label>
                                        <input type="text" id="payment_term" class="form-control" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Quotation Date <span class="text-danger">*</span></label>
                                        <input type="date" id="quotation_date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>

                                </div>

                                <hr class="my-4">
                                <h4 class="mb-3">Quotation Products</h4>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="quotationTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:4%">Sr</th>
                                                <th style="width:16%">Product Name</th>
                                                <th style="width:9%">Brand</th>
                                                <th style="width:9%">Category</th>
                                                <th style="width:9%">Cost Per Item</th>
                                                <th style="width:9%">Sale Price (Basic)</th>
                                                <th style="width:8%; cursor:pointer; user-select:none;" id="marginSortHeader">
                                                    Profit Margin % <span id="marginSortIcon">⇅</span>
                                                </th>
                                                <th style="width:9%">Customer Price (Basic)</th>
                                                <th style="width:9%">Total Saving %</th>
                                                <th style="width:10%">Last GRN Date</th>
                                                <th style="width:8%" class="text-center">
                                                    <button type="button" class="btn btn-sm" style="background:#e97457;color:#fff;" id="addRowBtn">+ Add</button>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody id="quotationBody">
                                            <tr>
                                                <td class="sr">1</td>
                                                <td>
                                                    <select class="form-control product-select">
                                                        <option value="">Search Product</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control brand" readonly></td>
                                                <td><input type="text" class="form-control category" readonly></td>
                                                <td><input type="text" class="form-control cost-per-item" readonly></td>
                                                <td><input type="number" class="form-control sale-price-basic" step="0.01" min="0"></td>
                                                <td><input type="text" class="form-control profit-margin" readonly></td>
                                                <td><input type="number" class="form-control customer-price" step="0.01" min="0"></td>
                                                <td><input type="text" class="form-control total-saving" readonly></td>
                                                <td><input type="text" class="form-control last-grn-date" readonly></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm remove-row" style="background:red;color:#fff;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-end">
                                        <button type="button" id="saveQuotationBtn" class="btn btn-lg" style="background:#e97457;color:#fff;">
                                            Save Quotation
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
/*
|============================================================================
| Eager Select2 init (no lazy-focus trigger — that approach was unreliable).
| Still fast at ~500 products because Select2 uses a shared `data:` array
| instead of real <option> DOM elements per row — the dropdown list itself
| is never duplicated into the page's DOM per row, only rendered on open.
|============================================================================
*/

let PRODUCT_CACHE = [];
let PRODUCT_MAP = {};
let SELECT2_DATA = [];
let USED_PRODUCT_IDS = new Set();

let marginSortDirection = null; // null | 'asc' | 'desc'

function safeNumber(val) {
    const n = parseFloat(val);
    return Number.isFinite(n) ? n : 0;
}

function buildProductMap() {
    PRODUCT_MAP = {};
    SELECT2_DATA = [];
    PRODUCT_CACHE.forEach(p => {
        PRODUCT_MAP[p.id] = p;
        SELECT2_DATA.push({ id: String(p.id), text: p.product_name });
    });
}

function initSelect2($select) {
    if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
    }

    $select.select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Search Product',
        dropdownParent: $('body'),
        data: SELECT2_DATA,
        matcher: function (params, data) {
            if (!data.id) return data;

            const ownValue = $select.data('current-value');
            if (USED_PRODUCT_IDS.has(data.id) && data.id !== ownValue) {
                return null;
            }

            if (!params.term || params.term.trim() === '') {
                return data;
            }

            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }

            return null;
        }
    });
}

function updateSr() {
    $('#quotationBody .sr').each(function (i) { $(this).text(i + 1); });
}

function addNewRow() {
    const tableBody = $('#quotationBody');
    const rowCount = tableBody.children().length + 1;

    const $newRow = $(`
        <tr>
            <td class="sr">${rowCount}</td>
            <td>
                <select class="form-control product-select">
                    <option value="">Search Product</option>
                </select>
            </td>
            <td><input type="text" class="form-control brand" readonly></td>
            <td><input type="text" class="form-control category" readonly></td>
            <td><input type="text" class="form-control cost-per-item" readonly></td>
            <td><input type="number" class="form-control sale-price-basic" step="0.01" min="0"></td>
            <td><input type="text" class="form-control profit-margin" readonly></td>
            <td><input type="number" class="form-control customer-price" step="0.01" min="0"></td>
            <td><input type="text" class="form-control total-saving" readonly></td>
            <td><input type="text" class="form-control last-grn-date" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm remove-row" style="background:red;color:#fff;"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    `);

    tableBody.append($newRow);
    updateSr();

    // Initialize THIS row's Select2 immediately — reliable, and still cheap
    // since it uses the shared data array, not per-row <option> DOM nodes.
    initSelect2($newRow.find('.product-select'));

    applyMarginFilterAndSort();

    return $newRow;
}

function calculateProfitMargin($row) {
    const cost = safeNumber($row.find('.cost-per-item').val());
    const salePrice = safeNumber($row.find('.sale-price-basic').val());

    const $marginField = $row.find('.profit-margin');
    $marginField.removeClass('margin-negative');

    let marginText = '';
    if (cost > 0 && salePrice > 0) {
        const margin = ((salePrice - cost) / cost) * 100;
        marginText = margin.toFixed(2) + '%';

        if (margin < 0) {
            $marginField.addClass('margin-negative');
        }
    }
    $marginField.val(marginText);
}

function calculateTotalSaving($row) {
    const salePrice = safeNumber($row.find('.sale-price-basic').val());
    const customerPrice = safeNumber($row.find('.customer-price').val());

    let savingText = '';
    if (salePrice > 0 && customerPrice > 0) {
        const saving = ((customerPrice - salePrice) / salePrice) * 100;
        savingText = saving.toFixed(2) + '%';
    }
    $row.find('.total-saving').val(savingText);
}

function applyGrnDateColor($row, lastGrnDate) {
    const $field = $row.find('.last-grn-date');

    $field.removeClass('grn-red grn-orange');

    if (!lastGrnDate) {
        $field.val('No GRN Yet').addClass('grn-red');
        return;
    }

    const grnDate = new Date(lastGrnDate);
    const today = new Date();
    const daysSince = Math.floor((today - grnDate) / (1000 * 60 * 60 * 24));

    $field.val(lastGrnDate);

    if (daysSince > 60) {
        $field.addClass('grn-red');
    } else if (daysSince > 30) {
        $field.addClass('grn-orange');
    }
}

function sortRowsByMargin() {
    const tableBody = $('#quotationBody');
    const $rows = tableBody.find('tr').get();

    $rows.sort(function (a, b) {
        const marginA = safeNumber($(a).find('.profit-margin').val());
        const marginB = safeNumber($(b).find('.profit-margin').val());

        if (marginSortDirection === 'asc') return marginA - marginB;
        if (marginSortDirection === 'desc') return marginB - marginA;
        return 0;
    });

    $.each($rows, function (i, row) { tableBody.append(row); });
    updateSr();
}

function updateMarginSortIcon() {
    const $icon = $('#marginSortIcon');
    if (marginSortDirection === 'asc') $icon.text('↑');
    else if (marginSortDirection === 'desc') $icon.text('↓');
    else $icon.text('⇅');
}

function applyMarginFilterAndSort() {
    if (marginSortDirection) sortRowsByMargin();
}

$('#marginSortHeader').on('click', function () {
    if (marginSortDirection === null) marginSortDirection = 'asc';
    else if (marginSortDirection === 'asc') marginSortDirection = 'desc';
    else marginSortDirection = null;

    updateMarginSortIcon();
    sortRowsByMargin();
});

$(document).ready(function () {

    $('#lead_customer_id').select2({ width: '100%', allowClear: true });

    fetch("{{ route('products.for-quotation') }}")
        .then(res => res.json())
        .then(products => {
            PRODUCT_CACHE = products;
            buildProductMap();

            // Data is ready — initialize the single starting row now
            initSelect2($('#quotationBody .product-select').first());
        })
        .catch(err => console.error('Failed to load products:', err));

    $('#addRowBtn').on('click', function () { addNewRow(); });

    $('#lead_customer_id').on('select2:select select2:clear', function () {

        const leadId = $(this).val();

        $('#customer_name, #mobile_number, #address, #payment_term').val('');

        if (!leadId) return;

        fetch(`/admin/lead-customer-details/${leadId}`)
            .then(res => res.json())
            .then(data => {
                $('#customer_name').val(data.customer_name ?? '');
                $('#mobile_number').val(data.mobile_number ?? '');
                $('#address').val(data.address ?? '');
                $('#payment_term').val(data.payment_term ?? '');
            });
    });

    const tableBody = $('#quotationBody');

    tableBody.on('change', '.product-select', function () {

        const $select = $(this);
        const $row = $select.closest('tr');
        const productId = $select.val();
        const previousId = $select.data('current-value') || null;

        if (previousId && String(previousId) !== String(productId)) {
            USED_PRODUCT_IDS.delete(String(previousId));
        }

        if (!productId) {
            $row.find('.brand, .category, .cost-per-item, .sale-price-basic, .profit-margin, .customer-price, .total-saving').val('');
            $row.find('.last-grn-date').val('').removeClass('grn-red grn-orange');
            $select.data('current-value', null);
            return;
        }

        const p = PRODUCT_MAP[productId];
        if (!p) return;

        $row.find('.brand').val(p.brand || '');
        $row.find('.category').val(p.category || '');
        $row.find('.cost-per-item').val(safeNumber(p.cost_per_item).toFixed(2));
        $row.find('.sale-price-basic').val(safeNumber(p.sale_price_loose_pcs).toFixed(2));
        $row.find('.customer-price').val('');
        $row.find('.total-saving').val('');

        applyGrnDateColor($row, p.last_grn_date);
        calculateProfitMargin($row);

        USED_PRODUCT_IDS.add(String(productId));
        $select.data('current-value', String(productId));

        if ($row.is(':last-child')) {
            addNewRow();
        }
    });

    tableBody.on('input', '.sale-price-basic', function () {
        const $row = $(this).closest('tr');
        calculateProfitMargin($row);
        calculateTotalSaving($row);
        applyMarginFilterAndSort();
    });

    tableBody.on('input', '.customer-price', function () {
        const $row = $(this).closest('tr');
        calculateTotalSaving($row);
    });

    tableBody.on('click', '.remove-row', function () {

        if (tableBody.children().length === 1) {
            Swal.fire({ icon: 'warning', title: 'Action Not Allowed', text: 'At least one product row is required.' });
            return;
        }

        const $row = $(this).closest('tr');
        const $select = $row.find('.product-select');
        const productId = $select.data('current-value');

        if (productId) {
            USED_PRODUCT_IDS.delete(String(productId));
        }

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('close');
            $select.select2('destroy');
        }

        $row.remove();
        updateSr();
    });

});

function buildQuotationPayload() {

    let items = [];

    $('#quotationBody tr').each(function () {
        const $row = $(this);
        const $select = $row.find('.product-select');
        const productId = $select.data('current-value') || $select.val();
        if (!productId) return;

        const p = PRODUCT_MAP[productId] || {};

        items.push({
            product_id            : productId,
            brand                  : p.brand || '',
            category                : p.category || '',
            cost_per_item           : safeNumber($row.find('.cost-per-item').val()),
            sale_price_basic        : safeNumber($row.find('.sale-price-basic').val()),
            profit_margin           : safeNumber($row.find('.profit-margin').val()),
            customer_price          : safeNumber($row.find('.customer-price').val()),
            total_saving_percent    : safeNumber($row.find('.total-saving').val()),
            last_grn_date           : p.last_grn_date || null,
        });
    });

    return {
        lead_customer_id : $('#lead_customer_id').val(),
        quotation_date   : $('#quotation_date').val(),
        items            : items,
    };
}

$('#saveQuotationBtn').on('click', function () {

    const payload = buildQuotationPayload();

    if (!payload.lead_customer_id) {
        Swal.fire({ icon: 'warning', title: 'Outlet Required', text: 'Please select an outlet.' });
        return;
    }

    if (!payload.quotation_date) {
        Swal.fire({ icon: 'warning', title: 'Date Required', text: 'Please select a quotation date.' });
        return;
    }

    if (payload.items.length === 0) {
        Swal.fire({ icon: 'warning', title: 'No Products', text: 'Please add at least one product.' });
        return;
    }

    const hasNoCustomerPrice = payload.items.some(i => i.customer_price < 0);
    if (hasNoCustomerPrice) {
        Swal.fire({ icon: 'warning', title: 'Customer Price Required', text: 'Please enter customer price for all products.' });
        return;
    }

    Swal.fire({
        title: 'Saving Quotation...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch("{{ route('quotations.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || 'Something went wrong');
        return data;
    })
    .then(data => {
        Swal.fire('Success', data.message, 'success').then(() => {
            window.location.href = data.redirect_url;
        });
    })
    .catch(err => {
        Swal.fire('Error', err.message, 'error');
    });
});
</script>

@endsection
