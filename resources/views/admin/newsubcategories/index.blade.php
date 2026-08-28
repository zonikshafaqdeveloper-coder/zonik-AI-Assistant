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
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Subcategory</h3>
                                            <a href="{{ route('subcategoriess.create') }}" type="button"
                                                class="btn btn-primary">Add Subcategory</a>
                                        </div>
                                        <p class="card-description">
                                            <!-- Add class <code>.table-striped</code> -->
                                        </p>
                                        <div class="table-responsive">

                                            <table id="subcategory1" class="table table-bordered">
                                                <thead class="b-shadow">
                                                    <tr>
                                                        <th class="text-center">Sr.</th>
                                                        <th class="text-center">Image</th>
                                                        <th class="text-center">Name</th>
                                                        <!-- <th>Category Name</th> -->
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($subcategories as $key => $subcategory)
                                                        <tr>
                                                            <td class="text-center">{{ ++$key }}</td>
                                                            <td class="text-center"><img src="/uploads/{{ $subcategory->image }}" height="50px"
                                                                    width="50px"></td>
                                                            <td class="text-center">{{ $subcategory->name }}</td>



                                                            <!-- <td>
                                                                                    <div class="table-image">
                                                                                        <img src="assets/images/product/1.png" class="img-fluid"
                                                                                            alt="">
                                                                                    </div>
                                                                                </td> -->



                                                            <td class="" style="display:flex;gap: 20px;">

                                                                <a href="{{ route('subcategoriess.edit', $subcategory->id) }}"
                                                                    class="align-items-center btn btn-success category-btn">
                                                                    <i class="ri-pencil-line"></i>Edit</a>

                                                                <form method="POST"
                                                                    action="{{ url('delete-subcategoriess/' . $subcategory->id) }}">
                                                                    @csrf
                                                                    @method('delete')

                                                                    <button type='submit'
                                                                        class="align-items-center btn btn-danger d-flexri-delete-bin-line">Delete

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
