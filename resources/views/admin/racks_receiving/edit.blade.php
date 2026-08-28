@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
table { table-layout: fixed; width: 100%; }
td { overflow: hidden; }

.select2-container { width: 100% !important; }

.select2-container--default .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
    box-sizing: border-box;
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
                <div class="col-sm-11 m-auto">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="mb-4">Rack Receiving – Update Stock Location</h3>

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
                                           value="{{ $grn->purchaseOrder->vendor->name ?? '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Receipt Date</label>
                                    <input type="date" class="form-control"
                                           value="{{ $grn->receipt_date }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Bill No</label>
                                    <input type="text" class="form-control"
                                           value="{{ $grn->bill_no }}" readonly>
                                </div>
                            </div>

                            {{-- Rack Table --}}
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="rackTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px">#</th>
                                            <th>Product</th>
                                            <th style="width:100px">Batch</th>
                                            <th style="width:100px">Expiry</th>
                                            <th style="width:90px">Received Qty</th>
                                            <th style="width:130px">Bay</th>
                                            <th style="width:130px">Column</th>
                                            <th style="width:130px">Floor</th>
                                            <th style="width:100px">Store Qty</th>
                                            <th style="width:90px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @php
                                        $netQtyByProduct = [];
                                        foreach ($grn->items as $grnItem) {
                                            $pid = $grnItem->product_id;
                                            $net = $grnItem->actual_qty + $grnItem->free_quantity;
                                            $netQtyByProduct[$pid] = ($netQtyByProduct[$pid] ?? 0) + $net;
                                        }
                                    @endphp

                                    @foreach($grn->rackStocks as $index => $stock)
                                        @php
                                            $netQty = $netQtyByProduct[$stock->product_id] ?? $stock->quantity;
                                        @endphp

                                        <tr data-product="{{ $stock->product_id }}"
                                            data-product-name="{{ $stock->product->product_name }}">

                                            <td>{{ $index + 1 }}</td>

                                            <td>
                                                {{ $stock->product->product_name }}
                                                <input type="hidden" class="product-id" value="{{ $stock->product_id }}">
                                            </td>

                                            <td>
                                                {{ $stock->batch_no ?? '-' }}
                                                <input type="hidden" class="batch-no" value="{{ $stock->batch_no }}">
                                            </td>

                                            <td>
                                                {{ $stock->expiry_date ?? '-' }}
                                                <input type="hidden" class="expiry-date" value="{{ $stock->expiry_date }}">
                                            </td>

                                            <td>
                                                <span class="badge bg-info">{{ $netQty }}</span>
                                                <input type="hidden" class="net-qty" value="{{ $netQty }}">
                                            </td>

                                            <td>
                                                <select class="form-control rack-select">
                                                    <option value="">Select</option>
                                                    @foreach(['A','B','C','D','F1','F2','F3','F4','F5','F6','F7'] as $rack)
                                                        <option value="{{ $rack }}"
                                                            {{ $stock->rack_no == $rack ? 'selected' : '' }}>
                                                            {{ $rack }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <select class="form-control level-select">
                                                    <option value="">Select</option>
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $stock->level_no == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>

                                            <td>
                                                <select class="form-control slot-select">
                                                    <option value="">Select</option>
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $stock->slot_no == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number"
                                                       class="form-control store-qty"
                                                       data-net-qty="{{ $netQty }}"
                                                       value="{{ $stock->quantity }}"
                                                       min="0">
                                            </td>

                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger remove-row">−</button>
                                                <button type="button" class="btn btn-sm btn-success add-row">+</button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                            {{-- Actions --}}
                            <div class="row mt-4">
                                <div class="col-md-4 offset-md-8 d-grid gap-2">
                                    <button type="button" id="saveRackBtn" class="btn btn-warning">
                                        Update Rack Allocation
                                    </button>
                                    <a href="{{ route('admin.rack.receiving.index') }}"
                                       class="btn btn-secondary">Back</a>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

/* ═══════════════════════════════════════════
   CONSTANTS
═══════════════════════════════════════════ */
const FRIDGE_RACKS = ['F1','F2','F3','F4','F5','F6'];

/* ═══════════════════════════════════════════
   1. productNetQtyMap
═══════════════════════════════════════════ */
let productNetQtyMap = {};

function rebuildNetQtyMap() {
    productNetQtyMap = {};
    $('#rackTable tbody tr').each(function () {
        const pid = String($(this).data('product')).trim();
        if (pid && !productNetQtyMap[pid]) {
            productNetQtyMap[pid] = parseFloat($(this).find('.net-qty').val()) || 0;
        }
    });
}

/* ═══════════════════════════════════════════
   2. Select2 helpers
═══════════════════════════════════════════ */
function safeDestroySelect2($selects) {
    $selects.each(function () {
        try {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
        } catch (e) {}
    });
}

function cleanClonedRow($row) {
    $row.find('select').each(function () {
        $(this)
            .removeClass('select2-hidden-accessible')
            .removeAttr('data-select2-id')
            .removeAttr('aria-hidden')
            .removeAttr('tabindex');
    });
    $row.find('.select2-container').remove();
}

function initSelect2OnRows($rows) {
    $rows.each(function () {
        $(this).find('select').each(function () {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({ width: '100%' });
            }
        });
    });
}

