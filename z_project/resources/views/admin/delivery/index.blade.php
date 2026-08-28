@extends('admin.layouts.appnew')
@section('content')
<style>
    /* td{
        text-transform: capitalize;

    }
    td:nth-child(9){
        white-space: nowrap;
    }

    .table thead th{
        padding: 10px
    }


    .tooltip {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

   /* 
a{ 
    text-decoration: none;
}

.btn-export:hover {
    transform: translateY(-1px);
    opacity: 0.9;
    background: #a558c8;
    color: #fff;
} */
/*  Add export to excel */
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
    /* white-space: nowrap;
    max-width: 150px; */
    font-size: 14px !important;
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

/* Order No + Invoice No */
.table td:nth-child(1),
.table td:nth-child(2) {
    min-width: 120px;
}

/* Outlet Name */
.table td:nth-child(6) {
    min-width: 180px;
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Expected Delivery Date */
.table td:nth-child(12) {
    min-width: 140px;
}

/* ===============================
   COLUMN WRAP FIXES
================================ */

/* Payment Status (buttons inside) */
.table td:nth-child(10) {
    white-space: normal !important;
    line-height: 1.4;
}

.table td:nth-child(10) .btn {
    display: block;
    margin-top: 5px;
}

/* Delivery Docs column */
.table td:nth-child(11) {
    white-space: normal !important;
}

/* Delivery Date column */
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

/* ===============================
   OPTIONAL SMALL IMPROVEMENTS
================================ */

/* Prevent ugly overflow in long text columns */
.table td {
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Better table header spacing */
.table thead th {
    padding: 10px;
}
.searchBar{
 width: 170px; 
 font-size: 13px; 
 padding: 5px 10px; 
 background-color:#ffffff;
 border-radius: 2px;
}
</style>
<div class="page-body">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper ">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                        <div class="row my-2 ">
                                            <h3 class="card-title col-2">Delivery Status</h3>

                                          <div class="col-10 d-flex justify-content-end">
                                            <a href="{{ route('delivery.create') }}" class="filter-btn btn text-white my-auto mx-4" style="background: green"> New Delivery</a>
                                            <button class="btn btn-info filter-btn mx-1 my-auto text-white" data-status="all">All</button>
                                            <!-- <button class="btn btn-success filter-btn my-auto mx-1" data-status="pending">Pending</button> -->
                                            <button class="btn filter-btn my-auto mx-1" style="background: rgb(0, 56, 0); color: white" data-status="in_progress">In Progress</button>
                                            <button class="btn btn-primary filter-btn my-auto mx-1" data-status="ready_for_dispatch">Ready for Dispatch (Internal)</button>
                                            <button class="btn btn-primary filter-btn my-auto mx-1" data-status="final_check_done">Final Check Done</button>
                                            <button class="btn btn-primary filter-btn my-auto mx-1" data-status="dispatched">Dispatched</button>
                                            <button class="btn  text-white filter-btn my-auto mx-1" style="background: rgb(100, 99, 0); color: white" data-status="delivered">Delivered</button>
                                            <button class="btn btn-danger text-white filter-btn my-auto mx-1" data-status="cancelled">Cancelled</button>
                                            <button class="btn btn-danger text-white filter-btn my-auto mx-1" data-status="unpaid">Unpaid</button>
                                          </div>
                                        </div>
                            
                                    <!-- Add export to excel function -->
                                     
                                     <div class="col-md-6">
                                        <a href="{{ route('export.delivery') }}" class="btn-export btn-success">
                                       ✔ Export To Excel
                                    </a>                        
                                     </div>
                                   
                                     <!-- end -->

                                    <p class="card-description"></p>
                                    <div class="table-responsive">
                                        <div class="d-flex justify-content-end mb-3">
                                          <input type="text"id="liveSearch"class="form-control searchBar">
                                      </div>
                                        <table id="delivery-table" class="   table table-bordered"  id="productsdata">
                                            <thead class="b-shadow">
                                                <tr class="p-2">
                                                    <th class="text-center">Order.No</th>
                                                    <th class="text-center">Invoice.No</th>
                                                    <th class="text-center">Delivery</th>
                                                    <th class="text-center"> Status</th>
                                                    <!-- <th class="text-center"> Address</th> -->
                                                    <th class="text-center">Customer Name</th>
                                                    <th class="text-center">Outlet Name</th>
                                                    <!-- <th class="text-center"> Contact</th> -->
                                                    <th class="text-center"> Paid Amount</th>
                                                    <th class="text-center"> Payment Mode</th>
                                                    <th class="text-center">Payment Status</th>
                                                    <th class="text-center">Delivery Docs</th>
                                                    <th class="text-center">Expected Delivery Date</th>
                                                    <th class="text-center">Delivery Date</th>
                                                   <th class="text-center">Delivery Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($deliveries->isEmpty())
                                                <tr>
                                                    <td colspan="10">No data found</td>
                                                </tr>
                                            @else
                                                @foreach($deliveries as $delivery)
<tr data-status="{{ $delivery->delivery_status }}{{ ($delivery->order && $delivery->order->payment_status == 'unpaid') ? ' unpaid' : '' }}">


                                                        <td class="copy-text text-center" title="Double click to copy" ondblclick="copyText(this)">
                                                          @if ( $delivery->delivery_status == 'cancelled' )
                                                            {{ $delivery->order->order_id }}

                                                            @else
                                                             <a href="{{ route('order.modify', $delivery->order->id) }}" 
                                                            class="badge bg-warning text-dark mt-1">
                                                            Modify Rate
                                                            </a>
                                                             @endif

                                                            
                                                        </td>
                                                       
                                                       
                                                        <td class="copy-text text-center" title="Double click to copy" ondblclick="copyText(this)">
    <a href="{{ route('generateInvoiceAndDeliveryCharges.list', ['id' => $delivery->order->id]) }}" 
       onclick="window.open(this.href, '_blank', 'width=800,height=600'); return false;" 
       class="font-weight-bold text-dark">
       {{ $delivery->order->order_id }}
    </a>
</td>

<!-- <td class="copy-text text-center" title="Double click to copy" ondblclick="copyText(this)">
    @if ($delivery->delivery_status == 'delivered')
        <a href="{{ route('generateInvoiceAndDeliveryCharges.list', ['id' => $delivery->order->id]) }}" 
           onclick="window.open(this.href, '_blank', 'width=800,height=600'); return false;" 
           class="font-weight-bold text-dark">
           {{ $delivery->order->order_id }}
        </a>
        @else

        Not Available   
    </td>
    @endif -->
                                                        <!-- <td class="text-center">
                                                              @if ( $delivery->delivery_status == 'delivered' || $delivery->delivery_status == 'cancelled')
                                                                {{ $delivery->delivery_id }}
                                                            @else
                                                                <a type="button" class="font-weight-bold text-dark" data-toggle="modal" onclick="openModal('editModal{{ $delivery->id }}')" data-target="#editModal{{ $delivery->id }}"> {{ $delivery->delivery_id }}</a>
                                                            @endif
                                                        </td> -->

                                                       <td class="text-center">
                                                         @if ($delivery->delivery_status == 'cancelled'){{ $delivery->delivery_id }} @else
                                                         <a type="button" class="font-weight-bold text-dark" data-toggle="modal" onclick="openModal('editModal{{ $delivery->id }}')" data-target="#editModal{{ $delivery->id }}">
                                                        {{ $delivery->delivery_id }}</a>
                                                        @endif
                                                         </td>

                                                        <td class="text-center">
                            
                                                                {{ $delivery->delivery_status }}
                                                           
                                                        </td>

                                                        <!-- <td class="text-center" style="min-width: 200px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $delivery->delivery_address }}">
    @if(strlen($delivery->delivery_address) > 10)
        <span id="short-delivery-{{ $delivery->id }}">{{ Str::limit($delivery->delivery_address, 10, '...') }}</span>
        <span id="full-delivery-{{ $delivery->id }}" style="display:none;">{{ $delivery->delivery_address }}</span>
        <div>
            <a href="javascript:void(0)" class="text-primary" id="view-delivery-link-{{ $delivery->id }}" onclick="toggleAddress('{{ $delivery->id }}', 'delivery')" style="font-size: 11px;">
                View More
            </a>
        </div>
    @else
        {{ $delivery->delivery_address }}
    @endif
</td> -->


                                                       <td class="text-center">{{ $delivery->user ? $delivery->user->name : '-' }}</td>
                                                       <td class="text-center">{{ $delivery->user ? $delivery->user->outlet_name : '-' }}</td>
                                                       @if($delivery->user && $delivery->user->kycdocuments->isNotEmpty())
                                                            <!-- <td>{{ $delivery->user->kycdocuments->first()->phone }}</td> -->
                                                        @else
                                                            <td>-</td> <!-- Or any other fallback content you prefer -->
                                                        @endif

                                                     <td class="text-center"> ₹ {{ $delivery->order ? $delivery->order->total_discount_value : '-' }}</td>
                                                     <td class="text-center">{{ $delivery->order ? $delivery->order->payment_method : '-' }}</td>
                                                   
                                                     
                                                           <td class="text-center">
                                                        {{ $delivery->order->payment_status ?? '-' }}
                                                        
                                                    @if ($delivery->order->payment_status !== 'paid')
                                                            <a href="{{ route('order.edit', ['id' => $delivery->order->id, 'from' => 'delivery']) }}" class="btn btn-danger text-white mx-1">Update</a>
                                                        @endif
                                                    </td>



                                                    @if($delivery->confirmation_doc && count($delivery->confirmation_doc))
                                                    <td>
                                                    @foreach($delivery->confirmation_doc as $file)
                                                     <a href="{{ asset('storage/' . $file) }}"
                                                      target="_blank" 
                                                     class="btn btn-sm btn-primary mb-1">
                                                    View Bill {{ $loop->iteration }}
                                                     </a>
                                                    @endforeach
                                                    </td>
                                                    @else
                                                     <td>
                                                    <span class="text-muted">No confirmation document</span>
                                                    </td>
                                                    @endif

                                                        
                                            <!-- @if($delivery->confirmation_doc && count($delivery->confirmation_doc))
                                                <td>
                                               @foreach($delivery->confirmation_doc as $file)
                                               <a href="{{ asset('storage/' . $file) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-primary mb-1">
                                               View Doc {{ $loop->iteration }}
                                               </a>
                                               @endforeach
                                              </td>
                                              @else
                                             <td>
                                             <span class="text-muted">No confirmation document</span>
                                             </td>
                                             @endif -->
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
                                                @endforeach
                                            @endif

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        @foreach($deliveries as $delivery)
                        <div class="modal fade" id="editModal{{ $delivery->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $delivery->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $delivery->id }}">Update Delivery</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('update.delivery', ['id' => $delivery->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="delivery_status{{ $delivery->id }}">Delivery Status</label>
                                                <select class="form-control" id="delivery_status{{ $delivery->id }}" name="delivery_status">
                                                    <!-- <option value="pending" {{ $delivery->delivery_status == 'pending' ? 'selected' : '' }}>Pending</option> -->
                                                    <option value="in_progress" {{ $delivery->delivery_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="ready_for_dispatch" {{ $delivery->delivery_status == 'ready_for_dispatch' ? 'selected' : '' }}>Ready for Dispatch (Internal)</option>
                                                    <option value="final_check_done" {{ $delivery->delivery_status == 'final_check_done' ? 'selected' : '' }}>Final Check Done</option>
                                                    <option value="dispatched" {{ $delivery->delivery_status == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                                    <option value="delivered" {{ $delivery->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                    <!--<option value="cancelled" {{ $delivery->delivery_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>-->
                                                </select>
                                            </div>

                                            <!-- Input field for confirmation document -->
                                            <div class="form-group" id="confirmation_doc_field{{ $delivery->id }}" style="display: none;">
                                                <label for="confirmation_doc{{ $delivery->id }}">Confirmation Document</label>
                                                <input type="file" class="form-control" id="confirmation_doc{{ $delivery->id }}" name="confirmation_doc[]" multiple>
                                            </div>

                                            <div class="form-group">
                                                <label for="note{{ $delivery->id }}">Note</label>
                                                <textarea class="form-control" id="note{{ $delivery->id }}" name="note">{{ $delivery->delivery_notes }}</textarea>
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
                            function copyText(element) {
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
                            }

                            function toggleConfirmationDocField(deliveryStatusId) {
                                var deliveryStatus = document.getElementById('delivery_status' + deliveryStatusId).value;
                                var confirmationDocField = document.getElementById('confirmation_doc_field' + deliveryStatusId);
                                var confirmationDoc = document.getElementById('confirmation_doc' + deliveryStatusId);
                                if (deliveryStatus === 'delivered') {
                                    confirmationDocField.style.display = 'block';
                                    confirmationDoc.setAttribute('required', 'true');
                                } else {
                                    confirmationDocField.style.display = 'none';
                                    confirmationDoc.removeAttribute('required');
                                }

                            }
                            toggleConfirmationDocField('{{ $delivery->id }}');

                            document.getElementById('delivery_status{{ $delivery->id }}').addEventListener('change', function() {
                                toggleConfirmationDocField('{{ $delivery->id }}');
                            });
                            </script>


                    @endforeach



                        <script>
                      document.addEventListener("DOMContentLoaded", function () {
    const filterBtns = document.querySelectorAll(".filter-btn");
    const tableRows = document.querySelectorAll("#delivery-table tbody tr");

    filterBtns.forEach(btn => {
        btn.addEventListener("click", function () {
            filterBtns.forEach(button => {
                button.classList.remove("active");
            });
            this.classList.add("active");

            const status = this.getAttribute("data-status");

            tableRows.forEach(row => {
                const rowStatus = row.getAttribute("data-status");

                if (status === 'all') {
                    row.style.display = "table-row";
                } else if (rowStatus && rowStatus.includes(status)) { // ✅ Match partial status (e.g., "unpaid")
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
});


                            function toggleAddress(deliveryId, type) {
                            // Get the short and full address elements
                            const shortAddress = document.getElementById('short-' + type + '-' + deliveryId);
                            const fullAddress = document.getElementById('full-' + type + '-' + deliveryId);
                            const viewLink = document.getElementById('view-' + type + '-link-' + deliveryId);

                            // Toggle visibility
                            if (fullAddress.style.display === "none") {
                                fullAddress.style.display = "inline";  
                                shortAddress.style.display = "none";   
                                viewLink.innerHTML = "View Less";      
                            } else {
                                fullAddress.style.display = "none";   
                                shortAddress.style.display = "inline"; 
                                viewLink.innerHTML = "View More";
                            }
                        }


                        </script>

                        <script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("liveSearch");
    const rows = document.querySelectorAll("#delivery-table tbody tr");

    searchInput.addEventListener("keyup", function () {
        let value = this.value.toLowerCase().trim();

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();

            if (text.includes(value)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

});
</script>

                        </html>
                        @endsection
