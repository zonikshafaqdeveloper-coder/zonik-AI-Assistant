@extends('web.layouts.app')

@section('content')
<div class="container">
    <h1>Enquiry Details</h1>

    <table class="table">
        <tr>
            <th>Enquiry Number:</th>
            <td>{{ $enquiry->enquiry_no }}</td>
        </tr>
        <tr>
            <th>Product ID:</th>
            <td>{{ $enquiry->product_id }}</td>
        </tr>
        <tr>
            <th>Quantity:</th>
            <td>{{ $enquiry->quantity }}</td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>{{ $enquiry->status }}</td>
        </tr>
        <tr>
            <th>Created At:</th>
            <td>{{ $enquiry->created_at }}</td>
        </tr>
    </table>

    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
</div>
@endsection
