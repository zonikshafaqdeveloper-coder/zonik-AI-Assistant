@extends('admin.layouts.appnew')
@section('content')

<style>

#delivery_table_data td {
    vertical-align: middle;
    font-size: 14px !important;
}


#delivery_table_data td:nth-child(2) {
    font-weight: 600;
    color: #111827;
    letter-spacing: 0.3px;
}


#delivery_table_data td:nth-child(1),
#delivery_table_data td:nth-child(2) {
    min-width: 120px;
}


#delivery_table_data td:nth-child(3),
#delivery_table_data th:nth-child(3) {
    min-width: 220px;
}


#delivery_table_data td:nth-child(7),
#delivery_table_data th:nth-child(7) {
    min-width: 160px;
}

#delivery_table_data td:nth-child(10) {
    white-space: normal !important;
    line-height: 1.4;
}

#delivery_table_data td:nth-child(10) .btn {
    display: block;
    margin-top: 5px;
}

#delivery_table_data td:nth-child(11),
#delivery_table_data td:nth-child(13) {
    white-space: normal !important;
}


.payment-status-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.payment-status-badge {
    display: inline-block;
    text-align: center;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
    min-width: 80px;
}

.payment-status-badge.paid {
    background: #d1fae5;
    color: #047857;
}

.payment-status-badge.unpaid {
    background: #fee2e2;
    color: #b91c1c;
}


.doc-btn {
    background: #4f46e5;
    color: #fff;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    transition: 0.2s;
}

.doc-btn:hover {
    background: #3730a3;
    color: #fff;
}


.dt-buttons {
    margin-bottom: 12px;
}

.dt-buttons .btn {
    margin-right: 6px;
    font-size: 13px;
}
</style>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Delivery Status</h3>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="delivery_table_data">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">Order.No</th>
                                            <th class="text-center">Invoice.No</th>
                                            <th class="text-center">Delivery</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Customer Name</th>
                                            <th class="text-center">Outlet Name</th>
                                            <th class="text-center">Paid Amount</th>
                                            <th class="text-center">Payment Mode</th>
                                            <th class="text-center">Payment Status</th>
                                            <th class="text-center">Delivery Docs</th>
                                            <th class="text-center">Expected Delivery Date</th>
                                            <th class="text-center">Delivery Date</th>
                                            <th class="text-center">Delivery Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

