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
                  <h4 class="card-title">Add User</h4>
                  <p class="card-description">
                   <!-- Add Category -->
                  </p>


                          
                          <form class="theme-form theme-form-2 mega-form"
      action="{{ route('usersupdate.update', $admin->id) }}"
      method="POST">
    @csrf
    @method('PUT')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Name -->
    <div class="mb-4 row align-items-center">
        <label class="form-label-title col-sm-3 mb-0">User Name</label>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="name" value="{{ $admin->name }}">
        </div>
    </div>

    <!-- Email -->
    <div class="mb-4 row align-items-center">
        <label class="form-label-title col-sm-3 mb-0">User Email</label>
        <div class="col-sm-9">
            <input class="form-control" type="email" name="email" value="{{ $admin->email }}">
        </div>
    </div>

    <!-- Password (leave blank if not changing) -->
    <div class="mb-4 row align-items-center">
        <label class="form-label-title col-sm-3 mb-0">User Password</label>
        <div class="col-sm-9">
            <input class="form-control" type="password" name="password"
                   placeholder="Leave blank to keep current password">
        </div>
    </div>

    <!-- Role -->
    <div class="mb-4 row align-items-center">
        <label class="form-label-title col-sm-3 mb-0">User Role</label>
        <div class="col-sm-9">
            <select class="form-select" name="role_id" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ $admin->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary ms-auto mt-4">Save</button>
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
