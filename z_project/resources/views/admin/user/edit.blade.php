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
                                <h5>Edit User</h5>
                            </div>

                          
                        
                            <form class="theme-form theme-form-2 mega-form" action="{{ route('admin.update', $admin->id) }}" method="post" >
                            @csrf
                            @method('PUT')

                           @if(session('success'))
                           <div class="alert alert-success">
                           {{ session('success') }}
                           </div>
                           @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Name</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="name" value="{{$admin->name}}"
                                         >
                                    </div>
                            </div>
                                </div>
                                
                                <div class="col-md-6"><div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Email</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="email" value="{{$admin->email}}"
                                         >
                                    </div>
                            </div></div>
                            </div>
                            

                            


                            <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Password</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="password" value="{{$admin->password}}"
                                         >
                                    </div>
                            </div>


                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">User Role</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="role" value="{{$admin->role}}"
                                         >
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