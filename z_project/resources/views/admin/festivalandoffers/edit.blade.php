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
                                <h4 class="card-title">Edit Brands image</h4>
                                <p class="card-description">
                                    <!-- Add Category -->
                                </p>
                                <form class="forms-sample"
                                    action="{{ route('festivalandoffers.update', $festivalandoffers->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
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
                                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Festival and
                                            Offers First Names </label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="festival_offier_name"
                                                value="{{  $festivalandoffers->festival_offier_name }}"
                                                placeholder="Banner Name">
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label for="exampleInputMobile" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="status" id="status">
                                                <option value="Active"
                                                    {{ $festivalandoffers->status == 'Active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="inActive"
                                                    {{ $festivalandoffers->status == 'inActive' ? 'selected' : '' }}>
                                                    Inactive
                                                </option>
                                            </select>
                                        </div>
                                    </div>



                                    <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
                                    <a href="{{ route('brandsimage.index') }}" class="btn btn-light">Cancel</a>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        @endsection