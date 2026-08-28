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
                                <h4 class="card-title">Add Festival and offers image</h4>
                                <p class="card-description">
                                    <!-- Add Category -->
                                </p>
                                <form method="POST" action="{{ route('brandsimage.store') }}"
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
                                        <label for="brand_name" class="col-sm-3 col-form-label">Brand Name</label>
                                        <div class="col-sm-9">
                                            <input id="brand_name"
                                                class="form-control @error('brand_name') is-invalid @enderror"
                                                type="text" name="brand_name" value="{{ old('brand_name') }}"
                                                placeholder="Brand Name">
                                            @error('brand_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="festivalandofffers" class="col-sm-3 col-form-label">Offer
                                            Name</label>
                                        <div class="col-md-9">
                                            <select class="js-example-basic-single w-100 form-select"
                                                name="festivalandofffers" id="festivalandofffers">
                                                <option value="">Select Festival</option>
                                                @foreach ($festivalandofffers as $festival)
                                                <option value="{{ $festival->id }}">
                                                    {{ $festival->festival_offier_name  }}
                                                    {{$festival->festival_offier_name2}}</option>
                                                @endforeach
                                            </select>
                                            @error('festivalandofffers')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="image" class="col-sm-3 col-form-label">Brand Image(Same size for each category)</label>
                                        <div class="col-sm-9">
                                            <input id="image" type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                placeholder="Image">
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label for="category_id" class="col-sm-3 col-form-label">Category</label>
                                        <div class="col-md-9">
                                            <select class="js-example-basic-single w-100 form-select" name="category_id"
                                                id="category_id">
                                                <option value="">Select Subcategory</option>
                                                @foreach ($categories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="search_by" class="col-sm-3 col-form-label">Search By</label>
                                        <div class="col-md-9">
                                            <select class="js-example-basic-single w-100 form-select" name="search_by"
                                                id="search_by">
                                                <option value="category">Category</option>
                                                <option value="brand">Brand</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-md-9">
                                            <select class="js-example-basic-single w-100 form-select" name="status"
                                                id="status">
                                                <option value="Active">Active</option>
                                                <option value="inActive">InActive</option>
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
