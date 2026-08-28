<!-- partial:partials/_footer.html -->
<footer class="footer">
    <div class="footer-wrap">
    <div class="d-sm-flex justify-content-center text-dark">
        <strong>
                    © <script>document.write(new Date().getFullYear());</script>
                </strong>&nbsp;
            <strong class="text-dark">Infigourment Networks Private Limited | All Rights Reserved.</strong>
        </span>
    </div>
    </div>
</footer>

<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- base:js -->
<script src="{{ asset('vendors/base/vendor.bundle.base.js')}}"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="{{ asset('vendors/adminnew/js/template.js') }}?v={{ time() }}"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<!-- End plugin js for this page -->
<script src="{{ asset('vendors/chart.js/Chart.min.js')}}"></script>
<script src="{{ asset('vendors/progressbar.js/progressbar.min.js')}}"></script>
<script src="{{ asset('vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js')}}"></script>
<script src="{{ asset('vendors/justgage/raphael-2.1.4.min.js')}}"></script>
<script src="{{ asset('vendors/justgage/justgage.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
<script src="{{ asset('/js/select2.min.js') }}"></script>
<script src="{{ asset('vendors/adminnew/js/jquery.cookie.js') }}"></script>
<!-- Custom js for this page-->
<script src="{{ asset('vendors/adminnew/js/dashboard.js')}}"></script>
<!-- End custom js for this page-->


<!--{{--  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>  --}}-->
<!--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>-->
<!--<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>-->
<!--<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>-->


<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

<!-- PDF Export (ORDER MATTERS) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<script>
$(document).ready(function() {
    // Common DataTable initialization for multiple tables
    
     // Count for payments only
    let total = {{ $payments->count() }};

    // Payments table settings
  $(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#pick_list')) {
        $('#pick_list').DataTable().destroy();
    }

    if ($.fn.DataTable.isDataTable('#payments-table')) {
        $('#payments-table').DataTable().destroy();
    }

    if ($.fn.DataTable.isDataTable('#stock_table')) {
        $('#stock_table').DataTable().destroy();
    }
   
    if ($.fn.DataTable.isDataTable('#delivery_table')) {
        $('#delivery_table').DataTable().destroy();
    }

    $('#payments-table, #stock_table, #pick_list, #delivery_table, #nonRunningTable, #order_table' ).DataTable({
        dom: 'Blfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        columnDefs: [{
            targets: 0,
            orderable: false
        }],
        order: [],
        paging: true,
        pageLength: 100,
        lengthMenu: [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]]
    });
    
  $('.customer-sales-table').DataTable({
    dom: 'Blfrtip',
    buttons: ['copy', 'excel', 'csv', 'pdf'],
    columnDefs: [
        { targets: 0, orderable: false },
        { targets: [4, 5, 6, 7, 8, 9, 10], type: 'num' } 
    ],
    order: [],
    paging: true,
    pageLength: 100,
    lengthMenu: [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]]
});

});


    const commonTableSettings = {
        dom: 'Blfrtip', // Specify the placement of the buttons
        buttons: ['copy', 'excel', 'csv', 'pdf'], // Add the buttons you want to display
        columnDefs: [{
            targets: 0, // The first column
            orderable: false // Disable sorting for the first column
        }],
        order: [] // No initial sorting
    };

    // Apply DataTable settings to multiple tables
    $('#category, #subcategory, .datatable, #pincode_list, #users, #customer, #enquiries, #customer_notification, #order_list, #admin_list, #enquiries_view, #productsdata, #stockTable, #vendorTable, #banner_list, #purchaseOrdersTable').DataTable(commonTableSettings);

    // Separate initialization for the order table if it has different settings
    // $('.order_table').DataTable({
    //     columnDefs: [{
    //         targets: 0, // The first column
    //         orderable: false // Disable sorting for the first column
    //     }],
    //     order: [] // No initial sorting
    // });

    // Event listener for edit button clicks
    $('.edit-btn').on('click', function() {
        var orderItemId = $(this).data('order-item-id');
        console.log(orderItemId); // Fixing the typo here
        var modalId = '#editModal' + orderItemId;
        $(modalId).modal('show');
    });
});


