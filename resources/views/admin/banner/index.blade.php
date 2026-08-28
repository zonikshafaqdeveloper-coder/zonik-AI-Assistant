@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">

    <body>

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
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
                                        <h3 class="card-title">Homepage Banners</h3>
                                        <a href="{{ route('banners.create') }}" type="button"
                                            class="btn btn-primary">Add
                                            homepage banners</a>
                                    </div>

                                    <p class="card-description">
                                        <!-- Add class <code>.table-striped</code> -->
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <table class="table all-package theme-table" id="banner_list">
                                                <thead class="b-shadow">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Banner Name</th>
                                                        <th>Banner Image</th>
                                                        <th>Category</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($banners as $key => $banner)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $banner->banner_name }}</td>
                                                        <td> <img src="/uploads/{{ $banner->banner_image }}"
                                                                style="width: 100px; height: 100px;"> </td>
                                                        <td style="text-transform: capitalize">
                                                            {{ $banner->category?->category_name }}
                                                        </td>
                                                       <td>
                                                        <div class="d-flex gap-2">
                                                            <!-- Edit -->
                                                            <a href="{{ route('banners.edit', $banner->id) }}"
                                                            class="btn btn-success btn-sm d-flex align-items-center">
                                                                 Edit
                                                            </a>

                                                            <!-- Delete -->
                                                            <form method="POST"
                                                                action="{{ route('banners.destroy', $banner->id) }}"
                                                                class="delete-banner-form">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="button"
                                                                        class="btn btn-danger btn-sm d-flex align-items-center delete-btn">
                                                                        Delete
                                                                </button>
                                                            </form>
                                                        </div>
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
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {

            const form = this.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This banner will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>

            @endsection

            <!-- All customer Table Ends-->