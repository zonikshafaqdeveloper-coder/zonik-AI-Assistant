@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">
<div class="container-fluid page-body-wrapper">
<div class="main-panel">
<div class="content-wrapper">

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="d-flex justify-content-between">
    <h3 class="card-title">Roles</h3>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        Add Role
    </a>
</div>

<div class="table-responsive mt-3">
<table class="table table-bordered">
<thead class="b-shadow">
<tr>
    <th class="text-center">Sr.</th>
    <th class="text-center">Role Name</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>
@foreach($roles as $key => $role)
<tr>
    <td class="text-center">{{ $key + 1 }}</td>
    <td class="text-center">{{ $role->name }}</td>
    <td class="d-flex gap-2 justify-content-center">

            @if($role->id != 1)

        <a href="{{ route('roles.permissions.edit', $role->id) }}"
           class="btn btn-warning">
            Assign Permissions
        </a>

        <a href="{{ route('roles.edit', $role->id) }}"
           class="btn btn-success">
            Edit
        </a>

        <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger"
                onclick="return confirm('Delete this role?')">
                Delete
            </button>
        </form>

          @else
        <span class="badge bg-secondary">System Role</span>
    @endif

    </td>
</tr>
@endforeach
</tbody>
</table>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>
@endsection