/* ═══════════════════════════════════════════
   3. Page-load init
═══════════════════════════════════════════ */
$(document).ready(function () {
    rebuildNetQtyMap();
    initSelect2OnRows($('#rackTable tbody tr'));
});

/* ═══════════════════════════════════════════
   4. Remove row — keep at least one per product
═══════════════════════════════════════════ */
$(document).on('click', '.remove-row', function () {

    const $row        = $(this).closest('tr');
    const productId   = String($row.data('product')).trim();
    const productName = $row.data('product-name');

    const $productRows = $('#rackTable tbody tr').filter(function () {
        return String($(this).data('product')).trim() === productId;
    });

    if ($productRows.length <= 1) {
        Swal.fire('Not Allowed',
            `At least one row must remain for "${productName}".`,
            'warning');
        return;
    }

    safeDestroySelect2($row.find('select'));
    $row.remove();
});

/* ═══════════════════════════════════════════
   5. Add row — block if fully allocated
═══════════════════════════════════════════ */
$(document).on('click', '.add-row', function () {

    const $row        = $(this).closest('tr');
    const productId   = String($row.data('product')).trim();
    const productName = $row.data('product-name');
    const netQty      = productNetQtyMap[productId] || 0;

    let totalAllocated = 0;
    $('#rackTable tbody tr').each(function () {
        if (String($(this).data('product')).trim() === productId) {
            totalAllocated += parseFloat($(this).find('.store-qty').val()) || 0;
        }
    });

    if (totalAllocated >= netQty) {
        Swal.fire('Limit Reached',
            `"${productName}": All received quantity (${netQty}) is already allocated.`,
            'warning');
        return;
    }

    const $productRows = $('#rackTable tbody tr').filter(function () {
        return String($(this).data('product')).trim() === productId;
    });

    safeDestroySelect2($productRows.find('select'));

    const $newRow = $row.clone();
    cleanClonedRow($newRow);
    $newRow.find('.store-qty').val(0);
    $newRow.find('select').val('');

    $productRows.last().after($newRow);

    const $allProductRows = $('#rackTable tbody tr').filter(function () {
        return String($(this).data('product')).trim() === productId;
    });

    initSelect2OnRows($allProductRows);
});

/* ═══════════════════════════════════════════
   6. Live qty guard
═══════════════════════════════════════════ */
$(document).on('input', '.store-qty', function () {

    const $input      = $(this);
    const $row        = $input.closest('tr');
    const productId   = String($row.data('product')).trim();
    const productName = $row.data('product-name');
    const netQty      = productNetQtyMap[productId] || 0;

    const val = parseFloat($input.val());
    if (isNaN(val) || val < 0) {
        $input.val(0);
        return;
    }

    let totalAllocated = 0;
    $('#rackTable tbody tr').each(function () {
        if (String($(this).data('product')).trim() === productId) {
            totalAllocated += parseFloat($(this).find('.store-qty').val()) || 0;
        }
    });

    if (totalAllocated > netQty) {
        Swal.fire('Invalid Quantity',
            `Total stored quantity for "${productName}" cannot exceed ${netQty}.`,
            'error');
        $input.val(0);
    }
});

