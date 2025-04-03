<main id="content" role="main">
    <!-- breadcrumb -->
    <div class="bg-gray-13 bg-md-transparent">
        <div class="container">
            <!-- breadcrumb -->
            <div class="my-md-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="/">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 active" aria-current="page">Theo dõi
                            đơn hàng</li>
                    </ol>
                </nav>
            </div>
            <!-- End breadcrumb -->
        </div>
    </div>
    <!-- End breadcrumb -->

    <div class="container">
        <div class="mx-xl-10">
            <div class="mb-6 text-center">
                <h1 class="mb-6">Theo dõi đơn hàng</h1>
                <p class="text-gray-90 px-xl-10">
                    Hãy nhập mã đơn hàng đã gửi đến email đã nhập trong thanh toán đơn hàng của bạn.
                </p>
            </div>

            <div class="my-4 my-xl-8">
                <!-- Order Tracking Form -->
                <form action="{{ route('order.trackOrder') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <!-- Order ID Field -->
                            <div class="form-group">
                                <label class="form-label" for="order_code">Mã đơn hàng</label>
                                <input type="text" class="form-control" name="order_code" id="order_code"
                                    placeholder="Vui lòng nhập mã đơn hàng của bạn" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col mb-1">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto">
                                Theo dõi đơn hàng
                            </button>
                        </div>
                    </div>
                </form>
                <!-- End Order Tracking Form -->

                <!-- Order Details Section -->
                @if (isset($order))
                    <div class="card">
                        <div class="card-header">
                            <h1 class="mb-0 display-5 text-center">Chi tiết đơn hàng</h1>
                        </div>
                        <div class="card-body">
                            <p><strong>Mã đơn hàng:</strong> {{ $order->code }}</p>
                            <p><strong>Trạng thái:</strong>
                                @if ($order->status === 'pending')
                                    <span class="badge badge-warning">Đang chờ</span>
                                @elseif ($order->status === 'processing')
                                    <span class="badge badge-warning">Đang xử lý</span>
                                @elseif ($order->status === 'delivered')
                                    <span class="badge badge-warning">Đã giao</span>
                                @elseif ($order->status === 'completed')
                                    <span class="badge badge-success">Hoàn thành</span>
                                @elseif ($order->status === 'canceled')
                                    <span class="badge badge-danger">Đã huỷ</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                @endif
                            </p>
                            <p><strong>Tổng tiền:</strong> {{ number_format($order->final_price, 0, ',', '.') }} đ</p>

                            <h4 class="mt-4">Những sản phẩm đã đặt:</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th>SKU</th>
                                            <th>Số lượng</th>
                                            <th>Giá</th>
                                            <th>Ảnh sản phẩm</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderItems as $item)
                                            @php
                                                $product = json_decode($item->product_info, true) ?? [];
                                            @endphp
                                            <tr>
                                                <td>{{ $product['name'] ?? 'Unknown' }}</td>
                                                <td>{{ $product['sku'] ?? 'N/A' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>
                                                    {{ isset($product['price']) ? number_format(floatval($product['price']) * $item->quantity, 0, ',', '.') . ' đ' : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if (!empty($product['thumbnail']))
                                                        <img src="{{ asset('storage/' . $product['thumbnail']) }}"
                                                            width="50" height="50" alt="Product Image">
                                                    @else
                                                        Không có ảnh
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</main>
