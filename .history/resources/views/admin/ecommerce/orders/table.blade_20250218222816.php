<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Table</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap CSS (Optional for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Order List</h2>

    <table id="order_table" class="table table-striped table-bordered">
        <thead>
            <tr>
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
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($order->order_amount, 2) }}</td>
                <td>{{ $statuses[$order->order_status] ?? 'Unknown' }}</td>
                <td>{{ $order->razor_order_id }}</td>
                <td>{{ $order->address->city ?? 'N/A' }}</td>
                <td>{{ $order->address->state ?? 'N/A' }}</td>
                <td>
                    <button class="btn btn-sm btn-info">View</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($orders->isEmpty())
    <p class="text-center">No orders found.</p>
    @endif
</div>

<!-- DataTables Initialization Script -->
<script>
    $(document).ready(function() {
        setTimeout(() => {
            if ($.fn.DataTable) {
                $("#order_table").DataTable({
                    "paging": true,
                    "searching": false,
                    "info": true,
                    "lengthChange": true,
                    "pageLength": 10,
                    "order": [[0, "desc"]],
                    "pagingType": "full_numbers",
                    "language": {
                        "paginate": {
                            "previous": "Previous",
                            "next": "Next"
                        }
                    }
                });
            } else {
                console.error("DataTables is not loaded.");
            }
        }, 500); // Ensure DataTables initializes after DOM loads
    });
</script>

<!-- Bootstrap JS (Optional for UI) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
