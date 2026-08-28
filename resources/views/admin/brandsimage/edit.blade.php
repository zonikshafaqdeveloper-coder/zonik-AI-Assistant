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
                                <h4 class="card-title">Edit Festival and offers image</h4>
                                <p class="card-description">
                                </p>
                                <form class="forms-sample" action="{{ route('brandsimage.update', $brandsimage->id) }}"
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
                                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Banner
                                            Name</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="brand_name"
                                                value="{{  $brandsimage->brand_name }}" placeholder="Banner Name">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="exampleInputMobile" class="col-sm-3 col-form-label">Category
                                            Name</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="festival_and_offer"
                                                id="festival_and_offer">
                                                @foreach ($festivalandofffers as $festival)
                                                <option value="{{ $festival->id }}"
                                                    {{ $festival->id ==  $brandsimage->category_id ? 'selected' : '' }}>
                                                    {{ $festival->festival_offier_name  }}
                                                    {{ $festival->festival_offier_name2  }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row ">
                                        <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Banner
                                            Image(Same size of each category)</label>
                                        <div class="col-sm-9 ">
                                            <input class="form-control" type="file" name="image" id="image"
                                                accept="image/*" placeholder="Product Image">
                                            <img src="/uploads/{{  $brandsimage->brand_image }}" width="300px"
                                                class="mt-5">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="exampleInputMobile" class="col-sm-3 col-form-label">Category
                                            Name</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="category_id" id="category_id">
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id ==  $brandsimage->category_id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="search_by" class="col-sm-3 col-form-label">Search By</label>
                                        <div class="col-md-9">
                                            <select class="js-example-basic-single w-100 form-select" name="search_by"
                                                id="search_by">
                                                <option value="category"
                                                {{ $brandsimage->search_by == 'category' ? 'selected' : '' }}>Category
                                            </option>
                                            <option value="brand"
                                                {{ $brandsimage->search_by == 'brand' ? 'selected' : '' }}>Brand
                                            </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="exampleInputMobile" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="status" id="status">
                                                <option value="Active"
                                                    {{ $brandsimage->status == 'Active' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="inActive"
                                                    {{ $brandsimage->status == 'inActive' ? 'selected' : '' }}>Inactive
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
