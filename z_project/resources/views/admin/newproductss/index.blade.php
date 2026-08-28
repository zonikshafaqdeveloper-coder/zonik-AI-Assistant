@extends('admin.layouts.appnew')
@section('content')
<style>
    .table-responsive {
    overflow-y: hidden;
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


                                        <div class="title-header option-title">
                                            <h4 class="card-title"></h4>
                                            <div class="d-flex justify-content-between">
                                                <h3 class="card-title">Product Management</h3>
                                                <a href="{{ route('productss.create') }}" type="button"
                                                    class="btn btn-primary">Add Product</a>
                                            </div>

                                            <div class="row display:flex align-items-center enquire-box w-500">
                                                <div class="col">
                                                    {{-- {{route('users.uploadproducts')}} --}}
                                                    <form action="{{ route('productss.import') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class=" w-350">
                                                            <div class="form-group row">
                                                                <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                                                                    <span style="color:red;">*</span>File
                                                                    Input(Datasheet)</label>
                                                                    <input type="file"
                                                                        class="form-control form-control-user @error('file') is-invalid @enderror"
                                                                        id="exampleFile" name="file"
                                                                        value="{{ old('file') }}">

                                                                    @error('file')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <!-- <button type="submit"
                                                                        class="btn btn-success btn-user float-right mb-3">Import
                                                                        Product</button> -->
                                                            <!-- <a class="btn btn-primary float-right mr-3 mb-3" href="">Cancel</a> -->
                                                            <div class="row d-flex">
                                                                <div class="col-md-6">
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-user float-right mb-3">Import
                                                                        Product</button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <a href="{{ route('productss.export') }}"
                                                                        class="btn btn-sm btn-success m-19">
                                                                        <i class="fas fa-check"></i> Export To Excel
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </form>

                                                </div>
                                                <!-- <div class="col-md-4">
                                                            <a href="{{ route('productss.export') }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Export To Excel
                                                            </a>
                                                        </div> -->
                                            </div>



                                            <form class="d-inline-flex">
                                                <a href="{{ route('productss.create') }}"
                                                    class="align-items-center btn btn-theme d-flex">
                                                    <i data-feather="plus-square"></i>Add Product
                                                </a>
                                            </form>


                                        </div>

                                        <div class="table-responsive category-table">
                                            <div>
                                                <table class="table all-package theme-table table-bordered"
                                                    id="productsdata">
                                                    <thead class="b-shadow">
                                                        <tr class="text-capitalize">
                                                            <th>Sr.</th>
                                                            <th class="text-center">Product Image</th>
                                                            <th class="text-center">Category Name</th>
                                                            <th class="text-center">Subcategory Name</th>
                                                            <th class="text-center">Product Name</th>
                                                            <th class="text-center">Unit</th>
                                                            <th class="text-center">Pack (Qty.)</th>
                                                            <th class="text-center">Peices Per Pack</th>
                                                            <th class="text-center">Carton Size</th>
                                                            <th class="text-center">MRP</th>
                                                            <th class="text-center">Cost Per Item</th>
                                                            <th class="text-center">Total GST (%)</th>
                                                            <th class="text-center">Total Cost with Tax</th>
                                                            <th class="text-center">sgst</th>
                                                            <th class="text-center">cgst</th>
                                                            <th class="text-center">igst</th>
                                                            <th class="text-center">cess</th>
                                                            <th class="text-center">Loose Sale Price (Basic)</th>
                                                            <th class="text-center">Carton Sale Price (Basic)</th>
                                                            <th class="text-center">Product Weight (Grams)</th>
                                                            <th class="text-center">Supplier Traced</th>
                                                            <th class="text-center">Carton Discount (Basic)</th>
                                                            <th class="text-center">Loose Discount (Basic)</th>
                                                            <th class="text-center">brand</th>
                                                            <th class="text-center">type</th>
                                                            <th class="text-center">tag</th>
                                                            <th class="text-center">Last updated Price</th>
                                                            <th class="text-center">status</th>
                                                            <th class="text-center">Action</th>

                                                        </tr>
                                                    </thead>

                                                 
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                
                <script>
 $(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This product will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '/products/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {

                    if (res.success) {

                        $('#productsdata').DataTable().ajax.reload();

                        Swal.fire('Deleted!', res.message, 'success');

                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Something went wrong', 'error');
                }
            });

        }

    });
});
                </script>
                
                @endsection

                <!-- All User Table Ends-->
