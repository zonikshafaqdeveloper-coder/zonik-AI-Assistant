@extends('admin.layouts.app')
@section('content')
    <!-- Container-fluid starts-->
    <div class="page-body">
        <!-- All customer Table Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="title-header option-title">
                                <h5>Customer Management</h5>
                                <form class="d-inline-flex">
                                    {{-- <a href="{{ route('customer.create') }}" class="align-items-center btn btn-theme d-flex">
                                        <i data-feather="plus-square"></i>Add customer
                                    </a> --}}
                                </form>
                            </div>

                            <div class="table-responsive category-table">
                                <div>
                                    <table class="table all-package theme-table" id="table_id">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Customer Name</th>
                                                <th>Customer Number</th>


                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($customers as $key => $customer)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $customer?->name }}</td>
                                                    <td>{{ $customer?->mobile_number }}</td>

                                                    <td>
                                                        <ul>
                                                            {{-- 
                                                            <li>
                                                                <a href="{{ route('customer.product.details', $customer->id) }}"
                                                                    class="align-items-center btn btn-danger d-flex"> <i
                                                                        class="tabler-eye"></i>view</a>
                                                            </li> --}}

                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ url('delete-customer/' . $customer->id) }}">
                                                                    @csrf
                                                                    @method('delete')

                                                                    <button type='submit'
                                                                        class="align-items-center btn btn-theme d-flex">delete
                                                                    </button>
                                                                </form>

                                                            </li>
                                                        </ul>
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

    <!-- All customer Table Ends-->
