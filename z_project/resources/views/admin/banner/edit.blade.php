@extends('admin.layouts.appnew')
@section('content')

<style>
.select2-container .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
}
</style>

<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Edit Homepage Banner</h4>

                                <form class="forms-sample"
                                      action="{{ route('banners.update', $banner->id) }}"
                                      method="POST"
                                      enctype="multipart/form-data">

                                    @csrf
                                    @method('PUT')

                                    {{-- Banner Name --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Banner Name <span class="text-danger">*</span></label>
                                        <div class="col-sm-6">
                                            <input type="text"
                                                   name="banner_name"
                                                   class="form-control"
                                                   value="{{ old('banner_name', $banner->banner_name) }}">
                                        </div>
                                    </div>

                                    {{-- Image Upload --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Banner Image</label>
                                        <div class="col-sm-6">
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                    </div>

                                    {{-- Existing Image Preview --}}
                                    @if($banner->banner_image)
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Current Image</label>
                                        <div class="col-sm-6">
                                            <img src="{{ asset('uploads/'.$banner->banner_image) }}"
                                                 width="300"
                                                 class="img-thumbnail mt-2">
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Category --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Category <span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <select name="category_id" class="form-control select2">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $banner->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Subcategory --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Sub Category <span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <select name="subcategory_id" class="form-control select2">
                                                <option value="">Select Sub Category</option>
                                                @foreach($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}"
                                                        {{ old('subcategory_id', $banner->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                                        {{ $subcategory->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('banners.index') }}" class="btn btn-light">Cancel</a>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('.select2').select2({
        width: '100%'
    });
});
</script>

@endsection
