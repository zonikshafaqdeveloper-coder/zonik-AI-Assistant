@extends('admin.layouts.appnew')

@section('content')
<style>
/* ---------- UI FIXES ---------- */
.mode-select {
    min-width: 140px;
}

.delete-mode-btn {
    height: 38px;
    width: 38px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-responsive {
    overflow: visible !important;
}
select.form-control,
select.typeahead,
select.tt-query,
select.tt-hint {
    padding: 0.4375rem 0.75rem;
    border: 0;
    outline: 1px solid #f1f6f8;
    color: #212529;   
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

        <h4 class="mb-4">Logistic List (Warehouse)</h4>
          <div class="mb-3">
    <a href="{{ route('logistics.export') }}" class="btn btn-success">
        <i class="fa fa-file-excel"></i> Export Excel
    </a>
</div>

<!-- Tab Navigation -->
<div class="row my-3">
    <div class="col-12">
        <ul class="nav nav-tabs" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="inprocess-tab" data-bs-toggle="tab" 
                        data-bs-target="#inprocess" type="button" role="tab">
                    In Process
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" 
                        data-bs-target="#completed" type="button" role="tab">
                    Completed
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content" id="orderTabsContent">
    
    <!-- IN PROCESS TAB -->
    <div class="tab-pane fade show active" id="inprocess" role="tabpanel">
        
        <!-- Status Filter Buttons for In Process -->
        <div class="row my-3">
            <div class="col-12 d-flex justify-content-end flex-wrap gap-2">
                <button class="btn btn-secondary status-filter active" data-status="all" data-tab="inprocess">All</button>
                <button class="btn btn-warning text-dark status-filter" data-status="pending" data-tab="inprocess">Received</button>
                <button class="btn btn-info text-white status-filter" data-status="in_progress" data-tab="inprocess">Accepted</button>
                <button class="btn btn-primary status-filter" data-status="ready_for_dispatch" data-tab="inprocess">Dispatched</button>
                <button class="btn btn-danger status-filter" data-status="cancelled" data-tab="inprocess">Cancelled</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped all-package theme-table" id="pick_list_inprocess">
                <thead class="b-shadow">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Outlet Name</th>
                        <!--<th>Delivery Location</th>-->
                        <th>Delivery Selected</th>
                        <th>Delivery Date</th>
                        <th>Fullfilment %</th>
                        <th>Order Status</th>
                        <th>Picked Status</th>
                        <th>Rack No</th>
                        <th>No of Box</th>
                        <th>Delivery Priority</th>
                        <th>Mode of Delivery</th>
                        <th>Invoice Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $index => $order)
                        @php
                            $deliveryStatus = optional($order->latestDelivery)->delivery_status ?? 'pending';
                        @endphp
                        
                        @if($deliveryStatus !== 'delivered')
                        <tr data-order-id="{{ $order->id }}" data-status="{{ $deliveryStatus }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->outlet->outlet_name ?? '-' }}</td>
                            <!--<td>{{ $order->shipping_address }}</td>-->
                            <td>
                                @if(!empty($order->delivery_slot_type))
                                    {{ strtoupper($order->delivery_slot_type) }}
                                @else
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                                @endif
                            </td>
                            <td>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d-m-Y') : '-' }}</td>
                            
                            @php
                                $picked  = $order->picked_qty ?? 0;
                                $ordered = $order->ordered_qty ?? 0;
                                $percent = $ordered > 0 ? round(($picked / $ordered) * 100, 2) : 0;
                                
                                if ($percent == 100) {
                                    $color = '#16a34a';
                                } elseif ($percent >= 50) {
                                    $color = '#f59e0b';
                                } elseif ($percent > 0) {
                                    $color = '#ef4444';
                                } else {
                                    $color = '#9ca3af';
                                }
                                
                                $statusMap = [
                                    'pending'=>'Received','in_progress'=>'Accepted',
                                    'hold' => 'On Hold',
                                    'ready_for_dispatch'=>'Ready For Dispatch',
                                    'dispatched'=>'Dispatched',
                                    'delivered'=>'Delivered',
                                    'final_check_done'=>'Final Check Done',
                                    'cancelled'=>'Cancelled'
                                ];
                                $statusClass = match ($deliveryStatus) {
                                    'delivered'=>'bg-success',
                                    'ready_for_dispatch'=>'bg-primary',
                                    'dispatched'=>'bg-primary',
                                    'final_check_done'=>'bg-primary',
                                    'in_progress'=>'bg-info',
                                    'cancelled'=>'bg-danger',
                                    'hold'=>'bg-dark',
                                    default=>'bg-warning'
                                };
                            @endphp
                            
                            <td>
                                <div style="background:#e5e7eb;border-radius:6px;height:18px;overflow:hidden;">
                                    <div style="width: {{ $percent }}%;background: {{ $color }};height:100%;text-align:center;font-size:11px;color:#fff;font-weight:600;line-height:18px;">
                                        {{ $percent }}%
                                    </div>
                                </div>
                            </td>
                            
                             <td>
                        {{-- Always show status badge --}}
                        <span class="badge {{ $statusClass }}">
                            {{ $statusMap[$deliveryStatus] ?? 'Pending' }}
                        </span>

                   
                           @php
                                $user = Auth::guard('admin')->user();
                            @endphp

                            @if($user->role_id == 1 || hasPermission('logistics.hold.manage'))

                                {{-- If Pending → Allow Put On Hold --}}
                                @if($deliveryStatus === 'pending')
                                    <div class="mt-1">
                                        <select class="form-control logistics-status"
                                                data-order-id="{{ $order->id }}"
                                                style="min-width: 140px;">
                                            <option value="">Change Status</option>
                                            <option value="hold">Put On Hold</option>
                                        </select>
                                    </div>
                                @endif

                                {{-- If Hold → Allow Remove Hold --}}
                                @if($deliveryStatus === 'hold')
                                    <div class="mt-1">
                                        <select class="form-control logistics-status"
                                                data-order-id="{{ $order->id }}"
                                                style="min-width: 140px;">
                                            <option value="">Change Status</option>
                                            <option value="pending">Remove Hold</option>
                                        </select>
                                    </div>
                                @endif

                            @endif
                    </td>
                    
                            <!--<td><span class="badge {{ $statusClass }}">{{ $statusMap[$deliveryStatus] ?? 'Pending' }}</span></td>-->
                            
                            @php
                                $picked = $order->pickList->status ?? 'PENDING';
                            @endphp
                            <td>
                                <span class="badge {{ $picked === 'PICKED' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $picked === 'PICKED' ? 'Completed' : 'Pending' }}
                                </span>
                            </td>
                            
                            <td data-order="{{ $order->logistics->dispatched_rack ?? 0 }}">
                                <input class="form-control rack-no" value="{{ $order->logistics->dispatched_rack ?? '-' }}" readonly>
                            </td>
                            
                            <td data-order="{{ $order->logistics->number_of_boxes ?? 0 }}">
                                <input class="form-control no-of-box" value="{{ $order->logistics->number_of_boxes ?? '-' }}" readonly>
                            </td>
                            
                            <td data-order="{{ optional($order->logistic)->delivery_priority ?? '' }}">
                                <select class="form-control delivery-priority">
                                    <option value="">Priority</option>
                                    @foreach(['P1','P2','P3','P4','P5','P6','P7','P8','P9','P10'] as $p)
                                        <option value="{{ $p }}" {{ optional($order->logistic)->delivery_priority === $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <td data-order="{{ optional($order->logistic)->mode_of_delivery_id ?? '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <select class="form-control mode-select" data-selected="{{ $order->logistic->mode_of_delivery_id ?? '' }}">
                                            <option value="">Select Mode</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-mode-btn" title="Delete Mode" {{ empty($order->logistic->mode_of_delivery_id) ? 'disabled' : '' }}>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            
                            <td>₹ {{ number_format($order->total_discount_value, 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- COMPLETED TAB -->
    <div class="tab-pane fade" id="completed" role="tabpanel">
        
        <div class="table-responsive">
            <table class="table table-striped all-package theme-table" id="pick_list_completed">
                <thead class="b-shadow">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Outlet Name</th>
                        <!--<th>Delivery Location</th>-->
                        <th>Delivery Selected</th>
                        <th>Delivery Date</th>
                        <th>Fullfilment %</th>
                        <th>Order Status</th>
                        <th>Picked Status</th>
                        <th>Rack No</th>
                        <th>No of Box</th>
                        <th>Delivery Priority</th>
                        <th>Mode of Delivery</th>
                        <th>Invoice Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $index => $order)
                        @php
                            $deliveryStatus = optional($order->latestDelivery)->delivery_status ?? 'pending';
                        @endphp
                        
                        @if($deliveryStatus === 'delivered')
                        <tr data-order-id="{{ $order->id }}" data-status="{{ $deliveryStatus }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->outlet->outlet_name ?? '-' }}</td>
                            <!--<td>{{ $order->shipping_address }}</td>-->
                            <td>
                                @if(!empty($order->delivery_slot_type))
                                    {{ strtoupper($order->delivery_slot_type) }}
                                @else
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                                @endif
                            </td>
                            <td>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d-m-Y') : '-' }}</td>
                            
                            @php
                                $picked  = $order->picked_qty ?? 0;
                                $ordered = $order->ordered_qty ?? 0;
                                $percent = $ordered > 0 ? round(($picked / $ordered) * 100, 2) : 0;
                                $color = '#16a34a';
                                
                                $statusMap = ['delivered'=>'Delivered'];
                                $statusClass = 'bg-success';
                            @endphp
                            
                            <td>
                                <div style="background:#e5e7eb;border-radius:6px;height:18px;overflow:hidden;">
                                    <div style="width: {{ $percent }}%;background: {{ $color }};height:100%;text-align:center;font-size:11px;color:#fff;font-weight:600;line-height:18px;">
                                        {{ $percent }}%
                                    </div>
                                </div>
                            </td>
                            
                            <td><span class="badge {{ $statusClass }}">Delivered</span></td>
                            
                            @php
                                $picked = $order->pickList->status ?? 'PENDING';
                            @endphp
                            <td>
                                <span class="badge {{ $picked === 'PICKED' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $picked === 'PICKED' ? 'Completed' : 'Pending' }}
                                </span>
                            </td>
                            
                            <td data-order="{{ $order->logistics->dispatched_rack ?? 0 }}">
                                <input class="form-control rack-no" value="{{ $order->logistics->dispatched_rack ?? '-' }}" readonly>
                            </td>
                            
                            <td data-order="{{ $order->logistics->number_of_boxes ?? 0 }}">
                                <input class="form-control no-of-box" value="{{ $order->logistics->number_of_boxes ?? '-' }}" readonly>
                            </td>
                            
                            <td data-order="{{ optional($order->logistic)->delivery_priority ?? '' }}">
                                <select class="form-control delivery-priority" disabled>
                                    <option value="">Priority</option>
                                    @foreach(['P1','P2','P3','P4','P5','P6','P7','P8','P9','P10'] as $p)
                                        <option value="{{ $p }}" {{ optional($order->logistic)->delivery_priority === $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <td data-order="{{ optional($order->logistic)->mode_of_delivery_id ?? '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <select class="form-control mode-select" data-selected="{{ $order->logistic->mode_of_delivery_id ?? '' }}" disabled>
                                            <option value="">Select Mode</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-mode-btn" title="Delete Mode" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            
                            <td>₹ {{ number_format($order->total_discount_value, 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>



<!-- Modal -->
<div class="modal fade" id="modeModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"><h5>Add Mode of Delivery</h5></div>
<div class="modal-body">
<input type="text" id="newModeName" class="form-control" placeholder="Ex: Bike-3">
</div>
<div class="modal-footer">
<button class="btn btn-primary" id="saveModeBtn">Save</button>
</div>
</div>
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

<script>
let currentSelect = null;

/* ---------- Load Modes ---------- */
function loadDeliveryModes() {
    console.log('--- loadDeliveryModes called ---');

    $.get("{{ route('delivery.modes.list') }}", function (data) {

        // Build a quick lookup of valid IDs
        const validModeIds = data.map(m => String(m.id));

        $('.mode-select').each(function (index) {

            let bladeValue = $(this).data('selected');
            let liveValue  = $(this).val();

            console.log('Row:', index + 1);
            console.log('  bladeValue:', bladeValue);
            console.log('  liveValue:', liveValue);

            let currentValue = liveValue || bladeValue || '';

            // 🔥 CRITICAL FIX:
            // If DB value is NOT in master list → invalidate it
            if (currentValue && !validModeIds.includes(String(currentValue))) {
                console.warn(
                    '  Invalid mode detected, resetting:',
                    currentValue
                );
                currentValue = '';
            }

            console.log('  final currentValue used:', currentValue);

            let options = '';

            // Explicit placeholder selection
            if (!currentValue) {
                options += '<option value="" selected>Select Mode</option>';
            } else {
                options += '<option value="">Select Mode</option>';
            }

            options += '<option value="new">+ Add New Mode</option>';

            data.forEach(function (mode) {
                options += `<option value="${mode.id}" ${
                    String(mode.id) === String(currentValue) ? 'selected' : ''
                }>${mode.name}</option>`;
            });

            $(this).html(options).val(currentValue);

            console.log('  DOM value after render:', $(this).val());

            let row = $(this).closest('tr');
            row.find('.delete-mode-btn').prop('disabled', !currentValue);
        });

        console.log('--- loadDeliveryModes finished ---');
    });
}


loadDeliveryModes();

/* ---------- Mode Change ---------- */
$(document).on('change','.mode-select',function(){
    let val = $(this).val();
    let row = $(this).closest('tr');

    row.find('.delete-mode-btn').prop('disabled', !val);

    if(val === 'new'){
        currentSelect = $(this);
        new bootstrap.Modal('#modeModal').show();
        return;
    }

    saveSingleField(row.data('order-id'),'mode_of_delivery_id',val);
});

/* ---------- Save New Mode ---------- */
$('#saveModeBtn').click(function(){
    let name = $('#newModeName').val().trim();
    if(!name) return alert('Mode name required');

    $.post("{{ route('delivery.modes.store') }}",
    {_token:"{{ csrf_token() }}",name},
    function(res){
        $('.mode-select').append(`<option value="${res.id}">${res.name}</option>`);
        currentSelect.val(res.id);
        saveSingleField(currentSelect.closest('tr').data('order-id'),'mode_of_delivery_id',res.id);
        bootstrap.Modal.getInstance('#modeModal').hide();
        $('#newModeName').val('');
    });
});

/* ---------- Delete Mode ---------- */
$(document).on('click', '.delete-mode-btn', function () {
    let row = $(this).closest('tr');
    let modeId = row.find('.mode-select').val();

    if (!modeId) return;

    if (!confirm('Delete this delivery mode?')) return;

    $.post("{{ route('delivery.modes.delete') }}", {
        _token: "{{ csrf_token() }}",
        id: modeId
    }, function (res) {

        // Clear ONLY this row
        row.find('.mode-select').val('');
        row.find('.delete-mode-btn').prop('disabled', true);

        // Reload options WITHOUT breaking other rows
        loadDeliveryModes();

    }).fail(err => alert(err.responseJSON.message));
});


/* ---------- Save Priority ---------- */
$(document).on('change','.delivery-priority',function(){
    saveSingleField($(this).closest('tr').data('order-id'),'delivery_priority',$(this).val());
});

/* ---------- Common Save ---------- */
function saveSingleField(orderId,field,value){
    $.post("{{ route('order.logistics.store.single') }}",
    {_token:"{{ csrf_token() }}",order_id:orderId,field,value});
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Status filter for In Process tab only
    const buttons = document.querySelectorAll(".status-filter[data-tab='inprocess']");
    const rows = document.querySelectorAll("#pick_list_inprocess tbody tr");
    
    let currentStatus = 'all';

    // Function to apply filter
    function applyFilter() {
        rows.forEach(row => {
            const rowStatus = row.dataset.status;

            if (currentStatus === "all" || rowStatus === currentStatus) {
                row.classList.remove('d-none');
                row.style.display = "table-row";
            } else {
                row.classList.add('d-none');
                row.style.display = "none";
            }
        });
    }

    // Status filter buttons
    buttons.forEach(btn => {
        btn.addEventListener("click", function () {
            currentStatus = this.dataset.status;

            // active highlight
            buttons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            applyFilter();
        });
    });

    // Monitor table changes (for sorting/pagination)
    const table = document.querySelector("#pick_list_inprocess");
    if (table) {
        // Use MutationObserver to detect when table rows are reordered
        const observer = new MutationObserver(function(mutations) {
            applyFilter();
        });

        observer.observe(table.querySelector('tbody'), {
            childList: true,
            subtree: false
        });
    }

    // If using DataTables, hook into its draw event
    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#pick_list_inprocess')) {
        $('#pick_list_inprocess').on('draw.dt', function() {
            applyFilter();
        });
    }

    // Initial apply
    applyFilter();
    
       $(document).on('change', '.logistics-status', function () {
    let orderId = $(this).data('order-id');
    let status = $(this).val();

    if (!status) return;

    $.post("{{ route('order.logistics.update.status') }}", {
        _token: "{{ csrf_token() }}",
        order_id: orderId,
        status: status
    }, function () {
        location.reload();
    });
});


});


</script>
<script>
$(document).ready(function () {

    
    $('#pick_list_inprocess').DataTable({
        paging: false,
        searching: false,
        ordering: true, 
        info: true,
        autoWidth: false,
       
        columnDefs: [
            { orderable: false, targets: [0] } 
        ]
    });

    
    $('#pick_list_completed').DataTable({
        paging: false,
        searching: false,
        ordering: true, 
        info: true,
        autoWidth: false,
      
        columnDefs: [
            { orderable: false, targets: [0] }
        ]
    });

});
</script>



@endsection



