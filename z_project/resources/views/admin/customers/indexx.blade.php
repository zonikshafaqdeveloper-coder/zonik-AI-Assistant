@extends('admin.layouts.appnew')
@section('content')
<style>
    .disabled {
    pointer-events: none;
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
<div class="page-body">

        <body>

            <!-- partial -->
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
                                        @if (request('type', 'group') === 'outlet')

                                          <h4 class="card-title">Outlets Management</h4>

                                           @else
                                          <h4 class="card-title">Customer Group Management</h4>
                                           
                                           @endif


 <!--<a href="{{ route('customer.indexx') }}" class="btn mx-1 btn-primary">Outlet</a>-->
 <!--<a href="{{ route('customer.indexx1') }}" class="btn mx-1 btn-primary">Group</a>-->


                                        <p class="card-description">
                                            <!-- Add class <code>.table-striped</code> -->
                                        </p>
                                        <div class="table-responsive">
                                            
                                            
                                            <form method="GET" action="">
                                                <div class="row mb-3">
                                                    <div class="col-md-3 align-items-end">
                                                        <label>Select Customer Type</label>
                                                        <select name="type" class="form-control" onchange="this.form.submit()">
                                                            
                                                            <option value="group" 
                                                                {{ request('type', 'group') == 'group' ? 'selected' : '' }}>
                                                                Group
                                                            </option>
                        
                                                            <option value="outlet" 
                                                                {{ request('type') == 'outlet' ? 'selected' : '' }}>
                                                                Outlet
                                                            </option>
                        
                                                            <!--<option value="all" -->
                                                            <!--    {{ request('type') == 'all' ? 'selected' : '' }}>-->
                                                            <!--    All-->
                                                            <!--</option>-->
                                                            
                                                        </select>
                                                    </div>
                                                    
                                                     <!--<div class="col-md-3 ms-auto">-->
                                                         
                                                         <div class="col-md-4 ms-auto gap-2">

                                                         
                                                           @if (request('type', 'group') === 'outlet')
                                                            <a href="{{ route('customer.export.outlets') }}"
                                                            class="btn btn-warning">
                                                                 Export Outlet Payment
                                                            </a>
                                                        @endif
                                                        
                                                        <a href="{{ route('customer.create') }}"
                                                        class="btn btn-primary w-50">
                                                            + Add Group Customer
                                                        </a>
                                                    </div>
                                                    
                                                </div>
                                            </form>
                    
                                            <table class="table table-striped">
                                                <table class="table all-package theme-table" id="customer">


                                                    <thead class="b-shadow">
                                                        <tr>
                                                            <th class="text-center">Sr.</th>
                                                          @if (request('type', 'group') === 'outlet')
                                                                <th class="text-center">Outlet User</th>
                                                                <th class="text-center">Outlet Name</th>
                                                                 <th class="text-center">Outlet Number</th>
                                                                  <th class="text-center">Outlet Company Name</th>
                                                            @else
                                                                <th class="text-center">Customer Name</th>
                                                                 <th class="text-center">Company Name</th>
                                                                 <th class="text-center">Customer Number</th>
                                                                 <th class="text-center">Status</th>
                                                            @endif
                                                            <th class="text-center">Email</th>
                                                            <!--<th class="text-center">Customer Type</th>-->
                                                            @if (request('type', 'group') === 'outlet')
                                                            <th class="text-center">Credit Status</th>
                                                            <th class="text-center">Credit Limit</th>
                                                            <th class="text-center">Due Max Days</th>
                                                            <th class="text-center">Status</th>
                                                              @endif
                                                            <th class="text-center">Registerd at</th> 
                                                           <th class="text-center" @if(request('type', 'group') !== 'group') style="width:23rem;" @else style="width:23rem;" @endif >
                                                                Action
                                                           </th>
                                                        
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($customers as $key => $customer)
                                                            <tr>
                                                                <td><b>{{ ++$key }}</b></td>
                                                                <td class="text-center">{{ $customer?->name }}</td>
                                                                <td class="text-center">{{ $customer?->outlet_name }}</td>
                                                                <td class="text-center">{{ $customer?->mobile_number }}</td>
                                                                 @if ($customer && $customer->type === 'group')
                                                                <td class="text-center">{{ $customer?->status }}</td>
                                                                @endif
                                                                  @if ($customer && $customer->type === 'outlet')
                                                                <td class="text-center">
                                                                    {{ $customer->outlet?->outlet_name ?? 'N/A' }}
                                                                </td>
                                                                  @endif
                                                                <td class="text-center">{{ $customer?->email }}</td>
                                                                <!--<td class="text-center" style="text-transform: capitalize">{{ $customer?->type }}</td>-->
                                                                  @if ($customer && $customer->type === 'outlet')
                                                                <td class="text-center" style="text-transform: capitalize">{{ $customer?->credit_status }}</td>
                                                                <td class="text-center" style="text-transform: capitalize">{{ $customer?->credit_limit }}</td>
                                                                <td class="text-center" style="text-transform: capitalize">{{ $customer?->due_days_limit }}</td>
                                                                  <td>
                                                                @if(optional($customer)->verified_status === 'verified')
                                                                    <span class="badge bg-success">Active</span>
                                                                @elseif(optional($customer)->verified_status === 'unverified')
                                                                    <span class="badge bg-danger">Inactive</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Not Set</span>
                                                                @endif
                                                            </td>
                                                                 @endif
                                                                <td class="text-center">{{ optional($customer->created_at)->format('Y-m-d') }}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center gap-2 flex-wrap">

                                                                        {{-- OUTLET CUSTOMER --}}
                                                                        @if ($customer && $customer->type === 'outlet')

                                                                            <a href="{{ url('edit-customer/' . $customer->id) }}"
                                                                            class="btn btn-primary">
                                                                                Payment Terms
                                                                            </a>

                                                                            <a href="{{ url('edit-outlet/' . $customer->id) }}"
                                                                            class="btn btn-secondary">
                                                                                Edit Outlet
                                                                            </a>

                                                                        {{-- NON-OUTLET CUSTOMER --}}
                                                                        @else

                                                                            <a href="{{ url('outletadd-customer/' . $customer->id) }}"
                                                                            class="btn btn-secondary">
                                                                                Add Outlet
                                                                            </a>
                                                                            
                                                                             <a  href="{{ route('customer.edit', $customer->id) }}"
                                                                            class="btn btn-secondary">
                                                                                Edit Customer
                                                                            </a>

                                                                        @endif

                                                                        {{-- DELETE BUTTON (ALWAYS SHOWN) --}}
                                                                        <form method="POST"
                                                                    action="{{ url('delete-customer/' . $customer->id) }}"
                                                                    class="m-0 delete-form">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="button"
                                                                            class="btn btn-danger"
                                                                            onclick="confirmDelete(this)">
                                                                        Delete
                                                                    </button>
                                                                </form>


                                                                    </div>
                                                                </td>


                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(button) {
    const form = button.closest('form');

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>

            @endsection

            <!-- All customer Table Ends-->
