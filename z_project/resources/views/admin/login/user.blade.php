@extends('admin.layouts.app')
 @section('content')

<div class="page-body">

<!-- New Product Add Start -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-sm-8 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header-2">
                                <h5>Add User</h5>
                            </div>

                          
                            <!-- <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"> -->
                            <form class="theme-form theme-form-2 mega-form" action="{{ route('admin.store') }}" method="POST" >
                            {{ csrf_field() }}

                           @if(session('success'))
                           <div class="alert alert-success">
                           {{ session('success') }}
                           </div>
                           @endif

                            <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Name</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="name"
                                         placeholder="User name">
                                    </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Email</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="email"
                                         placeholder="User email">
                                    </div>
                            </div>


                            <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Password</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="password"
                                         placeholder="User password">
                                    </div>
                            </div>


                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Role</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="role"
                                         placeholder="User role">
                                    </div>
                            </div>



                                <button type="submit" name="submit" class="btn btn-primary ms-auto mt-4">Save</button>
                             
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- New Product Add End -->
@endsection