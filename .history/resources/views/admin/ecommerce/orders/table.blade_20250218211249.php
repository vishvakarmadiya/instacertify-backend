@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Order Management</h2>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>
                        <select class="form-select order-status" data-id="{{ $order->id }}">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="showOrderDetails({{ $order }})" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">View</button>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $order->id }})">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
                <p><strong>Customer Name:</strong> <span id="modalCustomerName"></span></p>
                <p><strong>Status:</strong> <span id="modalOrderStatus"></span></p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Show Order Details in Modal
    function showOrderDetails(order) {
        $('#modalOrderId').text(order.id);
        $('#modalCustomerName').text(order.customer_name);
        $('#modalOrderStatus').text(order.status);
        $('#orderDetailsModal').modal('show');
    }

    // Handle Order Status Change
    $('.order-status').change(function() {
        var orderId = $(this).data('id');
        var newStatus = $(this).val();
        
        $.ajax({
            url: '/orders/' + orderId + '/update-status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                Swal.fire('Success', 'Order status updated!', 'success');
            },
            error: function() {
                Swal.fire('Error', 'Failed to update status!', 'error');
            }
        });
    });

    // Confirm Delete Order
    function confirmDelete(orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/orders/' + orderId + '/delete',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', 'Order has been removed.', 'success')
                            .then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to delete order!', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
