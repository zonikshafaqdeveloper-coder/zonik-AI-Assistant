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
                                        <h3 class="card-title">Festivals and Offers Header</h3>
                                        <a href="{{ route('festivalandoffers.create') }}" type="button"
                                            class="btn btn-primary">Add Festivals and Offers Header</a>
                                    </div>

                                    <!-- Add class <code>.table-striped</code> -->
                                    </p>
                                    <div class="table-responsive">
                                        <table id="category" class="table table-bordered">
                                            <thead class="b-shadow">
                                                <tr class="s-div">
                                                    <th class="text-center">Sr.</th>
                                                    <th class="text-center">Festival and Offer</th>
                                                    <th >View</th>
                                                    <th >Action</th>
                                                    <th >Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($festivalandoffers as $key=>$festival)
                                                <tr>
                                                    <td  class="text-center">{{ ++$key }}</td>

                                                    <td class="text-center">{{ $festival->festival_offier_name }}
                                                    </td>
                                                    <td class="text-center"> <a href="brandsimage/{{$festival->id}}"
                                                            class="align-items-center btn btn-secondary category-btn d-flex">View
                                                    </td>

                                                    <td class="text-center" style="display:flex; gap: 20px;">

                                                        <a href="{{ route('festivalandoffers.edit', $festival->id) }}"
                                                            class="align-items-center btn btn-success category-btn d-flex">Edit</a>


                                                        <form method="POST"
                                                            action="{{ url('delete-festivalandoffers/' . $festival->id) }}">
                                                            @csrf
                                                            @method('delete')

                                                            <button type='submit'
                                                                class="align-items-center btn btn-danger d-flexri-delete-bin-line">
                                                                Delete
                                                            </button>
                                                        </form>



                                                    </td>

                                                    <td>
                                                        @if ($festival->status == 'Active')
                                                        <form method="POST"
                                                            action="{{ route('festivalandoffers.statusUpdate', $festival->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="Inactive">
                                                            <button type="submit"
                                                                class="btn btn-secondary">Active</button>
                                                        </form>
                                                        @else
                                                        <form method="POST"
                                                            action="{{ route('festivalandoffers.statusUpdate', $festival->id) }}">
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