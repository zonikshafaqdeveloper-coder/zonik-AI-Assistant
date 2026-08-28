@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper ">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add BrandsImage</h4>
                                <p class="card-description">
                                    <!-- Add Category -->
                                </p>
                                <form class="forms-sample" action="{{ route('festivalandoffers.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="form-group row">
                                        <label for="festival_offier_name" class="col-sm-3 col-form-label">Festival and
                                            Offers First Name</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="festival_offier_name"
                                                value="{{ old('festival_offier_name') }}"
                                                placeholder="Festival and Offers First Name">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="status" id="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
                                    <a href="{{ route('festivalandoffers.index') }}" class="btn btn-light">Cancel</a>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        @endsection