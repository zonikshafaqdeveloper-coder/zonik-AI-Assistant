@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">

    <body>

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">

                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    <div class="title-header option-title">
                                        <h4 class="card-title"></h4>
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Holiday Management</h3>
                                            <a href="{{ route('holidays.create') }}" type="button"
                                                class="btn btn-primary">Add Holiday</a>
                                        </div>

                                        <div class="row display:flex align-items-center enquire-box w-500">
                                            <div class="col">
                                                {{-- {{route('users.uploadproducts')}} --}}
                                                <form action="{{ route('holidays.import') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class=" w-350">
                                                        <div class="form-group row">
                                                            <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                                                                <span style="color:red;">*</span>File
                                                                Input(Datasheet)</label>
                                                                <input type="file"
                                                                    class="form-control form-control-user @error('file') is-invalid @enderror"
                                                                    id="exampleFile" name="file"
                                                                    value="{{ old('file') }}">

                                                                @error('file')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="">
                                                        <!-- <button type="submit"
                                                                    class="btn btn-success btn-user float-right mb-3">Import
                                                                    Product</button> -->
                                                        <!-- <a class="btn btn-primary float-right mr-3 mb-3" href="">Cancel</a> -->
                                                        <div class="row d-flex">
                                                            <div class="col-md-6">
                                                                <button type="submit"
                                                                    class="btn btn-success btn-user float-right mb-3">Import
                                                                    Holidays</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <a href="{{ route('holidays.export') }}"
                                                                    class="btn btn-sm btn-success m-19">
                                                                    <i class="fas fa-check"></i> Export To Excel
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </form>

                                            </div>
                                           {{--  <div class="col-md-4">
                                                        <a href="{{ route('holidays.export') }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fas fa-check"></i> Export To Excel
                                                        </a>
                                                    </div>  --}}
                                        </div>



                                        <form class="d-inline-flex">
                                            <a href="{{ route('productss.create') }}"
                                                class="align-items-center btn btn-theme d-flex">
                                                <i data-feather="plus-square"></i>Add Product
                                            </a>
                                        </form>


                                    </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped" id="pincode_list">
                                                <thead class="b-shadow" >
                                                    <tr>
                                                        <th class="text-center">Holiday Date</th>
                                                        <th class="text-center">Holiday Name</th>
                                                        <th>Actions</th> <!-- New column for actions -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($holidays as $holiday)
                                                    <tr>
                                                        <td class="text-center">{{ $holiday->holiday_date }}</td>
                                                        <td class="text-center">{{ $holiday->holiday_name }}</td>
                                                        <td>
                                                            <!-- Edit Button -->
                                                            <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-primary btn-sm text-white">Edit</a>

                                                            <!-- Delete Form -->
                                                            <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm text-white" onclick="return confirm('Are you sure you want to delete this holiday?')">Delete</button>
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
