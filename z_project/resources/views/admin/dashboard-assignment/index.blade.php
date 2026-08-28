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

<h3 class="card-title">Dashboard Assignment</h3>

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
@foreach($assignments as $key => $role)
<tr>
    <td class="text-center">{{ $key + 1 }}</td>
    <td class="text-center">{{ $role->name }}</td>
    <td class="d-flex gap-2 justify-content-center">
        @if($role->id != 1)
            <a href="{{ route('dashboard-assignment.edit', $role->id) }}" class="btn btn-warning">
                Assign Dashboard Sections
            </a>
        @else
            <span class="badge bg-secondary">Full Access</span>
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