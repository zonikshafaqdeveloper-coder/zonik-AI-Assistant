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
                                        <h5>All types</h5>
                                        <form class="d-inline-flex">
                                            <a href="{{ route('brands.create') }}"
                                                class="align-items-center btn btn-theme d-flex">
                                                <i data-feather="plus-square"></i>Add Type
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
                                                        <th>Type Name</th>
                                                         <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($types as $type)
                                                    <tr>
                                                        <td>{{ $type->id}}</td>
                                                        <td>{{ $type->subcategory->name}}</td>
                                                        <td>{{ $type->name}}</td>

                                                         <td>
                                                            <ul>

                                                                <li>
                                                                <a href="{{ route('types.edit', $type->id) }}"  class="align-items-center btn btn-success d-flex" >edit</a>
                                                                </li>

                                                                <li>
                                                                <form method="POST" action="{{ url('delete-types/'.$type->id) }}">
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
