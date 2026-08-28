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

<h3 class="card-title">Assign Dashboard Sections — {{ $role->name }}</h3>

<form method="POST" action="{{ route('dashboard-assignment.update', $role->id) }}" class="mt-3">
    @csrf
    @method('PUT')

    <div class="row">
        @foreach($sections as $section)
            <div class="col-md-4 mb-3">
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="sections[]"
                        id="section_{{ $section->id }}"
                        value="{{ $section->id }}"
                        {{ in_array($section->id, $assignedSections) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="section_{{ $section->id }}">
                        {{ $section->label }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('dashboard-assignment.index') }}" class="btn btn-secondary">Back</a>
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