$(document).ready(function () {

    
    if ($.fn.DataTable.isDataTable('#modifyStockTable')) {
        $('#modifyStockTable').DataTable().destroy();
    }

    $('#modifyStockTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf']
    });

});


$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#stockadjustment')) {
        $('#stockadjustment').DataTable().destroy();
    }

    $('#stockadjustment').DataTable({

        dom:
            '<"row mb-2"<"col-12"B>>' +
            '<"row mb-2"<"col-12"l>>' +
            '<"row mb-2"<"col-12"f>>' +
            'rtip',

        buttons: ['copy', 'excel', 'csv', 'pdf'],

        columnDefs: [
            { targets: 0, orderable: false }
        ],

        order: [],
        pageLength: 10,

        language: {
            search: "",
            searchPlaceholder: "🔍 Search Stock Adjustment..."
        }

    });

});


$(document).ready(function () {

    // destroy if already exists
    if ($.fn.DataTable.isDataTable('#new_stock_table')) {
        $('#new_stock_table').DataTable().destroy();
    }

    $('#new_stock_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.stock-receivings.ledger') }}",

        dom: 'Blfrtip', // ✅ keeps same UI
        buttons: ['copy', 'excel', 'csv', 'pdf'], // ✅ export working

       columns: [
    { data: 'DT_RowIndex', searchable: false },

    { data: 'created_at', name: 'stock_movements.created_at' },

    { data: 'product_name', name: 'products.product_name' },

    { data: 'type', name: 'stock_movements.movement_type', searchable: true },

    { data: 'quantity', name: 'stock_movements.quantity' },

    { data: 'total_stock', name: 'total_stock', searchable: false },

    { data: 'unit_cost', name: 'stock_movements.unit_cost' },

    { data: 'reference', name: 'stock_movements.reference_id' },

   { data: 'party_name', name: 'party_name' },

    { data: 'remarks', name: 'stock_movements.remarks' }
],

        order: [],
        pageLength: 50,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100, "All"]],

        language: {
            search: "",
            searchPlaceholder: "🔍 Search Stock Ledger..."
        }
    });

});

$(document).ready(function () {

    // destroy if already exists
    if ($.fn.DataTable.isDataTable('#productsdata')) {
        $('#productsdata').DataTable().destroy();
    }

    $('#productsdata').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('productss.index') }}",

        dom: 'Blfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', orderable: false, searchable: false },
            { data: 'category_name' },
            { data: 'subcategory_name' },
            { data: 'product_name' },
            { data: 'unit' },
            { data: 'product_quantity' },
            { data: 'peices_per_pack' },
            { data: 'carton_size' },
            { data: 'product_mrp' },
            { data: 'cost_per_item' },
            { data: 'gst' },
            { data: 'total_cost_with_tax' },
            { data: 'sgst' },
            { data: 'cgst' },
            { data: 'igst' },
            { data: 'cess' },
            { data: 'sale_price_loose_pcs' },
            { data: 'sale_price_carton' },
            { data: 'product_weight_grams' },
            { data: 'supplier' },
            { data: 'carton_discount_basic' },
            { data: 'loose_discount_basic' },
            { data: 'brand_name' },
            { data: 'type_name' },
            { data: 'tag_name' },
            { data: 'updated_at' },
            { data: 'status', orderable: false },
            { data: 'action', orderable: false, searchable: false }
        ],

        order: [],
        pageLength: 10, // ⚡ faster load
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100, "All"]],

        scrollX: true, // ✅ important for wide table

        language: {
            search: "",
            searchPlaceholder: "🔍 Search Products..."
        }
    });

});

// Function to update notification status
function updateNotification() {
    fetch('{{ route('notifications.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
        },
        body: JSON.stringify({ read: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('sales-notification').innerText = '(0)'; // Assuming all notifications are marked as read
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to update notification status
function updateUserNotification() {
    fetch('{{ route('newusernotifications.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
        },
        body: JSON.stringify({ read: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('newuser-notificaion').innerText = '(0)'; // Assuming all notifications are marked as read
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to open a modal by its ID
function openModal(modalId) {
    $('#' + modalId).modal('show');
}
</script>
