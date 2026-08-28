@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">

<div class="container-fluid">
<div class="row">
<div class="col-12 my-5">

<div class="card">
<div class="card-body">

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="d-flex justify-content-between">
    <h3 class="card-title">Permissions</h3>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
        Add Permission
    </a>
</div>

<div class="table-responsive mt-3">
<table class="table table-bordered">
<thead class="b-shadow">
<tr>
    <th class="text-center">Sr.</th>
    <th class="text-center">Module</th>
    <th class="text-center">Permission Name</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>
@foreach($permissions as $key => $permission)
<tr>
    <td class="text-center">{{ $key + 1 }}</td>
    <td class="text-center">{{ $permission->module }}</td>
    <td class="text-center">{{ $permission->name }}</td>
    <td class="d-flex gap-2 justify-content-center">

        <a href="{{ route('permissions.edit', $permission->id) }}"
           class="btn btn-success">
            Edit
        </a>

        <form method="POST" action="{{ route('permissions.destroy', $permission->id) }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger"
                onclick="return confirm('Delete this permission?')">
                Delete
            </button>
        </form>

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
@endsection
