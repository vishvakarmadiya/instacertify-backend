<div id="orders-data">
    <table class="table align-middle table-row-dashed fs-6 gy-5">
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
                    <td>{{ $order->order_status }}</td>
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

    <!-- Pagination at the bottom -->
    <div class="d-flex justify-content-center mt-4">
        {!! $orders->links() !!}
    </div>
</div>
<script>
    $(document).on('click', '.pagination a', function(event) {
    event.preventDefault();
    let page = $(this).attr('href').split('page=')[1];

    $.ajax({
        url: "?page=" + page,
        type: "GET",
        success: function(data) {
            $("#orders-data").html($(data).find("#orders-data").html());
        },
        error: function() {
            console.error("Pagination fetch failed.");
        }
    });
});

</script>