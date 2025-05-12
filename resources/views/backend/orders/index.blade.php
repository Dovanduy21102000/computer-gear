<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="pagetitle">
        <h1>Quản lý đơn hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Danh sách đơn hàng</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách đơn hàng</h5>
                        <!-- Table with stripped rows -->
                        <div class="col-lg-2">
                            <label for="orderStatusFilter" class="form-label">Lọc theo trạng thái:</label>
                            <select id="orderStatusFilter" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="pending">Đang chờ xử lý</option>
                                <option value="processing">Đang xử lý</option>
                                <option value="delivered">Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="canceled">Hủy đơn</option>
                            </select>
                        </div>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Tên người nhận</th>
                                    <th>Tổng giá trị</th>
                                    <th>Giảm giá</th>
                                    <th>Tổng tiền thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>PTTT</th>
                                    <th>Trạng Thái TT</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->code }}</td>
                                        <td>{{ $order->shipping_user_name }}</td>
                                        <td>{{ number_format($order->total_price) }}</td>
                                        <td>{{ number_format($order->coupon_discount) }}</td>
                                        <td>{{ number_format($order->final_price) }}</td>
                                        <td>
                                            <span data-status="{{ $order->status }}"
                                                class="badge {{ $order->status === 'pending'
                                                    ? 'bg-warning'
                                                    : ($order->status === 'processing'
                                                        ? 'bg-primary'
                                                        : ($order->status === 'delivered'
                                                            ? 'bg-success'
                                                            : ($order->status === 'completed'
                                                                ? 'bg-info'
                                                                : ($order->status === 'canceled'
                                                                    ? 'bg-danger'
                                                                    : '')))) }}">
                                                {{ $order->status === 'pending'
                                                    ? 'Đang chờ xử lý'
                                                    : ($order->status === 'processing'
                                                        ? 'Đang xử lý'
                                                        : ($order->status === 'delivered'
                                                            ? 'Đang giao hàng'
                                                            : ($order->status === 'completed'
                                                                ? 'Hoàn thành'
                                                                : ($order->status === 'canceled'
                                                                    ? 'Hủy đơn'
                                                                    : '')))) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $order->payment_method === 'momo'
                                                    ? 'bg-success'
                                                    : ($order->payment_method === 'cash'
                                                        ? 'bg-danger'
                                                        : ($order->payment_method === 'vn_pay'
                                                            ? 'bg-info'
                                                            : '')) }}">
                                                {{ $order->payment_method === 'momo'
                                                    ? 'Momo'
                                                    : ($order->payment_method === 'cash'
                                                        ? 'Khi nhận hàng'
                                                        : ($order->payment_method === 'vn_pay'
                                                            ? 'VN Pay'
                                                            : '')) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->payment_status)
                                                <span class="badge bg-success">Đã thanh toán</span>
                                            @else
                                                <span class="badge bg-danger">Chưa thanh toán</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at ? $order->created_at->format('d-m-Y') : 'Không' }}
                                        </td>
                                        <td class="text-center text-nowrap" style="width: 1px;">
                                            <a href="{{ route('orders.show', $order->id) }}"><button type="button"
                                                    class="btn btn-success btn-sm"><i
                                                        class="bi bi-eye"></i></button></a>
                                            <a href="{{ route('orders.edit', $order->id) }}"><button type="button"
                                                    class="btn btn-warning btn-sm"><i
                                                        class="bi bi-wrench"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let filter = document.getElementById('orderStatusFilter');

        function filterOrders() {
            let status = filter.value?.trim().toLowerCase() ||
                ""; // Lấy giá trị từ dropdown, nếu undefined thì gán ""
            let rows = document.querySelectorAll('.datatable tbody tr');

            rows.forEach(row => {
                let cell = row.querySelector('td:nth-child(6) span'); // Lấy cột trạng thái
                if (!cell) return; // Nếu không tìm thấy, bỏ qua

                let cellStatus = cell.dataset.status?.trim().toLowerCase() ||
                    ""; // Lấy trạng thái thực tế, nếu undefined thì gán ""

                // Nếu chọn "Tất cả", hiển thị tất cả đơn hàng
                if (status === "") {
                    row.style.display = "";
                    return;
                }

                // Nếu trạng thái đơn hàng trùng với giá trị lọc, hiển thị, ngược lại ẩn
                if (cellStatus === status) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Lọc khi trang load
        filterOrders();

        // Lọc khi thay đổi dropdown
        filter.addEventListener('change', filterOrders);
    });
</script>
