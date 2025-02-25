<table class="table align-middle table-row-dashed fs-6 gy-5 table-row-bordered" id="kt_datatable_zero_configuration">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th>#</th>
            <th>User Name</th>
            <th>Order Amount</th>
            <th>Order Status</th>
            <th>Razor Order ID</th>
            <th>City</th>
            <th>State</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="fw-semibold text-gray-600">
        @foreach ($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($order->order_amount, 2) }}</td>
                <td>
                    <select class="form-select form-select-sm" onchange="updateOrderStatus({{ $order->id }}, this.value)">
                        @php
                            $statuses = [
                                0 => 'Created',
                                1 => 'Payment Done',
                                2 => 'Order Accept',
                                3 => 'Order Preparing',
                                4 => 'Order Shipped',
                                5 => 'Order Delivered',
                                6 => 'Order Completed',
                                7 => 'Order Rejected',
                                8 => 'Order Returned',
                                9 => 'Order Cancelled'
                            ];
                        @endphp
                        @foreach ($statuses as $key => $status)
                            <option value="{{ $key }}" {{ $order->order_status == $key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>{{ $order->razor_order_id }}</td>
                <td>{{ $order->address->city ?? 'N/A' }}</td>
                <td>{{ $order->address->state ?? 'N/A' }}</td>
                <td>
                    <button class="btn btn-sm btn-light-info" onclick="showOrderDetails({{ $order }})">
                        View Details
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if ($orders->isEmpty())
    <p class="text-center">No orders found.</p>
@endif

<!-- Modal for Order Details -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Order Information</h6>
                <table class="table table-bordered">
                    <tbody id="order-info-content"></tbody>
                </table>
                <h6>User Address</h6>
                <table class="table table-bordered">
                    <tbody id="user-address-content"></tbody>
                </table>
                <h6>Order Items</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Sale Price</th>
                            <th>Total Price</th>
                            <th>Tax</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-content"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function showOrderDetails(order) {
        let orderInfoContent = `
            <tr><th>Order ID</th><td>#${order.id}</td></tr>
            <tr><th>User Name</th><td>${order.user?.name || 'N/A'}</td></tr>
            <tr><th>Order Amount</th><td>${parseFloat(order.order_amount).toFixed(2)}</td></tr>
            <tr><th>Order Status</th><td>${getOrderStatusText(order.order_status)}</td></tr>
            <tr><th>Razor Order ID</th><td>${order.razor_order_id || 'N/A'}</td></tr>`;
        document.getElementById('order-info-content').innerHTML = orderInfoContent;

        let address = order.address;
        let addressContent = `
            <tr><th>Name</th><td>${address?.name || 'N/A'}</td></tr>
            <tr><th>City</th><td>${address?.city || 'N/A'}</td></tr>
            <tr><th>State</th><td>${address?.state || 'N/A'}</td></tr>`;
        document.getElementById('user-address-content').innerHTML = addressContent;

        let itemsContent = '';
        order.order_items.forEach(item => {
            itemsContent += `
                <tr>
                    <td>${item.product?.name || 'N/A'}</td>
                    <td>${item.qty}</td>
                    <td>${parseFloat(item.sale_price).toFixed(2)}</td>
                    <td>${parseFloat(item.total_price).toFixed(2)}</td>
                    <td>${parseFloat(item.tax).toFixed(2)}</td>
                </tr>`;
        });
        document.getElementById('order-items-content').innerHTML = itemsContent;

        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
    }

    function getOrderStatusText(status) {
        const statuses = [
            'Created', 'Payment Done', 'Order Accept', 'Order Preparing', 'Order Shipped',
            'Order Delivered', 'Order Completed', 'Order Rejected', 'Order Returned', 'Order Cancelled'
        ];
        return statuses[status] || 'Unknown';
    }

    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#kt_datatable_zero_configuration')) {
            $("#kt_datatable_zero_configuration").DataTable({
                "paging": true,
                "searching": false,
                "info": true,
                "lengthChange": true,
                "pageLength": 10,
                "order": [[0, 'desc']],
                "pagingType": "simple_numbers",
                "language": {
                    "paginate": {
                        "previous": "Previous",
                        "next": "Next"
                    }
                }
            });
        }
    });
</script>
