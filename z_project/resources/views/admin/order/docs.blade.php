@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper ">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Payment Documents</h4>
                                <p class="card-description">
                                </p>
                              
 <div class="row mt-3">
            @foreach($payment->documents as $doc)
                <div class="col-md-3 text-center mt-3">
                    @php $ext = pathinfo($doc, PATHINFO_EXTENSION); @endphp

                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                        <img src="{{ asset('uploads/payment_docs/'.$doc) }}" style="width:100%; border:1px solid #ddd;">
                    @else
                        <a href="{{ asset('uploads/payment_docs/'.$doc) }}" target="_blank" class="btn btn-info w-100">
                            View File
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        @endsection
