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
                                        <h5>User Management</h5>
                                        <form class="d-inline-flex">
                                            <a href="{{ route('user.create') }}"
                                                class="align-items-center btn btn-theme d-flex">
                                                <i data-feather="plus-square"></i>Add User
                                            </a>
                                        </form>

                <div class="row">
               
                <div class="col-md-6">
                    <a href="{{ route('users.export') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Export To Excel
                    </a>
                </div>
              </div>

 </div>

                                    <div class="table-responsive category-table">
                                        <div>
                                            <table class="table all-package theme-table" id="table_id">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>User Name</th>
                                                        <th>User Email</th>
                                                        <th>User Password</th>
                                                      
                                                        
                                                       <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($users as $user)
                                                    <tr>
                                                        <td>{{ $user->id}}</td>
                                                       
                                                        <td>{{ $user->name}}</td>
                                                        <td>{{ $user->email}}</td>
                                                        <td>{{ $user->password}}</td>
                                                      
                                                        
                                                       

                                                        
                                                        <td>
                                                            <ul>
                                                            
                                                                <li>
                                                                <a href="{{ route('users.edit', $user->id) }}" class="align-items-center btn btn-danger d-flex" > <i class="ri-pencil-line"></i>edit</a>
                                                                </li>

                                                                <li>
                                                                <form method="POST" action="{{ url('delete-users/'.$user->id) }}">
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