/* ═══════════════════════════════════════════
   7. Build payload
═══════════════════════════════════════════ */
function buildRackReceivingPayload() {

    const items         = [];
    const productTotals = {};

    $('#rackTable tbody tr').each(function () {

        const $row        = $(this);
        const productId   = String($row.data('product')).trim();
        const productName = $row.data('product-name');

        const rack     = ($row.find('.rack-select').val()  || '').trim();
        const level    = ($row.find('.level-select').val() || '').trim();
        const slot     = ($row.find('.slot-select').val()  || '').trim();
        const qty      = parseFloat($row.find('.store-qty').val()) || 0;
        const isFridge = FRIDGE_RACKS.includes(rack);

        if (!productId) {
            throw { title: 'Data Error', message: 'A row is missing product information.' };
        }

        if (!rack) {
            throw { title: 'Bay Required', message: `Please select Bay for "${productName}".` };
        }

        /* Column & Floor only required for non-fridge racks */
        if (!isFridge) {
            if (!level) {
                throw { title: 'Column Required', message: `Please select Column for "${productName}".` };
            }
            if (!slot) {
                throw { title: 'Floor Required', message: `Please select Floor for "${productName}".` };
            }
        }

        if (qty <= 0) {
            throw { title: 'Quantity Required', message: `Please enter Store Qty > 0 for "${productName}".` };
        }

        if (!productTotals[productId]) {
            productTotals[productId] = {
                total : 0,
                net   : productNetQtyMap[productId] || 0,
                name  : productName
            };
        }
        productTotals[productId].total += qty;

        items.push({
            product_id  : productId,
            rack_no     : rack,
            level_no    : isFridge ? null : level,
            slot_no     : isFridge ? null : slot,
            quantity    : qty,
            batch_no    : $row.find('.batch-no').val()    || null,
            expiry_date : $row.find('.expiry-date').val() || null
        });
    });

    Object.keys(productTotals).forEach(pid => {
        const p = productTotals[pid];
        if (p.total !== p.net) {
            throw {
                title  : 'Quantity Mismatch',
                message: `"${p.name}": Received = ${p.net}, Allocated = ${p.total}. Please match the totals.`
            };
        }
    });

    if (items.length === 0) {
        throw { title: 'No Data', message: 'No rack allocation rows found.' };
    }

    return { items };
}

/* ═══════════════════════════════════════════
   8. Save / Update
═══════════════════════════════════════════ */
$('#saveRackBtn').on('click', function () {

    let payload;

    try {
        payload = buildRackReceivingPayload();
    } catch (err) {
        Swal.fire(err.title || 'Validation Error', err.message || 'Please check your inputs.', 'warning');
        return;
    }

    Swal.fire({
        title            : 'Updating Rack Allocation…',
        allowOutsideClick: false,
        didOpen          : () => Swal.showLoading()
    });

    fetch("{{ route('admin.rack.receiving.update', $grn->id) }}", {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'Accept'       : 'application/json',
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(payload)
    })
    .then(async res => {
        const contentType = res.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            const text = await res.text();
            throw new Error('Server returned non-JSON:\n' + text.substring(0, 300));
        }
        const data = await res.json();
        if (!res.ok) {
            throw new Error(data.message || `HTTP ${res.status}`);
        }
        return data;
    })
    .then(res => {
        Swal.close();
        if (res.success) {
            Swal.fire('Updated!', res.message, 'success')
                .then(() => window.location.href = res.redirect_url);
        } else {
            Swal.fire('Failed', res.message || 'Unable to update rack allocation.', 'error');
        }
    })
    .catch(err => {
        Swal.close();
        console.error('Rack update error:', err);
        Swal.fire('Server Error', err.message || 'Something went wrong. Check console.', 'error');
    });
});

</script>
@endsection
