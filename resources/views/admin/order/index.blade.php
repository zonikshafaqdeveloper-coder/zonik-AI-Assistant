@extends('admin.layouts.appnew')
@section('content')
<style>
    td{
        text-transform: capitalize;

    }

/* add some css for table design:  */
body,
.order_table,
.order_table th,
.order_table td,
.card,
.card-title,
.btn,

a {
    font-family: 'Poppins', sans-serif !important;
    font-size: 13px !important;
    font-weight: 600 !important; 
}

.btn-update,
.btn-cancel {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Page background */
.content-wrapper {
    background: #f6f8fb;
}
/* Table base */
.order_table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto; 
}

.order_table th,
.order_table td {
  white-space: nowrap;
  color: #111827;
  font-weight: 600 !important;
  font-family: 'Poppins', sans-serif !important;
}

.order_table th {
    text-align: left;
}

.order_table td {
    text-align: left;
}

.order_table a {
    color: #111827;
}

.order_table a:hover {
    text-decoration: none !important;
}

/* Header */
.order_table thead {
    background: #a558c8;
}

.order_table thead th {
    color: #fff;
    font-weight: 400;
    
}
/* Rows */
.order_table tbody tr:nth-child(even) {
    background: #fafafa;
}

.order_table tbody tr:hover {
    background: #f9fafb;
}
.truncate {
    max-width: 140px;
    display: inline-block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
/* STATUS BADGES (MODERN) */
.status-pending {
    background: #e6f4ea;
    color: #b26a00;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 400;
}

.status-progress {
    background: #e6f4ea;
    color: #1d4ed8;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 13px;
   font-weight: 400;
}

.status-delivered {
    background: #e6f4ea;
    color: #1b7f3b;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 400;
}

/* Buttons */
.btn-update {
    background: #ef4444;
    border: none;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 6px;
    color: #fff;
}

.btn-cancel-icon {
    background: #fee2e2;
    color: #ef4444;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
}

.btn-cancel-icon:hover {
    background: #ef4444;
    color: #fff;
    transform: scale(1.1);
}
.btn-update,
.btn-cancel,
.btn-export {
    font-weight: 400;
}

.order_table td:nth-child(4),
.order_table th:nth-child(4) {
    min-width: 220px;   /* increase as needed */
    max-width: 300px;
    width: 250px;
}

/* Due colors */
.due-red { color: #ef4444; font-weight: 400; }
.due-orange { color: #f59e0b; font-weight: 400; }
.due-green { color: #10b981; font-weight: 400; }

/* Export button */
.btn-export {
    background: #a558c8;
    color: #fff;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 13px;
    border: none;
    font-weight: 600;
}

/* Inputs */
.dataTables_length select,
.dataTables_filter input {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 6px 10px;
}

/* Hover buttons */
.btn-update:hover,
.btn-cancel:hover,
.btn-export:hover {
    transform: translateY(-1px);
    opacity: 0.9;
}

/* Card */
.card {
    border-radius: 16px !important;
    border: none !important;
}
.status-pending,
.status-progress,
.status-delivered {
    margin: 2px;
    display: inline-block;
}

.status-paid {
    background: #e6f4ea;
    color: #1b7f3b;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 400;
}
.payment-status-text {
    font-size: 13px;
    color: #374151;
}

.payment-action {
    margin-top: 6px;
}
.date-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    line-height: 1.3;
}

.date-box .date {
    font-size: 13px;
}

.date-box .time {
    font-size: 12px;
    color: #6b7280; /* light gray */
}
.due-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    line-height: 1.3;
}

.due-text {
    font-size: 13px;
    font-family: 'Poppins', sans-serif !important;
}

.due-date {
    font-size: 12px;
    color: #6b7280;
}
.order_table td {
    vertical-align: middle;
}
.enquire-box {
    margin-left: 0px !important;
    margin-bottom: 15px !important;
}

</style>
<div class="page-body">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper ">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card" style="border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.06); border:none;">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif

                                    <div class="title-header option-title">
                                        <h4 class="card-title"></h4>
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Order List</h3>
                                        </div>

                                        <div class="row display:flex align-items-center enquire-box w-500">
                                            <div class="col">
                                                  <div class="">
                                                            <div class="col-md-6">
                                                               <a href="{{ route('orders.export') }}" class="btn-export">
                                                                ✔ Export To Excel
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>

                                    </div>
                                        <div class="table-responsive">                                        
                                         <table id="order_table" class="table order_table">
                                            <thead class="">
                                                <tr>
                                                    <th class="text-center">Invoice Number</th>
                                                    <th class="text-center">Order No.</th>
                                                    <th class="text-center">Customer Name</th>
                                                    <th class="text-center">Outlet Name</th>
                                                    <th class="text-center">Delivery Date</th>
                                                    <th class="text-center">Delivery Status</th>
                                                  
                                                    <th class="text-center">Grand Value</th>
                                                    <th class="text-center">Payment Method</th>
                                                    <th class="text-center">Payment Status</th>
                                                    <th class="text-center">Order Date</th>
                                                    <th class="text-center">Due Date</th>
                                                    <th>Action</th>
                                                   
                                                </tr>
                                            </thead>

                                        @include('admin.order.partials.order_table_rows', ['orders' => $orders])
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        </html>
                        @endsection


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>


                        </script>
                        
                        <script>
function cancelOrder(orderId) {

    Swal.fire({
        title: 'Are you sure?',
        text: 'This will cancel the order and restore stock!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {

        if (result.isConfirmed) {

            let token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('order.accept.cancel', '') }}/" + orderId,
                type: 'POST',
                data: { _token: token },

                beforeSend: function () {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Cancelling order...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },

                success: function (response) {

                    if (response.success) {
                        Swal.fire('Cancelled!', response.message, 'success')
                            .then(() => {
                                location.reload(); // or redirect
                            });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },

                error: function (xhr) {

                    let message = 'Something went wrong';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire('Error', message, 'error');
                }
            });
        }
    });
}
</script>
                        
                        