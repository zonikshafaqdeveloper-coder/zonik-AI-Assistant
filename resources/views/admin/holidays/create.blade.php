@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper ">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Holiday</h4>
                                <p class="card-description">
                                </p>
                                <form action="{{ route('holidays.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="holiday_date" class="form-label">Holiday Date</label>
                                        <input type="date" class="form-control" id="holiday_date" name="holiday_date" required>
                                        @error('holiday_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="holiday_name" class="form-label">Holiday Name</label>
                                        <input type="text" class="form-control" id="holiday_name" name="holiday_name" required>
                                        @error('holiday_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>



                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        @endsection
