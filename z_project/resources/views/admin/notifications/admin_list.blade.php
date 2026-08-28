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

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="title-header option-title">
                                <h5>Quotes Notifications</h5>

                            </div>

                            <div class="table-responsive category-table">
                                <div>
                                    <table class="table all-package theme-table" id="table_id">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                {{-- <th>User Name</th> --}}
                                                <th>Customer Masseage</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if (auth()->user()?->unreadNotifications)
                                            @foreach (auth()->user()->unreadNotifications as $key=>$notification)
                                                @if (isset($notification->data['tag']) && $notification->data['tag'] == 'Admin')
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{ $notification->data['data'] }} </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
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
