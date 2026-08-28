@extends('admin.layouts.app')
 @section('content')

<div class="page-body">

<!-- New Product Add Start -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-sm-8 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header-2">
                                <h5>Category Information</h5>
                            </div>

                            <form class="theme-form theme-form-2 mega-form">
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Category Name</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="category"
                                            placeholder="Category Name">
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Category Slug
                                        Name</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text"
                                            placeholder="Product Name">
                                    </div>
                                </div>

                                <button class="btn btn-primary ms-auto mt-4">Save</button>
                              

                              
                               

                               
                               
                                
                               
                            </form>
                        </div>
                    </div>

                 
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- New Product Add End -->
@endsection