@extends('admin.layouts.appnew')
@section('content')
<style>
/* ===============================
   BUTTONS
================================ */
.btn-export {
    background: #a558c8;
    color: #fff;
    border-radius: 5px;
    padding: 8px 16px;
    font-size: 13px;
    border: none;
    font-weight: 400;
}

/* ===============================
   TABLE BASE
================================ */
.table td {
    vertical-align: middle;
    font-size: 14px !important;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Highlight Invoice Column */
.table td:nth-child(2) {
    font-weight: 600;
    color: #111827;
    letter-spacing: 0.3px;
}

/* ===============================
   COLUMN WIDTH CONTROL
================================ */
.table td:nth-child(1),
.table td:nth-child(2) {
    min-width: 120px;
}

.table td:nth-child(6) {
    min-width: 180px;
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table td:nth-child(12) {
    min-width: 140px;
}

/* ===============================
   COLUMN WRAP FIXES
================================ */
.table td:nth-child(10) {
    white-space: normal !important;
    line-height: 1.4;
}

.table td:nth-child(10) .btn {
    display: block;
    margin-top: 5px;
}

.table td:nth-child(11) {
    white-space: normal !important;
}

.table td:nth-child(13) {
    white-space: normal !important;
}

/* ===============================
   DATE FORMAT (DATE + TIME)
================================ */
.date-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    line-height: 1.3;
}

.date-box .date {
    font-weight: 600;
}

.date-box .time {
    font-size: 11px;
    color: #6b7280;
}

/* ===============================
   DELIVERY DOCS UI
================================ */
.docs-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
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

.table thead th {
    padding: 10px;
}

.searchBar {
    width: 170px;
    font-size: 13px;
    padding: 5px 10px;
    background-color: #ffffff;
    border-radius: 2px;
}
</style>

<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="row my-2">
                                    <h3 class="card-title col-2">Delivery Status</h3>
                                </div>

                                <div class="table-responsive">
                                    <table id="delivery_table" class="table table-bordered">
                                        <thead class="b-shadow">
                                            <tr class="p-2">
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
                                            @forelse($deliveries as $delivery)
                                                <tr data-status="{{ $delivery->delivery_status }}{{ ($delivery->order && $delivery->order->payment_status == 'unpaid') ? ' unpaid' : '' }}">

                                                    <td class="copy-text text-center" title="Double click to copy" ondblclick="copyText(this)">
                                                        {{ $delivery->order->order_id }}
                                                    </td>

                                                    <td class="copy-text text-center" title="Double click to copy" ondblclick="copyText(this)">
                                                        <a href="{{ route('generateInvoiceAndDeliveryCharges.list', ['id' => $delivery->order->id]) }}"
                                                           onclick="window.open(this.href, '_blank', 'width=800,height=600'); return false;"
                                                           class="font-weight-bold text-dark">
                                                           {{ $delivery->order->order_id }}
                                                        </a>
                                                    </td>

                                                    <td class="text-center">
                                                        @if ($delivery->delivery_status == 'cancelled')
                                                            {{ $delivery->delivery_id }}
                                                        @else
                                                            <a type="button" class="font-weight-bold text-dark edit-delivery-link"
                                                               data-toggle="modal"
                                                               data-target="#editDeliveryModal"
                                                               data-id="{{ $delivery->id }}"
                                                               data-status="{{ $delivery->delivery_status }}"
                                                               data-note="{{ $delivery->delivery_notes }}"
                                                               data-update-url="{{ route('update.delivery', ['id' => $delivery->id]) }}">
                                                                {{ $delivery->delivery_id }}
                                                            </a>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">{{ $delivery->delivery_status }}</td>

                                                    <td class="text-center">{{ $delivery->user ? $delivery->user->name : '-' }}</td>
                                                    <td class="text-center">{{ $delivery->user ? $delivery->user->outlet_name : '-' }}</td>

                                                    <td class="text-center">₹ {{ $delivery->order ? $delivery->order->total_discount_value : '-' }}</td>
                                                    <td class="text-center">{{ $delivery->order ? $delivery->order->payment_method : '-' }}</td>

                                                    <td class="text-center">
                                                        {{ $delivery->order->payment_status ?? '-' }}
                                                        @if (($delivery->order->payment_status ?? null) !== 'paid')
                                                            <a href="{{ route('order.edit', ['id' => $delivery->order->id, 'from' => 'delivery']) }}" class="btn btn-danger text-white mx-1">Update</a>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @if($delivery->confirmation_doc && count($delivery->confirmation_doc))
                                                            @foreach($delivery->confirmation_doc as $file)
                                                                <a href="{{ asset('storage/' . $file) }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-primary mb-1">
                                                                   View Bill {{ $loop->iteration }}
                                                                </a>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No confirmation document</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">{{ $delivery->delivery_date }}</td>

                                                    <td class="text-center">
                                                        @if ($delivery->delivery_status == 'delivered')
                                                            {{ $delivery->updated_at }}
                                                        @elseif($delivery->delivery_status == 'cancelled')
                                                            <span class="text-danger">Order Cancelled</span>
                                                        @else
                                                            <span>Not Delivered Yet</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">{{ $delivery->delivery_notes }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="13" class="text-center">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Single shared modal for all rows -->
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

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // --- Copy to clipboard ---
        window.copyText = function (element) {
            var tempInput = document.createElement("input");
            tempInput.value = element.textContent.trim();
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);

            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                toast: true,
                position: 'top-start',
                showConfirmButton: false,
                timer: 1500
            });
        };

        // --- Shared edit modal: populate on open ---
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

        document.querySelectorAll('.edit-delivery-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                form.action = this.dataset.updateUrl;
                statusSelect.value = this.dataset.status;
                noteInput.value = this.dataset.note || '';
                toggleConfirmationDocField();

                // Explicitly show the modal (covers cases where data-toggle
                // alone doesn't fire, e.g. Bootstrap 5 or JS load-order issues)
                if (window.jQuery) {
                    jQuery('#editDeliveryModal').modal('show');
                } else if (window.bootstrap && window.bootstrap.Modal) {
                    var modalEl = document.getElementById('editDeliveryModal');
                    var modalInstance = window.bootstrap.Modal.getOrCreateInstance(modalEl);
                    modalInstance.show();
                } else if (typeof openModal === 'function') {
                    openModal('editDeliveryModal');
                } else {
                    console.warn('No modal library (jQuery/Bootstrap) or openModal() found — modal cannot be shown.');
                }
            });
        });

        statusSelect.addEventListener('change', toggleConfirmationDocField);

        // --- Status filter buttons (if present on page) ---
        const filterBtns = document.querySelectorAll(".filter-btn");
        const tableRows = document.querySelectorAll("#delivery_table tbody tr");

        filterBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                filterBtns.forEach(function (button) {
                    button.classList.remove("active");
                });
                this.classList.add("active");

                const status = this.getAttribute("data-status");

                tableRows.forEach(function (row) {
                    const rowStatus = row.getAttribute("data-status");

                    if (status === 'all') {
                        row.style.display = "table-row";
                    } else if (rowStatus && rowStatus.includes(status)) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });

        // --- Live search (if #liveSearch input exists on page) ---
        const searchInput = document.getElementById("liveSearch");
        if (searchInput) {
            searchInput.addEventListener("keyup", function () {
                let value = this.value.toLowerCase().trim();

                tableRows.forEach(function (row) {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(value) ? "" : "none";
                });
            });
        }

        // --- Address toggle (kept for compatibility if used elsewhere) ---
        window.toggleAddress = function (deliveryId, type) {
            const shortAddress = document.getElementById('short-' + type + '-' + deliveryId);
            const fullAddress = document.getElementById('full-' + type + '-' + deliveryId);
            const viewLink = document.getElementById('view-' + type + '-link-' + deliveryId);

            if (!shortAddress || !fullAddress || !viewLink) return;

            if (fullAddress.style.display === "none") {
                fullAddress.style.display = "inline";
                shortAddress.style.display = "none";
                viewLink.innerHTML = "View Less";
            } else {
                fullAddress.style.display = "none";
                shortAddress.style.display = "inline";
                viewLink.innerHTML = "View More";
            }
        };

    });
</script>
@endsection
