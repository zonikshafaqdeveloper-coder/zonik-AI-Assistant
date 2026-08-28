@extends('admin.layouts.appnew')

@section('content')
<style>
    .form-check-input:checked + label {
    font-weight: 600;
}

</style>
<div class="page-body">

<div class="container-fluid">
<div class="row">
<div class="col-12 my-5">
<div class="row">
<div class="col-sm-8 m-auto">

<div class="card">
<div class="card-body">

<div class="card-header-2 mb-3">
    <h3 class="card-title">
        Assign Permissions — {{ $role->name }}
    </h3>
</div>

<form method="POST"
      action="{{ route('roles.permissions.update', $role->id) }}">
@csrf

@foreach($permissions as $module => $perms)

    <div class="card mt-3">
        <div class="card-header bg-light">
            <strong>{{ strtoupper($module) }}</strong>
        </div>

        <div class="card-body">
            <div class="row">

                @foreach($perms as $permission)
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="permissions[]"
                                   id="perm_{{ $permission->id }}"
                                   value="{{ $permission->id }}"
                                   {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="perm_{{ $permission->id }}">
                                {{ $permission->display_name }}
                            </label>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endforeach

<div class="mt-4">
    <button class="btn btn-primary">
        Update Permissions
    </button>

    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
        Back
    </a>
</div>

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
