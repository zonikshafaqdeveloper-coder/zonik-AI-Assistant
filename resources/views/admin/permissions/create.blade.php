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
    <h3 class="card-title">Create Permission</h3>
</div>

<form method="POST" action="{{ route('permissions.store') }}">
@csrf

<div class="form-group">
    <label>Module</label>
    <input type="text" name="module" class="form-control" placeholder="data" required>
</div>

<div class="form-group mt-3">
    <label>Permission Name</label>
    <input type="text" name="name" class="form-control" placeholder="data.view" required>
</div>

<button class="btn btn-primary mt-3">
    Save Permission
</button>

<a href="{{ route('permissions.index') }}" class="btn btn-secondary mt-3">
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
