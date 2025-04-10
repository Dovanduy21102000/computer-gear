<main id="main" class="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="pagetitle">
        <h1>Quản lý huỷ đơn hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Đơn huỷ & chờ duyệt</li>
            </ol>
        </nav>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs" id="orderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled"
                type="button" role="tab">Đơn đã huỷ</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button"
                role="tab">Chờ duyệt huỷ</button>
        </li>
    </ul>

    <div class="tab-content pt-3" id="orderTabsContent">
        {{-- Tab: Đơn đã huỷ --}}


        <div class="tab-pane fade show active" id="canceled" role="tabpanel">
            <div class="mb-3">
                <input type="text" class="form-control search-input"
                    placeholder="Tìm đơn theo mã đơn hoặc tên khách hàng..." onkeyup="searchOrders(this)">
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Danh sách đơn hàng đã huỷ</h5>
                </div>
                <div class="card-body">
                    @if ($canceledOrders->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>SĐT</th>
                                        <th>Tổng tiền</th>
                                        <th>Lý do huỷ</th>
                                        <th>Thời gian</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($canceledOrders as $order)
                                        <tr>
                                            <td><a href="{{ route('orders.show', $order->id) }}">#{{ $order->code }}</a></td>
                                            <td>{{ $order->shipping_user_name }}</td>
                                            <td>{{ $order->shipping_phone }}</td>
                                            <td class="text-danger">
                                                {{ number_format($order->final_price, 0, ',', '.') }}₫</td>
                                            <td>{{ $order->cancel_reason ?? 'Không có' }}</td>
                                            <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                                            
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Không có đơn hàng huỷ nào.</p>
                    @endif
                </div>
                
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $canceledOrders->links('pagination::bootstrap-5') }}
            </div>
            
        </div>

        {{-- Tab: Đơn chờ duyệt --}}
        <div class="tab-pane fade" id="pending" role="tabpanel">
            <div class="mb-3">
                <input type="text" class="form-control search-input"
                    placeholder="Tìm đơn theo mã đơn hoặc tên khách hàng..." onkeyup="searchOrders(this)">
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Đơn hàng chờ duyệt huỷ</h5>
                </div>
                <div class="card-body">
                    @if ($pendingCancelOrders->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>SĐT</th>
                                        <th>Tổng tiền</th>
                                        <th>Lý do yêu cầu</th>
                                        <th>Yêu cầu lúc</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingCancelOrders as $order)
                                        <tr>
                                            <td><a href="{{ route('orders.show', $order->id) }}">#{{ $order->code }}</a></td>
                                            <td>{{ $order->shipping_user_name }}</td>
                                            <td>{{ $order->shipping_phone }}</td>
                                            <td class="text-warning">
                                                {{ number_format($order->final_price, 0, ',', '.') }}₫</td>
                                            <td>{{ $order->cancel_reason ?? 'Không có' }}</td>
                                            <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('orders.cancel-approve', $order->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm">Duyệt</button>
                                                </form>
                                            
                                                <form action="{{ route('orders.cancel-reject', $order->id) }}" method="POST" class="d-inline ms-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm">Từ chối</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Không có đơn hàng chờ duyệt huỷ nào.</p>
                    @endif
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $pendingCancelOrders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</main>
<script>
    function searchOrders(input) {
        const searchText = input.value.toLowerCase();
        // Tìm table gần nhất sau input
        const table = input.parentElement.nextElementSibling?.querySelector('table');
        if (!table) return;

        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const orderCode = row.cells[0].innerText.toLowerCase(); // Mã đơn
            const customerName = row.cells[1].innerText.toLowerCase(); // Tên khách

            if (orderCode.includes(searchText) || customerName.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
