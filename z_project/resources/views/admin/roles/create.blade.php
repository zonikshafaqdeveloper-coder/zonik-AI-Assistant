@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-6 m-auto">


 <div class="card">
                            <div class="card-body">

                                <div class="card-header-2 mb-3">
                                   
<h3 class="card-title">Create Role</h3>

</div>

<form method="POST" action="{{ route('roles.store') }}">
@csrf

<div class="form-group ">
    <label>Role Name</label>
    <input type="text" name="name" class="form-control" required>
</div>

<button class="btn btn-primary mt-3">
    Save Role
</button>

<a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">
    Back
</a>

</form>

               </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
