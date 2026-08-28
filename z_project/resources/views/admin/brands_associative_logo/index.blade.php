@extends('admin.layouts.appnew')
@section('content')
    <div class="page-body">

        <body>

            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <div class="main-panel">
                    <div class="content-wrapper ">
                        <div class="row">

                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Category</h3>
                                            <a href="{{ route('brandsassoc.create') }}" type="button"
                                                class="btn btn-primary">Add Brand Logo</a>
                                        </div>
                                        <p class="card-description">
                                            <!-- Add class <code>.table-striped</code> -->
                                        </p>
                                        <div class="table-responsive">
                                            <table id="category" class="table table-bordered">
                                                <thead class="b-shadow">
                                                    <tr class="s-div">
                                                        <th>Sr.</th>
                                                   
                                                        <th class="text-center">Brand Logo Image</th>

                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($brandsassoc as $key => $category)
                                                        <tr>
                                                            <td>{{ ++$key }}</td>

                                                       

                                                            <td  class="text-center" style=""><img src="/uploads/{{ $category->image }}"
                                                                    height="50px" width="50px"></td>


                                                            <td style="display:flex; gap: 20px;">

                                                                <a href="{{ route('brandsassoc.edit', $category->id) }}"
                                                                    class="align-items-center btn btn-success category-btn d-flex">Edit</a>

                                                                <form method="POST"
                                                                    action="{{ url('delete-brandsassoc/' . $category->id) }}">
                                                                    @csrf
                                                                    @method('delete')

                                                                    <button type='submit'
                                                                        class="align-items-center btn btn-danger d-flexri-delete-bin-line">
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


                            </html>
                        @endsection
