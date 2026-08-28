@extends('admin.layouts.appnew')
@section('content')
    <div class="page-body">
        <!-- partial -->
        <div class="container page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Category</h4>
                                    <p class="card-description">
                                        <!-- Add Category -->
                                    </p>
                                    <form class="forms-sample"
                                        action="{{ route('subcategoriess.update', $subcategory->id) }}" method="POST"
                                        enctype="multipart/form-data">
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

                                        <div class="form-group row">
                                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Subcategory
                                                Name</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="subcategory"
                                                    value="{{ $subcategory->name }}" placeholder="Category Name">
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Subcategory
                                                Image</label>
                                            <div class="col-sm-9 ">
                                                <input class="form-control" type="file" name="image" id="image"
                                                    accept="image/*" placeholder="Product Image">
                                                <img src="/uploads/{{ $subcategory->image }}" width="300px" class="mt-5">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Category
                                                Name</label>
                                            <div class="col-sm-9">

                                                <select class="form-control" name="category_id" id="category_id">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            @if ($category->id == $subcategory->category_id) selected @endif>
                                                            {{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
                                        <button class="btn btn-light">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
        @endsection
