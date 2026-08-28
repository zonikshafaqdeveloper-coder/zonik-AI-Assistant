@extends('admin.layouts.app')
 @section('content')

<!-- Container-fluid starts-->
   <div class="page-body">
                <!-- All User Table Start -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="card-body">

                                @if(session('success'))
                                <div class="alert alert-success">
                                {{ session('success') }}
                                </div>
                                @endif

                                    <div class="title-header option-title">
                                        <h5>All Brands</h5>
                                        <form class="d-inline-flex">
                                            <a href="{{ route('brands.create') }}"
                                                class="align-items-center btn btn-theme d-flex">
                                                <i data-feather="plus-square"></i>Add Brand
                                            </a>
                                        </form>
                                    </div>

                                    <div class="table-responsive category-table">
                                        <div>
                                            <table class="table all-package theme-table" id="table_id">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Subcategory Name</th>
                                                        <th>Brand Name</th>
                                                         <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($brands as $brand)
                                                    <tr>
                                                        <td>{{ $brand->id}}</td>
                                                        <td>{{ $brand->subcategory->name}}</td>
                                                        <td>{{ $brand->name}}</td>

                                                         <td>
                                                            <ul>
                                                            
                                                                <li>
                                                                <a href="{{ route('brands.edit', $brand->id) }}"  class="align-items-center btn btn-success d-flex" >edit</a>
                                                                </li>

                                                                <li>
                                                                <form method="POST" action="{{ url('delete-brands/'.$brand->id) }}">
                                                                    @csrf
                                                                    @method('delete')

                                                                   <button type='submit' class="align-items-center btn btn-theme d-flex">delete
                                                                  
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

                <!-- All User Table Ends-->