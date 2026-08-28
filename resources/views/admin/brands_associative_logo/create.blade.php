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
                                    <h4 class="card-title">Add Brand Logo</h4>
                                    <p class="card-description">
                                        <!-- Add Category -->
                                    </p>
                                    <form class="forms-sample" action="{{ route('brandassoc.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}

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
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Brand
                                                Image(500px X 500px)</label>
                                            <div class="col-sm-9">
                                                <input type="file" name="image" class="form-control"
                                                    placeholder="image">
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
