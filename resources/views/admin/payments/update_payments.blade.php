@extends('admin.layouts.appnew')
@section('content')
<style>
    span.text-danger, .text-danger span{
        color: #dc3545 !important;
    }
</style>
<div class="page-body">
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
                                        <h3 class="card-title">Updated Payment's</h3>
                                    </div>
                                   <div class="table-responsive">
    <table class="table table-bordered" id="payments-table">
        <thead class="b-shadow">
        <tr>
            <th>ID</th>
             <!-- <th>User</th> -->
                          
                        <th class="text-center">Order ID</th>
                        <th class="text-center">Invoice ID</th>
                        <th class="text-center">Outlet</th>
                        <th class="text-center">Invoice Date</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Total Amount</th>
                        <th class="text-center">Total Paid</th>
                        <th class="text-center">Remaining</th>
                        <th class="text-center">Action</th>
                       
        </tr>
        </thead>

         <tbody>
                @foreach ($payments as $payment)
                    @php
                        $totalAmount = $payment->total_amount ?? ($payment->order->total_discount_value ?? 0);
                        $totalPaid = $payment->total_paid ?? 0;
                        $remaining = $totalAmount - $totalPaid;
                    @endphp

                    <tr>
                        <td class="text-center">
                             {{ $loop->iteration }}
                        </td>

                        <!-- <td class="text-center">
                            {{ $payment->user->name ?? 'N/A' }} <br>
                            <small>ID: {{ $payment->user_id }}</small>
                        </td> -->

                        

                      
                        
                         <td class="text-center">
   
      {{ $payment->order->order_id ?? $payment->order_id }}
   
</td>
                         <td class="text-center">
    <a href="{{ route('generateInvoiceAndDeliveryCharges.list', ['id' => $payment->order_id]) }}" 
       onclick="window.open(this.href, '_blank', 'width=800,height=600'); return false;" 
       class="font-weight-bold text-dark">
      {{ $payment->order->invoice_id ?? $payment->order_id }}
    </a>
</td>

 <td class="text-center"> 
                             {{ $payment->outlet->outlet_name ?? 'N/A' }} <br> 
                         </td> 
                         
                            <td class="text-center">
                        {{ $payment->order->invoice_date ? $payment->order->invoice_date : 'N/A' }}
                        </td>

                         <td class="text-center">
                        {{ $payment->updated_at ? $payment->updated_at->format('d M Y') : 'N/A' }}
                        </td>
                        
                      

                        <td class="text-center">
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>

                        <td class="text-center">
                            ₹ {{ number_format($totalPaid, 2) }}
                        </td>

                        <td class="text-center">
                            ₹ {{ number_format($remaining, 2) }}
                        </td>

                       <td class="text-center">

    {{-- History --}}
   

    {{-- Pay / Update --}}
@if($remaining > 0)
    <a href="{{ route('order.edit', ['id' => $payment->order_id, 'from' => 'update_payments']) }}"
       class="btn btn-sm btn-primary">
       Pay
    </a>
@else
    <span class="btn btn-sm btn-success">Paid</span>
@endif

     
        <a href="{{ route('payments.history', $payment->order_id) }}"
           class="btn btn-sm btn-info">
           History ({{ $payment->histories->count() }})
        </a>
        
    

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
