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
                                <h4 class="card-title">Edit Pincode</h4>
                                <p class="card-description">
                                    <!-- Edit Pincode -->
                                </p>
                                <form method="POST" action="{{ route('pincode.update', $pincode->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT') <!-- Use PUT method for updating -->

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="zone_id" class="col-sm-3 col-form-label">Zones</label>
                                            <div class="col-sm-9">
                                                <select name="zone_id" id="zone_id"
                                                    class="form-select form-control @error('zone_id') is-invalid @enderror">
                                                    <option value="">Select Zone</option>
                                                    @foreach ($zones as $zone)
                                                    <option value="{{ $zone->id }}" @if(old('zone_id', $pincode->zone_id) == $zone->id)
                                                        selected @endif>{{ $zone->zone_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('zone_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 ">
                                            <label for="pincode" class="col-sm-3 col-form-label">Pincode</label>
                                            <div class="col-sm-9">
                                                <input id="pincode"
                                                    class="form-control @error('pincode') is-invalid @enderror"
                                                    type="text" name="pincode" value="{{ old('pincode', $pincode->pincode) }}"
                                                    placeholder="Pincode">
                                                @error('pincode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary me-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        @endsection
