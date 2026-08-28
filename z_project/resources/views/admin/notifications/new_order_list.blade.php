@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
   <!-- partial -->
    <div class=" container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="card-description">
                                <h5>Order Notifications</h5>

                            </div>

                            <div class="table-responsive">
                    <table class="table table-striped">
                    <table class="table all-package theme-table" >
                                <div>
                                    <table class="table all-package theme-table" id="order_list">
                                        <thead class="b-shadow">
                                            <tr>
                                                <th>Sr.</th>
                                                {{-- <th>User Name</th> --}}
                                                <th>Customer Message</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if (auth()->user()?->unreadNotifications)
                                                @foreach (auth()->user()->unreadNotifications as $key=>$notification)
                                                    @if (isset($notification->data['tag']) && $notification->data['tag'] !== 'Customer')
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
