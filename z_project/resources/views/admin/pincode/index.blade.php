@extends('admin.layouts.appnew')
@section('content')
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

                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Pincode Manage</h3>
                                        <a href="{{ route('pincode.createNew') }}" type="button"
                                            class="btn btn-primary">Add Pincode</a>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            {{-- {{route('users.uploadproducts')}} --}}
                                            <form action="{{ route('pincode.import') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                                                            <span style="color:red;">*</span>File
                                                            Input(Datasheet)</label>
                                                            <input type="file"
                                                                class="form-control form-control-user @error('file') is-invalid @enderror"
                                                                id="exampleFile" name="file" value="{{ old('file') }}">

                                                            @error('file')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="card-footer">
                                                    <button type="submit"
                                                        class="btn btn-success btn-user float-right mb-3">Import
                                                        Pincode</button>

                                                    <!-- <a class="btn btn-primary float-right mr-3 mb-3" href="">Cancel</a> -->
                                                </div>


                                            </form>
                                        </div>
                                    </div>



                                    <p class="card-description">
                                        <!-- Add class <code>.table-striped</code> -->
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <table class="table all-package theme-table" id="pincode_list">
                                                <thead class="b-shadow">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Zone Name</th>
                                                        <th>Pincode</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($pincode as $key => $pin)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $pin->zone?->zone_name }}</td>
                                                        <td>{{ $pin->pincode }}</td>
                                                        <td>
                                                            @if ($pin->status == 'Active')
                                                                <form method="POST" action="{{ route('pincode.statusUpdate', $pin->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="Inactive">
                                                                    <button type="submit" class="btn btn-secondary">Active</button>
                                                                </form>
                                                            @else
                                                                <form method="POST" action="{{ route('pincode.statusUpdate', $pin->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="Active">
                                                                    <button type="submit" class="btn btn-danger text-white">Inactive</button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('pincode.edit', $pin->id) }}" class="align-items-center btn btn-success category-btn">
                                                                <i class="ri-pencil-line"></i>Edit
                                                            </a>
                                                            <form method="POST" action="{{ url('delete-pincode/' . $pin->id) }}">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="align-items-center btn btn-danger d-flex">Delete</button>
                                                            </form>
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
            @endsection
