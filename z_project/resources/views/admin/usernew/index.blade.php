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
                                        <h4 class="card-title justify-content-between d-lg-flex">User Management
                                            <a href="{{ route('users.create') }}"
                                                class="align-items-center btn btn-success   ">
                                                <i data-feather="plus-square"></i>
                                                Add Userss
                                            </a>
                                        </h4>

                                        <p class="card-description">

                                        </p>
                                        <div class="table-responsive">

                                            <table class="table all-package table-bordered theme-table" id="users">
    <thead class="b-shadow">
        <tr>
            <th class="text-center">Sr.</th>
            <th class="text-center">User Name</th>
            <th class="text-center">User Email</th>
            <th class="text-center">Role</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($admins as $key => $user)
            <tr>
                <td class="text-center">{{ ++$key }}</td>

                <td class="text-center">{{ $user->name }}</td>
                <td class="text-center">{{ $user->email }}</td>

                {{-- Show Role Name --}}
                <td class="text-center">
                    {{ $user->role ? $user->role->name : 'N/A' }}
                </td>

                <td style="display: flex; gap: 20px;">
                    <a href="{{ route('usersedit.edit', $user->id) }}"
                        class="align-items-center btn btn-success d-flex">
                        <i class="ri-pencil-line"></i> Edit
                    </a>

                    <form method="POST" action="{{ url('delete-userss/' . $user->id) }}">
                        @csrf
                        @method('delete')
                        <button type="submit"
                            class="align-items-center btn btn-theme btn-danger d-flex">
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
                        </div>
                    </div>
                </div>
            @endsection
            <!-- All User Table Ends-->
