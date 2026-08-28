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
                                        <h3 class="card-title">Festivals and Offers Images</h3>
                                        <a href="{{ route('brandsimage.create') }}" type="button"
                                            class="btn btn-primary">Add Festivals and Offers Image</a>
                                    </div>
                                    <p class="card-description">
                                        <!-- Add class <code>.table-striped</code> -->
                                    </p>
                                    <div class="table-responsive">
                                        <table id="category" class="table table-bordered">
                                            <thead class="b-shadow">
                                                <tr class="s-div">
                                                    <th>Sr.</th>
                                                    <th>Brand Name</th>
                                                    <th>Brands Image</th>
                                                    <th>Category</th>
                                                    <th>Festival and Offer</th>
                                                    <th>Action</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($brandimage as $key=>$banner)
                                                <tr>
                                                    <td>{{ ++$key }}</td>

                                                    <td>{{ $banner->brand_name }}</td>

                                                    <td style=""><img src="/uploads/{{ $banner->brand_image }}"
                                                            height="50px" width="50px"></td>
                                                    <td>{{ $banner->category?->category_name }}
                                                    </td>
                                                    <td>{{ $banner->festivalandoffer?->festival_offier_name }}
                                                        {{$banner->festivalandoffer?->festival_offier_name2}}
                                                    </td>



                                                    <td style="display:flex; gap: 20px;">

                                                        <a href="{{ route('brandsimage.edit', $banner->id) }}"
                                                            class="align-items-center btn btn-success category-btn d-flex">Edit</a>


                                                        <form method="POST"
                                                            action="{{ url('delete-brandsimage/' . $banner->id) }}">
                                                            @csrf
                                                            @method('delete')

                                                            <button type='submit'
                                                                class="align-items-center btn btn-danger d-flexri-delete-bin-line">
                                                                Delete
                                                            </button>
                                                        </form>



                                                    </td>

                                                    <td>
                                                        @if ($banner->status == 'Active')
                                                        <form method="POST"
                                                            action="{{ route('brandsimage.statusUpdate', $banner->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="Inactive">
                                                            <button type="submit"
                                                                class="btn btn-secondary">Active</button>
                                                        </form>
                                                        @else
                                                        <form method="POST"
                                                            action="{{ route('brandsimage.statusUpdate', $banner->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="Active">
                                                            <button type="submit"
                                                                class="btn btn-danger text-white">Inactive</button>
                                                        </form>
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


                        </html>
                        @endsection