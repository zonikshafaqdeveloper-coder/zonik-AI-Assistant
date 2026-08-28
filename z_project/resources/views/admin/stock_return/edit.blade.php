@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>Edit Return Request — {{ $returnRequest->order->order_id ?? '-' }}</h3>
                                <a href="{{ route('stock-return.index') }}" class="btn btn-outline-secondary">← Back</a>
                            </div>

                            <form id="editReturnForm">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th style="width:110px;">Purchase Rate</th>
                                                <th style="width:120px;">Return Qty</th>
                                                <th>Location</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($returnRequest->items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product->product_name ?? 'N/A' }}
                                                    <input type="hidden" class="f-item-id" value="{{ $item->id }}">
                                                </td>
                                                <td>₹{{ number_format($item->purchase_rate, 2) }}</td>
                                                <td>
                                                    <input type="number" class="form-control f-return-qty" min="1" value="{{ $item->return_qty }}" required>
                                                </td>
                                                <td>Rack: {{ $item->rack_no }} / Level: {{ $item->level_no }} / Slot: {{ $item->slot_no }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-lg" style="background:#e97457;color:#fff;">Save Changes</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#editReturnForm').on('submit', function (e) {
        e.preventDefault();

        const items = [];
        $('tbody tr').each(function () {
            items.push({
                item_id: $(this).find('.f-item-id').val(),
                return_qty: $(this).find('.f-return-qty').val(),
            });
        });

        $.ajax({
            url: '{{ route("stock-return.update", $returnRequest->id) }}',
            method: 'PUT',
            data: { items: items },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                Swal.fire('Saved', res.message, 'success').then(() => {
                    window.location.href = '{{ route("stock-return.index") }}';
                });
            },
            error: function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
            }
        });
    });
});
</script>
@endsection
