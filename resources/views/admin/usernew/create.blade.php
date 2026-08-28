    @extends('admin.layouts.appnew')
@section('content')
    <div class="page-body">
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add User</h4>
                                    <p class="card-description">
                                        <!-- Add Category -->
                                    </p>

                                    <!-- <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"> -->
                                    <form class="theme-form theme-form-2 mega-form" action="{{ route('users.store') }}"
                                        method="POST">
                                        {{ csrf_field() }}

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <!-- <strong>Whoops!</strong> There were some problems with your input.<br><br> -->
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
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
        <select class="form-select" name="role_id" required>
            <option value="">Select Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
</div>




                                        <button type="submit" name="submit"
                                            class="btn btn-primary ms-auto mt-4">Save</button>

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
