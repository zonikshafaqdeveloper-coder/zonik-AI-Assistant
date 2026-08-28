@extends('admin.layouts.appnew')
@section('content')

<style>
.select2-container .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
    opacity: 1 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear{
    display:none;
}
</style>
<div class="page-body">
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper ">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add homepage banners</h4>
                                <p class="card-description">
                                    <!-- Add Category -->
                                </p>
                                <form method="POST" action="{{ route('banners.store') }}" enctype="multipart/form-data">
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
                                        <label for="banner_name" class="col-sm-3 col-form-label">Banner Name <span class="text-danger">*</span></label>
                                        <div class="col-sm-6">
                                            <input id="banner_name"
                                                class="form-control @error('banner_name') is-invalid @enderror"
                                                type="text" name="banner_name" value="{{ old('banner_name') }}"
                                                placeholder="Banner Name">
                                            @error('banner_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-sm-3 col-form-label">Banner Image(1140px X 350px) <span class="text-danger">*</span> </label>
                                        <div class="col-sm-6">
                                            <input id="image" type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                placeholder="Image">
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="image" class="col-sm-3 col-form-label">Category <span class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <select id="category_id" class="form-control select2" name="category_id">
                                                <option value="">Select Categories</option>
                                                @foreach ($categories as $categories)
                                                <option value="{{ $categories->id }}">
                                                    {{ $categories->category_name }}
                                                </option>
                                                @endforeach
                                            </select>

                                            @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="image" class="col-sm-3 col-form-label">Sub Category <span class="text-danger">*</span></label>
                                        <div class="col-md-3    ">
                                            <select class="js-example-basic-single w-100 form-select select2"
                                                name="subcategory_id" id="subcategory_id">
                                                <option value="">Select Sub categories</option>
                                                @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">
                                                    {{ $subcategory->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
                                    <a href="{{ route('banners.index') }}" class="btn btn-light">Cancel</a>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>        
<script>
$(document).ready(function () {
    $('.select2').select2({
        width: '100%',
        allowClear: true
    });
});
</script>

        @endsection