<div class="modal fade" id="editDeliveryModal" tabindex="-1" aria-labelledby="editDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeliveryModalLabel">Update Delivery</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editDeliveryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="delivery_status_input">Delivery Status</label>
                        <select class="form-control" id="delivery_status_input" name="delivery_status">
                            <option value="in_progress">In Progress</option>
                            <option value="ready_for_dispatch">Ready for Dispatch (Internal)</option>
                            <option value="final_check_done">Final Check Done</option>
                            <option value="dispatched">Dispatched</option>
                            <option value="delivered">Delivered</option>
                            <!--<option value="cancelled">Cancelled</option>-->
                        </select>
                    </div>

                    <div class="form-group" id="confirmation_doc_field" style="display: none;">
                        <label for="confirmation_doc_input">Confirmation Document</label>
                        <input type="file" class="form-control" id="confirmation_doc_input" name="confirmation_doc[]" multiple>
                    </div>

                    <div class="form-group">
                        <label for="note_input">Note</label>
                        <textarea class="form-control" id="note_input" name="note"></textarea>
                        @error('note')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="tooltip">
    <span class="tooltiptext" id="myTooltip">Text Copied!</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    let activeStatusFilter = 'all';

 const table = jQuery('#delivery_table_data').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 100,
    lengthMenu: [50, 100, 250, 500],
    ordering: true,
    order: [[0, 'desc']],
    dom: 'Blfrtip',
    buttons: ['copy', 'excel', 'csv', 'pdf'],
    ajax: {
        url: "{{ route('admin.delivery.new_index.data') }}",
        data: function (d) {
            d.status_filter = activeStatusFilter;
        }
    },
    columns: [
        { data: 'order_no', name: 'order_no' },
        { data: 'invoice_no', name: 'invoice_no' },
        { data: 'delivery_col', name: 'delivery_col', className: 'text-center' },
        { data: 'delivery_status', name: 'delivery_status', className: 'text-center' },
        { data: 'customer_name', name: 'customer_name', className: 'text-center' },
        { data: 'outlet_name', name: 'outlet_name', className: 'text-center' },
        { data: 'paid_amount', name: 'paid_amount', className: 'text-center' },
        { data: 'payment_mode', name: 'payment_mode', className: 'text-center' },
        { data: 'payment_status_col', name: 'payment_status_col', className: 'text-center' },
        { data: 'docs', name: 'docs', className: 'text-center' },
        { data: 'expected_delivery_date', name: 'expected_delivery_date', className: 'text-center' },
        { data: 'delivery_date_col', name: 'delivery_date_col', className: 'text-center' },
        { data: 'delivery_notes_col', name: 'delivery_notes_col', className: 'text-center' },
    ]
});

   
    document.querySelectorAll(".filter-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            activeStatusFilter = this.getAttribute("data-status");
            table.ajax.reload();
        });
    });


    const searchInput = document.getElementById("liveSearch");
    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            table.search(this.value).draw();
        });
    }


    document.getElementById('delivery_table_data').addEventListener('dblclick', function (e) {
        const el = e.target.closest('.copy-text');
        if (!el) return;
        const tempInput = document.createElement("input");
        tempInput.value = el.textContent.trim();
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        Swal.fire({ icon: 'success', title: 'Copied!', toast: true, position: 'top-start', showConfirmButton: false, timer: 1500 });
    });


    const statusSelect = document.getElementById('delivery_status_input');
    const noteInput = document.getElementById('note_input');
    const form = document.getElementById('editDeliveryForm');

    function toggleConfirmationDocField() {
        const field = document.getElementById('confirmation_doc_field');
        const fileInput = document.getElementById('confirmation_doc_input');
        if (statusSelect.value === 'delivered') {
            field.style.display = 'block';
            fileInput.setAttribute('required', 'true');
        } else {
            field.style.display = 'none';
            fileInput.removeAttribute('required');
        }
    }

    const modalEl = document.getElementById('editDeliveryModal');

    function openModalManually() {
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        modalEl.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');

        if (!document.getElementById('editDeliveryBackdrop')) {
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'editDeliveryBackdrop';
            document.body.appendChild(backdrop);
        }
    }

    function closeModalManually() {
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        modalEl.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');

        const backdrop = document.getElementById('editDeliveryBackdrop');
        if (backdrop) backdrop.remove();
    }

    document.getElementById('delivery_table_data').addEventListener('click', function (e) {
        const link = e.target.closest('.edit-delivery-link');
        if (!link) return;
        e.preventDefault();
        form.action = link.dataset.updateUrl;
        statusSelect.value = link.dataset.status;
        noteInput.value = link.dataset.note || '';
        toggleConfirmationDocField();

       
        if (window.jQuery && jQuery.fn && typeof jQuery.fn.modal === 'function') {
            jQuery(modalEl).modal('show');
        } else {
            openModalManually();
        }
    });

    modalEl.addEventListener('click', function (e) {
        if (e.target.closest('[data-dismiss="modal"]') || e.target === modalEl) {
            if (window.jQuery && jQuery.fn && typeof jQuery.fn.modal === 'function') {
                jQuery(modalEl).modal('hide');
            }
            closeModalManually();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modalEl.classList.contains('show')) {
            closeModalManually();
        }
    });

    statusSelect.addEventListener('change', toggleConfirmationDocField);

    form.addEventListener('submit', function () {
        const fileInput = document.getElementById('confirmation_doc_input');
        const field = document.getElementById('confirmation_doc_field');
        if (field.style.display === 'none') {
            fileInput.removeAttribute('required');
        }
    });
});
</script>
@endsection
