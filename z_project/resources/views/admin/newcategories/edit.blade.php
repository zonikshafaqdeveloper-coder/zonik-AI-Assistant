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
                  <h4 class="card-title">Add Category</h4>
                  <p class="card-description">
                   <!-- Add Category -->
                  </p>
                  <form class="forms-sample" action="{{ route('categoriess.update', $category->id) }}" method="POST" enctype="multipart/form-data">
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
                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Category Name</label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="category"
                                                    value="{{ $category->category_name }}">
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Category Image(500px X 500px)</label>
                      <div class="col-sm-9">
                      <input class="form-control" type="file" name="image" id="image"
                                                    accept="image/*" placeholder="Category Image">
                                                <img src="/uploads/{{ $category->image }}" style="
                                                margin-top: 14px;
                                            " width="300px">
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