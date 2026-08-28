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
                                    </div>

                                    <div class="table-responsive category-table">
                                        <div>
                                            <table class="table all-package theme-table" id="table_id">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Sr.no</th>
                                                        <th class="text-center">User Name</th>
                                                        <th class="text-center">User Email</th>
                                                        <th class="text-center">User Password</th>
                                                        <th class="text-center">User Role</th>
                                                        
                                                       <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($admins as $user)
                                                    <tr>
                                                        <td class="text-center">{{ $user->id}}</td>
                                                       
                                                        <td class="text-center">{{ $user->name}}</td>
                                                        <td class="text-center">{{ $user->email}}</td>
                                                        <td class="text-center">{{ $user->password}}</td>
                                                        <td class="text-center">{{ $user->role}}</td>
                                                        
                                                       

                                                        
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