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
                        <div class="alert alert-success alert-dismissible fade show">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif


                    <div class="card">
                        <div class="card-body">

                            {{-- Header --}}
                            <div class="card-header-2 mb-3">
                               <h4>
                                 Create Stock Damaged
                               </h4>
                            </div>



    <form id="damageForm" method="POST" action="{{ route('stock-damaged.store') }}">
        @csrf

        <!-- Product Select -->
        <div class="mb-3">
            <label>Product</label>
            <select name="product_id" id="product_id" class="form-control select2">
                <option value="">Select Product</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->product_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Dynamic Table -->
        <table class="table table-bordered" id="p_table">
            <thead>
                <tr>
                    <th>Batch</th>
                    <th>Expiry</th>
                    <th>Type</th>
                    <th>Available Qty</th>
                    <th>Damage Qty</th>
                    <th>Unit Cost</th>
                    <th>Total</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button type="submit" class="btn btn-success">Submit</button>
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

    // ==============================
    // INIT SELECT2
    // ==============================
    $('#product_id').select2({
        width: '100%',
        placeholder: 'Select Product',
        allowClear: true
    });

    // ==============================
    // SELECT PRODUCT
    // ==============================
    $('#product_id').on('select2:select', function (e) {
        let product_id = e.params.data.id;
        loadProductStock(product_id);
    });

    // CLEAR
    $('#product_id').on('select2:clear', function () {
        $('#p_table tbody').html('');
    });

    // ==============================
    // LOAD STOCK
    // ==============================
    function loadProductStock(product_id) {

        $('#p_table tbody').html(`
            <tr>
                <td colspan="8" class="text-center text-muted">
                    Loading stock...
                </td>
            </tr>
        `);

        $.ajax({
            url: '/get-product-stock',
            type: 'GET',
            data: { product_id: product_id },

            success: function (data) {

                let tbody = '';

                if (!data || data.length === 0) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'No Stock Found',
                        text: 'No stock available for this product'
                    });

                    tbody = `
                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                No stock available
                            </td>
                        </tr>`;
                } else {

                    data.forEach((item, index) => {

                        let badge = item.stock_type === 'GRN'
                            ? '<span class="badge bg-success">GRN</span>'
                            : '<span class="badge bg-info">Opening</span>';

                        tbody += `
                        <tr>
                            <td>${item.batch_no ?? '-'}</td>
                            <td>${formatDate(item.expiry_date)}</td>
                            <td>${badge}</td>
                            <td><span class="badge bg-warning">${item.quantity}</span></td>

                            <td>
                                <input type="number"
                                       name="items[${index}][qty]"
                                       class="form-control qty"
                                       min="1"
                                       max="${item.quantity}">
                            </td>

                            <td>
                                <input type="text"
                                       name="items[${index}][unit_cost]"
                                       value="${parseFloat(item.unit_cost || 0).toFixed(2)}"
                                       class="form-control"
                                       readonly>
                            </td>

                            <td>
                                <input type="text"
                                       name="items[${index}][total]"
                                       class="form-control total"
                                       readonly>
                            </td>

                            <td>
                                <input type="text"
                                       name="items[${index}][reason]"
                                       class="form-control reason"
                                       placeholder="Enter reason">
                            </td>

                            <input type="hidden" name="items[${index}][batch_no]" value="${item.batch_no}">
                            <input type="hidden" name="items[${index}][expiry_date]" value="${item.expiry_date}">
                            <input type="hidden" name="items[${index}][stock_receiving_id]" value="${item.stock_receiving_id ?? ''}">
                            <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                            <input type="hidden" name="items[${index}][stock_type]" value="${item.stock_type}">
                        </tr>`;
                    });
                }

                $('#p_table tbody').html(tbody);
            },

            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load stock data'
                });
            }
        });
    }

    // ==============================
    // CALCULATE TOTAL
    // ==============================
    $(document).on('input', '.qty', function () {

        let row = $(this).closest('tr');

        let qty = parseFloat($(this).val()) || 0;
        let max = parseFloat($(this).attr('max')) || 0;

        if (qty > max) {
            qty = max;
            $(this).val(max);

            Swal.fire({
                icon: 'warning',
                title: 'Limit Exceeded',
                text: 'Quantity cannot exceed available stock'
            });
        }

        let cost = parseFloat(row.find('[name*="[unit_cost]"]').val()) || 0;
        row.find('.total').val((qty * cost).toFixed(2));
    });

    // ==============================
    // FORM VALIDATION + SUBMIT
    // ==============================
    $('#damageForm').submit(function (e) {

        let isValid = true;
        let hasQty = false;

        $('.qty').each(function () {

            let qty = parseFloat($(this).val()) || 0;
            let reason = $(this).closest('tr').find('.reason').val();

            if (qty > 0) {
                hasQty = true;

                if (!reason) {
                    isValid = false;
                }
            }
        });

        if (!hasQty) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'No Quantity Entered',
                text: 'Please enter at least one damage quantity'
            });

            return false;
        }

        if (!isValid) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Missing Reason',
                text: 'Please enter reason for all damaged items'
            });

            return false;
        }

        // SUCCESS CONFIRMATION
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to mark stock as damaged!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Submit',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#damageForm')[0].submit();
            }
        });

    });

    // ==============================
    // DATE FORMAT
    // ==============================
    function formatDate(dateString) {
        if (!dateString) return '-';

        let d = new Date(dateString);
        return d.toLocaleDateString('en-GB');
    }

});
</script>
@endsection