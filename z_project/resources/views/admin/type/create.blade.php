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
                                <h5>Add Type Category</h5>
                            </div>

                          
                            <!-- <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"> -->
                            <form class="theme-form theme-form-2 mega-form" action="{{ route('type.store') }}" method="POST" >
                            {{ csrf_field() }}

                           @if(session('success'))
                           <div class="alert alert-success">
                           {{ session('success') }}
                           </div>
                           @endif


                           <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label form-label-title">Subcategory Name</label>
                                    <div class="col-sm-9">
                                    <select class="js-example-basic-single w-100" name="subcategory_id" id="category_id">
                                    @foreach($subcategories as $subcategory)
                                   
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                     </select>
                                   </div>
                            </div>


                            <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Type Name</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="type_name"
                                         placeholder="Type name">
                                    </div>
                            </div>

                          

                          


                                <button type="submit" name="submit" class="btn btn-primary ms-auto mt-4">Save</button>
                             
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